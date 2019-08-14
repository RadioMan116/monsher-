<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//AddEventHandler("catalog", "OnBeforePriceUpdate", Array("CMNTSkuEvents", "OnBeforePriceUpdateHandler"));
AddEventHandler("catalog", "OnPriceUpdate", Array("CMNTSkuEvents", "OnPriceAddUpdateHandler"));
AddEventHandler("catalog", "OnPriceAdd", Array("CMNTSkuEvents", "OnPriceAddUpdateHandler"));

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("CMNTSkuEvents", "OnAfterIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("CMNTSkuEvents", "OnAfterIBlockElementAddHandler"));
AddEventHandler("iblock", "OnIBlockElementDelete", Array("CMNTSkuEvents", "OnIBlockElementDeleteHandler"));

class CMNTSkuEvents
{
	function OnPriceAddUpdateHandler($priceID, $arFields)
	{
		CMNTSku::UpdateSkuParamsInBaseEl(array("ELEMENT_ID" => $arFields["PRODUCT_ID"], "IBLOCK_ID" => "", "EVENT" => "OnBeforePriceUpdateHandler", "MODE" => "PRICE", "PRICE_ID" => $arFields["CATALOG_GROUP_ID"]));
	}
	
	function OnBeforePriceUpdateHandler($priceID, &$arFields)
	{
		CMNTSku::UpdateSkuParamsInBaseEl(array("ELEMENT_ID" => $arFields["PRODUCT_ID"], "IBLOCK_ID" => "", "EVENT" => "OnBeforePriceUpdateHandler", "MODE" => "PRICE", "PRICE_ID" => $arFields["CATALOG_GROUP_ID"]));
	}
	
	function OnAfterIBlockElementUpdateHandler(&$arFields)
	{
		if(!empty($arFields["RESULT"]))
			CMNTSku::UpdateSkuParamsInBaseEl(array("ELEMENT_ID" => $arFields["ID"], "IBLOCK_ID" => $arFields["IBLOCK_ID"], "EVENT" => "OnAfterIBlockElementUpdateHandler"));
	}
	
	function OnAfterIBlockElementAddHandler(&$arFields)
	{
		if($arFields["ID"] > 0)
			CMNTSku::UpdateSkuParamsInBaseEl(array("ELEMENT_ID" => $arFields["ID"], "IBLOCK_ID" => $arFields["IBLOCK_ID"], "EVENT" => "OnAfterIBlockElementAddHandler"));
	}
	
	function OnIBlockElementDeleteHandler($elementID)
	{
		if($elementID > 0)
			CMNTSku::UpdateSkuParamsInBaseEl(array("ELEMENT_ID" => $elementID, "IBLOCK_ID" => "", "EVENT" => "OnIBlockElementDeleteHandler"));
	}
}
?>