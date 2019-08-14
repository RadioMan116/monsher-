<?
$bIndexPage = ($_SERVER["SCRIPT_NAME"] == "/index.php" ? true : false);
define("PATH_TO_404", "/404.php");
$menu_tip = 'catalog';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог бытовой техники");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
CModule::IncludeModule("iblock");
CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');


$desc = '';
$pic_bg = 'style="background-image: url('.$tpl_path_front.'images/catalog.jpg);"';

if($arBlock = getArIblock('mn_catalog', $request->get("IBLOCK_CODE"))) {
	
	//pre($arBlock);
	
	$desc = $arBlock["DESCRIPTION"];
	if($arBlock["PICTURE"])	{		
		$pic = CFile::GetPath($arBlock["PICTURE"]);
		$pic_bg = 'style="background-image: url('.$pic.');"';
	}
}
else {
	define("ERROR_404", true);
}
$APPLICATION->AddChainItem($arBlock["NAME"], '/catalog/'.$arBlock["CODE"].'/');
$zag = $arBlock["NAME"];

if($request->offsetGet("SECTION_CODE")) {
	$arSection = getArSection($arBlock["ID"], $request->get("SECTION_CODE"));
	$desc = $arSection["DESCRIPTION"];
	
	if($arSection["PICTURE"])	{		
		$pic = CFile::GetPath($arSection["PICTURE"]);
		$pic_bg = 'style="background-image: url('.$pic.');"';
	}
	
	$zag = $arSection["NAME"];
	/*
	if(strstr($arSection["NAME"], $arBlock["NAME"])) {
		$zag = $arSection["NAME"];
	}
	else {
		$zag = $arSection["NAME"].' '.strtolower($arBlock["NAME"]);
	}
	*/
	
	$APPLICATION->AddChainItem($arSection["NAME"], $arSection["SECTION_PAGE_URL"]);
		
}

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
		
	$info_by_page = true;
	$dd_p = '.';	
	if($K_PAGEN) {
		$info_by_page = false;
		if($K_PAGEN > 1) $dd_p = ' - Страница '.$K_PAGEN;
	}
	
	
	// Чтобы фильтр нормально работал.
	
	
	//unset($_SESSION["arrFilter2"]);

	

	
		// Кол-во страниц	
		if(!$_COOKIE["PAGE_KOL"]) $PAGE_KOL = 12;
		else $PAGE_KOL = $_COOKIE["PAGE_KOL"]; 

		$SORT_NAME = 'CATALOG_PRICE_'.CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]];
		$SORT_ORDER = 'ASC';		
		/*
switch($_COOKIE["K_SORT"])
{
	case "HIT":
		$SORT_NAME = 'PROPERTY_S_HIT';
	break;
	case "NEW":		
		$SORT_NAME = 'PROPERTY_S_NEW';
	break;
	case "PRICE":
		$SORT_NAME = 'CATALOG_PRICE_1';
	break;
}
*/
//if($_COOKIE["K_ORDER"]) $SORT_ORDER = $_COOKIE["K_ORDER"];
	
	
	//pre($PAGE_KOL);
	//pre($SORT_NAME);
	//pre($SORT_ORDER);
	
	if(!$_SESSION["CATALOG_VIEW"]) $_SESSION["CATALOG_VIEW"] = 'thumb';	 		
		
	switch($_SESSION["CATALOG_VIEW"])	 {
		case "thumb": $tpl = 'tovs.list'; break;
		case "list": $tpl = 'tovs.list.line'; break;	
	}
	global $arrFilter2;
	
	
	
	
	
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
	
	
							$SORT_NAME = $arResult["SORT"]["NAME"];
							$SORT_ORDER = $arResult["SORT"]["ORDER"];
							
							//PRE($GLOBALS["arrFilter2"]);
							
	
							//$arrFilter2["!PROPERTY_MSK_VALUE"] = array("Нет в наличии", "Снят с производства");
							$GLOBALS["arrFilter2"]["PROPERTY_MSK_VALUE"] = array("В наличии");	
							$info_by_page = false;
	
	//pre($arResult);
							$APPLICATION->AddChainItem($arResult["TYPE_TITLE"]);
							
