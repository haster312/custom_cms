<?php

if (!function_exists('messages')) {
    function messages($name) {
        return __("messages.$name");
    }
}

if (!function_exists('roles')) {
    function roles($name) {
        return config("role.$name");
    }
}
