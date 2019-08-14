<?php

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");


$iBlockType = 'lb_catalog';
$shopUnid = 'LHR';
$availableCodesLinkage = array(
    0 => 'null',
    1 => 'В наличии',
    2 => 'Уточняйте наличие',//'Под заказ',
    3 => 'Нет в наличии',
    4 => 'Снят с производства'
);
$territoryCodeToPropArray = array(
    'MSK' => 'MSK',
    'SPB' => 'SPB',
);


$data = unserialize($_POST['data']);
file_put_contents("data.log", var_export($data, true));

if ($data['shop_unid'] != $shopUnid) return false;

$propCode = $territoryCodeToPropArray[$data['territory_code']];
if (!$propCode) return false;

$product = MnLibElementElement::getForIBlockType($iBlockType, array('PROPERTY__UNID' => $data['product_unid'], 'ACTIVE' => null));
if (!$product) return false;
$product = $product[0];

$propData = MnLibIBlockPropertyProperty::getListTypeDataByCodeAndIBlockId($propCode, $product['IBLOCK_ID']);

if ($availableCodesLinkage[$data['avail_code']] == 'null') $propValueId == null;
else
{
    foreach ($propData as $propDataEntry)
    {
        if ($propDataEntry['VALUE'] == $availableCodesLinkage[$data['avail_code']])
        {
            $propValueId = $propDataEntry['ID'];
            break;
        }
    }
    if (!$propValueId) return false;
}

CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], array($propCode => array('VALUE' => $propValueId)));

echo 'ok';
BXClearCache(true);
file_put_contents("data.log", PHP_EOL . "ok", FILE_APPEND);
?>