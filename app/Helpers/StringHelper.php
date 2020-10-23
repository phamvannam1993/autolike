<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/19/2018
 * Time: 9:46 AM
 */

namespace App\Helpers;


class StringHelper
{
    public static function quickRandom($pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $length = 16)
    {
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public static function generateCode($length = 6)
    {
        return self::quickRandom('23456789ABCDEFGHJKLMNPQRSTUVWXYZ', $length);
    }
}