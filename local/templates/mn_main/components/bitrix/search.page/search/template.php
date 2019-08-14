<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

	$arResult["REQUEST"]["QUERY"] = $_GET["q"];
//echo "<pre>".print_r($arResult, true)."</pre>";

$nSelectedCount = count($arResult["ITEMS"]) > 0 ? $arResult["NAV_RESULT"]->NavRecordCount : 0;

?>



<div class="search-found ">
								<div class="search-found__item">
									<form class="search-found__form" action="" method="get">
										<input type="text" name="q" value="<?=$request->get('q')?>" placeholder="Поиск" />
										<input type="submit" value="Найти" class="btn-sub3 abuse-popup__submit" name="">
									</form>
								</div>
								<div class="search-found__item">
									<span class="search__info">
									
									<?if(!empty($arResult["REQUEST"]["QUERY"])):?>
										<?if(count($arResult["ITEMS"])):?>
											В каталоге <?=declOfNum($nSelectedCount, array('найдена', 'найдено', 'найдено'))?> <strong><?=$nSelectedCount?></strong> <?=declOfNum($nSelectedCount, array('модель', 'модели', 'моделей'))?>
										<?else:?>
											По запросу «<?=$request->get('q')?>» ничего не найдено
										<?endif;?>
									<?else:?>
										Введите запрос в поисковую строку
									<?endif;?>
									
									
									
									</span>
								</div>
</div>



<?if(count($arResult["ITEMS"]) > 0):?>

<div class="catalog__items js-ecom_product-list" data-list="Search Result">
<?foreach($arResult["ITEMS"] as $arElement):?>
<?
	

	$COMPARE_LIST = $arParams["CATALOG_COMPARE_LIST"][$arElement["IBLOCK_ID"]]["ITEMS"];

	
	$arShow = CStatic::CheckViewParams($arElement);
	?>
	
	<div class="catalog__item js-ecom_product-item" data-id="<?=$arElement['ID']?>" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
	
								<a class="catalog__pic" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">
									<img data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" class="special-offers__img catalog__img lazyload" />
								</a>
								<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="special-offers__text catalog__text"><?=$arElement["NAME"]?></a>
								<?
									$dd_ex = '';
									switch($arElement["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"]) {
										case "Под заказ": $dd_ex = 'grey'; break;
									}
								?>
								<span class="catalog__presence <?=$dd_ex?>"><?=$arElement["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"]?></span>
								
								<div class="catalog__specification">
									<?
										$k = 0;
										$arElement["MICRO_DESC"] = '';
										foreach($arElement["DISPLAY_PROPERTIES"] as $code=>$arProp):?>
											<?if($arProp["FILTRABLE"] == "Y" && $arProp["VALUE"]):?>
											<?
																	if($k > 5) break;

																	$val = $arProp["DISPLAY_VALUE"];
																	if( is_array($val) ){
																		$val = implode(', ',$val);
																	}
																	$val = preg_replace('#<a[^>]*>(.*?)</a>#is', '$1', $val);

																	$strLen = strlen($arProp["NAME"]) + strlen($val);

																	if(in_array(strtolower($val),array('нет','n')) || $strLen > 45 )
																		continue;

																	$k++;

																	//if(in_array(strtolower($val),array('да','y'))) {$val = '<i class="params-value_check"></i>';}
											$arElement["MICRO_DESC"].=$arProp["NAME"].': '.$val.', ';
											?>
											<span class="catalog__feature"><?=$arProp["NAME"]?>: <span class="catalog__bold"><?=$val?></span></span>
										<?endif;?>
									<?endforeach;?>	
								</div>
								
<?
$arPropID = array();
foreach($arElement["PROPERTIES"] as $prop) {
	if($arPropDesc = CStatic::DescProp($prop["ID"], $prop["VALUE"], $arResult["ID"])) {
		$arPropID[] = $arPropDesc["ID"];
	}
}
?>

<?if($arPropID):?>
<div class="catalog__prop">
<?foreach($arPropID as $prodId):?>
<?
$arProp = $arResult["ICONS_ALL"][$prodId];
$pic = '';
if($arProp["PICTURE"]) {$pic = $arProp["PICTURE"];}
if($arProp["PROPERTIES"]["SVG"]["VALUE"]) {
	$pic = CFile::GetPath($arProp["PROPERTIES"]["SVG"]["VALUE"]);
}
?>
<span class="link-pop-glossary">
	<img data-src="<?=$pic?>" class="icons-prop-item lazyload" title="<?=$arProp["PREVIEW_TEXT"]?>" />
