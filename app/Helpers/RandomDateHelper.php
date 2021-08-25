<?php

/**
 * Генерируем случайную дата
 * @param string $format
 * @return string
 */

function RandomDateHelper(string $format = 'Y-m-d'): string
{
    return date($format, strtotime('+' . mt_rand(-90, 0) . ' days')) . " " . date('H', strtotime('+' . mt_rand(0, 24) . ' hours')) . ":" . rand(1, 59) . ":" . rand(1, 59);
}
