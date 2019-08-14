<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
ini_set('max_execution_time', 60000);
CModule::IncludeModule('iblock');


$catalogType = 'lb_catalog';
$modelPropCode = 'MODEL';
$unidPropCode = '_UNID';
$brandString = '';
$brandPropCode = '_BRAND';
$brandsIBlockId = 3;


$productsSelect = array('ID', 'NAME', 'IBLOCK_ID', 'ACTIVE', 'PROPERTY_' . $modelPropCode, 'PROPERTY_' . $unidPropCode);
if (empty($brandString))
{
    if (!empty($brandsIBlockId)) $brands = MnLibElementElement::getByIBlockId($brandsIBlockId, array(), array(), array('INDEX' => array('ID')));
    $productsSelect[] = 'PROPERTY_' . $brandPropCode;
}

$iBlocks = MnLibIBlockIBlock::getByType($catalogType, array('ACTIVE' => null));

$productsAll = MnLibElementElement::getForIBlockType(
    $catalogType, array(), $productsSelect, array('NO_ADDITIONS' => 'Y', 'NO_PROPS' => 'Y', 'ACTIVE' => null)
);

/*
$products = array();
foreach ($productsAll as $productsAllEntry)
{
    if ($productsAllEntry['ACTIVE'] == 'Y') $products[] = $productsAllEntry;
}
*/
$products = $productsAll;


//models fill empty
foreach ($products as $product)
{
    $product['PROPERTY_' . $modelPropCode . '_VALUE'] = trim($product['PROPERTY_' . $modelPropCode . '_VALUE']);
    if (empty($product['PROPERTY_' . $modelPropCode . '_VALUE']))
    {
        if (!empty($brandString)) $brandToSeek = $brandString;
        elseif (!is_numeric($product['PROPERTY_' . $brandPropCode . '_VALUE'])) $brandToSeek = $product['PROPERTY_' . $brandPropCode . '_VALUE'];
        elseif (is_numeric($product['PROPERTY_' . $brandPropCode . '_VALUE']))
        {
            $brandLinked = $brands[$product['PROPERTY_' . $brandPropCode . '_VALUE']];
            $brandToSeek = $brandLinked['NAME'];
        }

        if (isset($brandToSeek))
        {
            $brandStringPos = strpos($product['NAME'], $brandToSeek);
            if ($brandStringPos === false)
            {
                $brandToSeek = strtoupper($brandToSeek);
                $brandStringPos = strpos($product['NAME'], $brandToSeek);
            }

            if ($brandStringPos !== false)
            {
                $product['PROPERTY_' . $modelPropCode . '_VALUE'] = substr($product['NAME'], $brandStringPos);
                $product['PROPERTY_' . $modelPropCode . '_VALUE'] = str_replace($brandToSeek . ' ', '', $product['PROPERTY_' . $modelPropCode . '_VALUE']);
                $product['PROPERTY_' . $modelPropCode . '_VALUE'] = trim($product['PROPERTY_' . $modelPropCode . '_VALUE']);
            }
            else $product['PROPERTY_' . $modelPropCode . '_VALUE'] = $product['NAME'];
        }
        else $product['PROPERTY_' . $modelPropCode . '_VALUE'] = $product['NAME'];

        CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], array($modelPropCode => array('VALUE' => $product['PROPERTY_' . $modelPropCode . '_VALUE'])));
    }
}
//models fill empty end

//extraspaces remove
foreach ($products as $product)
{
    $product['PROPERTY_' . $modelPropCode . '_VALUE'] = trim($product['PROPERTY_' . $modelPropCode . '_VALUE']);
    $product['PROPERTY_' . $modelPropCode . '_VALUE'] = str_replace('  ', ' ', $product['PROPERTY_' . $modelPropCode . '_VALUE']);
    $product['PROPERTY_' . $modelPropCode . '_VALUE'] = str_replace('   ', ' ', $product['PROPERTY_' . $modelPropCode . '_VALUE']);
    $product['PROPERTY_' . $modelPropCode . '_VALUE'] = str_replace('    ', ' ', $product['PROPERTY_' . $modelPropCode . '_VALUE']);

    CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], array($modelPropCode => array('VALUE' => $product['PROPERTY_' . $modelPropCode . '_VALUE'])));
}
//extraspaces remove end

//models clean from brand
foreach ($products as $product)
{
    $product['PROPERTY_' . $modelPropCode . '_VALUE'] = trim($product['PROPERTY_' . $modelPropCode . '_VALUE']);

    if (!empty($brandString)) $brandToSeek = $brandString;
    elseif (!is_numeric($product['PROPERTY_' . $brandPropCode . '_VALUE'])) $brandToSeek = $product['PROPERTY_' . $brandPropCode . '_VALUE'];
    elseif (is_numeric($product['PROPERTY_' . $brandPropCode . '_VALUE']))
    {
        $brandLinked = $brands[$product['PROPERTY_' . $brandPropCode . '_VALUE']];
        $brandToSeek = $brandLinked['NAME'];
    }

    if (isset($brandToSeek))
    {
        $brandStringPos = strpos($product['PROPERTY_' . $modelPropCode . '_VALUE'], $brandToSeek);
        if ($brandStringPos === false)
        {
            $brandToSeek = strtoupper($brandToSeek);
            $brandStringPos = strpos($product['PROPERTY_' . $modelPropCode . '_VALUE'], $brandToSeek);
        }

        if ($brandStringPos !== false && substr($product['PROPERTY_' . $modelPropCode . '_VALUE'], -strlen($brandToSeek)) != $brandToSeek)
            $product['PROPERTY_' . $modelPropCode . '_VALUE'] = str_replace($brandToSeek . ' ', '', $product['PROPERTY_' . $modelPropCode . '_VALUE']);

        CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], array($modelPropCode => array('VALUE' => $product['PROPERTY_' . $modelPropCode . '_VALUE'])));
    }
}
//models clean from brand end

