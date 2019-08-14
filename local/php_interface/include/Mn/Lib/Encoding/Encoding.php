<?php

class MnLibEncodingEncoding
{
    public static function convertArray($array, $from, $to)
    {
        foreach($array as $key => $value)
        {
            if (is_array($value) && count($value) > 0)
            {
                $nestedFunctionParams = array($value, $from, $to);
                $converted[$key] = call_user_func_array(__METHOD__, $nestedFunctionParams);
                continue;
            }

            $converted[$key] = mb_convert_encoding($value, $to, $from);
        }

        return $converted;
    }
}
