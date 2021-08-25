<?php

/**
 * Генерируем случайную дата
 * @param string $startTime
 * @param string $endTime
 * @return string
 */

function GetDaysBetweenDatesHelper($startTime, $endTime): array
{
    $day = 86400;
    $format = 'Y-m-d';
    $startTime = strtotime($startTime);
    $endTime = strtotime($endTime);
    //$numDays = round(($endTime - $startTime) / $day) + 1;
    $numDays = round(($endTime - $startTime) / $day); // без +1

    $days = array();

    $days[] = date($format, $startTime);

    for ($i = 1; $i < $numDays; $i++) {
        $days[] = date($format, ($startTime + ($i * $day)));
    }

    $days[] = date($format, $endTime);

    return $days;
}
