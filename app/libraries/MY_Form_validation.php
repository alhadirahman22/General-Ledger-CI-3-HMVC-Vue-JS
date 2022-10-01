<?php

class MY_Form_validation extends CI_Form_validation {

    public function seo_url($str) {
        return (bool) preg_match('/^[a-z0-9\/\_.,-]+$/i', $str);
    }

    public function code($str) {
        return (bool) preg_match('/^[a-z0-9\_.,-]+$/i', $str);
    }

}
