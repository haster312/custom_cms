<?php

namespace App\Helpers;


class StringHelper
{
    /**
     * Generate a random 8 characters string
     * @param $length
     * @return int
     */
    public function generateCode($length = 8)
    {
        $characters = 'QWERTYUIOPLKJHGFDSAZXCVBNM1234567890';
        $charactersLength = strlen($characters);

        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[mt_rand(0, $charactersLength - 1)];
        }

        return $code;
    }

    /**
     * Format phone to US number
     * @param $phone
     * @return string|string[]|null
     */
    public function formatPhone($phone)
    {
        // note: making sure we have something
        if (!isset($phone{3})) {
            return '';
        }
        // note: strip out everything but numbers
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $length = strlen($phone);
        switch ($length) {
            case 7:
                return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                break;
            case 10:
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
                break;
            case 11:
                return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
                break;
            default:
                return $phone;
                break;
        }
    }
}