</span>			

<?endforeach;?>
</div>
<?endif;?>
								
								
								<div class="special-offers__sale catalog__sale">
								
									<?if($arShow["PRICE"]):?>
									
										<?if(count($arElement["PRICES"]) > 1 && $arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]] && $arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]] ):?>	
															<span class="special-offers__price catalog__price price__dashed">
																<?															
																$discountPercent = round(($arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]-$arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]);
																?>
																<?=number_format($arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> <i class="special-offers__rub catalog__rub">руб.</i>
																<span class="price__absolute"><?=number_format($arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> руб.</span>
															</span>	
																	
										<?else:?>
														<span class="special-offers__price catalog__price <?if($arElement["MIN_PRICE"]["DISCOUNT_VALUE"]!=$arElement["MIN_PRICE"]["VALUE"]):?>price__dashed<?endif;?>">
																<?															
																$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
																?>																
																<?=number_format($arElement["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '.', ' ')?> <i class="special-offers__rub catalog__rub">руб.</i>																
																<?if($arElement["MIN_PRICE"]["DISCOUNT_VALUE"]!=$arElement["MIN_PRICE"]["VALUE"]):?>
																	<span class="price__absolute"><?=number_format($arElement["MIN_PRICE"]["VALUE"], 0, '.', ' ')?> руб.</span>	
																<?endif;?>
														</span>		
										<?endif;?>	
									
									<?endif;?>
									
									<?if($arShow["BUY"]):?>
										<a href="" class="special-offers__buy js-add2basket" data-id="<?=$arElement["ID"]?>">купить</a>
									<?endif;?>
									
								</div>								
								
								
								<div class="catalog__existence">

									<?
										$dd_c = '';
										$dd_text = 'Сравнить';
										if($COMPARE_LIST[$arElement["ID"]]) {$dd_c = 'active';$dd_text = 'В сравнении';}
									?>
									<a href="" class="catalog__simile js-compare <?=$dd_c?>" data-id="<?=$arElement["ID"]?>"><?=$dd_text?></a>												
									<a href="" class="catalog__oneclick js-viewForm" data-action="BuyOneClick" data-id="<?=$arElement["ID"]?>">Купить&nbsp;в&nbsp;1&nbsp;клик</a>								
								</div>
								
								
								<?if($arShow["LABELS"]):?>
															<!-- begin label -->
															<div class="announcing">
																<?if($arElement["LABELS"]["LABEL_FREE_CONNECTION"]):?>
																	<div class="announcing__item"><img class="lazyload" data-src="<?=CStatic::$pathV?>images/service.png" alt="Бесплатное подключение" title="Бесплатное подключение" /></div>
																<?endif;?>
																<?if($arElement["LABELS"]["LABEL_FREE_DELIVERY"]):?>
																	<div class="announcing__item"><img class="lazyload" data-src="<?=CStatic::$pathV?>images/delivery.png" alt="Бесплатная доставка" title="Бесплатное доставка" /></div>
																<?endif;?>
																
																<?if($arElement["LABELS"]["S_NEW"]):?>
																	<div class="announcing__item"><img data-src="<?=CStatic::$pathV?>images/new.png" alt="Новинка" class="label__pic lazyload" /></div>
																<?endif;?>
																<?/*if($arElement["LABELS"]["S_HIT"]):?>
																	<div class="label__item"><img data-src="/tpl/images/hit.png" alt="Хит" class="label__pic lazyload" /></div>
																<?endif;*/?>
																
																<?if($arElement["LABELS_DOP"]):?>
																	<?foreach($arElement["LABELS_DOP"] as $label):?>
																		<div class="label__item"><img data-src="<?=$label["IMG"]?>" alt="<?=$label["NAME"]?>" class="label__pic lazyload" /></div>
																	<?endforeach;?>
																<?endif;?>
																
															</div>
															<!-- end label -->
								<?endif;?>	

		<?$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_product.php',
			 Array("PRODUCT" => $arElement),
			 Array("MODE"=>"php")
		 )?>
								
	</div>
	
	
	
	<?endforeach;?>



</div>

<?=$arResult["NAV_STRING"]?>


<?endif;?>
	

