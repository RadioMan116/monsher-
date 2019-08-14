<?
$menu_tip = 'tov_detail';
//$bIndexPage = ($_SERVER["SCRIPT_NAME"] == "/index.php" ? true : false);
define("PATH_TO_404", "/404.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");




$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$arBlock = getArIblock('mn_catalog', $request->get("IBLOCK_CODE"));
$arTov = getArElement($arBlock["ID"], $request->get("ELEMENT_CODE"));
$arTov = CStatic::getElement($arTov["ID"], $arBlock["ID"]);
$arPrice = current(CStatic::getPrice($arTov["ID"]));
// Добавляем ИБ в цепочку навигации
$APPLICATION->AddChainItem($arBlock["NAME"], '/liebherr/'.$arBlock["CODE"].'/');




		$arProps = array();		
		$rProp = CIBlockProperty::GetList(
			Array("SORT" => "ASC"), 
			Array(
			"ACTIVE" => "Y", 
			"IBLOCK_ID" => $arBlock["ID"],
			//"FILTRABLE" => "Y"
			)
		);
		while ($arProp = $rProp->GetNext())
		{
		  $arProps[] = $arProp["CODE"];
		  if($arProp["FILTRABLE"] == 'Y') {$arProps2[] = $arProp["CODE"];}		  
		}	
		
		$arFieldsDop = CASDiblockTools::GetIBUF($arBlock["ID"]);
		$arName = CStatic::getElement($arFieldsDop["UF_NAME_ID"], 20);		
		$name_syno = strtolower($arName["PROPERTIES"]["SYNO"]["VALUE"]);
		
		$name_rus = str_replace('Liebherr','Либхер',$arTov["NAME"]);


if($arPrice) {			
	$APPLICATION->SetPageProperty("title", $arTov["NAME"].' в Москве с официальной гарантией по цене '.$arPrice["DISCOUNT_VALUE"].' руб., отзывы инструкции и схемы - купить Либхер '.$arTov["PROPERTIES"]["MODEL"]["VALUE"].' в интернет-магазине на l-rus.ru.');
}
else {
	$APPLICATION->SetPageProperty("title", $arTov["NAME"].' в Москве с официальной гарантией, цена по запросу, отзывы инструкции и схемы - купить Либхер '.$arTov["PROPERTIES"]["MODEL"]["VALUE"].' в интернет-магазине на l-rus.ru.');
		
}
$APPLICATION->SetPageProperty("description", $arTov["NAME"].' в Москве - купить '.mb_lcfirst($name_rus).' в интернет-магазине - бесплатная  доставка и гарантия производителя, сравните отзывы и характеристики,  инструкция со схемой на сайте l-rus.ru.');
	
		
//PRE($arProps);
$tpl = 'tov.detail';
if($_GET["mode"]) $tpl = 'tov.detail.new';




if(!$_SESSION["CATALOG_VIEWS_LIST"]) {
	$_SESSION["CATALOG_VIEWS_LIST"] = array();
	
}
if(!in_array($arTov["ID"], $_SESSION["CATALOG_VIEWS_LIST"])) $_SESSION["CATALOG_VIEWS_LIST"][] = $arTov["ID"];
//PRE($_SESSION["CATALOG_VIEWS_LIST"]);
//PRE($_SESSION["CATALOG_COMPARE_LIST"]);


?>



<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"tov.detail.new",
	Array(
		"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
		"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
		"FAVORITE_LIST" => $_COOKIE["FAVORITE_LIST"],
		"CATALOG_VIEWS_LIST" => $_SESSION["CATALOG_VIEWS_LIST"],
		"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
		"IBLOCK_TYPE" => "mn_catalog",
		"IBLOCK_ID" => $arBlock["ID"],
		"ELEMENT_ID" => "",
		"ELEMENT_CODE" => $request->get("ELEMENT_CODE"),
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"PROPERTY_CODE" => $arProps,		
		"OFFERS_LIMIT" => "0",
		"TEMPLATE_THEME" => "red",
		"DISPLAY_NAME" => "Y",
		"DETAIL_PICTURE_MODE" => "IMG",
		"ADD_DETAIL_TO_SLIDER" => "Y",
		"DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_CLOSE_POPUP" => "Y",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"USE_VOTE_RATING" => "N",
		"USE_COMMENTS" => "N",
		"BRAND_USE" => "N",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"CHECK_SECTION_ID_VARIABLE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"SET_STATUS_404" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"DISPLAY_COMPARE" => "Y",
		"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/order/",
		"USE_PRODUCT_QUANTITY" => "Y",
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(),
		"ADD_TO_BASKET_ACTION" => array("ADD"),
		"LINK_IBLOCK_TYPE" => "mn_catalog",
		"LINK_IBLOCK_ID" => $arBlock["ID"],
		"LINK_PROPERTY_SID" => "SIMILAR",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"ADD_PICT_PROP" => "PHOTOS",
		"LABEL_PROP" => "-",
		"MESS_BTN_COMPARE" => "Сравнить",
	)
);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>