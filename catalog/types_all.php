<?
$bIndexPage = ($_SERVER["SCRIPT_NAME"] == "/index.php" ? true : false);
define("PATH_TO_404", "/404.php");
$menu_tip = 'catalog';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новинки техники Monsher 2018 года");
$APPLICATION->SetPageProperty("description", "Новинки техники Monsher 2018 года - выгодные цены в нашем официальном интернет-магазине Либхер. Бесплатная доставка и подключение в Москве!");
$APPLICATION->SetPageProperty("title", "Новинки техники Monsher 2018 - купить в Москве бытовую технику Либхер в официальном интернет-магазине l-rus по лучшей цене с отзывами.");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
CModule::IncludeModule("iblock");
CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');


$desc = '';
$pic = '/tpl/images/catalog-seo__pic.jpg';
/*
if($arBlock = getArIblock('mn_catalog', $request->get("IBLOCK_CODE"))) {
	$desc = $arBlock["DESCRIPTION"];
	if($arBlock["PICTURE"])	$pic = CFile::GetPath($arBlock["PICTURE"]);
}
else {
	define("ERROR_404", true);
}
$APPLICATION->AddChainItem($arBlock["NAME"], '/catalog/'.$arBlock["CODE"].'/');
$zag = $arBlock["NAME"].' Monsher';


if($request->offsetGet("SECTION_CODE")) {
	$arSection = getArSection($arBlock["ID"], $request->get("SECTION_CODE"));
	
	if(strstr($arSection["NAME"], $arBlock["NAME"])) {
		$zag = $arSection["NAME"].' Monsher';
	}
	else {
		$zag = $arSection["NAME"].' '.strtolower($arBlock["NAME"]).' Monsher';
	}		
}
*/
//pre($arBlock);

		$arProps = array("PHOTOS");
		
		$rProp = CIBlockProperty::GetList(
			Array("SORT" => "ASC"), 
			Array(
				"ACTIVE" => "Y", 
				"IBLOCK_ID" => $arBlock["ID"],
				"FILTRABLE" => "Y",			
			)
		);
		while ($arProp = $rProp->GetNext())
		{
			//pre($arProp);
			if($arProp["CODE"]!='CML2_LINK')
			{
				$arProps[] = $arProp["CODE"];				
		    }
		}	
		
		/*
		$arResult = $APPLICATION->IncludeComponent("inter.olsc:menu.catalog.special","",
								Array(
									"BLOCK" => $arBlock,
									"SECTION" => $arSection,
									"TYPE_CODE" => $request->offsetGet("TYPE_CODE"),
									"MODE" => "GetData"
								),
								false
							);
		
		$GLOBALS["arrFilter2"] = $arResult["FILTER"];
		
		$zag = $arResult["TITLE"];
		$APPLICATION->SetTitle($zag);	
	
	
	//pre($arResult);
	
	
	$arBlock_dop = CASDiblockTools::GetIBUF($arBlock["ID"]);
	$arName = CStatic::getElement($arBlock_dop["UF_NAME_ID"], 677);

	$month = (int)date("m");
	if($month < 4) $year = (int)date("Y")-1;
	else if($month > 10) $year = date("Y").'-'.((int)date("Y")+1);
	else $year = date("Y");	
	
	
	if($request->offsetGet("TYPE_CODE") == 'novelty') {
		$title = 'Лучшие '.strtolower($arName["NAME"]).' Monsher - купить лучшую модель '.$year.' года с бесплатной доставкой по Москве.';
		$description = 'Выбрать и купить лучшие модели '.strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]).' Либхер на сайте официального дилера Monsher в Москве';
	}
	else {
		$title = 'Распродажа '.strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]).' Monsher в Москве. Бесплатная доставка, выгодные цены!';
		$description = 'Выбрать и купить '.strtolower($arName["NAME"]).' Либхер по распродаже. Интернет-магазин Monsher-store.ru в Москве';
	}
	
	
	
	$APPLICATION->SetPageProperty("title",  $title);
	$APPLICATION->SetPageProperty("description",  $description);
	*/
	
	



	
	// Чтобы фильтр нормально работал.
	
	
	//unset($_SESSION["arrFilter2"]);

	

	
		// Кол-во страниц	
		if(!$_COOKIE["PAGE_KOL"]) $PAGE_KOL = 12;
		else $PAGE_KOL = $_COOKIE["PAGE_KOL"]; 


		// костыль, чтобы при первом открытии этих доп страниц у нас была сортировка по новинкам
		if($request->offsetGet("TYPE_CODE") == 'nov' && !$_SESSION["TYPE_PAGE"]) {
			$_COOKIE["SORT"] = 'NEW';
			setcookie("SORT", 'NEW', time() + 15552000, "/");
			$_SESSION["TYPE_PAGE"] = 1;			
		}
		
	$SORT_NAME = 'SORT';
		$SORT_ORDER = 'DESC';		
		
