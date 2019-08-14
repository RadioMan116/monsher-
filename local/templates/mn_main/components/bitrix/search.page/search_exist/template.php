<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

	$arResult["REQUEST"]["QUERY"] = $_GET["q"];
//echo "<pre>".print_r($arResult, true)."</pre>";

$nSelectedCount = count($arResult["TOVS_ID"]) > 0 ? count($arResult["TOVS_ID"]) : 0;


?>

<div class="search-found">

								<form class="search-found__form" action="" method="get">
									<input type="text" name="q" value="<?=$request->get('q')?>" placeholder="Поиск" />
									<input type="submit" value="Найти" class="btn-sub3 abuse-popup__submit" />
								</form>
								<hr>

								<span class="search__info">
									<?if(!empty($arResult["REQUEST"]["QUERY"])):?>
										<?if($nSelectedCount):?>
											В каталоге <?=declOfNum($nSelectedCount, array('найдена', 'найдено', 'найдено'))?> <strong><?=$nSelectedCount?></strong> <?=declOfNum($nSelectedCount, array('позиция', 'позиции', 'позиций'))?>
										<?else:?>
											По запросу «<?=$request->get('q')?>» ничего не найдено
										<?endif;?>
									<?else:?>
										Введите запрос в поисковую строку
									<?endif;?>
								</span>


<?if(count($arResult["TOVS_ID"]) > 0):?>




<?
		global $arrFilterD;
		$arrFilterD["ID"] = $arResult["TOVS_ID"];

		//PRE($arrFilterD);

	$APPLICATION->IncludeComponent(
		"inter.olsc:catalog.section",
		"tovs.list",
		Array(			
			"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
			"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
			"CATALOG_COMPARE_LIST" => $arParams["CATALOG_COMPARE_LIST"],
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_SECTIONS_CHAIN" => "Y",
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
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "propertysort_".$GLOBALS["K_EXIST_CODE"],
			"ELEMENT_SORT_ORDER" => "ASC",
			"ELEMENT_SORT_FIELD2" => "CATALOG_PRICE_".CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]],
			"ELEMENT_SORT_ORDER2" => "ASC",
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
			"OFFERS_FIELD_CODE" => array(),
			"OFFERS_PROPERTY_CODE" => array(),
			"OFFERS_CART_PROPERTIES" => array(),
			"OFFERS_LIMIT" => "99",			
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
			"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"], $GLOBALS["K_PRICE_CODE_SALE"]),
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
		)
	);?>
<?endif;?>

</div>

