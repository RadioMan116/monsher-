<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");


$iBlocksType = 'lb_catalog';
$modelPropCode = 'MODEL';
$unidPropCode = '_UNID';
$brandPropCode = '_BRAND';
$priceTypeId = 1;
$originCategoriesLinkage = array(
	36 => 29, // Холодильники
	39 => 53, // Профессиональная техника
	35 => 30, // Винные шкафы
	37 => 29, // Встраиваемые холодильники
	38 => 49, // Аксессуары
	34 => 31, // Хьюмидоры
);
$originBrandsLinkage = array(
    1110 => 3482, // liebherr
    1111 => 3615, // magic-power
	46 => 3616  // TOP HOUSE
);


$xml = '<offers>' . PHP_EOL;

$iBlocks = MnLibIBlockIBlock::getByType($iBlocksType);

$elementsSelect = array('ID', 'NAME', 'IBLOCK_ID', 'SORT', 'ACTIVE', 'ACTIVE_FROM', 'ACTIVE_TO', 'PREVIEW_TEXT', 'PREVIEW_TEXT_TYPE',
    'PROPERTY_' . $modelPropCode, 'PROPERTY_' . $unidPropCode
);
if (!empty($brandPropCode)) $elementsSelect[] = 'PROPERTY_' . $brandPropCode;

$elements = MnLibElementElement::getForIBlockType($iBlocksType, array('ACTIVE' => 'Y'), $elementsSelect, array('NO_ADDITIONS' => 'Y'));

foreach ($elements as $element)
{
    foreach ($iBlocks as $iBlock)
    {
        if ($element['IBLOCK_ID'] == $iBlock['ID'])
        {
            $iBlockName = $iBlock['NAME'];
            break;
        }
    }

    $dbProductPrice = CPrice::GetList(
        array(), array(
            "PRODUCT_ID" => $element['ID'],
            "CATALOG_GROUP_ID" => $priceTypeId
        )
    );
    if ($productPrice = $dbProductPrice->Fetch()) $price = $productPrice["PRICE"];
    else $price = 0;

    $xml.= '<offer>' . PHP_EOL;
    $xml.= '<id>' . htmlspecialcharsbx($element['ID']) . '</id>' . PHP_EOL;
    $xml.= '<name>' . htmlspecialcharsbx($element['NAME']) . '</name>' . PHP_EOL;
    $xml.= '<model>' . htmlspecialcharsbx($element['PROPERTY_' . $modelPropCode . '_VALUE']) . '</model>' . PHP_EOL;
    $xml.= '<unid>' . htmlspecialcharsbx($element['PROPERTY_' . $unidPropCode . '_VALUE']) . '</unid>' . PHP_EOL;
    $xml.= '<price>' . htmlspecialcharsbx($price) . '</price>' . PHP_EOL;
    $xml.= '<category>' . $iBlockName . '</category>' . PHP_EOL;
    $xml.= '<origin_category_id>' . $originCategoriesLinkage[$element['IBLOCK_ID']] . '</origin_category_id>' . PHP_EOL;
    $xml.= '<sort>' . $element['SORT'] . '</sort>' . PHP_EOL;
    $xml.= '<active>' . $element['ACTIVE'] . '</active>' . PHP_EOL;
    /*
	$xml.= '<active_from>' . CDatabase::FormatDate($element['ACTIVE_FROM'], CLang::GetDateFormat("FULL"), "YYYY-MM-DD HH:MI:SS") . '</active_from>' . PHP_EOL;
    $xml.= '<active_to>' . CDatabase::FormatDate($element['ACTIVE_TO'], CLang::GetDateFormat("FULL"), "YYYY-MM-DD HH:MI:SS") . '</active_to>' . PHP_EOL;
   */

    if (!empty($brandPropCode))
    {
        $xml.= '<origin_brand_id>' . $originBrandsLinkage[$element['PROPERTY_' . $brandPropCode . '_VALUE']] . '</origin_brand_id>' . PHP_EOL;
    }
    else
    {
        foreach ($originBrandsLinkage as $locBrandId => $originBrandId) break;

        $xml.= '<origin_brand_id>' . $originBrandId . '</origin_brand_id>' . PHP_EOL;
    }

    //$xml.= MnLibIBlockPropertyProperty::exportXmlFormatForProduct($element['ID'], $element['IBLOCK_ID'], $iBlocksType);


    $xml.= '</offer>' . PHP_EOL;
}

$xml.= '</offers>';

//MnServiceSimpleXMLSimpleXML::export($xml);
file_put_contents(dirname(__FILE__) . "/export.xml", $xml);

echo 'ok';

?>