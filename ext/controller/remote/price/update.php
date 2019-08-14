<?php

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");


$iBlockType = 'lb_catalog';
$shopUnid = 'LHR';
$priceTypeIds = array('MSK' => 1, 'SPB' => 2);

file_put_contents("last_data.log", $_POST['data']);
$data = unserialize($_POST['data']);
/*
if ($data['shop_unid'] != $shopUnid) return false;

$product = MnLibElementElement::getForIBlockType($iBlockType, array('PROPERTY__UNID' => $data['product_unid']));
$product = $product[0];

if ($data['shop_price_code'] == false) $priceTypeId = $priceTypeIds[0];
else $priceTypeId = $priceTypeIds[$data['shop_price_code']];

$syncOK = array();
foreach($priceTypeIds as $k => $priceTypeId){
	$syncOK[$k] = false;
}

foreach($priceTypeIds as $k => $priceTypeId){
	$priceFields = Array(
		"PRODUCT_ID" => $product['ID'],
		"CATALOG_GROUP_ID" => $priceTypeId,
		"PRICE" => $data['price_value'],
		"CURRENCY" => "RUB"
	);

	$dbPrice = CPrice::GetList(array(), array("PRODUCT_ID" => $product['ID'], "CATALOG_GROUP_ID" => $priceTypeId));
	if ($arPrice = $dbPrice->Fetch())
	{
		if (CPrice::Update($arPrice["ID"], $priceFields))
		{
			//echo 'ok';
			$syncOK[$k] = true;
		}
		else return false;
	}
	else
	{
		if (CPrice::Add($priceFields)) $syncOK[$k] = true;
		else return false;
	}
}

$allSyncOk = true;
foreach($syncOK as $ok){
	if( !$ok ){
		$allSyncOk = false;
	}
}

if( $allSyncOk ) echo "ok";

BXClearCache(true);
*/


if ($data['shop_unid'] != $shopUnid) return false;

$product = MnLibElementElement::getForIBlockType($iBlockType, array('PROPERTY__UNID' => $data['product_unid']));
$product = $product[0];

if ($data['shop_price_code'] == false) $priceTypeId = $priceTypeIds[0];
else $priceTypeId = $priceTypeIds[$data['shop_price_code']];

$priceFields = Array(
    "PRODUCT_ID" => $product['ID'],
    "CATALOG_GROUP_ID" => $priceTypeId,
    "PRICE" => $data['price_value'],
    "CURRENCY" => "RUB"
);

$dbPrice = CPrice::GetList(array(), array("PRODUCT_ID" => $product['ID'], "CATALOG_GROUP_ID" => $priceTypeId));
if ($arPrice = $dbPrice->Fetch())
{
    if (CPrice::Update($arPrice["ID"], $priceFields)) echo 'ok';
    else return false;
}
else
{
    if (CPrice::Add($priceFields)) echo 'ok';
    else return false;
}

BXClearCache(true);

?>