switch($_COOKIE["K_SORT"])
{
	case "HIT":
		$SORT_NAME = 'SORT';
	break;
	case "NEW":		
		$SORT_NAME = 'DATE_CREATE';
	break;
	case "PRICE":
		$SORT_NAME = 'CATALOG_PRICE_'.CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]];
	break;
}
if($_COOKIE["K_ORDER"]) $SORT_ORDER = $_COOKIE["K_ORDER"];
	
	
	//pre($PAGE_KOL);
	//pre($SORT_NAME);
	//pre($SORT_ORDER);
	
	if(!$_SESSION["CATALOG_VIEW"]) $_SESSION["CATALOG_VIEW"] = 'thumb';	 		
		
	switch($_SESSION["CATALOG_VIEW"])	 {
		case "thumb": $tpl = 'tovs.list'; break;
		case "list": $tpl = 'tovs.list.line'; break;	
	}
	global $arrFilter2;
	$arrFilter2["!PROPERTY_MSK_VALUE"] = array("Нет в наличии", "Снят с производства");
	$arrFilter2["PROPERTY_S_NEW"] = "Y";
	
	$APPLICATION->AddChainItem("Новинки", '/catalog/type-nov/');
	//pre($arrFilter2);
	
	
	?>
	
	
	

		<div class="content-top" <?=$pic_bg?>>
				<div class="content-top__inner">
					<div class="container">

						<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", array(
												"START_FROM" => "0",
												"PATH" => "",
												"SITE_ID" => "s3"
											),
											false
						);?>
						
						<div class="content-top__aside">
							<h1 class="page__title content-top__title content-top__title_top"><?$APPLICATION->ShowTitle(false);?></h1>								
							<div class="page__title content-top__title content-top__title_bottom"><?=$desc?></div>
						</div>

				</div>

			</div>
		</div>
		<div class="content">
			<div class="container">					
				<div class="col-md-3">
					<div class="filter__date">Обновление цен от <?=strtolower(FormatDate("d F Y", time()))?></div>

					
						<div class="sidebar">		
						<?$APPLICATION->IncludeFile('/local/include_areas/block-sidebar_2.php',
													array(
														"ACTIVE" => array(
															"MENU" => true
														)
													),
													array("mode"=>"php")
								);?>
						</div>
				</div>
				<div class="special-offers catalog">	
					
						<?$APPLICATION->IncludeFile('/local/include_areas/sort_change.php')?>
	
