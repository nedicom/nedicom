<?php

namespace App\Helpers;

class GetIp
{
    public static function get_ip()
    { {
            $value = '';

            $is_bot = empty($_SERVER['HTTP_USER_AGENT']) || preg_match(
                "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl|request|Guzzle|Java)~i",
                $_SERVER['HTTP_USER_AGENT']
            );

            if (!$is_bot) {
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $value = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $value = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
                    $value = $_SERVER['REMOTE_ADDR'];
                }
                return $value;
            }
            return false;
        }
    }
}
