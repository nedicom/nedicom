<?php

namespace App\Helpers;

class PhoneValid
{
    public static function Clear($phones)
    {
        // 1. Проверка на пустую строку
        if (empty(trim($phones))) {
            return '';
        }

        // 2. Находим первый телефонный номер в строке с помощью регулярного выражения
        preg_match('/\+?[78][\s\-()]*\d{3}[\s\-()]*\d{3}[\s\-]*\d{2}[\s\-]*\d{2}/', $phones, $matches);

        if (empty($matches)) {
            return '';
        }

        $phone = $matches[0];

        // 3. Очищаем номер от всех нецифровых символов
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // 4. Проверяем длину и форматируем номер
        if (strlen($digits) === 11) {
            // Российские номера: заменяем начальную 7 на 8
            if ($digits[0] === '7') {
                return '8' . substr($digits, 1);
            }
            return $digits;
        }

        // Если номер без кода страны (10 цифр)
        if (strlen($digits) === 10) {
            return '8' . $digits;
        }

        return '';
    }
}