<?
$K_PAGEN = false;
		
	
		if($GLOBALS["PAGEN_1"]) $K_PAGEN  = $GLOBALS["PAGEN_1"];
		elseif($GLOBALS["PAGEN_2"]) $K_PAGEN  = $GLOBALS["PAGEN_2"];
		elseif($GLOBALS["PAGEN_3"]) $K_PAGEN  = $GLOBALS["PAGEN_3"];
		elseif($GLOBALS["PAGEN_4"]) $K_PAGEN  = $GLOBALS["PAGEN_4"];
		elseif($GLOBALS["PAGEN_5"]) $K_PAGEN  = $GLOBALS["PAGEN_5"];
		elseif($GLOBALS["PAGEN_6"]) $K_PAGEN  = $GLOBALS["PAGEN_6"];
		elseif($GLOBALS["PAGEN_7"]) $K_PAGEN  = $GLOBALS["PAGEN_7"];
		elseif($GLOBALS["PAGEN_8"]) $K_PAGEN  = $GLOBALS["PAGEN_8"];
		elseif($GLOBALS["PAGEN_9"]) $K_PAGEN  = $GLOBALS["PAGEN_9"];
		elseif($GLOBALS["PAGEN_10"]) $K_PAGEN  = $GLOBALS["PAGEN_10"];
		
	
	
	$mn_t = '';
	if($K_PAGEN) $mn_t = ' - Страница '.$K_PAGEN;

// проверка на несуществующие страницы, если такой нет, выдаем 404
if($K_PAGEN > 1) {	
				$arFiltP = $GLOBALS['arrFilter2'];
				$arFiltP["IBLOCK_ID"] = $arBlock["ID"];
				$arFiltP["ACTIVE"] = "Y";			
				if($request->offsetGet("SECTION_CODE")!='') {
					$arFiltP["SECTION_CODE"] = $request->offsetGet("SECTION_CODE");
				}	
				$arFiltP["INCLUDE_SUBSECTIONS"] = "Y";	
				
				//dump($arFiltP);
				$cnt = CIBlockElement::GetList(
					array(),
					$arFiltP,
					array(),
					false,
					array('ID', 'NAME')
				); 				

				
				
				//dump($cnt);
				$page_max = ceil($cnt/$PAGE_KOL);
				if($page_max < $K_PAGEN) {
					define("ERROR_404", true);					
				}
}



?>
<?if($_POST['AJAX']=='Y') $APPLICATION->RestartBuffer();?>	
<?$APPLICATION->IncludeComponent(
	"inter.olsc:catalog.section",
	$tpl,
	Array(
		"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
		"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
		"AJAX" => $request->offsetGet("AJAX"),
		"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
		"IBLOCK_TYPE" => "mn_catalog",
		"IBLOCK_ID" => CStatic::$catalogIdBlock,
		"SECTION_ID" => "",
		//"SECTION_CODE" => $request->get("SECTION_CODE"),
		"SECTION_USER_FIELDS" => array("", "", ""),
		"ELEMENT_SORT_FIELD" => "propertysort_".$GLOBALS["K_EXIST_CODE"],
		"ELEMENT_SORT_ORDER" => "ASC",
		"ELEMENT_SORT_FIELD2" => $SORT_NAME,
		"ELEMENT_SORT_ORDER2" => $SORT_ORDER,
		"FILTER_NAME" => "arrFilter2",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => $PAGE_KOL,
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => $arProps,
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
		"SET_TITLE" => "Y",
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




	<div class="catalog__description description">
			
<?
		$page = $APPLICATION->GetCurPage();

        global $arrFilter3;
		$arrFilter3 = array(
			"!PROPERTY_URL_NOT" => array($page),
			"PROPERTY_URL" => array('all', $page)
        );

	$_SESSION["SEO_COUNTER"] = '1';

?>


<?$APPLICATION->IncludeComponent("bitrix:news.list",  "seo.list.bottom", array(
        "IBLOCK_TYPE" => "mn_content",
        "IBLOCK_ID" => "55",
        "NEWS_COUNT" => "2",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "NAME",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arrFilter3",
        "FIELD_CODE" => array(
            "ID",
            "NAME",
            "PREVIEW_TEXT",
            "PREVIEW_PICTURE",
        ),
        "PROPERTY_CODE" => array(
            "URL",
            "URL_NOT",
        ),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_TEMPLATE" => "",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_OPTION_ADDITIONAL" => ""
    ),
    false
);?>
				
	</div>				
				



					
				</div>	
				
			</div>	
		</div>	

	


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>