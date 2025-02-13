<?php

namespace App\Helpers;

class TgSend
{
    public static function SendMess($event, $name, $url)
    {
        $chat_name = env('MAIN_CHAT');
        $token = env('MAIN_CHAT_TOKEN');

        $message = $event . "\n" . $name . "\n" . $url . "\nТы - лучший!";

        $text = urlencode($message);
        $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_name}&text={$text}";

       file_get_contents($url);
    }
}
