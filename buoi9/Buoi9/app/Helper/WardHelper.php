<?php

namespace App\Helper;

class WardHelper{

    public static function get_ward(){
        $json = file_get_contents(public_path("data/wards.json"));
        $data=json_decode($json,true);

        return collect($data['communes']??[])
        ->sortBy('name')
        ->values()
        ->toArray();
    }

    public static function get_Wards(){
        return self::get_ward();
    }
}
