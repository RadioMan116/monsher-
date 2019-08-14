<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



?>
<?if($arResult["ITEMS"]):?>
				<div class="special-offers">
					<div class="container">
						<span class="special-offers__title"><?=$arParams["BLOCK_TITLE"]?></span>
						<!-- Slider main container -->
						<div class="swiper-container swiper-container-two js-swiper-two">
							<!-- Additional required wrapper -->
							<div class="swiper-wrapper special-offers__wrapper">
		
		<?foreach($arResult["ITEMS"] as $i => $arElement):?>
		<?				
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	
		$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
		
		$arShow = CStatic::CheckViewParams($arElement);
		//pre($arElement["MIN_PRICE"]);
		
		
		
		?>		
		
		
		<div class="swiper-slide special-offers__slide js-ecom_product-item" data-id="<?=$arElement['ID']?>" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
									<a class="special-offers__pic" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">
												<?/*if($arShow["LABELS"]):?>
												<div class="label">
																<?if($arElement["LABELS"]["LABEL_FREE_CONNECTION"]):?>
																	<div class="label__item"><img data-src="/tpl/images/label_connection.png" alt="Бесплатное подключение" class="label__pic lazyload" width="192" height="31" /></div>
																<?endif;?>
																<?if($arElement["LABELS"]["LABEL_FREE_DELIVERY"]):?>
																	<div class="label__item"><img data-src="/tpl/images/label_delivery.png" alt="Бесплатная доставка" class="label__pic lazyload" width="172" height="31" /></div>
																<?endif;?>
																<?if($arElement["LABELS"]["S_NEW"]):?>
																	<div class="label__item"><img data-src="/tpl/images/new.png" alt="Новинка" class="label__pic lazyload" /></div>
																<?endif;?>
																<?if($arElement["LABELS"]["S_HIT"]):?>
																	<div class="label__item"><img data-src="/tpl/images/hit.png" alt="Хит" class="label__pic lazyload" /></div>
																<?endif;?>												
												</div>
												<?endif;*/?>		
									
										<img data-src="<?=$arElement["IMG_1"]?>" data-srcset="<?=$arElement["IMG_2"]?> 2x,<?=$arElement["IMG_3"]?> 3x" class="special-offers__img lazyload" />
									</a>
									<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="special-offers__text"><?=$arElement["NAME"]?></a>
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

		<?/*$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_product.php',
			 Array("PRODUCT" => $arElement),
			 Array("MODE"=>"php")
		 )*/?>

										
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
																	<div class="announcing__item"><img class="lazyload" data-src="<?=CStatic::$pathV?>images/new.png" alt="Новинка" class="label__pic" /></div>
																<?endif;?>
																<?/*if($arElement["LABELS"]["S_HIT"]):?>
																	<div class="label__item"><img data-src="/tpl/images/hit.png" alt="Хит" class="label__pic lazyload" /></div>
																<?endif;*/?>
																
															</div>
															<!-- end label -->
								<?endif;?>	
									
		</div>

				<?endforeach;?>				
				</div>	
				<div class="swiper-scrollbar"></div>
				
				
				
			</div>

			<div class="swiper-pagination_2"></div>
			<div class="swiper-button-prev special-offers_prev"></div>
			<div class="swiper-button-next special-offers_next"></div>
			
		</div>	
</div>
<?endif;?>	
