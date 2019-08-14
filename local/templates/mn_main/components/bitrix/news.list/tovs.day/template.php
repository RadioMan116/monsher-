<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	$FAVORITE_LIST = array();
	if($arParams["FAVORITE_LIST"]) $FAVORITE_LIST = explode('|',$arParams["FAVORITE_LIST"]);

?>
<?if($arResult["ITEMS"]):?>				
		
		<?foreach($arResult["ITEMS"] as $i => $arElement):?>
		<?				
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	
		$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
		
		$arShow = CStatic::CheckViewParams($arElement);
		//pre($arElement["MIN_PRICE"]);
		
		
		
		?>			
		
		<div class="product-day" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
							<div class="product-day__item product-day__item_left">
								<div class="product-day__el">
									<div class="product-day__title">
										Товар дня
										
		<?/*$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_product.php',
			 Array("PRODUCT" => $arElement),
			 Array("MODE"=>"php")
		 )*/?>
									</div>
									<div class="product-day__reviews reviews">									
									
										<?
										$arRate = CStatic::GetReviewsRating($arElement["ID"]);
										$rating_all = $arRate["RATE"];										
										?>									
										<div class="ratings ratings_small">
											<?
											$k = 0;
											while($k++ < 5):?>
												<div class="ratings__star <?if($k > $rating_all):?>ratings__none<?endif;?>"></div>
											<?endwhile;?>	
											
											<b class="ratings__number"><?=$rating_all?></b>
										</div>
										<a href="/reviews/product-<?=$arElement["CODE"]?>/" class="reviews__all"><?=$arRate["COUNT"]?> <?=declOfNum($arRate["COUNT"], array('отзыв', 'отзыва', 'отзывов'))?></a>									
									
									</div>
									<div class="product-day__social ">
										<a href="#" class="item js-share">											
											<img class="lazyload" data-src="<?=CStatic::$pathV?>images/share.svg" alt="Поделиться" />
											<p>Поделиться</p>
										</a>
										<div class="share__items">
											<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter"></div>
										</div>
										<?
											$dd_c = '';									
											$dd_text = 'Сравнить';									
											if($COMPARE_LIST[$arElement["ID"]]) {$dd_c = 'active'; $dd_text = 'В сравнении';}							
										?>	
										<a href="" class="item compare js-compare <?=$dd_c?>" data-id="<?=$arElement["ID"]?>">											
											<p><?=$dd_text?></p>
										</a>
										<?
											$dd_c = '';
											$dd_text = 'В избранное';
											if(in_array($arElement["ID"],$FAVORITE_LIST)) {$dd_c = 'active'; $dd_text = 'Из избранного';}							
										?>	
										<a href="" class="item favorite <?=$dd_c?> js-add2favorite" data-id="<?=$arElement["ID"]?>">											
											<p><?=$dd_text?></p>
										</a>
									</div>
								</div>
								<div class="product-day__pic">
									<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">
										<img class="lazyload" data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" />
									</a>
								</div>
							</div>
							<div class="product-day__item product-day__item_right">
								<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="product-day__subtitle"><?=$arElement["NAME"]?></a>
								<?if($arElement["PROPERTIES"]["_UNID"]["VALUE"]):?><span class="product-day__key">Код товара: <?=$arElement["PROPERTIES"]["_UNID"]["VALUE"]?></span><?endif;?>
								
								<?if($arShow["PRICE"]):?>							
									<span class="product-day__price catalog__price">
											<?if(count($arElement["PRICES"]) > 1 && $arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]] && $arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]] ):?>	
															
																<?															
																$discountPercent = round(($arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]-$arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]);
																?>
																<?=number_format($arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> <i class="product-day__rub catalog__rub">руб.</i>
																<span class="price__absolute"><?=number_format($arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> руб.</span>																
																	
											<?else:?>
														
																<?															
																$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
																?>																
																<?=number_format($arElement["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '.', ' ')?> <i class="product-day__rub catalog__rub">руб.</i>																
																<?if($arElement["MIN_PRICE"]["DISCOUNT_VALUE"]!=$arElement["MIN_PRICE"]["VALUE"]):?>
																	<span class="price__absolute"><?=number_format($arElement["MIN_PRICE"]["VALUE"], 0, '.', ' ')?> руб.</span>	
																<?endif;?>
															
											<?endif;?>	
									</span>								
								<?endif;?>
								
								<div class="product-day__button">
									<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="В карту товара" class="product-day__link">В карту товара</a>
									
									<?if($arShow["BUY"]):?>
										<a href="" title="В корзину" class="js-add2basket product-day__buy" data-id="<?=$arElement["ID"]?>">В корзину</a>
									<?endif;?>	
									
								</div>
							</div>
						</div>
		
				<?endforeach;?>		
<?endif;?>	
