<?php

class Reservation {
    private Car $car;
    private array $selectedOptions = [];
    private array $datesRange;
    private $cancelPage;

    public function __construct(Car $car, $selectedOptionsNames, DatesRange $datesRange) {
        $this->car = $car;
        $this->datesRange = $datesRange->get();

        $selectedOptionsNames = explode(',', $selectedOptionsNames);
        foreach ($this->car->getAvailableOptions() as $availableOption) {
            if (!in_array($availableOption['name'], $selectedOptionsNames)) continue;
            $this->selectedOptions[] = $availableOption;
        }
    }

    public function getCar() {
        return $this->car;
    }
    public function getDatesRange() {
        return $this->datesRange;
    }
    public function getSelectedOptions() {
        return $this->selectedOptions;
    }

    public function getCarTotal(): array
    {
        $carPrice = new PriceCalculator($this->car->getRentalPeriodPrices(), $this->datesRange, $this->car->getFranchise());
        return $carPrice->getTotalMessage();
    }

    public function getOptionsTotal() {
        $optionsTotal = array();
        foreach ($this->getSelectedOptions() as $option) {
            $option = new PriceCalculator($option['rentalPeriodPrices'], $this->datesRange);
            $optionsTotal[] = $option->getTotalMessage();
        }
        return $optionsTotal;
    }
    public function getOptionsText() {
        $options_strings = [];
        foreach ($this->getSelectedOptions() as $option) {
            $options_strings[] = "{$option['name']}";
        }
        return implode(', ', $options_strings);
    }

    public function getTotal() {
        $cartItems = array(
            $this->getCarTotal(),
        );
        $cartItems = array_merge($cartItems, $this->getOptionsTotal());

        $cart = array(
            'per_day' => 0,
            'price' => 0,
            'full_days' => 0,
            "extra_hours" => 0,
            "full_days_text" => "",
            "extra_hours_text" => "",
            'price_index' => 0,
            'currency' => carbon_get_theme_option('currency'),
        );

        foreach ($cartItems as $cartItem) {
            $cart['per_day'] += $cartItem['per_day'];
            $cart['price'] += $cartItem['price'];

            if ($cart['full_days_text'] !== "") continue;

            $cart['price_index'] = $cartItem['price_index'];
            $cart['full_days'] = $cartItem['full_days'];
            $cart['extra_hours'] = $cartItem['extra_hours'];
            $cart['full_days_text'] = $cartItem['full_days_text'];
            $cart['extra_hours_text'] = $cartItem['extra_hours_text'];
        }

        return $cart;
    }

    public function setCancelPage($cancelPage) {
        $this->cancelPage = $cancelPage;
    }
    public function getCancelPage() {
        return $this->cancelPage;
    }

    public static function getReservationData($id) {
        $car_post_id = carbon_get_post_meta($id, 'product_id');

        $date_start = carbon_get_post_meta($id, 'date_start');
        $date_end = carbon_get_post_meta($id, 'date_end');

        $duration = DateHelper::getDatesDuration($date_start, $date_end);

        $price = carbon_get_post_meta($id, 'amount');
        if (!empty($price)) {
            $price = (int) $price / 100;
        }

        $booking = array(
            'name' => carbon_get_post_meta($id, 'name'),
            'car_post_id' => $car_post_id,
            'car_id' => carbon_get_post_meta($car_post_id, 'rentprog_id'),
            'start_date' => $date_start,
            'end_date' => $date_end,
            'start_place' =>  carbon_get_post_meta($id, 'location_start'),
            'end_place' =>  carbon_get_post_meta($id, 'location_end'),
            'phone' =>  carbon_get_post_meta($id, 'phone'),
            'email' =>  carbon_get_post_meta($id, 'email'),
            'days' => $duration['full_days'],
            'additional_hours' => $duration['extra_hours'],
            'price' => $price,
            'receipt_url' => carbon_get_post_meta($id, 'receipt_url'),
            'flight_number' => carbon_get_post_meta($id, 'flight_number'),
            'options' => carbon_get_post_meta($id, 'options'),
            'date_of_birth' => carbon_get_post_meta($id, 'date_of_birth'),
            'agree' => carbon_get_post_meta($id, 'agree'),
            'crm_booking_id' => carbon_get_post_meta($id, 'crm_booking_id'),
        );

        return $booking;
    }
}
