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
	
		
}

//pre($arBlock);

		$arProps = array("PHOTOS");
		$arPropsIds = array();
		
		$rProp = CIBlockProperty::GetList(
			Array("SORT" => "ASC"), 
			Array(
				"ACTIVE" => "Y", 
				"IBLOCK_ID" => $arBlock["ID"],
				//"FILTRABLE" => "Y",			
			)
		);
		while ($arProp = $rProp->GetNext())
		{			
			//pre($arProp);
			if($arProp["CODE"]!='CML2_LINK')
			{
				if($arProp["FILTRABLE"] == 'Y') $arProps[] = $arProp["CODE"];				
				$arPropsIds[] = $arProp["ID"];				
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
		if($K_PAGEN) $info_by_page = false;
	
	
	// Чтобы фильтр нормально работал.
	
	
	//unset($_SESSION["arrFilter2"]);

	

	
		// Кол-во страниц	
		if(!$_COOKIE["PAGE_KOL"]) $PAGE_KOL = 12;
		else $PAGE_KOL = $_COOKIE["PAGE_KOL"]; 

		
		if(!$_COOKIE["K_SORT"]) $_COOKIE["K_SORT"] = 'HIT';
		if(!$_COOKIE["K_ORDER"]) $_COOKIE["K_ORDER"] = 'DESC';
		
		
switch($_COOKIE["K_SORT"])
{
	case "HIT":
		$SORT_NAME = 'SORT';
	break;
	case "NEW":		
		$SORT_NAME = 'PROPERTY_S_NEW';
	break;
	case "PRICE":
		$SORT_NAME = 'CATALOG_PRICE_'.CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]];
	break;
}
if($_COOKIE["K_ORDER"]) $SORT_ORDER = $_COOKIE["K_ORDER"];
	
	
	//pre($PAGE_KOL);
	/*pre($_COOKIE["K_SORT"]);
	pre($_COOKIE["K_ORDER"]);
	pre($SORT_NAME);
	pre($SORT_ORDER);*/
	
	if(!$_SESSION["CATALOG_VIEW"]) $_SESSION["CATALOG_VIEW"] = 'thumb';	 		
		
	switch($_SESSION["CATALOG_VIEW"])	 {
		case "thumb": $tpl = 'tovs.list'; break;
		case "list": $tpl = 'tovs.list.line'; break;	
	}
	global $arrFilter2;
	
	//$arrFilter2["!PROPERTY_MSK_VALUE"] = array("Нет в наличии", "Снят с производства");
	
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





$tag_page = false;
if (CModule::IncludeModule("primelab.urltosef") && CPrimelabUrlToSEF::isHasSEF() ) {	
	$tag_page = true;
}



	$arBlock_dop = CASDiblockTools::GetIBUF($arBlock["ID"]);
	$arName = CStatic::getElement($arBlock_dop["UF_NAME_ID"], 20);



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
		"BLOCK_CODE" => $request->offsetGet("IBLOCK_CODE"),		
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

					<?if(!$tag_page && $info_by_page):?>
					
					<?
					$arFilterArt =  array("PROPERTY_BLOCK_ID" => $arBlock["ID"]);
					if($arSection) $arFilterArt["PROPERTY_SECTION_ID"] = $arSection["ID"];	
					?>
						<?$APPLICATION->IncludeFile('/local/include_areas/block-sidebar_1.php',
											array(
												"ACTIVE" => array(
													"BANNER" => true
												),
												"ART_FILTER" =>   $arFilterArt,
												"ACC_FILTER" =>   array("PROPERTY_BLOCK_ID" => $arBlock["ID"]),
												"ACC_TITLE" => strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]).' Monsher'
											),
											array("mode"=>"php")
						);?>				

						<?$APPLICATION->IncludeComponent("inter.olsc:tech.docs", "catalog.left", 
							array(
								"BLOCK_TITLE" => strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]),
								"IBLOCK_ID" => $arBlock["ID"],
								"SECTION_ID" => $arSection["ID"],
								"LIMIT" => 3
							), 
						false);?>
						
					<?endif;?>
							
										
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
$page_site = $APPLICATION->GetCurPage();
global $arFilter2;

$arFilter3 = array(
	"LOGIC" => "OR",
	array("PROPERTY_BLOCK_ID" => $arBlock["ID"]),
	array("PROPERTY_BLOCK_URL" => $page_site)
);


