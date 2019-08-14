<?php

class MnLibElementFilter
{
    public static function getForQuery($filter, $options)
    {
        if (!array_key_exists('ACTIVE', $options)) $options['ACTIVE'] = 'Y';

        if (!array_key_exists('ACTIVE', $filter)) $filter['ACTIVE'] = $options['ACTIVE'];
        if (!array_key_exists('IBLOCK_ACTIVE', $filter) && $options['ACTIVE'] != 'N') $filter['IBLOCK_ACTIVE'] = $options['ACTIVE'];
        if (!array_key_exists('SECTION_ACTIVE', $filter) && $options['ACTIVE'] != 'N') $filter['SECTION_ACTIVE'] = $options['ACTIVE'];
        if (!array_key_exists('SECTION_GLOBAL_ACTIVE', $filter) && $options['ACTIVE'] != 'N') $filter['SECTION_GLOBAL_ACTIVE'] = $options['ACTIVE'];
        if (!array_key_exists('INCLUDE_SUBSECTIONS', $filter)) $filter['INCLUDE_SUBSECTIONS'] = 'Y';

        $filterAdds = array();
        if(array_key_exists('SECTION_ACTIVE', $filter))
        {
            $filterAdds['section']['SECTION_ACTIVE'] = $filter['SECTION_ACTIVE'];
            unset($filter['SECTION_ACTIVE']);
        }
        if(array_key_exists('SECTION_GLOBAL_ACTIVE', $filter))
        {
            $filterAdds['section']['SECTION_GLOBAL_ACTIVE'] = $filter['SECTION_GLOBAL_ACTIVE'];
            unset($filter['SECTION_GLOBAL_ACTIVE']);
        }

        return array('base' => $filter, 'adds' => $filterAdds);
    }

    public static function combine($filter)
    {
        $filterCombined = $filter['base'];

        foreach ($filter['adds'] as $filterAdd)
        {
            $filterCombined = array_merge($filterCombined, $filterAdd);
        }

        return $filterCombined;
    }
}
