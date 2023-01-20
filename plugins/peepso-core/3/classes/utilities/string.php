<?php

class PeepSo3_Utilities_String {

    /**
     * @param string $text
     * @param bool $grayscale three channels get averaged into a grayscale result
     * @param int $randomize whether the same string should result in slightly different color for the same string
     * @param int $min_brightness 0-255 the lowest acceptable average of 3 channels
     * @param int $brigtness_scale how far from min_brightness we can go
     * @return string
     */
    public static function hex_color_from_string(string $text, bool $grayscale = FALSE, int $randomize = 20, int $min_brightness=120, int $brigtness_scale=0)
    {
        if($min_brightness < 0 || $min_brightness > 255) {
            $min_brightness = 100;
        }

        $max_brigthness = min(255, $min_brightness + $brigtness_scale);

        $hash = md5($text);

        $colors = array();

        for($i=0;$i<3;$i++) {
            $colors[$i] = max(array(round(((hexdec(substr($hash, 10 * $i, 10))) / hexdec(str_pad('', 10, 'F'))) * 255), $min_brightness)); //convert hash into 3 decimal values between 0 and 255
        }

        // Randomizing allows different results based on the same string, if desired
        if($randomize > 0) {
            for ($i = 0; $i < 3; $i++) {
                $rand = mt_rand(0-$randomize, $randomize);
                $colors[$i] += $rand;
            }
        }

        // Adjust for minimum brightness
        while (array_sum($colors) / 3 < $min_brightness) {
            for ($i = 0; $i < 3; $i++) {
                $colors[$i] += 10;
            }
        }


        // Adjust for maximum brightness
        if($max_brigthness >= $min_brightness) {
            while (array_sum($colors) / 3 > $max_brigthness) {
                for ($i = 0; $i < 3; $i++) {
                    $colors[$i] = max(0,$colors[$i] - 10);
                }
            }
        }

        // Final brigthness validation
        for ($i = 0; $i < 3; $i++) {
            if($colors[$i]<0) {
                $colors[$i] = 0;
            }
            if($colors[$i]>255) {
                $colors[$i] = 255;
            }
        }


        // Average if it's supposed to be grayscale
        if($grayscale) {
            $avg = intval(array_sum($colors) / 3);
            for($i=0;$i<3;$i++) {
                $colors[$i] = $avg;
            }
        }

        $output = '';

        for($i=0;$i<3;$i++) {
            $output .= str_pad(dechex($colors[$i]), 2, 0, STR_PAD_LEFT);
        }

        return $output;
    }

    public static function maybe_mb_substr(string $string, int $start, int $end) {

        if(function_exists('mb_substr')) {
            return mb_substr($string, $start, $end);
        }

        return substr($string, $start, $end);
    }

    public static function maybe_mb_strlen(string $string) {

        if(function_exists('mb_strlen')) {
            return mb_strlen($string);
        }

        return strlen($string);
    }

    public static function maybe_mb_ucwords(string $string, $mode = MB_CASE_TITLE) {
        if(function_exists('mb_convert_case')) {
            return mb_convert_case($string, $mode);
        }

        return ucwords($string);
    }

    public static function intval($text) {

        if(filter_var($text, FILTER_VALIDATE_INT)) {
            return intval($text);
        }

        if(is_numeric($text)) {
            return $text;
        }

        return 0;
    }

    public static function shorten_big_number($n,$decimals=1, $threshold=1000) {

        if ($n < $threshold) {
            return $n;
        }

        if ($n < 1000000) {
            return number_format($n / 1000, $decimals) . _x('k','short for thousands','peepso-core');
        }

        return number_format($n / 1000000, $decimals) . _x('m', 'short for millions', 'peepso-core');

        return $n;
    }
}