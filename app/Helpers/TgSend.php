<?php
namespace App\Helpers;

class TgSend
{
    public static function SendMess($event, $name, $url)
    {
        $chat_name = env('MAIN_CHAT');
        $token = env('MAIN_CHAT_TOKEN');

        $message = "$event\n название - $name\n ссылка - $url\n Ты - лучший!";

        $text = urlencode($message);
        $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_name}&text={$text}";

        $ch = curl_init();
        $optArray = [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true];
        curl_setopt_array($ch, $optArray);
        curl_exec($ch);
        curl_close($ch);
    }
}