// доп склонения для названий							
$arBlock_dop = CASDiblockTools::GetIBUF($arBlock["ID"]);
$arName = CStatic::getElement($arBlock_dop["UF_NAME_ID"], 20);



									$month = (int)date("m");			
									$year = date("Y");
									if($month < 4) {$year = (date("Y")-1).'-'.date("Y");}
									else if($month > 10) {$year = date("Y").'-'.(date("Y")+1);}				
													
							switch($request->offsetGet("TYPE_CODE")) {
								case "hit":
								
									$zag_n = "Лучшие ".strtolower($zag)." Monsher";
									
									$APPLICATION->SetPageProperty("title", "Лучшие ".strtolower($zag)." Monsher ".$year." года – купить с доставкой по Москве".$dd_p);
									$APPLICATION->SetPageProperty("description", "Выбирайте лучшие ".strtolower($zag)." Либхер в специализированном интернет-магазине Monsher в Москве".$dd_p);								
								
								break;
								case "akcii":
									
									$zag_n = "Акции на ".strtolower($zag)." Monsher";			
									
									$APPLICATION->SetPageProperty("title", "Акции на ".strtolower($zag)." Monsher – купить холодильник по выгодной цене с доставкой по Москве".$dd_p);
									$APPLICATION->SetPageProperty("description", "Выбрать и купить ".strtolower($zag)." Либхер по акции в специализированном интернет-магазине Monsher в Москве".$dd_p);
									
									$mess_nofind = 'В настоящий момент актуальных предложений нет, но вы можете выбрать технику по ценам, рекомендованным производителем';
									$pic_nofind = '/tpl/images/info-discount.png';
									
								break;
								case "nov":
									
									$zag_n = "Новые ".strtolower($zag)." Monsher";									
									
									$APPLICATION->SetPageProperty("title", "Новые ".strtolower($zag)." Monsher ".$year." года – купить с доставкой по Москве".$dd_p);
									$APPLICATION->SetPageProperty("description", "Выбрать и купить новые ".strtolower($zag)." Либхер в специализированном интернет-магазине Monsher в Москве".$dd_p);
									
									
									$mess_nofind = 'В настоящий момент новых моделей на сайте нет, но вы можете выбрать лучшие и популярные модели';
									$pic_nofind = '/tpl/images/info-new.png';
								break;
							}
							
							$APPLICATION->SetTitle($zag_n);
													
							
							
	
	
	
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

				


//pre($cnt);
//pre($GLOBALS["arrFilter2"]);
//pre($SORT_NAME);
//pre($SORT_ORDER);





$tag_page = false;
if (CModule::IncludeModule("primelab.urltosef") && CPrimelabUrlToSEF::isHasSEF() ) {	
	$tag_page = true;
}


?>


		<div class="content-top" <?if($tag_page):?><?$APPLICATION->ShowViewContent('seo_img');?><?else:?><?=$pic_bg?><?endif;?>>
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
							<h1 class="page__title content-top__title content-top__title_top">
								<?$APPLICATION->ShowTitle(false);?>
								<?if($K_PAGEN):?>
								<span>страница <?=$K_PAGEN?></span>
								<?endif;?>
							</h1>
								
							<div class="page__title content-top__title content-top__title_bottom">
							
								<?if($info_by_page):?>
									<?if ($tag_page):?>
										<?$APPLICATION->ShowViewContent('seo_text_target_1');?>
									<?else:?>
										<?=$desc?>
									<?endif;?>	
								<?endif;?>	
								
							</div>
						</div>						
						
				</div>

			</div>
		</div>
		<div class="content">
			<div class="container">					
				<div class="col-md-3">
					<div class="filter__date">Обновление цен от <?=strtolower(FormatDate("j F Y", time()))?></div>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.smart.filter", 
	".default", 
	array(
		"MODE" => $_GET["mode"],
		"BLOCK_CODE" => $arBlock["CODE"],
		"TYPE_CODE" => $request->offsetGet("TYPE_CODE"),
		"INCLUDE_SUBSECTIONS" => "Y",
		"COMPONENT_TEMPLATE" => "left.filter",
		"IBLOCK_TYPE" => "mn_catalog",
		"IBLOCK_ID" => $arBlock["ID"],
		"SECTION_ID" => "",
		"SECTION_CODE" => $_GET["SECTION_CODE"],
		"FILTER_NAME" => "arrFilter2",
		"HIDE_NOT_AVAILABLE" => "N",
		"TEMPLATE_THEME" => "blue",
		"FILTER_VIEW_MODE" => "vertical",
		"POPUP_POSITION" => "right",
		"DISPLAY_ELEMENT_COUNT" => "Y",
		"SEF_MODE" => "N",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SAVE_IN_SESSION" => "N",
		"INSTANT_RELOAD" => "Y",
		"PAGER_PARAMS_NAME" => "arrPager",
		"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
		"CONVERT_CURRENCY" => "N",
		"XML_EXPORT" => "N",
		"SECTION_TITLE" => "-",
		"SECTION_DESCRIPTION" => "-",
		"SHOW_ALL_WO_SECTION" => "Y"
	),
	false
);?>
					
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
					
						<?//$APPLICATION->IncludeFile('/local/include_areas/sort_change.php')?>
	
