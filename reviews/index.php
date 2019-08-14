<?
$menu_tip = 'reviews';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Читайте отзывы о холодильном оборудовании Либхер. Выбирайте надежную технику!");
$APPLICATION->SetPageProperty("title", "Liebherr | Отзывы о холодильной технике Либхер");
$APPLICATION->SetTitle("Liebherr - отзывы о холодильной технике");
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
?>					
	
<?
global $arrFilter;

$block_id = false;
$product_id = false;

$dd_p = '';
if($request->offsetGet("PAGEN_1") > 1) $dd_p = ' - Страница '.$request->offsetGet("PAGEN_1");

if($request->offsetGet("IBLOCK_CODE")) {
	$arBlock = getArIblock('mn_catalog', $request->offsetGet("IBLOCK_CODE"));
	$arrFilter["PROPERTY_TOV_ID.IBLOCK_ID"] = $block_id = $arBlock["ID"];
	
	$zag = $arBlock["NAME"];		
	
	$APPLICATION->AddChainItem($arBlock["NAME"], '/reviews/category-'.$arBlock["CODE"].'/');		
	
	if($request->offsetGet("SECTION_CODE")) {
		$arSection = getArSection($arBlock["ID"], $request->offsetGet("SECTION_CODE"));
		$arrFilter["ID"] = CStatic::GetReviewsIDs($arBlock["ID"], $arSection["ID"]);
		
		//pre($arSection);
		/*
		if($arBlock["ID"] == 211) $zag = $arBlock["NAME"].' '.strtolower($arSection["NAME"]);
		else $zag = $arSection["NAME"].' '.strtolower($arBlock["NAME"]);
		*/
		
		$zag = $arSection["NAME"];
		


		$APPLICATION->AddChainItem($arSection["NAME"]);
	}
	
	
	$APPLICATION->SetTitle($zag.' Liebherr - отзывы'.$dd_p);
	
	$APPLICATION->SetPageProperty("title", $zag." Либхер - отзывы покупателей нашего интернет-магазина, выбрать лучший.".$dd_p);
	$APPLICATION->SetPageProperty("description", "Отзывы на ".mb_lcfirst($zag)." Либхер. Выбирайте надежную технику!".$dd_p);
	
	
	
}
else if($request->offsetGet("ELEMENT_CODE")) {
	$arTov = getArElement(CStatic::$catalogIdBlock, $request->offsetGet("ELEMENT_CODE"));
	$arTov = CStatic::getElement($arTov["ID"], $arTov["IBLOCK_ID"]);
	$arBlock = getArIblock('dd_catalog', false, $arTov["IBLOCK_ID"]);
	$arrFilter["PROPERTY_TOV_ID"] = $product_id = $arTov["ID"];
	
	
	$APPLICATION->SetTitle($arTov["NAME"].' - отзывы'.$dd_p);	
	
	$name_rus = str_replace('Liebherr','Либхер',$arTov["NAME"]);
	
	
	$APPLICATION->SetPageProperty("title", $arTov["NAME"].' - отзывы покупателей нашего интернет-магазина.'.$dd_p);
	$APPLICATION->SetPageProperty("description", "Отзывы на ".mb_lcfirst($name_rus).". Выбирайте надежную технику!".$dd_p);
	
	
	$APPLICATION->AddChainItem($arTov["NAME"]);	
}









if($request->offsetGet("TYPE")) {
	switch($request->offsetGet("TYPE")) {
		case "PLUS":
			$arrFilter[">PROPERTY_RATING"] = 3;
		break;
		case "MINUS":
			$arrFilter["<=PROPERTY_RATING"] = 3;
		break;
	}	
}
if($request->offsetGet("STARS")) {
	$arrFilter["PROPERTY_RATING"] = $request->offsetGet("STARS");
}
$page = $APPLICATION->GetCurPage();

?>


<?$APPLICATION->IncludeComponent("bitrix:news.list", "reviews.list.all", Array(	
		"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
        "K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],	
		"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
		"PRODUCT" => $product_id,	
		"BLOCK" => $block_id,	
		"SECTION" => $arSection["ID"],	
		"PAGE" => $page,	
		"TYPE" => $request->offsetGet("TYPE"),	
		"STARS" => $request->offsetGet("STARS"),	
		"IBLOCK_TYPE" => "mn_content",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => CStatic::$ReviewsIdBlock,	// Код информационного блока
		"NEWS_COUNT" => 10,	// Количество новостей на странице
		"SORT_BY1" => "DATE_ACTIVE_FROM",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
		"SORT_BY2" => "ID",	// Поле для второй сортировки новостей
		"SORT_ORDER2" => "DESC",	// Направление для второй сортировки новостей
		"FILTER_NAME" => "arrFilter",	// Фильтр
		"FIELD_CODE" => array(
            "ID",
            "NAME",                      
            "PREVIEW_TEXT",                      
            "DETAIL_TEXT",                      
            "DATE_ACTIVE_FROM",                      
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
		"PAGER_TITLE" => "Отзывы страница",	// Название категорий
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


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>