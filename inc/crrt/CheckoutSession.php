<?php

class CheckoutSession {
    private Reservation $reservation;
    private Client $client;
    private Car $car;
    private $products = array();
    private $bookingPostId = null;

    public function __construct(Reservation $reservation, Client $client) {
        $this->reservation = $reservation;
        $this->client = $client;
        $this->car = $reservation->getCar();

        $this->products[] = $this->getCarProduct();
        foreach ($this->getOptionsProducts() as $product) {
            $this->products[] = $product;
        }
    }

    public function getCarProduct() {
        $total = $this->reservation->getCarTotal();
        $product = new Product(
            $this->car->getName(),
            $total,
            $this->car->getImage(),

        );
        $product->setDescription($this->getMetadata());
        return $product->get();
    }

    public function getOptionsProducts() {
        $products = array();
        foreach ($this->reservation->getSelectedOptions() as $option) {
            $optionPrice = new PriceCalculator($option['rentalPeriodPrices'], $this->reservation->getDatesRange());
            $optionsTotal = $optionPrice->getTotalMessage();
            $product = new Product(
                $option['name'],
                $optionsTotal,
                null,
            );
            $products[] = $product->get();
        }
        return $products;
    }


    public function getBookingPostId() {
        return $this->bookingPostId;
    }

    public function getProducts() {
        return $this->products;
    }

    public function getMetadata() {
        $locations = $this->client->getLocations();
        $datesRange = $this->reservation->getDatesRange();
        return array(
            'user_phone' => $this->client->getPhone(),
            'user_email' => $this->client->getEmail(),
            'product_id' => $this->car->getCarId(),
            'booking_id' => $this->bookingPostId,
            'date_start' => $datesRange['start'],
            'date_end' => $datesRange['end'],
            'location_start' => $locations['start'],
            'location_end' => $locations['end'],
            'flight_number' => $this->client->getFlightNumber(),
            'options' => $this->reservation->getOptionsText(),
            'agree' => $this->client->getAgreeTerms(),
            'date_of_birth' => $this->client->getDateOfBirth(),
        );
    }

    public function create() {
        $title = "Пользователь начал бронирование {$this->client->getPhone()} - {$this->client->getEmail()}";
        $post_data = array(
            'post_title' => $title,
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'car_booking',
        );
        $this->bookingPostId = wp_insert_post($post_data);

        carbon_set_post_meta( $this->bookingPostId, 'booking_id', $this->bookingPostId);
        carbon_set_post_meta( $this->bookingPostId, 'phone', $this->client->getPhone());
        carbon_set_post_meta( $this->bookingPostId, 'email', $this->client->getEmail());
        carbon_set_post_meta( $this->bookingPostId, 'flight_number', $this->client->getFlightNumber());
        carbon_set_post_meta( $this->bookingPostId, 'agree', $this->client->getAgreeTerms());
        carbon_set_post_meta( $this->bookingPostId, 'date_of_birth', $this->client->getDateOfBirth());

        $locations = $this->client->getLocations();
        carbon_set_post_meta( $this->bookingPostId, 'location_start', $locations['start']);
        carbon_set_post_meta( $this->bookingPostId, 'location_end', $locations['end']);

        $datesRange = $this->reservation->getDatesRange();
        carbon_set_post_meta( $this->bookingPostId, 'date_start', $datesRange['start']);
        carbon_set_post_meta( $this->bookingPostId, 'date_end', $datesRange['end']);

        carbon_set_post_meta( $this->bookingPostId, 'product_id', $this->car->getCarId());
        carbon_set_post_meta( $this->bookingPostId, 'options', $this->reservation->getOptionsText());

        $checkoutSession = Stripe::getCheckoutSession($this->reservation, $this->client, $this->getProducts(), $this->getMetadata());

        carbon_set_post_meta( $this->bookingPostId, 'payment_session_id', $checkoutSession['id']);

        return $checkoutSession;
    }
    public static function getPost($paymentSessionId) {

        $args = array(
            'post_type' => 'car_booking',
            'meta_query' => array(
                array(
                    'key' => 'payment_session_id',
                    'value' => $paymentSessionId,
                    'compare' => '=',
                ),
            ),
        );

        $query = new WP_Query($args);

        $postId = null;

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $postId = get_the_ID();
                // Здесь вы можете использовать информацию о найденном посте
//                the_title();
//                the_content();
                // и так далее
            }
            wp_reset_postdata(); // сброс данных о посте
        }

        return $postId;

    }

    public static function handleComplete($event) {
        $checkoutSessionId = $event->data->object->id;
        $reservationId = self::getPost($checkoutSessionId);
        $metadata = $event->data->object->metadata;

        $customerName = null;
        if (isset($event->data->object->customer_details->name)) {
            $customerName = $event->data->object->customer_details->name;
        }

        $title = "Успешная бронь: {$metadata->user_email} - {$metadata->user_phone}";
        if (!empty($customerName)) {
            $title .= " - {$customerName}";
            carbon_set_post_meta($reservationId, 'name', $customerName);
        }
        $post_data = array(
            'ID' => $reservationId,
            'post_title' => $title,
        );
        $post_updated = wp_update_post($post_data);

        $amount = $event->data->object->amount_total;
        carbon_set_post_meta($reservationId, 'amount', $amount);
        carbon_set_post_meta($reservationId, 'json', $event);

        $receipt_url = $event->data->object->receipt_url;
        if ($receipt_url) {
            carbon_set_post_meta( $reservationId, 'receipt_url', $receipt_url);
        }

        return $reservationId;
    }

    public function handleExprire() {

    }
}
