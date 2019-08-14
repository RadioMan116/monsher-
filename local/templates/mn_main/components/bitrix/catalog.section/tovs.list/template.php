<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$PAGE_URL = $arResult["LIST_PAGE_URL"];
$this->setFrameMode(true);

// номер текущей страницы
$curPage = $arResult["NAV_RESULT"]->NavPageNomer;
// всего страниц - номер последней страницы
$totalPages = $arResult["NAV_RESULT"]->NavPageCount;
// номер постраничной навигации на странице
$navNum = $arResult["NAV_RESULT"]->NavNum;



$FAVORITE_LIST = array();
if($arParams["FAVORITE_LIST"]) $FAVORITE_LIST = explode('|',$arParams["FAVORITE_LIST"]);

$COMPARE_LIST = $arParams["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"];
$count_all = $arResult["NAV_RESULT"]->NavRecordCount;

$random = rand(1,7);


//pre($arResult["ICONS_ALL"]);
?>

<?if($arParams["AJAX"] != "Y"):?> 
<input type="hidden" class="js-catalog_count" name="catalog_count" value="<?=$count_all?> <?=declOfNum($count_all, array('позиция', 'позиции', 'позиций'))?>" />
<?endif;?>

<?if($arResult["ITEMS"]):?>

	<?if($arParams["AJAX"] != "Y"):?> 
	<div class="js-news" >
	<div class="js-news__inner catalog__items js-ecom_product-list" data-list="Catalog Result">
	<?endif;?>
	
	<?foreach($arResult["ITEMS"] as $k=>$arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

	//pre($arElement);

	$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);

	$arShow = CStatic::CheckViewParams($arElement);
	//pre($arElement["DISPLAY_PROPERTIES"]);
	?>
	
	<?if($arResult["PROMO"]):?>
	<?if($k == $random && count($arResult["ITEMS"]) > 4 && $arParams["PAGE_ELEMENT_COUNT"] < 12):?>	
	
		<?
		$arPromo = $arResult["PROMO"];
		
		
		if(!$arPromo["PROPERTIES"]["LINK"]["VALUE"]) $arPromo["PROPERTIES"]["LINK"]["VALUE"] = '#';
		
		$blk_text = false;
		if($arPromo["PROPERTIES"]["TEXT_1"]["VALUE"]["TEXT"] || $arPromo["PROPERTIES"]["TEXT_2"]["VALUE"]["TEXT"] || $arPromo["PROPERTIES"]["TEXT_3"]["VALUE"]["TEXT"]) $blk_text = true;
		
		?>
		<div class="catalog__item stock">
								<a class="upsale__parent" href="<?=$arPromo["PROPERTIES"]["LINK"]["VALUE"]?>" title="<?=$arPromo["NAME"]?>">
									<img class="stock_img lazyload" data-src="<?=$arPromo["IMG_1"]?>" data-srcset="<?=$arPromo["IMG_2"]?> 2x,<?=$arPromo["IMG_3"]?> 3x" alt="<?=$arPromo["NAME"]?>" />
								</a>
								
								<?if($blk_text):?>
								<div class="stock__info">
									<?if($arPromo["PROPERTIES"]["TEXT_1"]["VALUE"]["TEXT"]):?>
										<span class="stock__title"><?=$arPromo["PROPERTIES"]["TEXT_1"]["~VALUE"]["TEXT"]?></span>
									<?endif;?>
									
									<?if($arPromo["PROPERTIES"]["TEXT_2"]["VALUE"]["TEXT"] || $arPromo["PROPERTIES"]["TEXT_3"]["VALUE"]["TEXT"]):?>
										<span class="stock__text">
											<?=$arPromo["PROPERTIES"]["TEXT_2"]["~VALUE"]["TEXT"]?>
											<?if($arPromo["PROPERTIES"]["TEXT_3"]["VALUE"]["TEXT"]):?><span><?=$arPromo["PROPERTIES"]["TEXT_3"]["~VALUE"]["TEXT"]?></span><?endif;?>
										</span>
									<?endif;?>
									
									<?if($arPromo["PROPERTIES"]["LINK"]["VALUE"]!='#'):?>
										<a class="stock__link" href="<?=$arPromo["PROPERTIES"]["LINK"]["VALUE"]?>">
											<span class="stock__arrow">Узнать больше</span>
										</a>
									<?endif;?>
									
								</div>
								<?endif;?>
		</div>	
	
	<?endif;?>	
	<?endif;?>	
	
	
	<div class="catalog__item js-ecom_product-item js-catalog_item" data-id="<?=$arElement['ID']?>" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
	
								<a class="catalog__pic" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">									
									<img data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?> preview 1" title="<?=$arElement["NAME"]?> фото 1" class="special-offers__img catalog__img lazyload" />
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


	<div class="catalog__prop">
	<?foreach($arElement["PROPERTIES"] as $arProp):?>
	<?
	
		if(!$arPropG = CStatic::DescPropCheck($arResult["G_PROPS_ALL"], $arProp["ID"], $arProp["VALUE"])) continue;

		//pre( $arProp);
		if(!$arPropG["PROPERTIES"]["ICON"]["VALUE"]) continue;

		$pic = CFile::ResizeImageGet($arPropG["PROPERTIES"]["ICON"]["VALUE"], array('width'=>39, 'height'=>39), BX_RESIZE_IMAGE_PROPERTIONAL, true)["src"];
	?>
	<span class="link-pop-glossary">
		<img data-src="<?=$pic?>" class="icons-prop-item lazyload" title="<?=$arPropG["PREVIEW_TEXT"]?>" />
	</span>			

	<?endforeach;?>
	</div>





								
								
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
										<a href="" class="special-offers__buy js-add2basket" data-id="<?=$arElement["ID"]?>">В корзину</a>
									<?endif;?>
									
								</div>								
								
								
								<div class="catalog__existence">

	
									<?
										$dd_c = '';
										$dd_text = 'Сравнить';
										if($COMPARE_LIST[$arElement["ID"]]) {$dd_c = 'active';$dd_text = 'В сравнении';}
									?>
									<a href="" class="catalog__simile js-compare <?=$dd_c?>" data-id="<?=$arElement["ID"]?>"></a>
									
									<?
										$dd_c = '';
										$dd_text = 'В избранное';
										if(in_array($arElement["ID"],$FAVORITE_LIST)) {$dd_c = 'active'; $dd_text = 'Из избранного';}							
									?>						
									<a href="" class="catalog__favorite <?=$dd_c?> js-add2favorite" data-id="<?=$arElement["ID"]?>" ></a>

									
									<?if($arShow["BUY"]):?>
									<a href="" class="catalog__oneclick js-viewForm" data-action="BuyOneClick" data-id="<?=$arElement["ID"]?>">Купить&nbsp;в&nbsp;1&nbsp;клик</a>	
									<?endif;?>
									
								</div>
								
								
								<?if($arShow["LABELS"]):?>
															<!-- begin label -->
															<div class="announcing">
																<?if($arElement["LABELS"]["LABEL_FREE_CONNECTION"]):?>
																	<div class="announcing__item"><img class="lazyload" data-src="<?=CStatic::$pathV?>images/small/tools.svg" alt="Бесплатное подключение" title="Бесплатное подключение" /></div>
																<?endif;?>
																<?if($arElement["LABELS"]["LABEL_FREE_DELIVERY"]):?>
																	<div class="announcing__item"><img class="lazyload" data-src="<?=CStatic::$pathV?>images/small/truck.svg" alt="Бесплатная доставка" title="Бесплатное доставка" /></div>
																<?endif;?>
																
																<?if($arElement["LABELS"]["S_NEW"]):?>
																	<div class="announcing__item"><img data-src="<?=CStatic::$pathV?>images/small/new.svg" alt="Новинка" class="label__pic lazyload" /></div>
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

		<?/*$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_product.php',
			 Array("PRODUCT" => $arElement),
			 Array("MODE"=>"php")
		 )*/?>
								
	</div>

	<?endforeach;?>
	
	<?if($arParams["AJAX"] != "Y"):?>
	</div>
	</div>


		<div class="all-video__bottom">
			<?if($totalPages > 1 && $curPage < $totalPages):?>	
						
						
						<a href="#" id="load-items" class="load-more">Загрузить еще</a>
						<script>
						$(function(){
							var newsSetLoader = new newsLoader({
								root: '.js-news',
								newsBlock: '.js-news__inner',
								newsLoader: '#load-items',
								ajaxLoader: '#ajax-loader img',
								loadSett:{
									endPage: <?=$totalPages?>,
									navNum: <?=$navNum?>,
									curPage: <?=$curPage?>
								}	
							});
							newsSetLoader.init();
						});
						</script>
						
			<?endif;?>

			<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
				<?=$arResult["NAV_STRING"]?>
			<?endif?>
		</div> 
	
	<?endif?>

<?endif?>
