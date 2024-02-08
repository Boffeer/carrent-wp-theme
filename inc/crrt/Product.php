<?php

class Product {
    private $name;
    private $description;
    private $price;
    private $image;
    private $currency = 'eur';

    private $metadata;


    public function __construct($name, $priceTotalMessage, $image = null) {
        $this->name = $this->formatName($name, $priceTotalMessage);
        $this->price = $this->formatPrice($priceTotalMessage);
        $this->image = $image;
    }
    public function formatName($name, $priceTotalMessage) {
        $name = "{$name} ({$priceTotalMessage['full_days_text']}";
        if (!empty($priceTotalMessage['extra_hours'])) {
            $name .= " {$priceTotalMessage['extra_hours_text']}";
        }
        $name .= ")";
        return $name;
    }
    public function setDescription($metadata) {
        $description = "Start: {$metadata['location_start']}, {$metadata['date_start']}.  End: {$metadata['location_end']}, {$metadata['date_end']}.";

        if (!empty($metadata['flight_number'])) {
            $description .= "\n Flight number: {$metadata['flight_number']}";
        }

        $this->description = $description;
    }
    public function formatPrice($priceTotalMessage) {
        return $priceTotalMessage['price'] * 100;
    }

    public function get() {
        $productData = array(
            'name' => $this->name,
            'description' => $this->description,
        );
        if (!empty($this->image)) {
            $productData['images'] = [$this->image];
        }

        return array(
            'price_data' => [
                'currency' => $this->currency,
                'product_data' => $productData,
                'unit_amount' => $this->price, // Amount in cents (e.g., $19.99)
            ],
            'quantity' => 1,
        );
    }
}
