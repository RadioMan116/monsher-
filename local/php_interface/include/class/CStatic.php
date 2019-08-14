<?
CModule::IncludeModule("iblock");
CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');

use Bitrix\Main;
global $USER;

class CStatic {


	
	public static $NoPriceExistView = 'Ожидаем поступления';
	public static $pathConf = '/personal-data/';
	
	public static $pathV = '/mockup/templates/main/build/';
	
	public static $catalogIdBlock = array(73,74,75,76,77,86);
	public static $accIdBlock = 77;
	public static $videoIdBlock = 80;
	public static $iconsIdBlock = 89;
	
	
	
	
	
	public static $commentIdBlock = 32;
	public static $commentIdSec = 62;
	
	public static $feedbackIdBlock = 24;
	public static $feedbackIdSec = 63;
	
	public static $guideIdBlock = 25;
	public static $guideIdSec = 66;
	
	public static $claimIdBlock = 26;
	public static $claimIdSec = 67;
	
	public static $ReviewsIdBlock = 33;
	public static $ReviewsIdSec = 65;
	public static $ReviewsLimit = 1;
	
	
	
	public static $ReviewsStoreIdBlock = 69;
	public static $ReviewsStoreIdSec = 64;
	
	public static $DataId =  2738;
	
	
	public static $DataIdByRegion = array(
		"MSK" => 2738,
		"SPB" => 2739,
		"REGIONS" => 2740,	
	);
	
	
	
	public static $catalogIdBlockMain = 543;
	
	public static $TypePrice = "MSK";
	
	public static $codePriceSale = "MSK_SALE";
	
	public static $arPricesByCode = ARRAY(
		"MSK_SALE" => 8,
		"MSK" => 1,
		"SPB" => 2,
	);
	
	public static $propertyCodeColorBase = "TSVET";
	
	public static $catalogIdblock_pay_delivery = 544;
	
	public static $labels = array(
		"HIT" => 64444,
		"NEW" => 64441
	);
	
	public static $arExist = array(
		"t1" => "", //В наличии
		"t2" => "available_order", //Под заказ
		"t3" => "available_not", //Нет в наличии
		"t5" => "available_soon", //Скоро будет
		"t4" => "available_old", //Снят с производства
	);

	public static $arExistMicro = array(
		"t1" => "InStock", //В наличии
		"t2" => "PreOrder", //Под заказ
		"t3" => "OutOfStock", //Нет в наличии
		"t5" => "OutOfStock", //Скоро будет
		"t4" => "Discontinued", //Снят с производства		
	);

	public static function GetProp ($iblockID, $propCode, $enumCode) {
		
		$result = false;
		
		if ($arEnums = self::getEnums()) {			
		
			if (array_key_exists($propCode, (array) $arEnums[$iblockID])) {
				$arEnum = $arEnums[$iblockID][$propCode];
				if (is_array($enumCode)) {
					$result = array_values(array_intersect_key((array) $arEnum, array_flip((array) $enumCode)));
				} else {
					$result = $arEnum[$enumCode];
				}
			}
		}
		
		return $result;
		
	}
	
	
	public static function getEnums ($iblockID = false) {
		
		$cache_time = 1 * 24 * 60 * 60;		
		$result = array();		
		$obCache = new CPHPCache();
	
			if (CModule::IncludeModule("iblock")) {
				
				$arEnum = array();
				$arProp = array();
				
				
				$arFilter = array();
				if ($iblockID) {
					$arFilter["IBLOCK_ID"] = $iblockID;
				}
				
				
				$db_res_enum = CIBlockPropertyEnum::GetList(array("ID" => "ASC"), $arFilter);
				while ($ar_res_enum = $db_res_enum->Fetch()) {
					$arEnum[] = $ar_res_enum;
				}
				
				$arFilter2 = array(
					"PROPERTY_TYPE" => "L"
				);
				if ($iblockID) {
					$arFilter2["IBLOCK_ID"] = $iblockID;
				}
				$db_res_prop = CIBlockProperty::GetList(
					array("ID" => "ASC"),
					$arFilter2
				);
				while ($ar_res_prop = $db_res_prop->Fetch()) {
					$arProp[$ar_res_prop["ID"]] = $ar_res_prop;
				}
				
				foreach ($arEnum as $enum) {
					
					$propertyID = $enum["PROPERTY_ID"];
					$iblockID = $arProp[$propertyID]["IBLOCK_ID"];
					$propertyCode = $arProp[$propertyID]["CODE"];
					$enumCode = $enum["XML_ID"];
					$enumID = $enum["ID"];
					if (!array_key_exists($iblockID, $result)) $result[$iblockID] = array();
					if (!array_key_exists($propertyCode, $result[$iblockID])) $result[$iblockID][$propertyCode] = array();
					if (!array_key_exists($enumCode, $result[$iblockID][$propertyCode])) $result[$iblockID][$propertyCode][$enumCode] = $enumID;
					
				}
				
			}
			
		
		return $result;
		
	}

	public function GetUserInfo($user_id = false) 
		{			
			$USER = new CUser;
			if(!$user_id) $user_id = $USER->GetID();			
			
			if($user_id)
			{
				$rUser = $USER->GetByID($user_id);
				$arUser = $rUser->Fetch();
				
				return $arUser;	
			}
			else
			{
				return false;	
			}
		}

