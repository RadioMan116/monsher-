<?php

class MnLibElementElement
{
    public static function getById($id, $options = array(), $select = array())
    {
        if (empty($id)) return false;

        $filter = MnLibElementFilter::getForQuery(array('ID' => $id), $options);

        $elements = array();
        $dbElements = CIBlockElement::GetList(array(), $filter['base'], false, false, $select);
        while ($arElement = $dbElements->GetNextElement())
        {
            $element = $arElement->GetFields();

            if ($options['ACTIVE'] != null && MnLibIBlockIBlock::hasSections($element['IBLOCK_ID'], array('ACTIVE' => null)))
            {
                $filterFull = MnLibElementFilter::combine($filter);
                $filterFull['ID'] = $element['ID'];

                $dbElement = CIBlockElement::GetList(array(), $filterFull, false, false, array());
                if (!$arElement = $dbElement->GetNextElement()) continue;
            }

            if (empty($select) && !isset($options['PROPERTIES'])) $element['PROPS'] = $arElement->GetProperties();
            else
            {
                if (isset($options['PROPERTIES']))
                    $element['PROPS'] = $arElement->GetProperties(array(), array('CODE' => $options['PROPERTIES']));
            }

            $elements[] = $element;
        }

        if (!is_array($id)) $elements = $elements[0];

        return $elements;
    }

    public static function getByCodeAndIBlockId($code, $iBlockId, $options = array())
    {
        if (empty($code)) return false;

        $filter = MnLibElementFilter::getForQuery(array('CODE' => $code, 'IBLOCK_ID' => $iBlockId), $options);

        if ($options['ACTIVE'] != null && MnLibIBlockIBlock::hasSections($iBlockId, array('ACTIVE' => null)))
        {
            $filterFull = MnLibElementFilter::combine($filter);
            $dbElement = CIBlockElement::GetList(array(), $filterFull, false, false, array());
        }
        else
        {
            $dbElement = CIBlockElement::GetList(array(), $filter['base'], false, false, array());
        }

        if ($arElement = $dbElement->GetNextElement())
        {
            $element = $arElement->GetFields();
            $element['PROPS'] = $arElement->GetProperties();
        }

        return $element;
    }

    public static function getByIBlockId($iBlockId, $addFilter = array(), $select = array(), $options = array())
    {
        CModule::IncludeModule('iblock');
        CPageOption::SetOptionString("main", "nav_page_in_session", "N"); //here while paging options inside

        $filterSourceCombined = array_merge(array('IBLOCK_ID' => $iBlockId), $addFilter);
        $filter = MnLibElementFilter::getForQuery($filterSourceCombined, $options);

        if ($options['ACTIVE'] != null && MnLibIBlockIBlock::hasSections($iBlockId, array('ACTIVE' => null)))
        {
            $filterFull = MnLibElementFilter::combine($filter);
            $dbElements = CIBlockElement::GetList(array('ID' => 'DESC'), $filterFull, false, false, $select);
        }
        else
        {
            $dbElements = CIBlockElement::GetList(array('ID' => 'DESC'), $filter['base'], false, false, $select);
        }

        if ($options['ITEMS_PER_PAGE'] != null)
        {
            $dbElements->NavStart($options['ITEMS_PER_PAGE']);
            $arResult["NAV_STRING"] = $dbElements->GetPageNavString('');
            $arResult["NAV_RESULT"] = $dbElements;
        }

        $elements = array();
        while ($arElement = $dbElements->getNextElement())
        {
            $element = $arElement->GetFields();

            if ($options['NO_PROPS'] != 'Y')
            {
                foreach ($select as $selectedField)
                {
                    if (substr($selectedField, 0, 9) == 'PROPERTY_' && !isset($options['PROPERTIES'])) $noPropsAddFetch = 'Y';
                }
            }
            else
            {
                if (!isset($options['PROPERTIES']))
                {
                    $noPropsAddFetch = 'Y';
                }
            }

            if ($noPropsAddFetch != 'Y')
            {
                if (isset($options['PROPERTIES']))
                {
                    $element['PROPS'] = $arElement->GetProperties(array(), array('CODE' => $options['PROPERTIES']));
                }
                else
                {
                    $element['PROPS'] = $arElement->GetProperties();
                }
            }

            if ($options['NO_ADDITIONS'] != 'Y')
            {
                $element['PREVIEW_PICTURE'] = CFile::GetFileArray($element['PREVIEW_PICTURE']);
            }

            $elements[] = $element;
        }

        if (isset($options['INDEX'])) $elements = MnLibToolsTools::reindexArrayBy($options['INDEX'], $elements);

        if ($options['ITEMS_PER_PAGE'] != null)
        {
            $arResult['ITEMS'] = $elements;
            return $arResult;
        }
        else return $elements;
    }

    public static function create($params = array(), $props = array())
    {
        $fields = $params;
        $fields['PROPERTY_VALUES'] = $props;

        $element = new CIBlockElement;
        $id = $element->Add($fields);

        return $id;
    }

    public static function getForIBlockType($iBlockType, $addFilter = array(), $select = array(), $options = array())
    {
        CModule::IncludeModule('iblock');

        if (isset($options['INDEX']))
        {
            $postOptions = array('INDEX' => $options['INDEX']);
            unset($options['INDEX']);
        }

        $iBlocks = MnLibIBlockIBlock::getByType($iBlockType, array('ACTIVE' => null));

        $elements = array();
        foreach ($iBlocks as $iBlock)
        {
            $iBlockElements = self::getByIBlockId($iBlock['ID'], $addFilter, $select, $options);
            $elements = array_merge($elements, $iBlockElements);
        }

        if (isset($postOptions['INDEX'])) $elements = MnLibToolsTools::reindexArrayBy($postOptions['INDEX'], $elements);

        return $elements;
    }
}
