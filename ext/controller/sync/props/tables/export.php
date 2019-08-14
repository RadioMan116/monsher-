<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
ini_set('max_execution_time', 60000);
CModule::IncludeModule('iblock');


$iBlocksType = 's2_catalog';

$originCategoriesLinkage = array(
    141 => 29,
    70  => 30,
    69  => 31,
    140 => 29,
    148 => 49
);


$xml = '<props>' . PHP_EOL;

foreach ($originCategoriesLinkage as $sourceIBlockId => $targetIBlockId)
{
    $props = MnLibIBlockPropertyProperty::getForIBlock($sourceIBlockId);

    foreach($props as $prop)
    {
        $xml.= '<prop>' . PHP_EOL;

        $xml.= '<id>' . $prop['ID'] . '</id>' . PHP_EOL;
        $xml.= '<category>' . $sourceIBlockId . '</category>' . PHP_EOL;
        $xml.= '<origin_category>' . $targetIBlockId . '</origin_category>' . PHP_EOL;
        $xml.= '<name>' . htmlspecialcharsbx($prop['NAME']) . '</name>' . PHP_EOL;
        $xml.= '<active>' . ($prop["ACTIVE"]=="Y"? "true": "false") . '</active>' . PHP_EOL;
        $xml.= '<multiple>' . ($prop["MULTIPLE"]=="Y"? "true": "false") . '</multiple>' . PHP_EOL;

        if($prop["PROPERTY_TYPE"]=="L")
        {
            $xml.= '<choices>' . PHP_EOL;

            $propListData = MnLibIBlockPropertyProperty::getListTypeData($prop['ID']);
            foreach ($propListData as $propListEntry)
            {
                $xml.= '<choice>' . PHP_EOL;
                $xml.= '<xml_id>' . htmlspecialcharsbx($propListEntry['XML_ID']) . '</xml_id>' . PHP_EOL;
                $xml.= '<value>' . htmlspecialcharsbx($propListEntry['VALUE']) . '</value>' . PHP_EOL;
                $xml.= '<default>' . ($propListEntry["DEF"]=="Y"? "true": "false") . '</default>' . PHP_EOL;
                $xml.= '<sort>' . intval($propListEntry['SORT']) . '</sort>' . PHP_EOL;
                $xml.= '</choice>' . PHP_EOL;
            }

            $xml.= '</choices>' . PHP_EOL;
        }

        $xml.= '<sort>' . intval($prop['SORT']) . '</sort>' . PHP_EOL;
        $xml.= '<code>' . htmlspecialcharsbx($prop['CODE']) . '</code>' . PHP_EOL;

        if (is_array($prop["DEFAULT_VALUE"]))
        {
            $xml.= '<default_value>' . htmlspecialcharsbx(serialize($prop["DEFAULT_VALUE"])) . '</default_value>' . PHP_EOL;
            $xml.= '<default_value_serialized>1</default_value_serialized>' . PHP_EOL;
        }
        else $xml.= '<default_value>' . htmlspecialcharsbx($prop["DEFAULT_VALUE"]) . '</default_value>' . PHP_EOL;

        $xml.= '<property_type>' . htmlspecialcharsbx($prop['PROPERTY_TYPE']) . '</property_type>' . PHP_EOL;
        $xml.= '<row_count>' . htmlspecialcharsbx($prop['ROW_COUNT']) . '</row_count>' . PHP_EOL;
        $xml.= '<col_count>' . htmlspecialcharsbx($prop['COL_COUNT']) . '</col_count>' . PHP_EOL;
        $xml.= '<list_type>' . htmlspecialcharsbx($prop['LIST_TYPE']) . '</list_type>' . PHP_EOL;
        $xml.= '<file_type>' . htmlspecialcharsbx($prop['FILE_TYPE']) . '</file_type>' . PHP_EOL;
        $xml.= '<multiple_cnt>' . htmlspecialcharsbx($prop['MULTIPLE_CNT']) . '</multiple_cnt>' . PHP_EOL;
        $xml.= '<link_iblock_id>' . htmlspecialcharsbx($prop['LINK_IBLOCK_ID']) . '</link_iblock_id>' . PHP_EOL;
        $xml.= '<with_description>' . ($prop["WITH_DESCRIPTION"]=="Y"? "true": "false") . '</with_description>' . PHP_EOL;
        $xml.= '<searchable>' . ($prop["SEARCHABLE"]=="Y"? "true": "false") . '</searchable>' . PHP_EOL;
        $xml.= '<filtrable>' . ($prop["FILTRABLE"]=="Y"? "true": "false") . '</filtrable>' . PHP_EOL;
        $xml.= '<user_type>' . htmlspecialcharsbx($prop['USER_TYPE']) . '</user_type>' . PHP_EOL;
        $xml.= '<is_required>' . ($prop["IS_REQUIRED"]=="Y"? "true": "false") . '</is_required>' . PHP_EOL;

        $xml.= '</prop>' . PHP_EOL;
    }
}

$xml.= '</props>';

MnServiceSimpleXMLSimpleXML::export($xml);

echo 'ok';

?>