<?php

/**
 * Определяем попадает ли число набора диапозонов (массив массивов)
 * @param array $array
 * @param $item
 * @return bool
 */

function WhereBetweenArrayHelper(array $array, int $item): bool
{
    $result = false;
    foreach ($array as $arr) {
        if ($arr[0] <= $item && $arr[1] > $item) {
            $result = true;
            break;
        }
    }
    return $result;
}
