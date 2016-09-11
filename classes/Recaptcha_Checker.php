<?php

class Recaptcha_Checker {

    const RECAPTCHA_KEY = "6LdXyykTAAAAANUEdo6nkQKQXbTtPWcZJh9r4qOx";
    const RECAPTCHA_SECRET = "6LdXyykTAAAAAAm6WLEB8VmuFEAhHlpWGdzKjQb0";

    public static function verify($resp) {
        if (empty($resp)) {
            return false;
        }
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $url = $google_url . "?secret=" . self::RECAPTCHA_SECRET . "&response=" . $resp;
        $res = json_decode(self::getCurlData($url), true);
        if ($res["success"] == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    private static function getCurlData($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
//curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $curlData = curl_exec($curl);
        curl_close($curl);
        return $curlData;
    }

}