        public static function getElement ($elementID, $iblockID = false, $cache = true)
        {	
		
			$obCache = new CPHPCache;
			$life_time = 60*60*24;			
			$cache_id = 'ELEMENTOS2_'.$elementID.'_'.$iblockID;
			$return = false;
			
			if($obCache->InitCache($life_time, $cache_id, "/element-data/") && $cache) {
				$vars = $obCache->GetVars();
				$return = $vars["IELEMENT"];
			} else {			
				 
				 
				 if (CModule::IncludeModule("iblock"))
				 {
					if($elementID) {
					 
							$arFilter = array('ID' => $elementID);
							if ($iblockID) {
								$arFilter["IBLOCK_ID"] = $iblockID;
							}
							
							$db_res = CIBlockElement::GetList(
								array(),
								$arFilter
							);
							
							
							if ($ob_res = $db_res->GetNextElement())
							{
								$ar_res = $ob_res->GetFields();
								$ar_res['PROPERTIES'] = $ob_res->GetProperties();
								
								
								$return = $ar_res;
							}					
					 }
				 }
			
			}
			
			if($return) {
				if($obCache->StartDataCache() ) {
					$obCache->EndDataCache(array(
						"IELEMENT"    => $return
					));
				}
			}
		
			return $return;	
	
        }												
		
		
		
				
		public static function GetDisplayPropsCodes($IBLOCK_ID) {
			
			
			$obCache = new CPHPCache;
			$life_time = 60*60*24;
			$cache_id = 'DISPLAYPROPSTOVLIST_'.$IBLOCK_ID;
			$arProps = array();
			
			
			if($obCache->InitCache($life_time, $cache_id, "/")) {
				$vars = $obCache->GetVars();
				$arProps = $vars["IDPROPSCODES"];
			} else {			
			
				CModule::IncludeModule('iblock');				
				$rProp = CIBlockProperty::GetList(
				Array("SORT" => "ASC"), 
				Array(
						"ACTIVE" => "Y", 
						"IBLOCK_ID" => $IBLOCK_ID,
						//"FILTRABLE" => "Y",			
					)
				);
				while ($arProp = $rProp->GetNext())
				{					
					if($arProp["CODE"]!='CML2_LINK')
					{
						$arProps[] = $arProp["CODE"];						
					}
				}					
			}
			if($obCache->StartDataCache()) {
				$obCache->EndDataCache(array(
					"IDPROPSCODES"    => $arProps
				));
			}

			
			return $arProps;			
		}	
			
			
			
			
			
			
			
			
		public static function GetDisplayProps($arElement, $arProps = false, $clear = false) {
			
			
			$obCache = new CPHPCache;
			$life_time = 60*60*24;
			$cache_id = 'DISPLAYPROPSTOV_'.$arElement["ID"].'_'.((int)$clear);
			$arResult = false;
			
			if($obCache->InitCache($life_time, $cache_id, "/")) {
				$vars = $obCache->GetVars();
				$arResult = $vars["IDPROPS"];
			} else {
			
				CModule::IncludeModule('iblock');				
				if(!$arProps) $arProps = self::GetDisplayPropsCodes($arElement["IBLOCK_ID"]);		
				
				$arResult = array();
				foreach($arProps as $pid)
				{		
					$prop = CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"][$pid], "catalog_out");
					if($clear) $prop["DISPLAY_VALUE"] = preg_replace('#<a[^>]*>(.*?)</a>#is', '$1', $prop["DISPLAY_VALUE"]);					
					$arResult[$pid] = $prop;					
				}
			}
			if($obCache->StartDataCache()) {
				$obCache->EndDataCache(array(
					"IDPROPS"    => $arResult
				));
			}
			
			return $arResult;
			
		}	
		
		
		public static function GetColors($idblock = 196) {
				
			$obCache = new CPHPCache;
			$life_time = 60*60*24;
			$cache_id = 'COLORS_'.$idblock;
			$arResult = false;
			
			if($obCache->InitCache($life_time, $cache_id, "/")) {
				$vars = $obCache->GetVars();
				$arResult = $vars["ICOLORS"];
			} else {	
				
				$arFilter = array(
					"IBLOCK_ID" => $idblock,
					"ACTIVE" => "Y",
				);
			
				$db_res = CIBlockElement::GetList(
                    array(),
                    $arFilter
                );
				
				$arResult = array();
                while($ob_res = $db_res->GetNextElement())
                {
                    $ar_res = $ob_res->GetFields();
					$ar_res['PROPERTIES'] = $ob_res->GetProperties();
					
					
					$arResult[$ar_res["ID"]]["NAME"] = $ar_res["NAME"];
					$arResult[$ar_res["ID"]]["CODE"] = $ar_res["PROPERTIES"]["CODE"]["VALUE"];
					
					
					if($ar_res["PREVIEW_PICTURE"]) {
						$arImg = CFile::ResizeImageGet($ar_res["PREVIEW_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_EXACT);		
						$arResult[$ar_res["ID"]]["IMG"] = $arImg["src"];
					}
					                    
                }
		
			}
			if($obCache->StartDataCache()) {
				$obCache->EndDataCache(array(
					"ICOLORS"    => $arResult
				));
			}
		
				return $arResult;
		}
		
		
		
		public static function GetLabels($idblock = 408) {
				
			$obCache = new CPHPCache;
			$life_time = 60*60*24;
			$cache_id = 'LABELS_'.$idblock;
			$arResult = false;
			
			if($obCache->InitCache($life_time, $cache_id, "/")) {
				$vars = $obCache->GetVars();
				$arResult = $vars["ILABELS"];
			} else {	
				
				$arFilter = array(
					"IBLOCK_ID" => $idblock,
					"ACTIVE" => "Y",
				);
			
				$db_res = CIBlockElement::GetList(
                    array(),
                    $arFilter
                );
				
				$arResult = array();
                while($ob_res = $db_res->GetNextElement())
               {
                    $ar_res = $ob_res->GetFields();
					
					if($ar_res["PROPERTIES"]["SVG"]["VALUE"]) $arResult[$ar_res["ID"]]["IMG"] = CFile::getpath($ar_res["PROPERTIES"]["SVG"]["VALUE"]);
					else if($ar_res["PREVIEW_PICTURE"]) $arResult[$ar_res["ID"]]["IMG"] = CFile::getpath($ar_res["PREVIEW_PICTURE"]); 
					else continue;
					
					$arResult[$ar_res["ID"]]["NAME"] = $ar_res["NAME"];
					$arResult[$ar_res["ID"]]["CODE"] = $ar_res["CODE"];
                }
		
			}
			if($obCache->StartDataCache()) {
				$obCache->EndDataCache(array(
					"ILABELS"    => $arResult
				));
			}
		
				return $arResult;
		}
		
		
		
		public static function GetPrice($PRODUCT_ID, $byCode = false) {
		
			CModule::IncludeModule('sale');
			CModule::IncludeModule('catalog');
			global $USER;
			
			
			$arFilter = array(
				"PRODUCT_ID" => $PRODUCT_ID,				
				">PRICE" => 0,				
			);
			
			if($GLOBALS["K_PRICE_CODE"]) {
				// ВЫВОДИМ ЦЕНУ РЕГИОНА И АКЦИОННУЮ
				$arFilter["CATALOG_GROUP_ID"] = array(CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]], 8);
			}
			
			//if($USER->GetID) 
			{
				$arFilter["CAN_BUY"] = "Y";
			}
			
			IF($_GET["mode"]) {
				//pre($arFilter);
			}
			
			
			$dbPrice = CPrice::GetList(
				array("PRICE" => "ASC", "SORT" => "ASC"),
				$arFilter,
				false,
				false,
				array("*")
			 );
			 $arResult = array();
			 while ($arPrice = $dbPrice->Fetch())
			 {
				 
				 //pre($arPrice);
				 
				$arDiscounts = CCatalogDiscount::GetDiscountByPrice(
				   $arPrice["ID"],
				   $USER->GetUserGroupArray(),
				   "N",
				   SITE_ID
				);
				$discountPrice = CCatalogProduct::CountPriceWithDiscount(
				   $arPrice["PRICE"],
				   $arPrice["CURRENCY"],
				   $arDiscounts
				);
				
				
				if($arPrice["CATALOG_GROUP_ID"] == 8) $arPrice["CODE"] = 'MSK_SALE';
				else if($arPrice["CATALOG_GROUP_ID"] == 2) $arPrice["CODE"] = 'SPB';
				else if($arPrice["CATALOG_GROUP_ID"] == 1) $arPrice["CODE"] = 'MSK';
				
				
				IF($arPrice["CURRENCY"]!="RUB") {					
					$arPrice["PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["PRICE"], $arPrice["CURRENCY"], "RUB");
					$discountPrice = CCurrencyRates::ConvertCurrency($discountPrice, $arPrice["CURRENCY"], "RUB");
				}
				
				$arPrice["VALUE"] = $arPrice["PRICE"];
				$arPrice["DISCOUNT_VALUE"] = $arPrice["DISCOUNT_PRICE"] = $discountPrice;
				
				if($byCode) {
					$arResult[$arPrice["CODE"]] = $arPrice;
				}
				else {
					$arResult[] = $arPrice;
				}
			 }
		
		
			return $arResult;
		}
		
		
		
		public static function goodsInBasket() {
		
			 CModule::IncludeModule('sale');
			 CModule::IncludeModule('catalog');
			 
			 $dbBasketItems = CSaleBasket::GetList(
				array(),
				array(
					"FUSER_ID" => CSaleBasket::GetBasketUserID(),
					"LID" => SITE_ID,
					"ORDER_ID" => "NULL",
					"CAN_BUY" => "Y",
				),
				false,
				false,
				array("ID", "QUANTITY","PRICE")
			);
		 
			$TOV_COUNT = 0; 
			$TOV_SUM = 0; 
			while ($arItem = $dbBasketItems->Fetch())
			{			
				$TOV_COUNT = $TOV_COUNT + $arItem["QUANTITY"];			
				$TOV_SUM = $TOV_SUM + $arItem["QUANTITY"]*$arItem["PRICE"];			
			}			 
			
			$arResult = array(
				"TOV_COUNT_BASKET" => $TOV_COUNT,
				"TOV_SUM_BASKET" => $TOV_SUM,
			);
			
			
			return $arResult;	
		}
		

		public static function goodsInBasketArray($ORDER_ID) {
		
			 CModule::IncludeModule('sale');
			 CModule::IncludeModule('catalog');
			 
			 $dbBasketItems = CSaleBasket::GetList(
				array(),
				array(					
					"LID" => SITE_ID,
					"ORDER_ID" => $ORDER_ID,					
				),
				false,
				false,
				array(
					"ID", 
					"PRODUCT_ID", 
					"CAN_BUY", 
					"QUANTITY",
					"PRICE",
					"NAME"
				)
			);
		 
			$arResult = array();
			while ($arItem = $dbBasketItems->Fetch())
			{			
				//pre($arItem);
				
				$arOffer = self::getElement($arItem["PRODUCT_ID"]);	
				if($arOffer["PROPERTIES"]["CML2_LINK"]) {
					$arElement = self::getElement($arOffer["PROPERTIES"]["CML2_LINK"]["VALUE"]);	
				}
				else {
					$arElement = $arOffer;						
				} 	
				// объем упаковка
				$arPack = CIBlockFormatProperties::GetDisplayValue($arOffer, $arOffer["PROPERTIES"]['PACK'], "catalog_out");				
				$arPack["DISPLAY_VALUE"] = preg_replace('#<a[^>]*>(.*?)</a>#is', '$1', $arPack["DISPLAY_VALUE"]);				
				// тип масла
				$arType = CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"]['COMPOSITION'], "catalog_out");				
				$arType["DISPLAY_VALUE"] = preg_replace('#<a[^>]*>(.*?)</a>#is', '$1', $arType["DISPLAY_VALUE"]);				
							
				$arItem["PRODUCT_NAME"] = $arOffer["NAME"].' '.$arPack["DISPLAY_VALUE"];
				$arItem["TYPE_NAME"] = $arType["DISPLAY_VALUE"];				
		
				$arResult[] = $arItem;
			}		 
			
			
			return $arResult;	
		}
		
		function UpdateElPrice($elementID, $priceTypeID, $priceVal)
		{
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("sale");
			CModule::IncludeModule("catalog");
			
			
			if(!$priceVal) $priceVal = '0';			
			
			if(strlen($priceVal) > 0)
			{
				$elementID = IntVal($elementID);
				$priceTypeID = IntVal($priceTypeID);
				$priceVal = floatval($priceVal);
				
				$arFieldsPrice = Array(
					"PRODUCT_ID" => $elementID,
					"CATALOG_GROUP_ID" => $priceTypeID,
					"PRICE" => $priceVal,
					"CURRENCY" => "RUB",
					"QUANTITY_FROM" => false,
					"QUANTITY_TO" => false
				);				
			
				$resPrice = CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $elementID,
						"CATALOG_GROUP_ID" => $priceTypeID
					)
				);
				if($arr = $resPrice->Fetch())
				{					
					if(CPrice::Update($arr["ID"], $arFieldsPrice))
						return true;
				}
				else
				{					
					if(CPrice::Add($arFieldsPrice))
						return true;
				}
				return false;
			}
			return false;
		}
		
		
			
	

	
		
		public static function ChangeSimb() {

				CModule::IncludeModule("iblock");
				$el = new CIBlockElement;
				
				$idblock = 39;
				
				$arFilter = array(
					"IBLOCK_ID" => $idblock,					
				);
			
					$db_res = CIBlockElement::GetList(
						array(),
						$arFilter,
						false,
						false,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",
							"CODE",
						)
					);
					$arResult = array();
					while ($arr = $db_res->Fetch())
					{
						$code_new = translit($arr["NAME"]);
						
						
						$arFields = array(
							"CODE" => $code_new
						);
						
						//pre($arFields);
						
						$res = $el->Update($arr["ID"], $arFields);					
						
					}			
	
		}
		
		
		public static function ClearTags ($str)
        {
		
		
			$search = array ("'<script[^>]*?>.*?</script>'si",  // Вырезает javaScript
					"'<[\/\!]*?[^<>]*?>'si",           // Вырезает HTML-теги
					"'([\r\n])[\s]+'",                 // Вырезает пробельные символы
					"'&(quot|#34);'i",                 // Заменяет HTML-сущности
					"'&(amp|#38);'i",
					"'&(lt|#60);'i",
					"'&(gt|#62);'i",
					"'&(nbsp|#160);'i",
					"'&(iexcl|#161);'i",
					"'&(cent|#162);'i",
					"'&(pound|#163);'i",
					"'&(copy|#169);'i",
					"'&#(\d+);'e",
					"|[\s]+|s"
				);
				// Что ищем на что заменяем
				$replace = array ("",
					"",
					"\\1",
					"\"",
					"&",
					"<",
					">",
					" ",
					chr(161),
					chr(162),
					chr(163),
					chr(169),
					"chr(\\1)",
					" "
				);
			
				return trim(preg_replace($search, $replace, strip_tags($str)));		
		
		}
		
		public static function PrevText ($str, $k = false, $pp = false) {		
		
				$str = self::ClearTags($str);
				if(!$k) $k = 199;
				
			    if(strlen($str)>$k) {	
					$k2 = strrpos(substr($str,0,$k),".");
					$str2 = substr($str,0,$k2+1);				
				}
				
				if($pp) {
					$k2 = strrpos(substr($str,0,$k),".");		
					$str2 = substr($str,0,$k2).'.';
				}
		
			return $str2;
		}
		
		public static function GetElementList ($arFilter, $limit = false, $arOrder = array("ID" => "ASC"), $more_info = false, $key_id = false) {
			
			
					$arNavStartParams = false;
					if($limit) $arNavStartParams = array("nTopCount" => $limit);
			
					$db_res = CIBlockElement::GetList(
						$arOrder,
						$arFilter,
						false,
						$arNavStartParams,
						array(
							"ID",
							"NAME",
							"CODE",
							"IBLOCK_ID",
							"ACTIVE_FROM",
							"ACTIVE_TO",
							"PREVIEW_PICTURE",
							"PREVIEW_TEXT",
							"DETAIL_PICTURE",
							"DETAIL_TEXT",
							"DETAIL_PAGE_URL",
						)
					);
					
					$arResult = array();
					if($more_info) {
						while ($ob_res = $db_res->GetNextElement())
						{
							$ar_res = $ob_res->GetFields();
							$ar_res['PROPERTIES'] = $ob_res->GetProperties();
							if($ar_res["PREVIEW_PICTURE"]) $ar_res["PICTURE"] = CFile::GetPath($ar_res["PREVIEW_PICTURE"]);
							
							if($key_id)	$arResult[$ar_res["ID"]] = $ar_res;
							else $arResult[] = $ar_res;
						}							
					}
					else {
						while ($ar_res = $db_res->Fetch())
						{						
							if($ar_res["PREVIEW_PICTURE"]) $ar_res["PICTURE"] = CFile::GetPath($ar_res["PREVIEW_PICTURE"]);
							
							if($key_id)	$arResult[$ar_res["ID"]] = $ar_res;
							else $arResult[] = $ar_res;
						}						
					}
			
			
			return $arResult;
		}
		
		public static function getElementCount ($arFilter)
        {	
		/*
			$obCache = new CPHPCache;
			$life_time = 60*60*24;
			$cache_id = 'ELEMENT2_'.$elementID.'_'.$iblockID;
			$return = false;
			
			if($obCache->InitCache($life_time, $cache_id, "/")) {
				$vars = $obCache->GetVars();
				$return = $vars["IELEMENT"];
			} else {			
		*/		 $count = 0;
				 if (CModule::IncludeModule("iblock"))
				 {
					 //pre($arFilter);
					 
					$count = CIBlockElement::GetList(
						array(),
						$arFilter,
						array()
					);					
				 }
			
			/*}
			if($obCache->StartDataCache()) {
				$obCache->EndDataCache(array(
					"IELEMENT"    => $return
				));
			}*/
		
		
			return $count;	
		
		
        }
		
		public static function GetElementsPrice ($arFilter) {
			
			   
		/*
			$obCache = new CPHPCache;
			$life_time = 60*60*1;
			$cache_id = 'ELEMENT_PRICE_'.$arFilter["IBLOCK_ID"].'_'.$arFilter["SECTION_ID"];
			$return = false;
			
			if($obCache->InitCache($life_time, $cache_id, "/")) {
				$vars = $obCache->GetVars();
				$return = $vars["IELEMENT"];
			} else {			
		*/
				 $return = false;
				 if (CModule::IncludeModule("iblock"))
				 {		
			 
					//pre($arFilter);
			 
					$db_res = CIBlockElement::GetList(
						array("CATALOG_PRICE_1" =>"ASC"),
						$arFilter,
						false,
						array("nTopCount" => "1"),
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",							
							"CATALOG_GROUP_1",							
														
						)
					);
					$MIN = 0;
					$MAX = 0;
					if ($arr = $db_res->Fetch())
					{
						$MIN = (int)$arr["CATALOG_PRICE_1"];		
					}
					
					$db_res = CIBlockElement::GetList(
						array("CATALOG_PRICE_1" =>"DESC"),
						$arFilter,
						false,
						array("nTopCount" => "1"),
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",							
							"CATALOG_GROUP_1",						
														
						)
					);
					
					if ($arr = $db_res->Fetch())
					{						
						$MAX = (int)$arr["CATALOG_PRICE_1"];
					}
					
					
					
					$return["MAX"] = $MAX;
					$return["MIN"] = $MIN;
				 }
				 
				 
			/*}
			if($obCache->StartDataCache()) {
				$obCache->EndDataCache(array(
					"IELEMENT"    => $return
				));
			}*/	 
			
			return $return;
				 
		}	
		
		public static function GetListElement($idblock = false, $arFilter_D = array(), $arOrder = array("SORT" => "ASC"),  $more_info = false, $key_id = false) {
		
		
					$arFilter = array();
					if($idblock) $arFilter["IBLOCK_ID"] = $idblock;	
					
					if($arFilter_D) {						
						$arFilter = array_merge($arFilter, $arFilter_D);
					}
					
					
					$db_res = CIBlockElement::GetList(
						$arOrder,
						$arFilter,
						false,
						false,
						array(
							"*",
							"DETAIL_PAGE_URL",
						)
					);
					$arResult = array();
					
					if($more_info) {
						while ($ob_res = $db_res->GetNextElement())
						{
							$ar_res = $ob_res->GetFields();
							$ar_res['PROPERTIES'] = $ob_res->GetProperties();
							if($ar_res["PREVIEW_PICTURE"]) $ar_res["PICTURE"] = CFile::GetPath($ar_res["PREVIEW_PICTURE"]);
							
							if($key_id)	$arResult[$ar_res["ID"]] = $ar_res;
							else $arResult[] = $ar_res;
						}							
					}
					else {
						while ($ar_res = $db_res->Fetch())
						{						
							if($ar_res["PREVIEW_PICTURE"]) $ar_res["PICTURE"] = CFile::GetPath($ar_res["PREVIEW_PICTURE"]);
							
							if($key_id)	$arResult[$ar_res["ID"]] = $ar_res;
							else $arResult[] = $ar_res;
						}						
					}
					
					
					
					
					return $arResult;
		
		}	
		
		
			
		
		public static function GetSectionList ($iblockID, $arFilter = array()) {		
			
				 $arRes = array();
				 if (CModule::IncludeModule("iblock"))
				 {					 
					 
					 if(!$arFilter) {
						 $arFilter = array(							
							"IBLOCK_ID" => $iblockID,						
							"ACTIVE" => "Y"						
						 );		
					 }					 
					
					$db_res = CIBlockSection::GetList(
						array("SORT"=> "ASC","NAME" => "ASC"),
						$arFilter						
					);
					while ($arr = $db_res->GetNext())
					{				
						if($arr["PICTURE"]) $arr["PICTURE"] = CFile::GetPath($arr["PICTURE"]);
						if($arr["DETAIL_PICTURE"]) {
							
							//$arr["DETAIL_PICTURE"] = CFile::GetPath($arr["DETAIL_PICTURE"]);
							$arImg = CFile::ResizeImageGet($arr["DETAIL_PICTURE"], array("width" => 245, "height" => 340), BX_RESIZE_IMAGE_PROPORTIONAL, true);
							$arr["DETAIL_PICTURE"] = $arImg["src"];
						}
				
						$arRes[] = $arr;
					}
				 }	
		
			return $arRes;
			
			
		}
		public static function SearchSectionByName ($name, $iblockID = false) {		
			
				 $return = false;
				 if (CModule::IncludeModule("iblock"))
				 {					 
					 $arFilter = array(							
						"IBLOCK_ID" => $iblockID,
						"NAME" => trim($name)
					 );
					
					$db_res = CIBlockSection::GetList(
						array(),
						$arFilter,
						false,
						false,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",							
							"CODE"							
						)
					);
					
					if ($arr = $db_res->Fetch())
					{
						$return = $arr;
					}
				 }	
		
			return $return;
			
			
		}
		
		
		public static function SearchBlockByName ($name)
        {
				 $return = false;
				 if (CModule::IncludeModule("iblock"))
				 {					 
					 $arFilter = array(		
						"SITE_ID" => "a1",
						"NAME" => trim($name)
					 );
					
					$db_res = CIBlock::GetList(
						array(),
						$arFilter,
						false,
						false,
						array(
							"ID",
							"NAME",											
							"CODE",											
						)
					);
					
					if ($arr = $db_res->Fetch())
					{
						$return = $arr;
					}
				 }				
		
			return $return;
		}	
		
		public static function SearchByName ($name, $iblockID = false)
        {
				 $return = false;
				 if (CModule::IncludeModule("iblock"))
				 {					 
					 $arFilter = array(							
						"IBLOCK_ID" => $iblockID,
						"=NAME" => trim($name)
					 );
					
					$db_res = CIBlockElement::GetList(
						array(),
						$arFilter,
						false,
						false,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID"							
						)
					);
					
					if ($arr = $db_res->Fetch())
					{
						$return = $arr["ID"];
					}
				 }
				
		
			return $return;
		}	
		
		public static function SearchByArt ($art, $iblockID = false)
        {
						
				 $return = FALSE;
				 if (CModule::IncludeModule("iblock"))
				 {					 
					 $arFilter = array(							
						"IBLOCK_ID" => $iblockID,
						"=PROPERTY_ART" => trim($art)
					 );
					
					$db_res = CIBlockElement::GetList(
						array(),
						$arFilter,
						false,
						false,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",	
							"IBLOCK_SECTION_ID",	
						)
					);
					
					if ($arr = $db_res->Fetch())
					{
						$return = $arr;
					}
				 }
				
		
			return $return;
		}
		
		
		public static function DescPropCheck($arResult, $prop_id, $prop_val = false) {
							
				$key = 'p_'.$prop_id;
				if($prop_val) $key.= '_'.translit($prop_val);				
				
				
				//pre($prop_val);
				//pre($key);				
				
				if($arResult[$key]) {
					return $arResult[$key];
				}
			
			return false;
		}
		
		
		
		
		
		public static function DescPropAll($idblock = false) {
			
					if(!$idblock) $idblock = 57;
					$arFilter = array(
						"IBLOCK_ID" => $idblock,
						"ACTIVE" => "Y",
						"!PROPERTY_PROPS_ID" => false
					);	

					
					
					$db_res = CIBlockElement::GetList(
						array("SORT"=>"ASK"),
						$arFilter,
						false						
					);
					$arResult = array(); 				
					
					while($ob_res = $db_res->GetNextElement())
					{    
						$arElement = $ob_res->GetFields();
						$arElement["PROPERTIES"] = $ob_res->GetProperties();
				
						foreach($arElement["PROPERTIES"]["PROPS_ID"]["VALUE"] as $k=>$val) {
							$arData = $arElement;
							
							$key = 'p_'.$val;
							if($arElement["PROPERTIES"]["PROPS_ID"]["DESCRIPTION"][$k]) {
								
								$key.= '_'.translit($arElement["PROPERTIES"]["PROPS_ID"]["DESCRIPTION"][$k]);
							}
							
							$arResult[$key] = $arData;
						}
					}
					
					
					
					return $arResult;
			
		}	
			
		public static function DescProp($prop_id, $prop_value = false, $element_id = false, $idblock = false) {
		
			global $USER;
		
			if(!$idblock) $idblock = 57;
			$obCache = new CPHPCache;
			$path = '/prop_desc_glossary/';
			$life_time = 60*60*24;
			
			if(!$prop_value) $prop_value = 0;
			
			$cache_id = 'PROPS_'.(int)$prop_id.'_'.translit($prop_value).'_'.(int)$element_id.'_'.(int)$idblock;
			
			if($obCache->InitCache($life_time, $cache_id, $path)) {
				$vars = $obCache->GetVars();
				$arResult = $vars["IPROP"];
			} else {
			
					$arFilter = array(
						"IBLOCK_ID" => $idblock,
						"ACTIVE" => "Y",
						"PROPERTY_PROPS_ID" => $prop_id
					);	

					if($_GET["mode"] == 'test') {
						//pre($arFilter);
					}
					if($prop_id == 1665) {
						//pre($arFilter);
					}
					
					$db_res = CIBlockElement::GetList(
						array("SORT"=>"ASK"),
						$arFilter,
						false						
					);
					$arResult = false; 				
					
					while($ob_res = $db_res->GetNextElement())
					{    
						if($arResult) break;					
					
						$arResult = $ob_res->GetFields();
						$arResult["PROPERTIES"] = $ob_res->GetProperties();
						
						
						$index = 0;
						foreach($arResult["PROPERTIES"]["PROPS_ID"]["VALUE"] as $k=>$val) {
							
							if($prop_id == $val) {
								
								if($_GET["mode"] == 'test') {
									//ECHO '<BR/>'.$prop_id.' == '.$val;
								}
								$index = $k;
							}
						}
						
							$prop_desc = $arResult["PROPERTIES"]["PROPS_ID"]["DESCRIPTION"][$index];				
					
							if($prop_desc) {
								
								$find = false;								
								//pre($prop_value);								
								if(!is_array($prop_value))	$prop_value = array($prop_value);
							
								foreach($prop_value as $prop_value2) {
									
									if(strtolower($prop_desc) == strtolower($prop_value2)) {							
										$find = true;
									}	

									//echo '<br/>'.$prop_desc.' == '.$prop_value2;
								}
								
								if(!$find) $arResult = false;								
							}
							
							//dump($arResult["ID"]);
						
					}
			}
			if($obCache->StartDataCache()) {
				$obCache->EndDataCache(array(
					"IPROP"    => $arResult
				));
			}
		
		
			
		
			return $arResult;		
		}
		
		public static function ChangeProductName($zag) {
			
			
			
			$zag = str_replace('Стиральная машина','стиральную машину', $zag);
			$zag = str_replace('Сушильная машина','сушильную машину', $zag);
			$zag = str_replace('Духовой шкаф','духовой шкаф', $zag);
			$zag = str_replace('Газовая панель','газовую панель', $zag);
			$zag = str_replace('Варочная поверхность','варочную поверхность', $zag);
			$zag = str_replace('Индукционная поверхность','Индукционную поверхность', $zag);
			$zag = str_replace('Вытяжка','вытяжку', $zag);
			$zag = str_replace('Холодильник','холодильник', $zag);
			$zag = str_replace('Посудомоечная машина','посудомоечную машину', $zag);
			$zag = str_replace('Декоративная панель','декоративную панель', $zag);
			
			
			
			return $zag;
		}
	
		
		
		public static function ProductsByBlock ($iblockID)
        {
				 $arResult = array();
				 if (CModule::IncludeModule("iblock"))
				 {					 
					 $arFilter = array(							
						"IBLOCK_ID" => $iblockID,
						"ACTIVE" => "Y"
					 );
					
					$db_res = CIBlockElement::GetList(
						array("SORT" => "ASC"),
						$arFilter						
					);
					
					while($ob_res = $db_res->GetNextElement())
					{
						$ar_res = $ob_res->GetFields();
						$ar_res['PROPERTIES'] = $ob_res->GetProperties();
					
					
						$arResult[] = $ar_res;
					}
				 }
				
		
			return $arResult;
		}	
		
		public static function MailOrderAddProps($id, $eventName, &$arFields) {
			
			 if ($eventName=="SALE_NEW_ORDER") {	

				
					CModule::IncludeModule('sale');			
					$order_id = $arFields["ORDER_REAL_ID"];
					
					$arContacts = array();
					$res = CSaleOrderPropsValue::GetOrderProps($order_id);
					while ($arProps = $res->Fetch())
					{						
						$arContacts[$arProps["CODE"]] = $arProps["VALUE"];
					}
					
					$arOrder = CSaleOrder::GetByID($order_id);
					
					$arDelivery = CSaleDelivery::GetByID($arOrder["DELIVERY_ID"]);
					$arPay = CSalePaySystem::GetByID($arOrder["PAY_SYSTEM_ID"]);
					
					$arContacts["DELIVERY_NAME"] = $arDelivery["NAME"];
					$arContacts["PAY_NAME"] = $arPay["NAME"];
					
					$arContacts["PRINT_TYPE_NAME"] = 'Интернет-магазин';
					$arContacts["PRINT_SITE_NAME"] = 'LIEBHERR';
					
					$arContacts["PATH_PIC"] = 'https://'.$_SERVER["SERVER_NAME"].'/tpl/images/mail/';
					$arContacts["PATH_SITE"] = 'https://'.$_SERVER["SERVER_NAME"].'/';				
					
					$arData = CStatic::getElement(CStatic::$DataId, 31);
	
	
	
					$phone_1 = $arData["PROPERTIES"]["PHONE_1"]["VALUE"];
					$phone_1_f = preg_replace("([^0-9])", "", $phone_1);
					
					$phone_2 = $arData["PROPERTIES"]["PHONE_2"]["VALUE"];
					$phone_2_f = preg_replace("([^0-9])", "", $phone_2);
					
					$arContacts["PHONE_1"] = $phone_1;
					$arContacts["PHONE_1_F"] = $phone_1_f;
					
					$arContacts["PHONE_2"] = $phone_2;
					$arContacts["PHONE_2_F"] = $phone_2_f;					
					
					
					$arContacts["CITY_TEXT"] = 'Москва';
					/*
					if($arContacts["CITY"]!= 'MSK') {
						$arContacts["CITY_TEXT"] = $arContacts["CITY2"];
					}
					*/
					switch($arContacts["LIFT"]) {
						case "BIG": $arContacts["LIFT"] = 'Грузовой'; break;
						case "SMALL": $arContacts["LIFT"] = 'Пассажирский'; break;
						case "NO": $arContacts["LIFT"] = 'Нет'; break;
					}
					
					$arContacts["ADDR_TEXT"] = $arContacts["CITY_TEXT"].' '.$arContacts["ADDR"];
					
					
					$arContacts["ORDER_DESCRIPTION"] = $arOrder["USER_DESCRIPTION"];
					
					
					$dbBasketItems = CSaleBasket::GetList(
						array(),
						array(							
							"LID" => SITE_ID,
							"ORDER_ID" => $order_id,
						),
						false,
						false,
						array("ID", "PRODUCT_ID", "QUANTITY","PRICE")
					);
				 
					$strOrderList2 = ""; 
					while ($arItem = $dbBasketItems->Fetch())
					{			
							$arElement = CStatic::getElement($arItem["PRODUCT_ID"]);				
				
							$strOrderList2 .= '<tr><td colspan="2" height="10"></td></tr>
											<tr>
												<td>
													<span style="color: #000;font-family: Tahoma;font-size: 14px;font-weight: 400;line-height: 22px;display: inline-block;">
														'.$arElement["NAME"].' - '.$arItem["QUANTITY"].' шт.
													</span>
												</td>												
												<td align="right">
													<span style="color: #000;font-family: Tahoma;font-size: 14px;font-weight: 400;line-height: 22px;display: inline-block;">
														'.number_format($arItem["PRICE"], 0, '.', ' ').' руб.
													</span>
												</td>
											</tr>
											<tr><td colspan="2" height="15"></td></tr>
											<tr><td colspan="2" height="1" bgcolor="#eaeaea"></td></tr>';
												
					}	
					
					$arContacts["ORDER_LIST2"] = $strOrderList2;
					
					
					$arFields = array_merge($arFields, $arContacts);	
			 }
		}
		
		public static function GetSectionsArrayTree($iblockID, $section_id) {	

			CModule::IncludeModule("iblock");
			
			$arSectionsActive = array();
			$nav = CIBlockSection::GetNavChain($iblockID, $section_id);
			while($arSectionPath = $nav->GetNext()){
				$arSectionsActive[] = $arSectionPath["ID"];		
			}

			return $arSectionsActive;
		}
		
		public static function SearchByBlockAcc ($blocks_id)
        {
						
				 $return = array();
				 if (CModule::IncludeModule("iblock"))
				 {					 
					 $arFilter = array(							
						"IBLOCK_ID" => 544,
						"PROPERTY_BLOCK_ID" => $blocks_id
					 );
					
					$db_res = CIBlockElement::GetList(
						array(),
						$arFilter,
						false,
						false,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",		
						)
					);
					
					if ($arr = $db_res->Fetch())
					{
						$return[] = $arr["ID"];
					}
				 }
				
		
			return $return;
		}
		
		
		public static $iblockCompare = 65;		
		public static function GetPropsByCompare($IBLOCK_ID, $SECTION_ID)
        {
						
				 $arFilter = array();
				 CModule::IncludeModule("iblock");
						
			 
					 $arFilterP = array(							
						"IBLOCK_ID" => CStatic::$iblockCompare,
						"PROPERTY_BLOCK_ID" => $IBLOCK_ID,
						"PROPERTY_SECTION_ID" => $SECTION_ID,
						"ACTIVE" => "Y"						
					 );
					 
					 //PRE($arFilterP);
					
					$db_res = CIBlockElement::GetList(
						array(),
						$arFilterP,
						false,
						false,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",	
							"IBLOCK_SECTION_ID",	
						)
					);
					
					if (!$ob_res = $db_res->GetNextElement())
					{
						
						 $arFilterP = array(							
							"IBLOCK_ID" => CStatic::$iblockCompare,
							"PROPERTY_BLOCK_ID" => $IBLOCK_ID,
							"ACTIVE" => "Y"						
						 );
						 
						 //PRE($arFilterP);
						
						$db_res = CIBlockElement::GetList(
							array(),
							$arFilterP,
							false,
							false,
							array(
								"ID",
								"NAME",
								"IBLOCK_ID",	
								"IBLOCK_SECTION_ID",	
							)
						);
						
						$ob_res = $db_res->GetNextElement();					
						
					}
					
					
					//pre($arFilterP);
					
					
					$arResult = array();
					if($ob_res) {
						$arProp = $ob_res->GetFields();
						$arProp["PROPERTIES"] = $ob_res->GetProperties();
						
						
						$arResult = array(
							"CODE" => array(),
							"NAME" => array(),						
						);
						
						
						foreach($arProp["PROPERTIES"]["PROPS_ID"]["VALUE"] as $prop_id) {
							$res = CIBlockProperty::GetByID($prop_id, $IBLOCK_ID);
							if($arProp = $res->GetNext()) {
								$arResult["CODE"][] = $arProp["CODE"];
								$arResult["NAME"][] = $arProp["NAME"];
							}
						}
						
						/*
						
						$arFilterP2 = array(
							"IBLOCK_ID" => $IBLOCK_ID,
							"ID" => $arProp["PROPERTIES"]["PROPS_ID"]["VALUE"],
						);
						pre($arFilterP2);
						
						$ob_prop = CIBlockProperty::GetList(array(), $arFilterP2);
						while ($arProp = $ob_prop->GetNext())
						{
							
							pre($arProp);
							
							$arResult[] = $arProp["CODE"];
						}

						*/
						
					}
						
					
					
					
					//pre($arResult);
					
					return $arResult;
					
					
					
					
		}				 
						
						
						
		
		public static $iblockRelated = 23;		
		public static function GetFilterByRelate($arProduct, $price_current)
        {
						
				 $arFilter = array();
				 if (CModule::IncludeModule("iblock"))
				 {		
			 
					 $arFilterP = array(							
						"IBLOCK_ID" => CStatic::$iblockRelated,
						"PROPERTY_BLOCK_ID" => $arProduct["IBLOCK_ID"],
						"ACTIVE" => "Y"						
					 );
					
					$db_res = CIBlockElement::GetList(
						array(),
						$arFilterP,
						false,
						false,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",	
							"IBLOCK_SECTION_ID",	
						)
					);
					
					while ($ob_res = $db_res->GetNextElement())
					{
						$arProp = $ob_res->GetFields();
						$arProp["PROPERTIES"] = $ob_res->GetProperties();
						
						switch($arProp["PROPERTIES"]["TYPE"]["VALUE_XML_ID"]) {
							
							case "prop":
								
								$res = CIBlockProperty::GetByID($arProp["PROPERTIES"]["PROP_ID"]["VALUE"], $arProduct["IBLOCK_ID"]);
								if($arPropData = $res->GetNext()) {									
									
									if($arProduct["PROPERTIES"][$arPropData["CODE"]]["VALUE"]) {
									
										if($arPropData["PROPERTY_TYPE"] == 'L') {
											$arFilter['PROPERTY_'.$arPropData["CODE"]] = $arProduct["PROPERTIES"][$arPropData["CODE"]]["VALUE_ENUM_ID"];
										}
										else {
											$arFilter['PROPERTY_'.$arPropData["CODE"]] = $arProduct["PROPERTIES"][$arPropData["CODE"]]["VALUE"];
										}
									}

									//pre($arProduct["PROPERTIES"][$arPropData["CODE"]]);
								}
								
							break;							
							case "price":
							
								if(($price_range =  $arProp["PROPERTIES"]["PRICE_RANGE"]["VALUE"]) && $price_current) {									
									
									$PRICE_MIN = ceil($price_current*(1-$price_range/100));
									$PRICE_MAX = ceil($price_current*(1+$price_range/100));		

									$arFilter["CATALOG_CURRENCY_1"] = "RUB";
									$arFilter[">=CATALOG_PRICE_1"] = $PRICE_MIN;
									$arFilter["<=CATALOG_PRICE_1"] = $PRICE_MAX;
								}								
							
							break;							
							case "section":
							
								$arFilter["SECTION_ID"] = $arProduct["IBLOCK_SECTION_ID"];
								
							break;
						}
						
					}
				 }
				
		
			return $arFilter;
		}
		
		public function GetSpecialCount($arFilterN, $exist = false) {
				
				$arFilter = array(
					"ACTIVE" => "Y",					
				);		
				if($exist) $arFilter["!PROPERTY_".$GLOBALS["K_EXIST_CODE"]."_VALUE"] = array("Нет в наличии", "Снят с производства");
				
				$arFilter = array_merge($arFilter, $arFilterN);	
				
				//if($_GET["mode"]) pre($arFilter);
				
				$count = CIBlockElement::GetList(
					array(),
					$arFilter,
					array(),
					false,
					array()
				);
				
				
				//if($_GET["mode"]) echo '<br/>['.$count.']';
				
				return $count;				
		}
		
		public static $labelsDefault = array(
			"S_NEW",
			"S_HIT",
			"LABEL_FREE_CONNECTION",
			"LABEL_FREE_DELIVERY",
		);
		public function GetLabelsInfoDop($arProduct) {
			
			$arLabels = array();
			if($arProduct["PROPERTIES"]["LABELS"]["VALUE"]) {
				
				$arLabelsAll = CStatic::GetLabels(408);
				foreach($arProduct["PROPERTIES"]["LABELS"]["VALUE"] as $label_id) {
					$arLabel = $arLabelsAll[$label_id];
					if(!in_array($arLabel["CODE"], CStatic::$labelsDefault)) {						
						$arLabels[$arLabel["CODE"]] = $arLabel;
					}					
				}				
			}
			
			return $arLabels;
			
		}
		
		public function GetCommission($arElement){
			
			$arCommission = array(
				"DELIVERY" => 99999,
				"CONNECTION" => 99999,
			);
			
			$db_res = CIBlockElement::getList(
				array(),
				array("IBLOCK_ID" => 22, "PROPERTY_BLOCK_ID" => $arElement["IBLOCK_ID"]),
				false, false,
				array("ID", "IBLOCK_ID", "NAME")
			);
			if ($ob_res = $db_res->GetNextElement()) {
				$arData = $ob_res->GetFields();
				$arData["PROPERTIES"] = $ob_res->GetProperties();	
			
				$arCommission["DELIVERY"] = $arData["PROPERTIES"]["COM_DELIVERY"]["VALUE"];
				$arCommission["CONNECTION"] = $arData["PROPERTIES"]["COM_CONNECTION"]["VALUE"];	
			}		
			
			return $arCommission;			
		}
		
		public function GetLabelsInfo($arProduct) {
			
			$arLabels = array(
				"S_NEW" => false,
				"S_HIT" => false,
				"S_SALE" => false,
				"LABEL_FREE_CONNECTION" => false,
				"LABEL_FREE_DELIVERY" => false,	
			);			
			
			
			/* ################################### доставка и подключение #################################### */

			$arCommission = self::GetCommission($arProduct);
			if($arProduct["PROPERTIES"]["COMMISSION"]["VALUE"] >= $arCommission["DELIVERY"]) {
				$arLabels["LABEL_FREE_DELIVERY"] = true;
			}
			if($arProduct["PROPERTIES"]["COMMISSION"]["VALUE"] >= $arCommission["CONNECTION"]) {
				$arLabels["LABEL_FREE_CONNECTION"] = true;
			}
				// если аксессуары, или встраиваемые в столешницу или  островные или подвесные,				
				/*if(
					$arProduct["IBLOCK_ID"] == CStatic::$catalogIdblock_pay_delivery 
					//|| $arProduct["PROPERTIES"]["INSTALLATION"]["VALUE_XML_ID"] == 't4'
					|| in_array($arProduct["IBLOCK_SECTION_ID"],array(369))				
				) {	
					$arLabels["LABEL_FREE_CONNECTION"] = false;
				}*/
				
				if(in_array($arProduct["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"], array('Снят с производства','Нет в наличии'))) {
					$arLabels["LABEL_FREE_CONNECTION"] = false;
					$arLabels["LABEL_FREE_DELIVERY"] = false;
				}
			
			/* ################################### доставка и подключение #################################### */
			
			 
			
			if($arProduct["PROPERTIES"]["S_NEW"]["VALUE"] == 'Y') {
				$arLabels["S_NEW"] = true;
			}		
			if($arProduct["PROPERTIES"]["S_HIT"]["VALUE"] == 'Y') {
				$arLabels["S_HIT"] = true;
			}
			if($arProduct["PROPERTIES"]["S_SALE"]["VALUE"] == 'Y') {
				$arLabels["S_SALE"] = true;
			}		

			
			
			
			return $arLabels;
			
		}
		
		
		
		
		public function GetReviewsIDs($block_id = false, $section_id = false, $cache = true) {
				
				
				
				
				$obCache = new CPHPCache;
				$life_time = 60*60*24;			
					
				$cache_path = '/reviews_ids/';
				$cache_id = 'REVIEWS_IDS_'.(int)$block_id.'_'.(int)$section_id;
				$arResult = false;
				
				if($obCache->InitCache($life_time, $cache_id, $cache_path) && $cache) {
					$vars = $obCache->GetVars();
					$arResult = $vars["IDATA"];
				} else {	
				
				
				
				
				
					$arFilter = array(							
						"IBLOCK_ID" => CStatic::$ReviewsIdBlock,						
						"ACTIVE" => "Y"
					 );
					if($block_id) $arFilter["PROPERTY_TOV_ID.IBLOCK_ID"] = $block_id; 
					
					//IF($_GET["mode"]) pre($arFilter);
					
					$db_res = CIBlockElement::GetList(
						array(),
						$arFilter,
						false,
						false,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",							
							"PROPERTY_TOV_ID",							
						)
					);	
					$arResult = array();
					while ($arFields = $db_res->Fetch())
					{				
				
						if( $section_id ) {
							$arProduct = self::getElement($arFields["PROPERTY_TOV_ID_VALUE"]);
							
							//IF($_GET["mode"]) ECHO '<br/>[[ '.$arProduct["IBLOCK_SECTION_ID"].' == '.$section_id;
							
							if($arProduct["IBLOCK_SECTION_ID"] == $section_id) {
								
								$arResult[] = $arFields["ID"];
								
							}
						}
						else {
							$arResult[] = $arFields["ID"];
						}
					}
					
					if(!$arResult) $arResult = false;					
					
			}
			if($obCache->StartDataCache()) {
				$obCache->EndDataCache(array(
					"IDATA"    => $arResult
				));
			}
					
					
			return $arResult;
				
		}
		public function GetReviewsPrices($arFilterN, $cache = true, $type = 'min') {
			
					$arIds = array();
					if(count($arFilterN) > 1) {
						foreach($arFilterN as $key=> $vals) {
							if(!is_array($vals)) $vals = array($vals);
							$arIds = array_merge($arIds, $vals);
						}
					}
					else {
						$arIds = reset($arFilterN);
						if(!is_array($arIds)) $arIds = array($arIds);
					}
			
			
				$obCache = new CPHPCache;
				$life_time = 60*60*24;			
					
				$cache_path = '/reviews_'.$type.'_price/';
				$cache_id = 'REVIEWS_IDS_'.array_sum($arIds);
				if($type == 'min') $PRICE = 9999999999999;	
				else $PRICE = 0;
				
				if($obCache->InitCache($life_time, $cache_id, $cache_path) && $cache) {
					$vars = $obCache->GetVars();
					$PRICE = $vars["IDATA"];
				} else {
					
					//PRE($arFilterN);
					//PRE($cache_id);					
					
					$arFilter = array(
						"ACTIVE" => "Y",
					);
					$arFilter = array_merge($arFilter, $arFilterN);
			
					$db_res = CIBlockElement::GetList(
						array(),
						$arFilter,
						false,
						false,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",	
							"PROPERTY_TOV_ID",	
						)
					);
					
					
					
					while($ob_res = $db_res->Fetch())
					{
						
						//PRE($ob_res["PROPERTY_TOV_ID_VALUE"]);
						$arPrice = reset(CStatic::GetPrice($ob_res["PROPERTY_TOV_ID_VALUE"]));
						
						if($type == 'min') {
							IF($PRICE > $arPrice["DISCOUNT_VALUE"]) $PRICE = $arPrice["DISCOUNT_VALUE"];
						}
						else {
							IF($PRICE < $arPrice["DISCOUNT_VALUE"]) $PRICE = $arPrice["DISCOUNT_VALUE"];
						}
						
						
					}
					//PRE($MIN_PRICE);
					
					
				}
				if($obCache->StartDataCache()) {
					$obCache->EndDataCache(array(
						"IDATA"    => $PRICE
					));
				}
					
					
					
				return $PRICE;
			
			
		}
		
		
		
		
		
		
		
		
		public function GetReviewsRating($product_id = false, $block_id = false, $section_id = false, $r_block_id = false, $r_section_id = false) {
			
			
					if(!$r_block_id) $r_block_id = CStatic::$ReviewsIdBlock;
					if(!$r_section_id) $r_section_id = CStatic::$ReviewsIdSec;
			
					$arFilter = array(							
						"IBLOCK_ID" => $r_block_id,	
						"SECTION_ID" => $r_section_id,	
						"ACTIVE" => "Y"
					 );
					 
					 if($product_id) $arFilter["PROPERTY_TOV_ID"] = $product_id;
					 if($block_id) $arFilter["PROPERTY_TOV_ID.IBLOCK_ID"] = $block_id;
					 if($section_id) {				 
						 $arFilter["ID"] = self::GetReviewsIDs($block_id, $section_id);
					 }
					 
					$db_res = CIBlockElement::GetList(
						array("SORT" => "ASC"),
						$arFilter						
					);
					
					$rating_sum = 0;
					$rating_count = 0;
					$rating = '';
					
					
					$arRatings = array();
					
					while($ob_res = $db_res->GetNextElement())
					{
						$ar_res = $ob_res->GetFields();
						$ar_res['PROPERTIES'] = $ob_res->GetProperties();
					
						if(!$arRatings[$ar_res["PROPERTIES"]["RATING"]["VALUE"]]) $arRatings[$ar_res["PROPERTIES"]["RATING"]["VALUE"]] = 0;
						$arRatings[$ar_res["PROPERTIES"]["RATING"]["VALUE"]]++;
						
						$rating_sum = $rating_sum + $ar_res["PROPERTIES"]["RATING"]["VALUE"];
						$rating_count++;						
					}
					
					
					if($rating_count) $rating = $rating_sum/$rating_count;			
			
					$arResult["LIST"] = $arRatings;
					$arResult["RATE"] = round($rating, 1);
					$arResult["COUNT"] = $rating_count;
			
			
			
				return $arResult;
		}
		
		
		public function CheckViewParams($arResult) {
			
			$arShow = array(
				"PRICE_MESS" => '',
				"PRICE_DETAIL" => true,
				"PRICE" => true,
				"LABELS" => true,
				"BUY" => true,
			);		
			//pre($arResult["MIN_PRICE"]);
			//pre($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]);
			
			if($_GET["mode"]) {
				//pre($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]);
				//dump($arResult["MIN_PRICE"]);
			}
			
			
			if(!$arResult["MIN_PRICE"]["VALUE"] || !$arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"]) {$arShow["PRICE"] = false; $arShow["PRICE_DETAIL"] = false; $arShow["PRICE_MESS"] = CStatic::$NoPriceExistView; $arShow["BUY"] = false;}			
			if(in_array($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"], array('Снят с производства','Нет в наличии',''))) {  $arShow["PRICE"] = false; $arShow["BUY"] = false;  $arShow["LABELS"] = false;}
			
			if($_GET["mode"]) {
				//pre($arShow);
			}
			
			
			
			return $arShow;
		}
		
		public static function RedirectByData() {
			
			
			$arSites = array(
				"s1" => 52,
				"s2" => 53,
			);
			
			
			global $APPLICATION;
			$page_site = $APPLICATION->GetCurPage();			
			if($_SERVER['REQUEST_URI_REAL']) {		
				$page_site_current = $_SERVER['REQUEST_URI_REAL'];						
			}
			elseif($_SERVER['REQUEST_URI']) {
				$page_site_current = $_SERVER['REQUEST_URI_REAL'];
			}			
			
						
			$page = empty($page_site_current)?($page_site):($page_site_current);
			
			
					$arFilter = array(
						"ACTIVE" => "Y",
						"IBLOCK_ID" => "59",
						"SECTION_ID" => $arSites[SITE_ID],		
						"PROPERTY_URL_OLD" => $page,		
					);
			
					$db_res = CIBlockElement::GetList(
						array("SORT" => "ASC"),
						$arFilter, 
						false,
						false,
						array(
							"ID",
							"NAME",
							"PROPERTY_URL_OLD",
						)
					);
					
					if($arElement = $db_res->Fetch())
					{
						LocalRedirect($arElement["NAME"], false, '301 Moved permanently');			
					}
		}
		
		
		
		public static function RedirectInit() {
			global $APPLICATION;
			$page_site = $APPLICATION->GetCurPage();			
			if($_SERVER['REQUEST_URI_REAL']) {		
				$page_site_current = $_SERVER['REQUEST_URI_REAL'];						
			}
			elseif($_SERVER['REQUEST_URI']) {
				$page_site_current = $_SERVER['REQUEST_URI_REAL'];
			}			
			
						
			$page = empty($page_site_current)?($page_site):($page_site_current);
			
			if(strstr($page, '/catalog/')) {				
				$page_n = str_replace('/catalog/','/liebherr/',$page);				
				//LocalRedirect($page_n, false, '301 Moved permanently');
			}			
		}
		
		/* ###################################### Фильтр display  ######################################### */
		
		public function FilterPropsDisplayChange($arPropsIds, $block_id, $val) {
			
				if($arPropsIds) {
					
					$ibp = new CIBlockProperty;
					
					//ECHO '<BR/> МЕНЯЕМ НА '.$val;
					
					foreach($arPropsIds as $prop_id) {
						$res = CIBlockProperty::GetByID($prop_id, $block_id);
						if($prop = $res->GetNext()) {
							
							//pre($prop["ID"]);
							$arFields = Array(
								"SMART_FILTER" => $val, 
								"IBLOCK_ID" => $block_id
							);							

							$ibp->Update($prop["ID"], $arFields);
						}
					}
				}
			
		}
		
		
		
		
		public function FilterPropsDisplayDelete($ID) {
			
			if($arElement = CStatic::getElement($ID, 60, false)) {
						
				$arPropsIds = $arElement["PROPERTIES"]["PROPS_ID"]["VALUE"];
				$block_id = $arElement["PROPERTIES"]["BLOCK_ID"]["VALUE"];
						
				self::FilterPropsDisplayChange($arPropsIds, $block_id, "N");					
			}			
		}
		
		public function FilterPropsDisplayAdd(&$arFields) {
			
			if($arFields["IBLOCK_ID"] == 60) {
				
				if($arFields["ID"] > 0) {
					
					$arElement = CStatic::getElement($arFields["ID"], $arFields["IBLOCK_ID"], false);
					
					$arPropsIds = $arElement["PROPERTIES"]["PROPS_ID"]["VALUE"];
					$block_id = $arElement["PROPERTIES"]["BLOCK_ID"]["VALUE"];
					
					
					self::FilterPropsDisplayChange($arPropsIds, $block_id, "Y");
				}
			}			
			
			return $arFields;
		}
		
		public function FilterPropsDisplayUpdate(&$arFields) {
			
			if($arFields["IBLOCK_ID"] == 60) {
				
				if($arFields["ID"] > 0) {	

					$arPropIds = array();
					// ID СВОЙСТВА СО СВОЙСТВАМИ КОТОРЫЕ СКРЫВАТЬ
					foreach($arFields["PROPERTY_VALUES"][2261] as $prop) {
						if($prop["VALUE"]) $arPropIds[] = $prop["VALUE"];
					}
					//pre($arPropIds);					
					
					$ibp = new CIBlockProperty;
					
					$arElementBefore = CStatic::getElement($arFields["ID"], $arFields["IBLOCK_ID"], false);
					
					$arPropIdsBefore = $arElementBefore["PROPERTIES"]["PROPS_ID"]["VALUE"];
					$block_id = $arElementBefore["PROPERTIES"]["BLOCK_ID"]["VALUE"];
					
					// ПОЛУЧАЕМ ОБЩИЕ, ЧТОБЫ ПОТОМ ИХ ОТСЕКАТЬ.					
					$commonIds = array_intersect($arPropIds, $arPropIdsBefore); 
					
					// УБИРАЕМ МАРКЕР ВЫВОДИТЬ В ФИЛЬТРЕ
					if($diffIds_off = array_diff($arPropIdsBefore, $commonIds)) {
						self::FilterPropsDisplayChange($diffIds_off, $block_id, "N");
					} 
					// ДОБАВЛЯЕМ МАРКЕР ВЫВОДИТЬ В ФИЛЬТРЕ
					self::FilterPropsDisplayChange($arPropIds, $block_id, "Y");
					/*
					if($diffIds_add = array_diff($arPropIds, $commonIds)) {
						self::FilterPropsDisplayChange($diffIds_off, $block_id, "Y");
					}		
					*/			
				}
			}			
			
			return $arFields;
		}
		
		/* ###################################### Фильтр display END ######################################### */
		
		
		public static function PageCanonical()
		{			
			global $APPLICATION;
			global $USER;
			//PRE($_SERVER["REQUEST_URI"]);
			IF(!$_GET) $_GET = array();
			
			
			
			
			
			
			
			if($_SERVER["REQUEST_URI_REAL"]) $page_real = $_SERVER["REQUEST_URI_REAL"];
			else $page_real = $_SERVER["REQUEST_URI"];
			
			$arUrl = explode('/', $page_real);
			
			
			$arGetMy = array();
			//pre(end($arUrl));
			
				// хак чтобы брать параметры не из GET, а те что после "?"		
				$arr = parse_url($page_real);
				if($arr["query"]) {					
					parse_str($arr["query"], $output);
					$arGetMy = $output;
				}
				
				
					
						
			
			
			
			
			$ParamsY = array(					
				//"PAGEN_1",			
				//"PAGEN_2"			
				//"ADVANTAGES_CODE",
			);
			
			if($arGetMy) {				
				
				//pre($_SERVER);
				$arPageParams = array_keys ($arGetMy);
				
					$page_site = $APPLICATION->GetCurPage();
					if($_SERVER['REQUEST_URI_REAL']) {		
						$page_site_current = current(explode('?',$_SERVER['REQUEST_URI_REAL']));			
					}			
					
					$page_r = empty($page_site_current)?($page_site):($page_site_current);
					
					if($USER->IsAdmin()) {
						//dump( substr(end($arUrl), 0,1));			
						//dump($arUrl);			
					}
				
				if(/*array_diff($arPageParams, $ParamsY) || */(array_intersect($arPageParams, $ParamsY) && in_array('ELEMENT_CODE', $_GET)) || substr(end($arUrl), 0,1) == '?') {					
						
						
						//$CAN = $APPLICATION->GetPageProperty('K_CANONICAL');
						$CAN = (CModule::IncludeModule("primelab.urltosef") && CPrimelabUrlToSEF::isHasSEF());
						
						if($USER->IsAdmin()) {
							  //dump($arUrl);							  
							  //dump(array_diff($arPageParams, $ParamsY));							  
							 // dump(array_intersect($arPageParams, $ParamsY));							  
							  //dump($page_r);							  
						}
						
						
						//if(!$CAN) 
						{
							
							$APPLICATION->AddHeadString('<link rel="canonical" href="https://'.$_SERVER["HTTP_HOST"].$page_r.'" />',true);
						}
				}	
			}
		}
		
		
		public function SortByWordstat(&$arFields) {
			//pre($arFields);			
			
			if(in_array($arFields["IBLOCK_ID"], CStatic::$catalogIdBlock)) {				
				
				$arProduct = CStatic::getElement($arFields["ID"], $arFields["IBLOCK_ID"], false);				
				
				$sort = (int)$arProduct["PROPERTIES"]["WORDSTAT"]["VALUE"];
				if($arProduct["PROPERTIES"]["S_NEW"]["VALUE"] == 'Y') $sort = $sort + 1000;
				if($arProduct["PROPERTIES"]["S_HIT"]["VALUE"] == 'Y') $sort = $sort + 1000;
				
				
				if($sort != $arProduct["SORT"]) {
					
					CModule::IncludeModule("iblock");
					$el = new CIBlockElement;
					
					$arFieldsNew = Array(
						"SORT" => $sort,
					);
					
					//pre($arFieldsNew);

					$el->Update($arProduct["ID"], $arFieldsNew);
				}
			}	
			
			return $arFields;
		}
		
		public function RegionChange() {
			
			$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
			
			if($request->offsetGet("K_REGION")) {
				
				global $APPLICATION;				
				
				setcookie("K_REGION", $request->offsetGet("K_REGION"), time() + 15552000, "/");
				$_COOKIE["K_REGION"] = $request->offsetGet("K_REGION");
				
				$url_new = $APPLICATION->GetCurPageParam("", array("K_REGION"));
				
				CStatic::RegionInit();
				CStatic::CityBasketUpdate();
				
				LocalRedirect($url_new, false, '301 Moved permanently');					
			}
			
			
			
		}
		
		
		public function RegionInit() {
			
			
			$region = 'MSK';
			if($_COOKIE["K_REGION"]) {
				$region = $_COOKIE["K_REGION"];
			}			
			if(strstr($_SERVER["SERVER_NAME"], 'spb.')) {	
				$region = 'SPB';
			}
			
			$arFilter = array(
				"IBLOCK_ID" => 71,
				"CODE" => $region,
			);
			
			$db_res = CIBlockElement::getList(
				array("SORT" => "ASC"),
				$arFilter,
				false, false,
				array("ID", "IBLOCK_ID", "NAME", "CODE")
			);
			$arItems = array();
			if ($ob_res = $db_res->GetNextElement()) {
				$arElement = $ob_res->GetFields();
				$arElement["PROPERTIES"] = $ob_res->GetProperties();				
				
				$GLOBALS["K_PRICE_CODE"] = $arElement["PROPERTIES"]["PRICE_CODE"]["VALUE"];
				$GLOBALS["K_REGION_NAME"] = $arElement["NAME"];
				$GLOBALS["K_EXIST_CODE"] = $arElement["PROPERTIES"]["EXIST_CODE"]["VALUE"];
				
				if(!$_COOKIE["K_REGION"]) {
					setcookie("K_REGION", $region, time() + 15552000, "/");
					$_COOKIE["K_REGION"] = $region;
				}
				
			}
			
			$GLOBALS["K_PRICE_CODE_SALE"] = 'MSK_SALE';
		}
		
		
		public function RegionInitOld() {
			
			$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
			
			if($request->offsetGet("K_REGION")) {
				
				global $APPLICATION;				
				
				setcookie("K_REGION", $request->offsetGet("K_REGION"), time() + 15552000, "/");
				$_COOKIE["K_REGION"] = $request->offsetGet("K_REGION");
				
				$url_new = $APPLICATION->GetCurPageParam("", array("K_REGION"));
				
				LocalRedirect($url_new, false, '301 Moved permanently');					
			}
			
			
			$region = 'MSK';
			if($_COOKIE["K_REGION"]) {$region = $_COOKIE["K_REGION"];}			
			if(strstr($_SERVER["SERVER_NAME"], 'spb.')) {	
				$region = 'SPB';
			}
			
			
			
			
			$arFilter = array(
				"IBLOCK_ID" => 71,
				"CODE" => $region,
			);
			
			$db_res = CIBlockElement::getList(
				array("SORT" => "ASC"),
				$arFilter,
				false, false,
				array("ID", "IBLOCK_ID", "NAME", "CODE")
			);
			$arItems = array();
			if ($ob_res = $db_res->GetNextElement()) {
				$arElement = $ob_res->GetFields();
				$arElement["PROPERTIES"] = $ob_res->GetProperties();				
				
				$GLOBALS["K_PRICE_CODE"] = $arElement["PROPERTIES"]["PRICE_CODE"]["VALUE"];
				$GLOBALS["K_REGION_NAME"] = $arElement["NAME"];
				$GLOBALS["K_EXIST_CODE"] = $arElement["PROPERTIES"]["EXIST_CODE"]["VALUE"];
				
				if(!$_COOKIE["K_REGION"]) {
					setcookie("K_REGION", $region, time() + 15552000, "/");
					$_COOKIE["K_REGION"] = $region;
				}
				
			}
			
			$GLOBALS["K_PRICE_CODE_SALE"] = 'MSK_SALE';
		}
		
		
		public static function CheckSalePrice($arResult, $arProduct = false) {


			if(!$arProduct) $arProduct = $arResult;
			
			if($arResult["ID"] == 1266) {
				//PRE($arResult["PRICES"]);
			}
			
			// если есть цена региона и акционная цена, и sale цена больше чем регион, тогда удаляем ее нахрен, т.к. у региона все равно дешевле
			if(
				$arResult["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]] && $arResult["PRICES"][$GLOBALS["K_PRICE_CODE"]]
				&& ($arResult["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"] > $arResult["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"])
			) {				
				unset($arResult["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]);
			}
			
			
			//pre($arResult["ID"]);
			//pre($arProduct["PROPERTIES"]["S_SALE_ONLY_MSK"]["VALUE"]);
			if($arResult["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]) {		
				if($arProduct["PROPERTIES"]["S_SALE_ONLY_MSK"]["VALUE"] == 'Y' && $_COOKIE["K_REGION"] != 'MSK') {
					
					unset($arResult["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]);
					$arResult["MIN_PRICE"] = $arResult["PRICES"][$GLOBALS["K_PRICE_CODE"]];
					
					$arResult["PRICE"] = $arResult["PRICES"][$GLOBALS["K_PRICE_CODE"]]["VALUE"];
					$arResult["BASE_PRICE"] = $arResult["PRICES"][$GLOBALS["K_PRICE_CODE"]]["VALUE"];
				}				
			}		
			
			return $arResult;
		}
		
		
		
		public function CityBasketUpdate() {
			
			
			CModule::IncludeModule('sale');
			CModule::IncludeModule('catalog');
			
			$dbBasketItems = CSaleBasket::GetList(
				array(
					"PRICE" => "DESC",
					"NAME" => "ASC",
					"ID" => "ASC"
				),
				array(
					"FUSER_ID" => CSaleBasket::GetBasketUserID(),
					"LID" => SITE_ID,
					"ORDER_ID" => "NULL"
				),
				false,
				false,
				array("ID", "NAME", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "DETAIL_PAGE_URL", "NOTES", "CURRENCY", "VAT_RATE")
			);
			while ($arItems = $dbBasketItems->GetNext()) {
				if (strlen($arItems["CALLBACK_FUNC"]) > 0) {					
					CSaleBasket::UpdatePrice($arItems["ID"], $arItems["CALLBACK_FUNC"], $arItems["MODULE"], $arItems["PRODUCT_ID"], $arItems["QUANTITY"]);
				}
			}
		}
		
}


?>