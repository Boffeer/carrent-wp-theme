<?php

class PriceCalculator {
    private int $days;
    private int $singleDayPrice;

    private int $hours;
    private int $franchise;


    public function __construct(array $rentalPeriodPrices, $datesRange,  int $franchise = 0)
    {
        $duration = DateHelper::getDatesDuration($datesRange['start'], $datesRange['end']);
        $this->days = $duration['full_days'];
        $this->singleDayPrice = (int)($rentalPeriodPrices[$this->getPriceIndex()] ?? end($rentalPeriodPrices));

        $this->hours = $duration['extra_hours'];
        $this->franchise = $franchise;
    }

    private function getPriceIndex() {
        if ($this->days < 4) {
            $priceIndex = 0;
        } elseif ($this->days < 8) {
            $priceIndex = 1;
        } elseif ($this->days < 16) {
            $priceIndex = 2;
        } elseif ($this->days < 31) {
            $priceIndex = 3;
        } else {
            $priceIndex = 4;
        }

        return $priceIndex;
    }

    private function getTotalForDays () {
        return $this->days * $this->singleDayPrice;
    }
    private function getTotalFranchise() {
        if ($this->franchise === 0) return 0;
        return $this->franchise * $this->hours;
    }
    public function calculateTotal() {
        return $this->getTotalForDays() + $this->getTotalFranchise();
    }

    public function getTotalMessage() {

        $fullDaysText = declension($this->days, ['day', 'days', 'days']);
        $extraHoursText = '';
        if ($this->hours > 0) {
            $extraHoursText = declension($this->hours, ['hour', 'hours', 'hours']);
        }

        return array(
            'per_day' => $this->singleDayPrice,
            'price' => $this->calculateTotal(),
            'full_days' => $this->days,
            'extra_hours' => $this->hours,
            'full_days_text' => $fullDaysText,
            'extra_hours_text' => $extraHoursText,
            'price_index' => $this->getPriceIndex(),
        );
    }
}