$arFilter2 = array($arFilter3);
//$arFilter2["!PROPERTY_POP_VALUE"] = 'Y';



		//pre($_SERVER);
 
		if($_SERVER['REQUEST_URI_REAL']) {		
			$page_site_current = current(explode('?',$_SERVER['REQUEST_URI_REAL']));			
		}
 
 
		$page_real = empty($page_site_current)?($page_site):($page_site_current);
		$page_site_back = '/catalog/'.$arBlock["CODE"].'/';
//pre($arFilter2);
	
//pre($_SERVER);

$APPLICATION->IncludeComponent("bitrix:news.list", "tags.list", Array(		
		"BLOCK_ID" => $arBlock["ID"],	
		"SECTION_ID" => $arSection["ID"],	
		"TAG_PAGE" => $tag_page,	
		"PAGE_SITE_CURRENT" => $page_real,	
		"PAGE_SITE" => $page_site,	
		"PAGE_SITE_BACK" => $page_site_back,	
		"IBLOCK_TYPE" => "primelab_URLTOSEF",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => "16",	// Код информационного блока
		"NEWS_COUNT" => "5",	// Количество новостей на странице
		"SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
		"SORT_BY2" => "NAME",	// Поле для второй сортировки новостей
		"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
		"FILTER_NAME" => "arFilter2",	// Фильтр
		"FIELD_CODE" => ARRAY(			
			"NAME",
			"PREVIEW_PICTURE",
		),	// Поля
		"PROPERTY_CODE" => array(	// Свойства				
			"TAG_TITLE",	
			"TITLE",	
		),
		"CHECK_DATES" => "N",	// Показывать только активные на данный момент элементы
		"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "N",	// Учитывать права доступа
		"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
		"ACTIVE_DATE_FORMAT" => "j F Y",	// Формат показа даты
		"SET_TITLE" => "N",	// Устанавливать заголовок страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
		"PARENT_SECTION" => "",	// ID раздела
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
		"COMPONENT_TEMPLATE" => "news.list",
		"SET_BROWSER_TITLE" => "Y",	// Устанавливать заголовок окна браузера
		"SET_META_KEYWORDS" => "Y",	// Устанавливать ключевые слова страницы
		"SET_META_DESCRIPTION" => "Y",	// Устанавливать описание страницы
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"SHOW_404" => "N",	// Показ специальной страницы
		"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
	),
	false
);?>					
	
<?if(!$tag_page):?>
<?	
global $arFilterD;
$arFilterD = array(
	"PROPERTY_BLOCK_ID" => $arBlock["ID"],
);
?>

<?$APPLICATION->IncludeComponent("bitrix:news.list", "tovs.day", Array(	
		"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
		"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],	
		"FAVORITE_LIST" => $_COOKIE["FAVORITE_LIST"],
		"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
		"IBLOCK_TYPE" => "mn_content",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => "66",	// Код информационного блока
		"NEWS_COUNT" => "1",	// Количество новостей на странице
		"SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
		"SORT_BY2" => "",	// Поле для второй сортировки новостей
		"SORT_ORDER2" => "",	// Направление для второй сортировки новостей
		"FILTER_NAME" => "arFilterD",	// Фильтр
		"FIELD_CODE" => ARRAY(			
			"NAME",
		),	// Поля
		"PROPERTY_CODE" => array(	// Свойства				
			"TOV_ID",	
		),
		"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
		"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
		"ACTIVE_DATE_FORMAT" => "j F Y",	// Формат показа даты
		"SET_TITLE" => "N",	// Устанавливать заголовок страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
		"PARENT_SECTION" => "",	// ID раздела
		"PARENT_SECTION_CODE" => "",	// Код раздела
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
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
		"COMPONENT_TEMPLATE" => "news.list",
		"SET_BROWSER_TITLE" => "Y",	// Устанавливать заголовок окна браузера
		"SET_META_KEYWORDS" => "Y",	// Устанавливать ключевые слова страницы
		"SET_META_DESCRIPTION" => "Y",	// Устанавливать описание страницы
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"SHOW_404" => "N",	// Показ специальной страницы
		"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
	),
	false
);?>						
<?endif;?>	
	
	
	
