<?
$menu_tip = 'reviews_store';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отзывы о магазине L-RUS");
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
?>					
	
<?
global $arrFilter;


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
<div class="d-flex flex-column flex-md-row">


<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", array(
												"START_FROM" => "0",
												"PATH" => "",
												"SITE_ID" => "s2"
											),
											false
						);?>
<h1 class="title"><?$APPLICATION->ShowTitle(false);?></h1>


<?if($_POST['AJAX']=='Y') $APPLICATION->RestartBuffer();?>	
<?$APPLICATION->IncludeComponent("bitrix:news.list", "reviews.list.store", Array(
		"AJAX" => $request->offsetGet("AJAX"),					
		"TYPE" => $request->offsetGet("TYPE"),	
		"STARS" => $request->offsetGet("STARS"),	
		"IBLOCK_TYPE" => "backform",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => CStatic::$ReviewsStoreIdBlock,	// Код информационного блока
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
		"PARENT_SECTION" => CStatic::$ReviewsStoreIdSec,	// ID раздела
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
<?if($_POST['AJAX']=='Y') die();?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>