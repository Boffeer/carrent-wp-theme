<?php


class DatesRange {
    private array $datesRange = array(
        'start' => null,
        'end' => null,
    );
    public function __construct($startDate, $startTime, $endDate, $endTime) {
        $this->datesRange['start'] = DateHelper::createDateTime($startDate, $startTime);
        $this->datesRange['end'] = DateHelper::createDateTime($endDate, $endTime);
    }
    public function get() {
        return $this->datesRange;
    }
}

