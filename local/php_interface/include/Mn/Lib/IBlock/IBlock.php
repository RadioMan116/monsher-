<?php

class MnLibIBlockIBlock
{
    public static function getByCodeAndType($code, $type, $options = array())
    {
        CModule::IncludeModule('iblock');

        $filter = MnLibIBlockFilter::getForQuery(array('CODE' => $code, 'TYPE' => $type), $options);

        $dbIBlock = CIBlock::GetList(array(), $filter);
        $arIBlock = $dbIBlock->Fetch();

        return $arIBlock;
    }

    public static function getById($id, $options = array())
    {
        CModule::IncludeModule('iblock');

        $filter = MnLibIBlockFilter::getForQuery(array('ID' => $id), $options);

        $iBlocks = array();
        $dbIBlocks = CIBlock::GetList(array(), $filter);
        while ($arIBlock = $dbIBlocks->Fetch()) $iBlocks[] = $arIBlock;

        if (!is_array($id)) $iBlocks = $iBlocks[0];

        return $iBlocks;
    }

    public static function getByType($type, $options = array())
    {
        CModule::IncludeModule('iblock');

        $filter = MnLibIBlockFilter::getForQuery(array('TYPE' => $type), $options);

        $iBlocks = array();
        $dbIBlocks = CIBlock::GetList(array('SORT' => 'ASC'), $filter);
        while ($arIBlock = $dbIBlocks->Fetch()) $iBlocks[] = $arIBlock;

        return $iBlocks;
    }

    public static function hasSections($iBlockId, $options = array())
    {
        if (MnLibSectionSection::isExistByIBlockId($iBlockId, $options)) return true;
    }
}
