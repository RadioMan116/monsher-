<?
$menu_tip = 'compare';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сравнение");



//pre($_SESSION["CATALOG_COMPARE_LIST"]);
?>



<?
// detect selected iblock or get first
$activeIB = 0;

$SecIds = array();
foreach($_SESSION["CATALOG_COMPARE_LIST"] as $iblock => $mTov){
	if( empty($mTov["ITEMS"]) )
		continue;
	
	foreach($mTov["ITEMS"] as $item) {
		$SecIds[] = $item["IBLOCK_SECTION_ID"];
	}

	if( count($mTov["ITEMS"]) == 1 ){
		//$_SESSION["CATALOG_COMPARE_LIST"][$iblock]["ITEMS"] = array();
		//continue;
	}

	if( $activeIB == 0 )
		$activeIB = $iblock;

	if( $mTov["ACTIVE"] )
		$activeIB = $iblock;
}

//PRE($_SESSION["CATALOG_COMPARE_SECTION_ACTIVE"]);
//PRE($_SESSION["CATALOG_COMPARE_LIST"]);

$SecIds = array_unique($SecIds);
//pre($SecIds);
//pre($_SESSION["CATALOG_COMPARE_LIST"]);

?>

<?if(!$activeIB):?>
	<div class="js-compare_page comparison">	
			<h1 class="title">Сравнение</h1>
			<div class="text-default__title b-center">Список сравниваемых элементов пуст.</div>		
	</div>	
<?else:?>

	<?
	// get properties $arProps
	$arProps = array();
	$rProp = CIBlockProperty::GetList(
		array("SORT" => "ASC"),
		array("ACTIVE" => "Y", "IBLOCK_ID" => $activeIB)
	);
	$counter = false;
	while ($arProp = $rProp->GetNext()) {
		
		//ECHO '<BR/> '.$arProp["CODE"];
		
		if( !$counter && $arProp["DEFAULT_VALUE"] == '+' )
			$counter = true;

		if( $counter ) {
			
			//ECHO '<BR/> FIND';
			$arProps[] = $arProp["CODE"];
		}
	}
	
	
	//PRE($arProps);
	?>

	<?$APPLICATION->IncludeComponent("bitrix:catalog.compare.result", "compare.list", Array(
			"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
			"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
			"CATALOG_COMPARE_SECTION_ACTIVE" => $_SESSION["CATALOG_COMPARE_SECTION_ACTIVE"],
			"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],
			"SECTIONS_ID" => $SecIds,
			"COMPONENT_TEMPLATE" => ".default",
			"NAME" => "CATALOG_COMPARE_LIST",	// Уникальное имя для списка сравнения
			"IBLOCK_TYPE" => "mn_catalog",	// Тип инфоблока
			"IBLOCK_ID" => $activeIB,	// Инфоблок			
			"SECTION_ID_ACTIVE" => $activeSecIB,	// Инфоблок
			"FIELD_CODE" => array(	// Поля
				"PREVIEW_PICTURE",
				"DETAIL_PICTURE",
			),
			"PROPERTY_CODE" => $arProps,	// Свойства
			"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
			"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
			"TEMPLATE_THEME" => "blue",	// Цветовая тема
			"AJAX_MODE" => "N",	// Включить режим AJAX
			"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
			"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
			"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
			"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
			"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
			"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
			"DISPLAY_ELEMENT_SELECT_BOX" => "N",	// Выводить список элементов инфоблока
			"ELEMENT_SORT_FIELD_BOX" => "name",	// По какому полю сортируем список элементов
			"ELEMENT_SORT_ORDER_BOX" => "asc",	// Порядок сортировки списка элементов
			"ELEMENT_SORT_FIELD_BOX2" => "id",	// Поле для второй сортировки списка элементов
			"ELEMENT_SORT_ORDER_BOX2" => "desc",	// Порядок второй сортировки списка элементов
			"HIDE_NOT_AVAILABLE" => "N",	// Не отображать в списке товары, которых нет на складах
			"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
			"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
			"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
			"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
			"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
			"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
			"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
			"BASKET_URL" => "/order/",	// URL, ведущий на страницу с корзиной покупателя
		),
		false
	);?>

<?endif;?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
