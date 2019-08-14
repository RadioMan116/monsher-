<?
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("highloadblock");				
use Bitrix\Highloadblock as HL;
use Bitrix\Main;

global $USER;

class CE {

	
	
	
	public static $ecommercePath = '/_ecommerce/';
	//public static $categoryIdBlock = 28;
	//public static $brandIdBlock = 20;
	//public static $generalIdBlock = 21;
	//public static $catalogIdBlock = 30;
	public static $catalogTypeBlock = 'mn_catalog';
	//public static $offerIdBlock = 64;
		
		
		
			
		
		
		
		
		public static function goodInBasket($id = false, $order_id = false) {
		
			 CModule::IncludeModule('sale');
			 CModule::IncludeModule('catalog');
			 
			 $arResult = array();	
			 
				$arFilter = array(
					"FUSER_ID" => CSaleBasket::GetBasketUserID(),
					"LID" => SITE_ID,
					"ORDER_ID" => "NULL",
					"CAN_BUY" => "Y",
				);
				
				if($id) $arFilter["ID"] = $id;
				if($order_id) $arFilter["ORDER_ID"] = $order_id;
			 //pre($arFilter);
			 
			 $dbBasketItems = CSaleBasket::GetList(
				array(),
				$arFilter,
				false,
				false,
				array("ID", "QUANTITY","PRODUCT_ID","ORDER_ID","PRICE")
			);
			
			while ($arElement = $dbBasketItems->Fetch())
			{			
				$arResult[] = $arElement;				
			}				
			
			return $arResult;	
		}
		
		
		
		
		
		
		
		
		public function GetByProduct($id) {
			
			
				$arTov = $arOffer = CStatic::getElement($id);
				if($arOffer["PROPERTIES"]["CML2_LINK"]["VALUE"]) {					
					$arTov = CStatic::getElement($arOffer["PROPERTIES"]["CML2_LINK"]["VALUE"]);	


					$arOffer["DISPLAY_PROPERTIES"] = CStatic::GetDisplayProps($arTov);					
					$arOffer["PROPERTIES"] = array_merge($arTov["PROPERTIES"], $arOffer["PROPERTIES"]);
				}
				else {
					$arOffer["DISPLAY_PROPERTIES"] = CStatic::GetDisplayProps($arOffer);					
					//pre($arOffer["DISPLAY_PROPERTIES"]);
				}
				
				$arOffer["PRICES"] = CStatic::GetPrice($arOffer["ID"]);		
				if($arTov["IBLOCK_SECTION_ID"]) {
					$arOffer["CATEGORY"] = getArSection($arTov["IBLOCK_ID"], false, $arTov["IBLOCK_SECTION_ID"]);
				}
				else {
					$arOffer["CATEGORY"] = getArIblock(self::$catalogTypeBlock, false, $arTov["IBLOCK_ID"]);
				}				
				
				
				
				return $arOffer;
			
		}	
			
	
		
		
		
		
		public function WriteDataLink($item) {
			
			foreach($item as $key=>$val) {
				if($key == 'code') continue; 			
				echo ' data-'.$key.'="'.$val.'"';
			}
			
		}
		public function GetCommerceData($item) {			
				
				$brand = 'Liebherr';
				
				$count = 1;
				
				
				if($item["DISPLAY_PROPERTIES"]["COLOR"]["VALUE"]) $color = $item["DISPLAY_PROPERTIES"]["COLOR"]["DISPLAY_VALUE"];
				else $color = 'не указан';
					
				//PRE($item["DISPLAY_PROPERTIES"]);
				
				$item2 = array(
					"name" => $item["NAME"],
					//"sku" => $item["ID"],
					"id" => $item["ID"],
					"price" => $item["PRICES"][0]["VALUE"],
					"brand" => $brand,
					"variant" => $color,
					"category" => $item["CATEGORY"]["NAME"],
					//"list" => $item["CATEGORY"]["NAME"]
				);
				
				return $item2;
			
		}
		
				
		
		
	
}


?>