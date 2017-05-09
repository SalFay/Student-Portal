<?php

class Filter {

    public static function string($string) {
        $str = trim($string);
        return filter_var($str, FILTER_SANITIZE_STRING);
    }

    public static function html($html) {
        return $html;
    }

}
