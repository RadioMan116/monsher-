<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

// типы цен для разных городов


AddEventHandler("sale", "OnBeforeBasketAdd", Array("CMNTBasketEvents", "OnBeforeAdd"));
AddEventHandler("sale", "OnBeforeBasketUpdate", Array("CMNTBasketEvents", "OnBeforeUpdate"));


class CMNTBasketEvents
{
	function OnBeforeAdd(&$arFields)
	{
		//pre($arFields);
		
		
		$arFields["CURRENCY"] = "RUB"; 
		$arFields["PRODUCT_PROVIDER_CLASS"] = false;
		$arFields["CALLBACK_FUNC"] = "MNTBasketCallback";
		$arFields["ORDER_CALLBACK_FUNC"] = "MNTBasketOrderCallback";
		$arFields["CANCEL_CALLBACK_FUNC"] = "CatalogBasketCancelCallback";
		$arFields["PAY_CALLBACK_FUNC"] = "CatalogPayOrderCallback";
		$arFields["IGNORE_CALLBACK_FUNC"] = "Y"; // без этой опции товар становится недоступным для покупки
		return true;
	}
	
	function OnBeforeUpdate($ID, &$arFields)
	{
		$arFields["CURRENCY"] = "RUB"; 
		$arFields["PRODUCT_PROVIDER_CLASS"] = false;
		$arFields["CALLBACK_FUNC"] = "MNTBasketCallback";
		$arFields["ORDER_CALLBACK_FUNC"] = "MNTBasketOrderCallback";
		$arFields["CANCEL_CALLBACK_FUNC"] = "CatalogBasketCancelCallback";
		$arFields["PAY_CALLBACK_FUNC"] = "CatalogPayOrderCallback";
		$arFields["IGNORE_CALLBACK_FUNC"] = "Y"; // без этой опции товар становится недоступным для покупки
		return true;
	}
	
}

// callback функции для обновления цен и наличия товаров в корзине
function MNTBasketCallback($productID, $quantity = 0, $renewal = "N", $intUserID = 0, $strSiteID = false)
{
	
	
	
	if($productID)
	{
		$CATALOG_GROUP_ID = !empty($GLOBALS["K_PRICE_CODE"]) ? CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]] : "1";
		$CATALOG_STORE_CODE = !empty($GLOBALS["K_EXIST_CODE"]) ? $GLOBALS["K_EXIST_CODE"] : "MSK";
		$CATALOG_PRICE_NAME = !empty($GLOBALS["K_REGION_NAME"]) ? $GLOBALS["K_REGION_NAME"] : "Москва";
		
		$CATALOG_SALE_ONLY_MSK = 'S_SALE_ONLY_MSK';
		
		CModule::IncludeModule("iblock");
		CModule::IncludeModule("catalog");
		
		// проверка наличия на складе
		$bStore = false;
		$arPricesIds = array($CATALOG_GROUP_ID, CStatic::$arPricesByCode["MSK_SALE"]);
		
		$resEl = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "ID" => $productID), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID"));
		if($obEl = $resEl->GetNextElement())
		{
			$arStore = $obEl->GetProperty($CATALOG_STORE_CODE);
			$arOnlyMsk = $obEl->GetProperty($CATALOG_SALE_ONLY_MSK);
			
			if(in_array($arStore["VALUE"], array("В наличии", "Под заказ"))) {$bStore = true;}
			if($arOnlyMsk["VALUE"] == 'Y' && $_COOKIE["K_REGION"]!='MSK') {$arPricesIds = array($CATALOG_GROUP_ID);}
		}
		
		if(!$bStore)
			return array();
		
		// получаем дефолтную
		$arResult = CatalogBasketCallback($productID, $quantity, $renewal, $intUserID , $strSiteID);
		
		//pre(array("PRODUCT_ID" => $productID, "CATALOG_GROUP_ID" => $arPricesIds));
		
		$dbPrice = CPrice::GetList(array("PRICE" => "ASC", "SORT" => "ASC"), array("PRODUCT_ID" => $productID, "CATALOG_GROUP_ID" => $arPricesIds), false, false, false);
		$arCurPrice = $dbPrice->Fetch();
		
		//pre($arCurPrice);
		$arResult["PRODUCT_PRICE_ID"] = $arCurPrice["ID"];
		$arResult["BASE_PRICE"] = $arResult["PRICE"] = $arCurPrice["PRICE"];
		
		$arResult["NOTES"] = $CATALOG_PRICE_NAME;
		
			//pre($arResult);
			
		
		return $arResult;
	}
	
	return false;
}

function MNTBasketOrderCallback($productID, $quantity = 0, $renewal = "N", $intUserID = 0, $strSiteID = false)
{
	if($productID)
	{
		$CATALOG_GROUP_ID = !empty($GLOBALS["K_PRICE_CODE"]) ? CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]] : "1";
		$CATALOG_STORE_CODE = !empty($GLOBALS["K_EXIST_CODE"]) ? $GLOBALS["K_EXIST_CODE"] : "MSK";
		$CATALOG_PRICE_NAME = !empty($GLOBALS["K_REGION_NAME"]) ? $GLOBALS["K_REGION_NAME"] : "Москва";
		
		$CATALOG_SALE_ONLY_MSK = 'S_SALE_ONLY_MSK';
		
		CModule::IncludeModule("iblock");
		CModule::IncludeModule("catalog");
		
		// проверка наличия на складе
		$bStore = false;		
		$arPricesIds = array($CATALOG_GROUP_ID, CStatic::$arPricesByCode["MSK_SALE"]);		
		
		$resEl = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "ID" => $productID), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID"));
		if($obEl = $resEl->GetNextElement())
		{
			$arStore = $obEl->GetProperty($CATALOG_STORE_CODE);
			$arOnlyMsk = $obEl->GetProperty($CATALOG_SALE_ONLY_MSK);
			
			if(in_array($arStore["VALUE"], array("В наличии", "Под заказ"))) {$bStore = true;}
			if($arOnlyMsk["VALUE"] == 'Y' && $_COOKIE["K_REGION"]!='MSK') {$arPricesIds = array($CATALOG_GROUP_ID);}			
		}
		
		if(!$bStore)
			return array();
		
		// получаем дефолтную
		$arResult = CatalogBasketOrderCallback($productID, $quantity, $renewal, $intUserID , $strSiteID);
		
		$dbPrice = CPrice::GetList(array("PRICE" => "ASC", "SORT" => "ASC"), array("PRODUCT_ID" => $productID, "CATALOG_GROUP_ID" => $arPricesIds), false, false, false);
		$arCurPrice = $dbPrice->Fetch();
		
		$arResult["PRODUCT_PRICE_ID"] = $arCurPrice["ID"];
		$arResult["BASE_PRICE"] = $arResult["PRICE"] = $arCurPrice["PRICE"];
		$arResult["NOTES"] = $CATALOG_PRICE_NAME;
		
		
		
		return $arResult;
	}
	
	return false;
}
?>