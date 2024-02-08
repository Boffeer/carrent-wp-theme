<?php

class CheckoutSession {
    private Reservation $reservation;
    private Client $client;
    private Car $car;
    private $products = array();
    private $bookingPostId;
    private $metadata;

    public function __construct(Reservation $reservation, Client $client) {
        $this->reservation = $reservation;
        $this->client = $client;
        $this->car = $reservation->getCar();

        $this->products[] = $reservation->getCarProduct();
        foreach ($reservation->getOptionsProducts() as $product) {
            $this->products[] = $product;
        }
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

        return Stripe::getCheckoutSession($this->reservation, $this->client, $this->getProducts(), $this->getMetadata());
    }
    public function handleComplete() {

    }
    public function handleExprire() {

    }
}
