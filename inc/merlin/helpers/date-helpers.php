<?php

function get_dates_range($startDateString, $endDateString, $returnType = 'array') {
    // Преобразовываем строки в объекты DateTime
    $startDate = DateTime::createFromFormat('d-m-Y H:i', $startDateString);
    $endDate = DateTime::createFromFormat('d-m-Y H:i', $endDateString);

    // Проверяем, что введены корректные даты
    if (!$startDate || !$endDate) {
        return "Некорректные даты";
    }

    // Создаем массив для хранения результатов
    $resultDates = [];

    // Итерируем по датам и добавляем их в результат
    $currentDate = clone $startDate;
    while ($currentDate <= $endDate) {
        $resultDates[] = $currentDate->getTimestamp();
        $currentDate->modify('+1 day');
    }

    // Преобразуем все даты в таймстэмпы
    $startTimestamp = $startDate->getTimestamp();
    $endTimestamp = $endDate->getTimestamp();

    if ($returnType === 'array') {
        return $resultDates;
//        return array_merge([$startTimestamp, $endTimestamp], $resultDates);
    } else {
        $resultTimestamps = array_combine(array_map(function ($date) {
            return 'date_' . $date;
        }, $resultDates), $resultDates);

        return array_merge(['startTimestamp' => $startTimestamp, 'endTimestamp' => $endTimestamp], $resultTimestamps);
    }
}

function getDatesRange($startDateString, $endDateString, $returnType = 'array') {
    // Преобразовываем строки в объекты DateTime
    $startDateTime = DateTime::createFromFormat('d-m-Y H:i', $startDateString);
    $endDateTime = DateTime::createFromFormat('d-m-Y H:i', $endDateString);

    // Проверяем, что введены корректные даты
    if (!$startDateTime || !$endDateTime) {
        return "Некорректные даты";
    }

    // Создаем массив для хранения результатов
    $resultDates = [];

    // Итерируем по датам и добавляем их в результат
    $currentDateTime = clone $startDateTime;
    while ($currentDateTime <= $endDateTime) {
        $resultDates[] = $currentDateTime->format('d-m-Y H:i');
        $currentDateTime->modify('+1 day');
    }

    // Преобразуем все даты в таймстэмпы
    $startTimestamp = $startDateTime->getTimestamp();
    $endTimestamp = $endDateTime->getTimestamp();

    if ($returnType === 'array') {
        return array_merge([$endDateString], $resultDates);
    } else {
        $resultTimestamps = array_combine(array_map(function ($date) {
            return 'date_' . $date;
        }, $resultDates), $resultDates);

        return array_merge(['startTimestamp' => $startTimestamp, 'endTimestamp' => $endTimestamp], $resultTimestamps);
    }
}

function convert_rentprog_date($timestamp) {
    // Time zone
    $timezone = new DateTimeZone('Europe/Moscow');

    // Create a DateTime object with the given timestamp and time zone
    $date = new DateTime('@' . $timestamp);
    $date->setTimeZone($timezone);

    // Format the date as required
    $formattedDateString = $date->format('D M d Y H:i:s T');

    return $formattedDateString;
}

class DateHelper
{
    /**
     * @param string $date. date have to be formatted as "DD.MM.YY HH:MM"
     * @return int|null
     */
    public static function dateToTimestamp(string $date): ?int
    {
        $dateTime = DateTime::createFromFormat('d-m-Y H:i', $date);
        return $dateTime ? $dateTime->getTimestamp() : null;
    }

    /**
     * @param int $daysToAdd
     * @return int|null
     */
    public static function addDaysToCurrentTimestamp(int $daysToAdd): ?int
    {
        $currentDate = new DateTime();
        $currentDate->modify("+$daysToAdd days");

        return $currentDate->getTimestamp();
    }

    /**
     * @param string $inputDate
     * @param int $daysToAdd
     * @param bool $isTimestamp
     * @return string|null
     */
    public static function addDaysToDate(string $inputDate, int $daysToAdd, bool $isTimestamp = false): ?string
    {
        $dateTime = DateTime::createFromFormat('d-m-Y H:i', $inputDate);

        if ($dateTime === false) {
            return null; // Invalid input date format
        }

        $dateTime->modify("+$daysToAdd days");

        if ($isTimestamp) return $dateTime->getTimestamp();

        return $dateTime->format('d-m-Y H:i');
    }

    /**
     * @param int $timestamp
     * @return string
     */
    public static function timestampToDate(int $timestamp): string
    {
        $dateTime = new DateTime();
        $dateTime->setTimestamp($timestamp);
        return $dateTime->format('d.m.Y H:i');
    }

    /**
     * @throws Exception
     */
    public static function getDatesDuration($dateStart, $dateEnd) {
        if  (is_string($dateStart) && is_string($dateEnd)) {
            $dateStart = self::dateToTimestamp($dateStart);
            $dateEnd = self::dateToTimestamp($dateEnd);
        }
        $dateStart = new DateTime("@$dateStart");
        $dateEnd = new DateTime("@$dateEnd");

        $interval = $dateStart->diff($dateEnd);

        $duration = array(
            'full_days' => $interval->days,
            'extra_hours' => $interval->h,
        );

        if ($interval->i > 0) {
            $duration['extra_hours']++;
        }

        $dayThreshold = (int)carbon_get_theme_option('day_threshold');
        if ($duration['extra_hours'] > $dayThreshold) {
            $duration['full_days']++;
            $duration['extra_hours'] = 0;
        } else if ($duration['extra_hours'] < $dayThreshold && $duration['full_days'] === 0) {
            $duration['full_days'] = 1;
            $duration['extra_hours'] = 0;
        }

        return $duration;
    }

    public static function createDateTime($date, $time) {
        return "{$date} {$time}";
    }
}