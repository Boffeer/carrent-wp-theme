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
        return array_merge([$endTimestamp], $resultDates);
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