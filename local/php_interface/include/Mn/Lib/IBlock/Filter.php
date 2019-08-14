<?php

class MnLibIBlockFilter
{
    public static function getForQuery($filter, $options)
    {
        if (!array_key_exists('ACTIVE', $options)) $options['ACTIVE'] = 'Y';

        if (!array_key_exists('ACTIVE', $filter)) $filter['ACTIVE'] = $options['ACTIVE'];

        return $filter;
    }
}
