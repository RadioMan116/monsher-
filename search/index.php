<?
$menu_tip = 'search';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Результаты поиска");	



//if($_REQUEST["q"]) $_REQUEST["q"] = "'".$_REQUEST["q"]."'";

//$GLOBALS["FILTER_SEARCH"] = array("!ITEM_ID" => "S%");


$_REQUEST["q"] = preg_replace("/[^a-zа-яё\d]]/i"," ",$_REQUEST["q"]);
//$_REQUEST["q"] = preg_replace("/[^\p{L}0-9\!]/iu"," ",$_REQUEST["q"]);

$tpl = 'search';
if($_GET["mode"]) $tpl = '';



$APPLICATION->IncludeComponent(
	"bitrix:search.page", 
	"search_exist", 
	array(		
		"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
        "K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
		"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
		"PHOTO_RESIZE_TYPE" => "SEARCH_PAGE",
		"RESTART" => "Y",
		"NO_WORD_LOGIC" => "N",
		"CHECK_DATES" => "N",
		"USE_TITLE_RANK" => "Y",
		"DEFAULT_SORT" => "rank",
		"FILTER_NAME" => "FILTER_SEARCH",
		"arrFILTER" => array(
			0 => "iblock_mn_catalog",
		),
		"={\"arrFILTER_iblock_\".\$GLOBALS[\"SITE_CONFIG\"][\"IBLOCK_TYPES\"][\"CATALOG\"]}" => array(
			0 => "all",
		),
		"SHOW_WHERE" => "N",
		"SHOW_WHEN" => "N",
		"PAGE_RESULT_COUNT" => "12",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Показано",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"USE_LANGUAGE_GUESS" => "N",
		"USE_SUGGEST" => "N",
		"SHOW_ITEM_TAGS" => "N",
		"SHOW_ITEM_DATE_CHANGE" => "N",
		"SHOW_ORDER_BY" => "N",
		"SHOW_TAGS_CLOUD" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"arrFILTER_iblock_mn_catalog" => array(
			0 => "all",
		),		
	),
	false
);?>


<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>