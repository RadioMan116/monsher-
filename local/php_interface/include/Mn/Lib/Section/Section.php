<?php

class MnLibSectionSection
{
    //@todo lol, what this doing here
    public static function getPropsBySectionAndIBlockIds($sectionId, $iBlockId, $iBlockType, $props = array())
    {
        CModule::IncludeModule('iblock');

        $sectionProperties = array();

        $dbSections = CIBlockSection::GetList(array(), array(
                'IBLOCK_ID'     => $iBlockId,
                'ID'            => $sectionId,
                'IBLOCK_TYPE'   => $iBlockType,
                'GLOBAL_ACTIVE' => 'Y',
                'ACTIVE'        => 'Y',
                'IBLOCK_ACTIVE' => 'Y'
            ), false, $props
        );

        if (is_array($sectionId))
        {
            while ($arSection = $dbSections->GetNext())
            {
                foreach ($props as $property)
                {
                    if (is_array($arSection[$property]))
                        $arSection[$property] = MnLibUserField::getListValues($arSection[$property]);

                    $sectionProperties[$arSection['ID']][$property] = $arSection[$property];
                }
            }
        }
        else
        {
            $arSection = $dbSections->Fetch();

            if (count($props) == 1)
            {
                if (is_array($arSection[$props[0]]))
                    $arSection[$props[0]] = MnLibUserField::getListValues($arSection[$props[0]]);

                return $arSection[$props[0]];
            }
            else foreach ($props as $property)
            {
                if (is_array($arSection[$property]))
                    $arSection[$property] = MnLibUserField::getListValues($arSection[$property]);

                $sectionProperties[$property] = $arSection[$property];
            }
        }

        return $sectionProperties;
    }

    public static function isParentByCodes($sectionCode, $iBlockCode, $iBlockType)
    {
        CModule::IncludeModule('iblock');

        if ($sectionCode)
        {
            $dbSection = CIBlockSection::GetList(array(), array(
                    'IBLOCK_TYPE'   => $iBlockType,
                    'IBLOCK_CODE'   => $iBlockCode,
                    'CODE'          => $sectionCode,
                    'GLOBAL_ACTIVE' => 'Y',
                    'ACTIVE'        => 'Y',
                    'IBLOCK_ACTIVE' => 'Y'
                ), false
            );

            $arSection = $dbSection->Fetch();
            //@todo check this or better delegate if possible
            if (!$arSection)
            {
                include($_SERVER["DOCUMENT_ROOT"]."/404.php");
                exit();
            }

            $dbSubSections = CIBlockSection::GetList(array(), array(
                    'IBLOCK_TYPE'   => $iBlockType,
                    'IBLOCK_CODE'   => $iBlockCode,
                    'SECTION_ID'    => $arSection['ID'],
                    'GLOBAL_ACTIVE' => 'Y',
                    'ACTIVE'        => 'Y',
                    'IBLOCK_ACTIVE' => 'Y'
                ), false
            );

            if ($arSubSection = $dbSubSections->Fetch()) return true;

        }
        else
        {
            $dbSections = CIBlockSection::GetList(array(), array(
                    'IBLOCK_TYPE'   => $iBlockType,
                    'IBLOCK_CODE'   => $iBlockCode,
                    'GLOBAL_ACTIVE' => 'Y',
                    'ACTIVE'        => 'Y',
                    'IBLOCK_ACTIVE' => 'Y'
                ), false
            );

            if ($arSection = $dbSections->Fetch()) return true;
        }
    }

    public static function getSiblings($sectionParentId, $iBlockType, $iBlockId, $depthLevel)
    {
        CModule::IncludeModule('iblock');

        $dbSections = CIBlockSection::GetList(array(), array(
                'IBLOCK_TYPE'   => $iBlockType,
                'IBLOCK_ID'     => $iBlockId,
                'SECTION_ID'    => $sectionParentId,
                'DEPTH_LEVEL'   => $depthLevel,
                'GLOBAL_ACTIVE' => 'Y',
                'ACTIVE'        => 'Y',
                'IBLOCK_ACTIVE' => 'Y'
            ), false
        );

        $siblings = array();
        while ($arSection = $dbSections->GetNext())
            $siblings[] = $arSection;

        return $siblings;
    }

    public static function getByCodeAndIBlockIdAndIBlockType($sectionCode, $iBlockId, $iBlockType)
    {
        CModule::IncludeModule('iblock');

        $dbSections = CIBlockSection::GetList(array(), array(
                'IBLOCK_TYPE'   => $iBlockType,
                'IBLOCK_ID'     => $iBlockId,
                'CODE'    => $sectionCode,
                'GLOBAL_ACTIVE' => 'Y',
                'ACTIVE'        => 'Y',
                'IBLOCK_ACTIVE' => 'Y'
            ), false
        );

        if ($arSection = $dbSections->Fetch())
            return $arSection;

    }

    public static function isExistByName($name, $iBlockId, $options = array())
    {
        $dbSections = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $iBlockId, 'NAME' => $name));
        if ($arSection = $dbSections->GetNext()) return true;
    }

    public function isExistByIBlockId($iBlockId, $options = array())
    {
        $dbSections = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $iBlockId));
        if ($arSection = $dbSections->GetNext()) return true;
    }
}
