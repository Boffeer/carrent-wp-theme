<?php


if (!function_exists('get_vd')) {
    function get_vd($vd)
    {
        echo '<pre>';
        var_dump($vd);
        echo '</pre>';
    }
}

if (!function_exists('log_telegram')) {
    function log_telegram($text) {
        $token = "6087568357:AAG3vvnV91U-u9lkp4-RdqvpWy4JUSNY9nM";
        $chat_id = "-1001726120894";

        $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$text}","r");
        return $sendToTelegram;
    }
}


if (!function_exists('get_protected_value')) {
    function get_protected_value($obj, $prop) {
        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);

        return $property->getValue($obj);
    }
}
