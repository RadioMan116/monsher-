<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column


	//pre($arResult["BASKET_ITEMS"]);



?>
<!-- begin basket-info -->
						<div class="order-form__table table">
							<div class="table__title">
								<span class="title__span name">Наименование</span>
								<span class="title__span how-many">Количество, шт.</span>
								<span class="title__span price">Цена</span>
								<span class="title__span amount">Сумма</span>
								<span class="title__span delete">Удалить</span>
							</div>
							
							<?

							$CREDIT = false;
							$arResult["DELIVERY_PRICE"] = 'Бесплатная доставка';
							?>
							<?foreach ($arResult["BASKET_ITEMS"] as $k => $arElement):?>

							<?
							$discountPercent = round(($arElement["BASE_PRICE"]-$arElement["PRICE"])*100/$arElement["BASE_PRICE"]);


							if($arElement["PRODUCT"]["IBLOCK_ID"] == CStatic::$catalogIdblock_pay_delivery) {
								$arResult["DELIVERY_PRICE"] = 'Доставка: 350 р.';
							}
							
							$arProduct = $arElement["PRODUCT"];
							//pre($arElement);
							//pre($arElement["BASE_PRICE"]);

							?>
							
							<div class="table__items js-product__row">
								<div class="table__item name">
									<a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" title="<?=$arProduct["NAME"]?>" class="table__link">
										<img data-src="<?=$arProduct["IMG_1"]?>" data-srcset="<?=$arProduct["IMG_2"]?> 2x,<?=$arProduct["IMG_3"]?> 3x" alt="<?=$arProduct["NAME"]?>" class="special-offers__img lazyload" />
									</a>
									<div class="table__text">
										<a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" title="<?=$arProduct["NAME"]?>" class="special-offers__text"><?=$arProduct["NAME"]?></a>
										<span class="catalog__presence"><?=$arProduct["PROPERTIES"]["MSK"]["VALUE"]?></span>
									</div>
								</div>
								<div class="table__item how-many js-change">
									<span class="item__title">Количество, шт</span>
									<a href="" class="decr qt_minus js-minus">-</a>
									<input type="text" data-basketID="<?=$arElement["ID"]?>" data-price="<?=$arElement["PRICE"]?>"  name=""QUANTITY_<?=$arElement["ID"] ?>" value="<?=$arElement["QUANTITY"]?>" class="qt_num js-input_number" />
									<a href="" class="incr qt_plus js-plus">+</a>
								</div>
								<div class="table__item price">
									<span class="item__title">Цена</span>
									<strong class="strong__price"><?=number_format($arElement["PRICE"], 0, '.', ' ')?> руб.</strong>
								</div>
								<div class="table__item amount">
									<span class="item__title">Сумма</span>
									<strong class="strong__amount js-sum"><?=number_format($arElement["QUANTITY"]*$arElement["PRICE"], 0, '.', ' ')?> руб.</strong>
								</div>
								<a href="#" class="delete__table js-product__del" data-id="<?=$arElement["ID"]?>" data-prodid="<?=$arElement["PRODUCT"]["ID"]?>">Удалить</a>
							</div>

							<?endforeach;?>
							
							<div class="table__total">
								Итого:
								<strong class="js-sum_all"><?=number_format($arResult["ORDER_PRICE"], 0, '.', ' ')?> руб.</strong>
							</div>
							
							
							
						</div>

<?if($arResult["FORGET_LIST"]):?>
<?	
		global $arrFilter2;
		$arrFilter2 = array(			
			"ID" => $arResult["FORGET_LIST"],
			"!PROPERTY_MSK_VALUE" => array('Нет в наличии'),
		);
	
//	pre($arrFilter2);
		
		

if($_GET["mode"]) {
		//PRE($arResult["PROPERTIES"]["CONNECT"]);
		//PRE($arrFilter2);
}	


	$APPLICATION->IncludeComponent(
		"inter.olsc:catalog.section",
		"tovs.list.recommend",
		Array(
			"EC_TYPE" => 'Basket Accessory',
			"BLOCK_TITLE" => "Не забудьте купить",
			"CAN_BUY" => "Y",
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_SECTIONS_CHAIN" => "N",
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
			"ELEMENT_SORT_FIELD" => "RAND",
			"ELEMENT_SORT_ORDER" => "",
			"ELEMENT_SORT_FIELD2" => "",
			"ELEMENT_SORT_ORDER2" => "",
			"FILTER_NAME" => "arrFilter2",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => CStatic::$accIdBlock,
			"IBLOCK_TYPE" => "jetair_catalog",
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
			"PAGE_ELEMENT_COUNT" => 99,
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array("MSK"),
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
							
							
							

							<!-- end basket-table -->