<?

		
/*
$arOrder = array();
$arFilter = array(
	"IBLOCK_ID" => 58,
	"ACTIVE_DATE" => "Y",
	"ACTIVE" => "Y",
	"PROPERTY_TYPE_VALUE" => "Список товаров"
);

$arPromo = CStatic::GetListElement(false, $arFilter, $arOrder, true);

if($arPromo) {
	$PAGE_KOL = 11;
}
*/
	
	

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




//pre($arPromo);

?>
<?if($_POST['AJAX']=='Y') $APPLICATION->RestartBuffer();?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	$tpl,
	Array(
		"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
		"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
		"AJAX" => $request->offsetGet("AJAX"),
		"PROMO" => reset($arPromo),
		"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
		"IBLOCK_TYPE" => "mn_catalog",
		"IBLOCK_ID" => $arBlock["ID"],
		"SECTION_ID" => "",
		"SECTION_CODE" => $request->get("SECTION_CODE"),
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
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"ADD_SECTIONS_CHAIN" => "N",
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





<?if($info_by_page):?>							
		
	<?if (!$tag_page):?>
	
<?


global $arFilterT;
$arFilterT = array(
	"PROPERTY_TOV_ID.IBLOCK_ID" => $arBlock["ID"]
);
if($arSection["ID"]) $arFilterT["ID"] = CStatic::GetReviewsIDs($arBlock["ID"], $arSection["ID"]);
//pre($arFilterT);


$APPLICATION->IncludeComponent("bitrix:news.list", "reviews.list", Array(
		"BLOCK" => $arBlock["ID"],	
		"SECTION" => $arSection["ID"],	
		"IBLOCK_TYPE" => "mn_content",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => CStatic::$ReviewsIdBlock,	// Код информационного блока
		"NEWS_COUNT" => CStatic::$ReviewsLimit,	// Количество новостей на странице
		"SORT_BY1" => "DATE_ACTIVE_FROM",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
		"SORT_BY2" => "ID",	// Поле для второй сортировки новостей
		"SORT_ORDER2" => "DESC",	// Направление для второй сортировки новостей
		"FILTER_NAME" => "arFilterT",	// Фильтр
		"FIELD_CODE" => array(
            "ID",
            "NAME",                      
            "PREVIEW_TEXT",                      
            "DETAIL_TEXT",                      
            "ACTIVE_FROM",                      
        ),
        "PROPERTY_CODE" => array(
            "NAME",
            "PLUS",
            "MINUS",	            
            "TXT",	            
            "RATING",	            
        ),
		"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
		"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "N",	// Тип кеширования
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
		"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
		"SET_TITLE" => "N",	// Устанавливать заголовок страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
		"PARENT_SECTION" => CStatic::$ReviewsIdSec,	// ID раздела
		"PARENT_SECTION_CODE" => "",	// Код раздела
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"PAGER_TITLE" => "",	// Название категорий
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "Y",	// Показывать ссылку "Все"
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
	),
	false
);?>	
<?endif?>




	<div class="catalog__description description">
	
			<?if ($tag_page):?>
				<div class="description__text"><?$APPLICATION->ShowViewContent('seo_text_target_2');?></div>
			<?else:?>
			
			
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
        "IBLOCK_ID" => " 55",
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
				
			<?endif?>
	</div>				
				
<?endif?>


					
				</div>	
				
			</div>	
		</div>	
				
				
				
				
				
				



	


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>