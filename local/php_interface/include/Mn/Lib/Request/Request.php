<?php

class MnLibRequestRequest
{
    public static function getUriWithoutParams($returnType = null, $customAddress = null)
    {
        if (isset($customAddress)) $uri = str_replace('http://' . $_SERVER['HTTP_HOST'], '', $customAddress);
        else $uri = $_SERVER['REQUEST_URI'];

        $paramsSign = strpos($uri, '?');
        if ($paramsSign) $uri = substr($uri, 0, $paramsSign);

        switch ($returnType)
        {
            case 'array':
                $uri = explode('/', trim($uri, '/'));
                break;
        }

        return $uri;
    }

    public static function isAjax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }
}
