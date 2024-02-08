<?php

class Product {
    private $name;
    private $description;
    private $price;
    private $image;
    private $currency = 'eur';


    public function __construct($name, $priceTotalMessage, $image = null) {
        $this->name = $this->formatName($name, $priceTotalMessage);
        $this->description = $this->formatDescription();
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
    public function formatDescription() {
        return 'desc';
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
