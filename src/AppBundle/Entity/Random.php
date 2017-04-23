<?php

namespace AppBundle\Entity;

class Random{
    public static function alpha($length = 15, $symbol = false , $case_sensitive = false ){

        $chars = 'abcdefghijklmnopqrstuvwxyz';
        if ( $case_sensitive)
            $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ( $symbol )
            $chars .= '!@#$%^&*()';

        $str = '';
        for ( $i = 0; $i < $length; $i++ )
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);

        return $str;
    }
    public static function numeric($length = 12, $symbol = false ){
        $chars = '1234567890';

        if ( $symbol )
            $chars .= '!@#$%^&*()';

        $str = '';
        for ( $i = 0; $i < $length; $i++ )
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);

        return $str;
    }
    public static function all($length = 15, $symbol = true , $case_sensitive = true){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+';

        if ( $case_sensitive)
            $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ( $symbol )
            $chars .= '!@#$%^&*()';

        $str = '';
        for ( $i = 0; $i < $length; $i++ )
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);

        return $str;
    }
}