//models duplication check
$productsHaystack = array();
foreach ($products as $product) $productsHaystack[$product['ID']] = $product['PROPERTY_' . $modelPropCode . '_VALUE'];

$modelDuplications = array();
foreach ($products as $product)
{
    unset($productsHaystack[$product['ID']]);
    if (in_array($product['PROPERTY_' . $modelPropCode . '_VALUE'], $productsHaystack, true)) $modelDuplications[$product['ID']] = $product['PROPERTY_' . $modelPropCode . '_VALUE'];
    $productsHaystack[$product['ID']] = $product['PROPERTY_' . $modelPropCode . '_VALUE'];
}

if (!empty($modelDuplications))
{
    $report = '<?xml version="1.0" encoding="windows-1251"?>' . PHP_EOL;
    $report.= '<report>' . PHP_EOL;
    $report.= '<duplications>' . PHP_EOL;
    foreach ($modelDuplications as $productId => $productModel)
    {
        $report.= '<duplication>' . PHP_EOL;
        $report.= '<product_id>' . $productId . '</product_id>' . PHP_EOL;
        $report.= '<product_model>' . $productModel . '</product_model>' . PHP_EOL;
        $report.= '</duplication>' . PHP_EOL;
    }
    $report.= '</duplications>' . PHP_EOL;
    $report.= '</report>' . PHP_EOL;

    MnServiceSimpleXMLSimpleXML::export($report, 'model_duplications');

    return false;
}
else {
    $report = '<?xml version="1.0" encoding="windows-1251"?>' . PHP_EOL;
    $report.= '<report>' . PHP_EOL;
    $report.= '<duplications>' . PHP_EOL;
    $report.= '</duplications>' . PHP_EOL;
    $report.= '</report>' . PHP_EOL;
    MnServiceSimpleXMLSimpleXML::export($report, 'model_duplications');
}
//models duplication check end

//unids duplication check
$productsHaystack = array();
foreach ($productsAll as $product) $productsHaystack[$product['ID']] = $product['PROPERTY_' . $unidPropCode . '_VALUE'];

$unidDuplications = array();
foreach ($productsAll as $product)
{
    unset($productsHaystack[$product['ID']]);
    if (in_array($product['PROPERTY_' . $unidPropCode . '_VALUE'], $productsHaystack, true) && !empty($product['PROPERTY_' . $unidPropCode . '_VALUE'])) $unidDuplications[$product['ID']] = array('unid' => $product['PROPERTY_' . $unidPropCode . '_VALUE'], 'model' => $product['PROPERTY_' . $modelPropCode . '_VALUE']);
    $productsHaystack[$product['ID']] = $product['PROPERTY_' . $unidPropCode . '_VALUE'];
}

if (!empty($unidDuplications))
{
    $report = '<?xml version="1.0" encoding="windows-1251"?>' . PHP_EOL;
    $report.= '<report>' . PHP_EOL;
    $report.= '<duplications>' . PHP_EOL;
    foreach ($unidDuplications as $productId => $productDuplicationEntry)
    {
        $report.= '<duplication>' . PHP_EOL;
        $report.= '<product_id>' . $productId . '</product_id>' . PHP_EOL;
        $report.= '<product_unid>' . $productDuplicationEntry['unid'] . '</product_unid>' . PHP_EOL;
        $report.= '<product_model>' . $productDuplicationEntry['model'] . '</product_model>' . PHP_EOL;
        $report.= '</duplication>' . PHP_EOL;
    }
    $report.= '</duplications>' . PHP_EOL;
    $report.= '</report>' . PHP_EOL;

    MnServiceSimpleXMLSimpleXML::export($report, 'unid_duplications');

    return false;

    $productsLegit = array();

    foreach ($unidDuplications as $productId => $productDuplicationEntry)
    {
        if (!array_key_exists($productDuplicationEntry['unid'], $productsLegit)) $productsLegit[$productDuplicationEntry['unid']] = $productId;
	 else
	 {
	     if ($productId < $productsLegit[$productDuplicationEntry['unid']]) $productsLegit[$productDuplicationEntry['unid']] = $productId;
	 }
    }

    $productsUnlegit = $unidDuplications;

    foreach ($productsLegit as $productsLegitEntryUnid => $productsLegitEntryId)
    {
        if (array_key_exists($productsLegitEntryId, $productsUnlegit)) unset($productsUnlegit[$productsLegitEntryId]);
    }

    foreach ($productsUnlegit as $productsUnLegitEntryid => $productsUnLegitEntry)
    {
        CIBlockElement::SetPropertyValuesEx($productsUnLegitEntryid, false, array('_UNID' => array('VALUE' => '')));
        //check output before deleting !
        //echo $productsUnLegitEntryid . '<br>';
    }
} else {
    $report = '<?xml version="1.0" encoding="windows-1251"?>' . PHP_EOL;
    $report.= '<report>' . PHP_EOL;
    $report.= '<duplications>' . PHP_EOL;
    $report.= '</duplications>' . PHP_EOL;
    $report.= '</report>' . PHP_EOL;
    MnServiceSimpleXMLSimpleXML::export($report, 'unid_duplications');
}
//unids duplication check end

echo 'ok';

?>