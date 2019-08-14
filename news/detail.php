<?
$menu_tip = 'news_detail';
$bIndexPage = ($_SERVER["SCRIPT_NAME"] == "/index.php" ? true : false);
define("PATH_TO_404", "/404.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

//PRE($request);

?>




<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail", 
	"articles.detail", 
	array(
		"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
        "K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
		"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
		"IBLOCK_TYPE" => "mn_content",
		"IBLOCK_ID" => "83",
		"ELEMENT_ID" => "",
		"ELEMENT_CODE" => $request->get("ELEMENT_CODE"),
		"CHECK_DATES" => "Y",
		"FIELD_CODE" => array(
			0 => "PREVIEW_TEXT",
			1 => "PREVIEW_PICTURE",
			2 => "DETAIL_TEXT",
			3 => "DETAIL_PICTURE",
			4 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "TYPE",
			1 => "TOVS_ID",
			2 => "",
		),
		"IBLOCK_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "N",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",		
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"USE_PERMISSIONS" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Страница",
		"PAGER_TEMPLATE" => "",
		"PAGER_SHOW_ALL" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"USE_SHARE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "articles.detail",
		"DETAIL_URL" => "",
		"SET_CANONICAL_URL" => "N",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"STRICT_SECTION_CHECK" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?> 


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>