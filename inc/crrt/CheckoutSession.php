<?php

class CheckoutSession {
    private Reservation $reservation;
    private Client $client;
    private Car $car;
    private $products = array();
    private $booking_post_id;
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

    public function getProducts() {
        return $this->products;
    }

    public function setMetadata($bookingId) {
        $locations = $this->client->getLocations();
        $datesRange = $this->reservation->getDatesRange();
        $this->metadata = array(
            'user_phone' => $this->client->getPhone(),
            'user_email' => $this->client->getEmail(),
            'product_id' => $this->car->getCarId(),
            'booking_id' => $bookingId,
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
        $this->booking_post_id = wp_insert_post($post_data);

        carbon_set_post_meta( $this->booking_post_id, 'booking_id', $this->booking_post_id);
        carbon_set_post_meta( $this->booking_post_id, 'phone', $this->client->getPhone());
        carbon_set_post_meta( $this->booking_post_id, 'email', $this->client->getEmail());
        carbon_set_post_meta( $this->booking_post_id, 'flight_number', $this->client->getFlightNumber());
        carbon_set_post_meta( $this->booking_post_id, 'agree', $this->client->getAgreeTerms());
        carbon_set_post_meta( $this->booking_post_id, 'date_of_birth', $this->client->getDateOfBirth());

        $locations = $this->client->getLocations();
        carbon_set_post_meta( $this->booking_post_id, 'location_start', $locations['start']);
        carbon_set_post_meta( $this->booking_post_id, 'location_end', $locations['end']);

        $datesRange = $this->reservation->getDatesRange();
        carbon_set_post_meta( $this->booking_post_id, 'date_start', $datesRange['start']);
        carbon_set_post_meta( $this->booking_post_id, 'date_end', $datesRange['end']);

        carbon_set_post_meta( $this->booking_post_id, 'product_id', $this->car->getCarId());
        carbon_set_post_meta( $this->booking_post_id, 'options', $this->reservation->getOptionsText());

//        Stripe::getPayLink($this->reservation, $this->client);
    }
    public function handleComplete() {

    }
    public function handleExprire() {

    }
}
