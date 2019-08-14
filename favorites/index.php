<?
$menu_tip = 'favorites';
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Избранное");
?>
<div class="js-favorite_list js-ecom_product-list" data-list="Избранное">
<?if($_COOKIE["FAVORITE_LIST"]):?>

<?

global $arrFilterV;	
		
		$FAVORITE_LIST = explode('|',$_COOKIE["FAVORITE_LIST"]);
		$arId = array_diff($FAVORITE_LIST, array(''));	
		$arrFilterV["ID"] = $arId;
		
		if($_SESSION["F_SECTIONS"]) {
			$arrFilterV["IBLOCK_SECTION_ID"] = $_SESSION["F_SECTIONS"];
		}
		
		
?>

<a href="" title="Сбросить избранное" class="favorite__reset js-favorite_remove-all">Сбросить избранное</a>

<?if($_POST['AJAX']=='Y') $APPLICATION->RestartBuffer();?>		   
<?$APPLICATION->IncludeComponent(
	"inter.olsc:catalog.section",
	"tovs.list",
	Array(
		"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
        "K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
		"AJAX" => $request->offsetGet("AJAX"),		
		"FAVORITE_LIST" => $_COOKIE["FAVORITE_LIST"],
		"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
		"IBLOCK_TYPE" => "mn_catalog",
		"IBLOCK_ID" => CStatic::$catalogIdBlock,
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array("", "", ""),
		"ELEMENT_SORT_FIELD" => "propertysort_".$GLOBALS["K_EXIST_CODE"],
		"ELEMENT_SORT_ORDER" => "ASC",
		"ELEMENT_SORT_FIELD2" => "",
		"ELEMENT_SORT_ORDER2" => "",
		"FILTER_NAME" => "arrFilterV",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => 12,
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array("PHOTOS"),
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "blue",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"ADD_SECTIONS_CHAIN" => "Y",
		"SET_STATUS_404" => "Y",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/order/",
		"USE_PRODUCT_QUANTITY" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"DISPLAY_COMPARE" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"MESS_BTN_COMPARE" => "Сравнить",		
	)
);?>
<?if($_POST['AJAX']=='Y') die();?>
	
<?else:?>
	<div class="text-default">Список избранных товаров пуст.</div>	
<?endif;?>
</div>		   
			   
			   
			   

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>