<?
$arOrder = array("RAND" => "ASC");
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
		
	
	
	$mn_t = '.';
	if($K_PAGEN) $mn_t = ' - Страница №'.$K_PAGEN;

// проверка на несуществующие страницы, если такой нет, выдаем 404
if($GLOBALS['PAGEN_1'] > 1) {	
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



$syno = $arName["PROPERTIES"]["SYNO"]["VALUE"];
$name_one = $arName["PROPERTIES"]["ONE"]["VALUE"];



if($arSection) {
	$syno = $zag;
}


// если аксессуары
if($arBlock_dop["UF_NAME_ID"] == 1053) {
	
	$APPLICATION->SetTitle('Аксессуары для холодильников');
	$APPLICATION->SetPageProperty("title", "Аксессуары для холодильников Monsher - купить в интернет-магазине Москвы monsher-store.ru".$mn_t);
	$APPLICATION->SetPageProperty("description", "Купить аксессуары для ухода за холодильником Monsher - выгодные цены в нашем интернет-магазине monsher-store.ru".$mn_t);
}
else {
	//pre($zag.' Monsher');
	
	$APPLICATION->SetTitle($zag.' Monsher');
	$APPLICATION->SetPageProperty("title",  $zag." Monsher - купить в интернет-магазине Москвы по официальной цене, читайте отзывы на ".strtolower($syno)." Либхер на monsher-store.ru".$mn_t);
	$APPLICATION->SetPageProperty("description",  "Купить ".strtolower($zag)." Либхер - выгодные цены в нашем интернет-магазине Monsher. Бесплатная доставка и подключение в Москве! Читайте отзывы, скачивайте инструкции, руководство и схемы установки на ".strtolower($zag)." Monsher".$mn_t);

}




//pre($arPromo);
//pre($_REQUEST);
?>
<?if($_POST['AJAX']=='Y') $APPLICATION->RestartBuffer();?>
<?
//pre($_POST);
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	$tpl,
	Array(
		"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
		"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
		"MODE" => $request->offsetGet("mode"),
		"AJAX" => $request->offsetGet("AJAX"),
		"PROMO" => reset($arPromo),
		"FAVORITE_LIST" => $_COOKIE["FAVORITE_LIST"],
		"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
		"IBLOCK_TYPE" => "mn_catalog",
		"IBLOCK_ID" => $arBlock["ID"],
		"SECTION_ID" => "",
		"SECTION_CODE" => $request->offsetGet("SECTION_CODE"),
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




<?if($info_by_page):?>	





					
<?
	$arFilterPop = array(
		"PROPERTY_BLOCK_ID" => $arBlock["ID"]
	);
	
	
if( !empty($arSection) ){
	
	$arFilterPop = array(		
		"PROPERTY_SECTION_ID" => $arSection["ID"]
	);
}


//pre($arFilterProp);
if($_GET["mode"]) {	
//pre($arFilterProp);
}

?>	
<?$APPLICATION->IncludeComponent("bitrix:news.list", "catalog.popular.section", array(		
        "IBLOCK_TYPE" => "mn_content",
        "IBLOCK_ID" => "93",
        "NEWS_COUNT" => "3",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "NAME",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arFilterPop",
        "FIELD_CODE" => array(
            "ID",
            "NAME",
            "PREVIEW_TEXT",
            "PREVIEW_PICTURE",
        ),
        "PROPERTY_CODE" => array(
            "LINK"
        ),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
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
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
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






						
		
	<?if (!$tag_page):?>				
<?


global $arFilterT;
$arFilterT = array(
	"PROPERTY_TOV_ID.IBLOCK_ID" => $arBlock["ID"]
);
if($arSection["ID"]) $arFilterT["ID"] = CStatic::GetReviewsIDs($arBlock["ID"], $arSection["ID"]);
//pre($arSection["ID"]);


$APPLICATION->IncludeComponent("bitrix:news.list", "reviews.list", Array(
		"BLOCK" => $arBlock["ID"],	
		"SECTION" => $arSection["ID"],	
		"IBLOCK_TYPE" => "mn_content",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => CStatic::$ReviewsIdBlock,	// Код информационного блока
		"NEWS_COUNT" => 3,	// Количество новостей на странице
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
		"CACHE_TYPE" => "A",	// Тип кеширования
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



					
<?
	$arFilterProp = array(
		"PROPERTY_PROPS_ID" => 99999999999,
		"!DETAIL_PICTURE" => false
	);
if( !empty($arPropsIds) ){
	$arFilterProp = array(
		"PROPERTY_PROPS_ID" => $arPropsIds,
		"!DETAIL_PICTURE" => false
	);
}


//pre($arFilterProp);
if($_GET["mode"]) {	
//pre($arFilterProp);
}

?>	
<?$APPLICATION->IncludeComponent("bitrix:news.list", "glossary.list.catalog", array(
		"BLOCK_TITLE" => strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]),
        "IBLOCK_TYPE" => "mn_content",
        "IBLOCK_ID" => "89",
        "NEWS_COUNT" => "36",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "NAME",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arFilterProp",
        "FIELD_CODE" => array(
            "ID",
            "NAME",
            "PREVIEW_TEXT",
            "PREVIEW_PICTURE",
            "DETAIL_PICTURE",
        ),
        "PROPERTY_CODE" => array(
            "PROPS_ID"
        ),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
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
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
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

<?$arInfo = $APPLICATION->IncludeComponent("inter.olsc:tech.docs", "", 
							array(								
								"IBLOCK_ID" => $arBlock["ID"],
								"SECTION_ID" => $arSection["ID"],
								"LIMIT" => 18,
								"PROP_CODE" => "VIDEOS",
								"GET_INFO" => true
							), 
						false);?>


	<?if($arInfo):?>

				
<?
	

//pre($arFilterProp);
if($_GET["mode"]) {	
//pre($arFilterProp);
}

$page_site = $_SERVER["REDIRECT_URL"]; 
 
 
		$page_site = $APPLICATION->GetCurPage(); 
		//pre($_SERVER);
 
		if($_SERVER['REQUEST_URI_REAL']) {		
			$page_site_current = current(explode('?',$_SERVER['REQUEST_URI_REAL']));			
		}
 
 
		$page_r = empty($page_site_current)?($page_site):($page_site_current);

		$page_q = array('all', $page_r);
		if($page_tag) $page_q = $page_r;
 
        global $arFilterProp;		
		$arFilterProp = array(
			"!PROPERTY_URL_NOT" => $page_r,
			"PROPERTY_URL" => $page_q
            
        );
		/*
		$arFilterProp = array(
			"ID" => $arInfo
		);
		*/

?>	
<?$APPLICATION->IncludeComponent("bitrix:news.list", "video.list.catalog", array(		
        "IBLOCK_TYPE" => "mn_content",
        "IBLOCK_ID" => CStatic::$videoIdBlock,
        "NEWS_COUNT" => "36",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "NAME",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arFilterProp",
        "FIELD_CODE" => array(
            "ID",
            "NAME",
            "PREVIEW_TEXT",
            "PREVIEW_PICTURE",
        ),
        "PROPERTY_CODE" => array(
            "CODE"
        ),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
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
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
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

	<?endif;?>
	
	
	
	
														
								
<?		
		global $arrFilterP;
		$arrFilterP = array();
		//$arrFilterP["ID"] = false;
		//if($arParams["CATALOG_VIEWS_LIST"])$arrFilterP["ID"] = $arParams["CATALOG_VIEWS_LIST"];
		 
	//$arrFilterP["!ID"] = $arResult["ID"];

		//PRE($arrFilterP);
		
	$block_ids = $arBlock["ID"];
	// если Хьюмдоры
	/*
	if($block_ids == 34) {
		$block_ids = array(35,36,37,39);
	}*/
		
		
	$APPLICATION->IncludeComponent(
		"inter.olsc:catalog.section",
		"tovs.list.popular",
		Array(			
			"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
			"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
			"BLOCK_TITLE" => 'Популярные товары',
			"EC_TYPE" => 'PopularList',
			"CATALOG_COMPARE_LIST" => $arParams["CATALOG_COMPARE_LIST"],
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BASKET_URL" => "/order/",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "propertysort_".$GLOBALS["K_EXIST_CODE"],
			"ELEMENT_SORT_ORDER" => "ASC",
			"ELEMENT_SORT_FIELD2" => "SORT",
			"ELEMENT_SORT_ORDER2" => "DESC",
			"FILTER_NAME" => "arrFilterP",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => $block_ids,
			"IBLOCK_TYPE" => "mn_catalog",
			"INCLUDE_SUBSECTIONS" => "Y",
			"LINE_ELEMENT_COUNT" => "3",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_COMPARE" => "Сравнить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_CART_PROPERTIES" => array(),
			"OFFERS_FIELD_CODE" => array(),
			"OFFERS_LIMIT" => "10",
			"OFFERS_PROPERTY_CODE" => array(),
			"OFFERS_SORT_FIELD" => "",
			"OFFERS_SORT_FIELD2" => "",
			"OFFERS_SORT_ORDER" => "",
			"OFFERS_SORT_ORDER2" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => 8,
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_DISPLAY_MODE" => "Y",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PROPERTY_CODE" => array("PHOTOS"),
			"SECTION_CODE" => $request->offsetGet("SECTION_CODE"),
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => "",
			"SET_BROWSER_TITLE" => "Y",
			"SET_META_DESCRIPTION" => "Y",
			"SET_META_KEYWORDS" => "Y",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_ALL_WO_SECTION" => "Y",
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"TEMPLATE_THEME" => "blue",
			"USE_PRICE_COUNT" => "N",
			"USE_PRODUCT_QUANTITY" => "N"
		),
		false
	);?>					
	
	
	
	


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
        "IBLOCK_ID" => " 87",
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
        "CACHE_TYPE" => "A",
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
        "DISPLAY_BOTTOM_PAGER" => "N",
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
														
								
<?			
		global $arrFilterD;
		$arrFilterD["ID"] = $_SESSION["CATALOG_VIEWS_LIST"];
		//$arrFilterD["!ID"] = $arResult["ID"];

		//PRE($arrFilterD);
		
	$APPLICATION->IncludeComponent(
		"inter.olsc:catalog.section",
		"tovs.list.viewed",
		Array(		
			"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
			"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
			"BLOCK_CLASS" => 'js-recently-watched',
			"EC_TYPE" => 'ViewsList',
			//"CATALOG_COMPARE_LIST" => $arParams["CATALOG_COMPARE_LIST"],
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BASKET_URL" => "/order/",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "SORT",
			"ELEMENT_SORT_ORDER" => "ASC",
			"ELEMENT_SORT_FIELD2" => "",
			"ELEMENT_SORT_ORDER2" => "",
			"FILTER_NAME" => "arrFilterD",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => CStatic::$catalogIdBlock,
			"IBLOCK_TYPE" => "mn_catalog",
			"INCLUDE_SUBSECTIONS" => "Y",
			"LINE_ELEMENT_COUNT" => "3",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_COMPARE" => "Сравнить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_CART_PROPERTIES" => array(),
			"OFFERS_FIELD_CODE" => array(),
			"OFFERS_LIMIT" => "1",
			"OFFERS_PROPERTY_CODE" => array(),
			"OFFERS_SORT_FIELD" => "",
			"OFFERS_SORT_FIELD2" => "",
			"OFFERS_SORT_ORDER" => "",
			"OFFERS_SORT_ORDER2" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => 12,
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_DISPLAY_MODE" => "Y",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PROPERTY_CODE" => array("PHOTOS"),
			"SECTION_CODE" => "",
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => "",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_ALL_WO_SECTION" => "Y",
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"TEMPLATE_THEME" => "blue",
			"USE_PRICE_COUNT" => "N",
			"USE_PRODUCT_QUANTITY" => "N"
		),
		false
	);?>					





	
				




					
					<div class="sidebar">					
						<?$APPLICATION->IncludeFile('/local/include_areas/block-sidebar_1.php',
											array(
												"ACTIVE" => array(
													"BANNER" => true
												),
												"ACC_FILTER" =>   array("PROPERTY_BLOCK_ID" => $arBlock["ID"]),
												"ACC_TITLE" => strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]).' Monsher'
											),
											array("mode"=>"php")
						);?>				

						<?$APPLICATION->IncludeComponent("inter.olsc:tech.docs", "catalog.left", 
							array(
								"BLOCK_TITLE" => strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]),
								"IBLOCK_ID" => $arBlock["ID"],
								"SECTION_ID" => $arSection["ID"],
								"LIMIT" => 3
							), 
						false);?>
							
										
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
				
			</div>	
		</div>	
				
				
				
				
				
				



	


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>