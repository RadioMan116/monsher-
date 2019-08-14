<?php

//@todo wheres "get" prefix in get functions names
class MnLibMenuMenu
{
    public static function catalogWithIBlocks($baseUrl, $iblockType)
    {
        CModule::IncludeModule('iblock');

        $menu = array();

        $iBlocks = MnLibIBlockIBlock::getByType($iblockType);

        foreach ($iBlocks as $iBlock)
        {
            $link = $baseUrl . $iBlock['CODE'] . '/';
            $menu[] = array($iBlock['NAME'], $link, array(), array('DEPTH_LEVEL' => 1));

            if (substr(MnLibRequestRequest::getUriWithoutParams(), 0, strlen($link)) == $link)
            {
                $iBlockSubMenu = MnServiceComponentComponent::getMenuSections($iblockType, $iBlock['ID'], $baseUrl);

                foreach ($iBlockSubMenu as $subMenu)
                {
                    $subMenu[3]['DEPTH_LEVEL']++;
                    $menu[] = $subMenu;
                }
            }
        }

        return $menu;
    }

    //@todo refactor - change name and get element list query out
    //@todo this is not right place for that
    public static function shopsWithIBlocks($shopsIBlockId, $baseUrl)
    {
        CModule::IncludeModule('iblock');

        $menu = array();

        $dbElements = CIBlockElement::GetList(array('IBLOCK_SECTION_ID' => 'ASC'), array(
                'IBLOCK_ID'             => $shopsIBlockId,
                'ACTIVE'                => 'Y',
            ), false, false, array()
        );

        while ($arElement = $dbElements->getNextElement())
        {
            $item = $arElement->GetFields();
            $item['PROPS'] = $arElement->GetProperties();

            $link = $baseUrl . $item['CODE'] . '/';
            $menu[] = array($item['NAME'], $link, array(), array('DEPTH_LEVEL' => 1, 'ID' => $item['ID'], 'BRANDS' => $item['PROPS']['_BRANDS']['VALUE']));
        }

        return $menu;
    }
}
