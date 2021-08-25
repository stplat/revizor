<?php

/**
 * Генерируем случайную дата
 * @param string $type
 * @return string
 */

function BoxTypesHelper(string $type): string
{
    switch ($type) {
        case 'koib':
            return 'КОИБ';
        case 'ballot_box':
            return 'Избирательный ящик';
        default:
            return 'Н/д';
    }
}
