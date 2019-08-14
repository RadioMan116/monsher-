<?
//if($_GET["IBLOCK_CODE"]) $menu_tip = 'catalog';
$bIndexPage = ($_SERVER["SCRIPT_NAME"] == "/index.php" ? true : false);
define("PATH_TO_404", "/404.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$iblock_code = $request->offsetGet("IBLOCK_CODE");
if(!$iblock_code) $iblock_code = $_GET["IBLOCK_CODE"];
//pre($request);
CModule::IncludeModule("iblock");
CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');


//$arBlock = getArIblock('asko_catalog', $request->get("IBLOCK_CODE"));
$arBlock = getArIblock('mn_catalog', $iblock_code);
if(!$arBlock)
{
	@define("ERROR_404", "Y");
	@define("CONFIRM_ERROR_404", "Y");
}

$APPLICATION->AddChainItem($arBlock["NAME"], '/catalog/'.$arBlock["CODE"].'/');
$zag = $arBlock["NAME"];

$arBlock_dop = CASDiblockTools::GetIBUF($arBlock["ID"]);
$arName = CStatic::getElement($arBlock_dop["UF_NAME_ID"], 20);

$zag_k = strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]);

if($request->offsetGet("SECTION_CODE")) {
	$arSection = getArSection($arBlock["ID"], $request->get("SECTION_CODE"));
	$desc = $arSection["DESCRIPTION"];
	
	if($arSection["PICTURE"])	{		
		$pic = CFile::GetPath($arSection["PICTURE"]);
		$pic_bg = 'style="background-image: url('.$pic.');"';
	}
	
	$zag = $arSection["NAME"];
	
	//pre($arSection["UF_NAME_ID"]);
	
	$arName = CStatic::getElement($arSection["UF_NAME_ID"], 21);

	$zag_k = strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]);
	
	//PRE( $arSection);
	
	$APPLICATION->AddChainItem($arSection["NAME"], $arSection["SECTION_PAGE_URL"]);	
}






$APPLICATION->AddChainItem('Подборки');



//pre($arBlock);

	$APPLICATION->SetTitle('Выборки '.$zag_k.' Monsher'.$dd_p);	
	$APPLICATION->SetPageProperty("title", "Подбор ".$zag_k." Monsher по параметрам.");
	$APPLICATION->SetPageProperty("description", "Выборки ".mb_lcfirst($zag_k)." Либхер по ключевым параметрам. Интернет-магазин l-rus.ru");
	
		




	
	




?>







<?

$page_site = $APPLICATION->GetCurPage();

$page_site_n = str_replace('/tags/','/catalog/',$page_site);

global $arFilter2;
/*
$arFilter2 = array(
	"PROPERTY_BLOCK_ID" => $arBlock["ID"]
);*/


$arFilter3 = array(
	"LOGIC" => "OR",
	array("PROPERTY_BLOCK_ID" => $arBlock["ID"]),
	array("PROPERTY_BLOCK_URL" => $page_site_n)
);


$arFilter2 = array($arFilter3);


$APPLICATION->IncludeComponent("bitrix:news.list", "tags.list", Array(
		"PAGE_SITE_BACK" => $page_site_n ,	
		"PAGE_SITE" => $page_site_n ,	
		"CATEGORY_TITLE" => $arBlock["NAME"],	
		"IBLOCK_TYPE" => "primelab_URLTOSEF",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => "16",	// Код информационного блока
		"NEWS_COUNT" => "99",	// Количество новостей на странице
		"SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
		"SORT_BY2" => "",	// Поле для второй сортировки новостей
		"SORT_ORDER2" => "",	// Направление для второй сортировки новостей
		"FILTER_NAME" => "arFilter2",	// Фильтр
		"FIELD_CODE" => ARRAY(			
			"NAME",
			"PREVIEW_PICTURE",
		),	// Поля
		"PROPERTY_CODE" => array(	// Свойства				
			"TAG_TITLE",	
			"TITLE",	
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
		"PARENT_SECTION" => 33,	// ID раздела
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



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>