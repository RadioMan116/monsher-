<?php

class MnLibSaleLocationLocation
{
    public static function getCityById($id)
    {
        $city = CSaleLocation::GetByID($id);

        if (strpos($city['CITY_NAME'], ' (')) $city['name'] = substr($city['CITY_NAME'], 0, strpos($city['CITY_NAME'], ' ('));
        elseif (strpos($city['CITY_NAME'], ' [')) $city['name'] = substr($city['CITY_NAME'], 0, strpos($city['CITY_NAME'], ' ['));
        else $city['name'] = $city['CITY_NAME'];

        return $city;
    }
}
