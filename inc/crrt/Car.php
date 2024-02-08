<?php

class Car {
    private $carId = null;
    private $name = null;
    private $rentalPeriodPrices = null;
    private $franchise = null;
    private $image = null;
    private $availableOptions = array();

    public function __construct($carId) {
        $this->carId = $carId;

        $this->name = carbon_get_post_meta($this->carId, 'car_name');
        $this->rentalPeriodPrices = explode(',', carbon_get_post_meta($this->carId, 'prices'));
        $this->franchise = (int)carbon_get_post_meta($this->carId, 'franchise');
        $this->image = get_image_url_by_id(carbon_get_post_meta($this->carId, 'photos')[0]);

        $options = carbon_get_post_meta($this->carId, 'car_options');
        foreach ($options as $option) {
            $this->availableOptions[] = array(
                'name' => $option['name'],
                'rentalPeriodPrices' => explode(',', $option['prices']),
            );
        }
    }

    public function getCarId() {
        return $this->carId;
    }
    public function getName() {
        return $this->name;
    }
    public function getImage() {
        return $this->image;
    }
    public function getRentalPeriodPrices() {
        return $this->rentalPeriodPrices;
    }
    public function getFranchise() {
        return $this->franchise;
    }

    public function getAvailableOptions() {
        return $this->availableOptions;
    }

    public function get() {
        return array(
            'id' => $this->carId,
            'name' => $this->name,
            'fullDayPrices' => $this->rentalPeriodPrices,
            'extraHourPrice' => $this->franchise,
            'image' => $this->image,
            'availableOptions' => $this->availableOptions,
        );
    }

}
