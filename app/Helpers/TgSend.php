<?php

namespace App\Helpers;

class TgSend
{
    public static function SendMess($name)
    {
        $phone = 321;
        $chat_name = "@nedicom_group";
        $token = "7471342210:AAEDkhuLXZootfnjOjDWpbKoeNLSuxzJhUw";
        $message = "Имя клиента - $name\n телефон - $phone\n Всем хорошего дня!";

        $text = urlencode($message);
        $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_name}&text={$text}";

        $ch = curl_init();
        $optArray = [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true];
        curl_setopt_array($ch, $optArray);
        curl_exec($ch);
        curl_close($ch);
    }
}
