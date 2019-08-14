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
$this->setFrameMode(true);

?>

<?if($arResult["ITEMS"]):?>

						<div class="special-offers similar-models articles-main__slider">
							<!-- Slider main container -->
							<div class="special-offers">
								<div class="container">
									<?if($arParams["BLOCK_TITLE"]):?><span class="special-offers__title"><?=$arParams["BLOCK_TITLE"]?></span><?endif;?>
									<!-- Slider main container -->
									<div class="swiper-container swiper-container-two">
										<!-- Additional required wrapper -->
										<div class="swiper-wrapper special-offers__wrapper js-ecom_product-list" data-list="<? echo $arParams["EC_TYPE"] ? $arParams["EC_TYPE"] : 'Product Detail'?>">


	<?foreach($arResult["ITEMS"] as $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

	//pre($arElement["CATALOG_PRICE_1"]);

	
	$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
	
	$COMPARE_LIST = $arParams["CATALOG_COMPARE_LIST"][$arElement["IBLOCK_ID"]]["ITEMS"];
	
	
	$arShow = CStatic::CheckViewParams($arElement);
	?>

	
	<div class="catalog__item swiper-slide js-ecom_product-item" data-id="<?=$arElement['ID']?>"  id="<?=$this->GetEditAreaId($arElement['ID']);?>">
												<a class="catalog__pic" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">												
													<img data-src="<?=$arElement["IMG_1"]?>" data-srcset="<?=$arElement["IMG_2"]?> 2x,<?=$arElement["IMG_3"]?> 3x" class="special-offers__img catalog__img lazyload" />
												</a>
												<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="special-offers__text catalog__text"><?=$arElement["NAME"]?></a>
												<span class="catalog__presence"><?=$arElement["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"]?></span>
												
												<div class="catalog__specification">
												
										<?
											$k = 0;
											foreach($arElement["DISPLAY_PROPERTIES"] as $code=>$arProp):?>
																<?if($arProp["FILTRABLE"] == "Y" && $arProp["VALUE"]):?>
																<?
																	if($k > 3) break;

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

																?>
																<div class="catalog__feature">
																	<?=$arProp["NAME"]?>: <span class="catalog__bold"><?=$val?></span>
																</div>
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
														<a href="" title="купить" class="js-add2basket special-offers__buy" data-id="<?=$arElement["ID"]?>">купить</a>
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
																	<div class="announcing__item"><img class="lazyload" data-src="<?=CStatic::$pathV?>images/new.png" alt="Новинка" /></div>
																<?endif;?>
																<?/*if($arElement["LABELS"]["S_HIT"]):?>
																	<div class="announcing__item"><img class="lazyload" data-src="/tpl/images/hit.png" alt="Хит" /></div>
																<?endif;*/?>
																
																<?if($arElement["LABELS_DOP"]):?>
																	<?foreach($arElement["LABELS_DOP"] as $label):?>
																		<div class="announcing__item"><img class="lazyload" data-src="<?=$label["IMG"]?>" alt="<?=$label["NAME"]?>" /></div>
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
								<div class="swiper-scrollbar"></div>
								<!-- If we need pagination -->
							</div>	
								
								
							<div class="swiper-pagination_2"></div>
							<div class="swiper-button-prev special-offers_prev"></div>
							<div class="swiper-button-next special-offers_next"></div>
			</div>
	</div>
</div>
<?endif;?>




