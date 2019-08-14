<?php

class MnLibCatalogCatalog
{
    public static function getParamsFromUri($option)
    {
        $uriData = MnLibRequestRequest::getUriWithoutParams('array');
        $params = array();

        $params['base_url'] = '/' . array_shift($uriData) . '/';

        if ($option == 'shop') if ($uriData) $params['shop_code'] = array_shift($uriData);

        if ($uriData) $params['iblock_code'] = array_shift($uriData);

        //@todo not using brand_code now but it would be good to not just delete that
        //if ($option == 'shop') if ($uriData) $params['brand_code'] = array_shift($uriData);

        if ($lastItem = array_pop($uriData))
        {
            $elementSign = strrpos($lastItem, '.');
            if ($elementSign)
            {
                $params['element_code'] = substr($lastItem, 0, $elementSign);
                if ($uriData) $params['section_code'] = array_pop($uriData);
            }
            else $params['section_code'] = $lastItem;
        }

        while ($uriData) $params['section_parents'] = array_shift($uriData);

        return $params;
    }
}
