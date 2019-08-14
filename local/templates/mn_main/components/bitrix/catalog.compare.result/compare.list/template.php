<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$PROPS = $arResult["SHOW_PROPERTIES"];
global $APPLICATION;
//$path = str_replace($_SERVER['DOCUMENT_ROOT'],'',dirname(__FILE__));



$arElement = $arResult["ITEMS"][0];
?>

<div class="js-compare_page comparison">	
			<h1 class="title">Сравнение</h1>

						<div class="fixed-header">
							<h3 class="title">Сравнение</h3>
							<div class="swiper-button-prev fixed-header-prev special-offers_prev"></div>
							<div class="swiper-button-next fixed-header-next special-offers_next"></div>
							<div class="swiper-pagination"></div>
							<div class="swiper-container">
								<div class="swiper-wrapper">
									<?foreach($arResult["ITEMS"] as $arElement):?>
									<div class="swiper-slide">
										<a class="fixed-header__pic" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">
											<img data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" class="fixed-header__img lazyload" />
										</a>
										<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="fixed-header__text"><?=$arElement["NAME"]?></a>
									</div>
									<?endforeach?>									
								</div>
							</div>

						</div>
						
						
						
						
						
						
						<table id="compare_table" class="comparison__middle">
							<thead class="comparison__main">
								<tr class="">
									<td class="">
										<div class=" swiper__comparison_first js-comparison-height">
											<div class="comparison__inner">
												<div class="comparison-menu__title">Категория сравнения</div>
												<ul class="comparison__list">
												
													<?
													/*
													$iblock_active = 0;
													
													$k = 0;
													foreach($_SESSION["CATALOG_COMPARE_LIST"] as $iblock => $mTov){
														if( empty($mTov["ITEMS"]) )continue;							
														$arBlock = getArIblock('lb_catalog', false, $iblock);
														
														$dd = ''; 
														if(!$iblock_active && !$k) {$iblock_active = $iblock; }
														
														
														if($mTov["ACTIVE"]) {$dd = 'active';$iblock_active = $iblock; }
													?>
													<li class="comparison__item">
														<a class="comparison__link js-compare_block-change <?=$dd?>" data-id="<?=$iblock?>" href="">
															<span class="comparison__text"><?=$arBlock["NAME"]?></span>
														</a>
													</li>
																	
													<? 
													$k++;
													
													} */?>	
													
													
													<?
													
													$iblock_active = 0;
													
													$k = 0;
													foreach($_SESSION["CATALOG_COMPARE_LIST"] as $iblock => $mTov){
														if( empty($mTov["ITEMS"]) )continue;							
														$arBlock = getArIblock('lb_catalog', false, $iblock);
														
														$dd = ''; 
														if(!$iblock_active && !$k) {$iblock_active = $iblock; }
														
														
														if($mTov["ACTIVE"]) {$dd = 'active';$iblock_active = $iblock; }
													?>
													
													
													<?foreach($arResult["SECTIONS"][$iblock] as $arSection):?>
													<?
													$dd = ''; 
													if($arSection["S_ACTIVE"]) $dd = 'active';
													?>
													
													<li class="comparison__item">
														<a class="comparison__link js-compare_block-change <?=$dd?>" data-id="<?=$arSection["ID"]?>" href="">
															<span class="comparison__text"><?=$arSection["NAME"]?></span>
														</a>
													</li>
													
													<?endforeach?>
																	
													<? 
													$k++;
													
													}?>	

													
													
												</ul>
												<span class="comparison__text"><?=count($arResult["ITEMS"])?> <?=declOfNum(count($arResult["ITEMS"]), array('товар', 'товара', 'товаров'))?> в категории</span>
											</div>
											<div id="compare_table_button" class="comparison__button">
												<div class="comparison-menu__title">Показать</div>
												
												<a href="#" class="comparison__select js-comparison__select active" compare="ALL">
													<i></i>Все параметры
												</a>
												<a href="#" class="comparison__select js-comparison__select" compare="DIFF">
													<i></i>Различающиеся
												</a>
												
											</div>											
											
											<a href="#" class="comparison__filter js-compare_block-clear" data-id="<?=$iblock_active?>">
												<i></i>Очистить список
											</a>
										</div>
									</td>
								</tr>
								
								<?if($arPropsInfo = CStatic::GetPropsByCompare($arElement["IBLOCK_ID"], $arElement["IBLOCK_SECTION_ID"] )):?>
								<?
								//pre($arPropsInfo);
								?>
									<tr class="comparison__diagram">
										<td class="diagram">
											<div class="diagram__title">Лучшие характеристики и качествa</div>
											<div class="diagram__items">
												<div class="diagram__sidebar">
													<div class="diagram__tabs js-diagram__param1 active">Стоимость товара</div>
													<?foreach($arPropsInfo["CODE"] as $k=>$code):?>
														<div class="diagram__tabs js-diagram__param<?=($k+2)?>"><?=$arPropsInfo["NAME"][$k]?></div>
													<?endforeach;?>
													
												</div>
												<div class="diagram__slider">
													<div class="swiper-wrapper">
													
														<?foreach($arResult["ITEMS"] as $arElement):?>
														<?
														$dd_param = ' data-param1='.$arElement["MIN_PRICE"]["DISCOUNT_VALUE"];
	
														$k2 = 2;
														foreach($arPropsInfo["CODE"] as $k=>$code) {
															$arProp = $arElement["PROPERTIES"][$code];
															$dd_param.= ' data-param'.$k2.'='.(int)$arProp["VALUE"];
															$k2++;	
														}


															/*
														foreach($arElement["PROPERTIES"] as $code=>$arProp) {		
															if(!in_array($code, $arPropsInfo["CODE"])) continue;
															$dd_param.= ' data-param'.$k2.'='.(int)$arProp["VALUE"];	

															$k2++;	
														}*/
														?>
														<div class="swiper-slide">
															<div class="schedule">
																<div class="diagram__text"></div>
															</div>
															<div class="diagram__item">
																<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" <?=$dd_param?> class="diagram__pic"></a>
																<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="diagram__name">Liebherr <?=$arElement["PROPERTIES"]["MODEL"]["VALUE"]?></a>
															</div>
														</div>
														<?endforeach;?>
														
														
													
													</div>
												</div>
												<div class="diagram__maxvalue"></div>

											</div>
											<div class="diagram__next swiper-button-next special-offers_next"></div>
											<div class="diagram__prev swiper-button-prev special-offers_prev"></div>
										</td>
									</tr>
								
								<?endif;?>
								
								
								
			<?
			$n = 0;
			foreach($PROPS as $code => $prop):?>
								<tr class="<? echo ($prop["IS_GROUP"]) ? 'characteristic__title js-characteristic__title':'characteristic__row';?> ">
									<td><?=$prop["NAME"]?></td>
								</tr>
			<?endforeach;?>


		</thead>
		<tbody class="swiper-container swiper-container-comparison js-swiper-comparison">
			<tr class="products-line swiper-wrapper">

						<?foreach($arResult["ITEMS"] as $arElement):?>
									<td class="swiper-slide">
										<div class="swiper__comparison">
											<a class="special-offers__pic" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">
												<img data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" class="special-offers__img lazyload" />
											</a>
											<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="special-offers__text"><?=$arElement["NAME"]?></a>
											<div class="special-offers__sale">
												
													<?if($arShow["PRICE"]):?>											
											
														<span class="special-offers__price">
															<?if(count($arElement["PRICES"]) > 1 && $arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]] && $arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]] ):?>	
															
																			<?															
																			$discountPercent = round(($arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]-$arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]);
																			?>
																			<?=number_format($arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> <i class="special-offers__rub">руб.</i>
																			<span class="price__absolute"><?=number_format($arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> руб.</span>																	
																		
															<?else:?>
																	
																			<?															
																			$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
																			?>																
																			<?=number_format($arElement["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '.', ' ')?> <i class="special-offers__rub">руб.</i>																
																			<?if($arElement["MIN_PRICE"]["DISCOUNT_VALUE"]!=$arElement["MIN_PRICE"]["VALUE"]):?>
																				<span class="price__absolute"><?=number_format($arElement["MIN_PRICE"]["VALUE"], 0, '.', ' ')?> руб.</span>	
																			<?endif;?>
															<?endif;?>												
														</span>	
														
													<?endif;?>
												
													<?if($arShow["BUY"]):?>
														<a href="" title="купить" class="js-add2basket special-offers__buy" data-id="<?=$arElement["ID"]?>">купить</a>
													<?endif;?>													
												
											</div>
										</div>
										<a href="" class="modal__close js-compare_remove active" data-id="<?=$arElement["ID"]?>" title="Закрыть">×</a>
									</td>
						<?endforeach;?>
						
			</tr>
			<?if($arPropsInfo):?><tr class="comparison__diagram"></tr><?endif;?>


			<?
			$n = 0;
			foreach($PROPS as $code => $prop):?>
			
								<tr class="<? echo ($prop["IS_GROUP"]) ? 'characteristic__title js-characteristic__title':'characteristic__row';?> swiper-wrapper">									
									
									<?foreach($arResult["ITEMS"] as $arElement):?>
<?
										if($prop["IS_GROUP"]) {$val = '';}
										else {
											$val = preg_replace('#<a[^>]*>(.*?)</a>#is', '$1', $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
											
														if(is_array($val))	$val = implode(", ", $val);											
														if($val == "Y") $val = "Есть";			
														if(empty($val))	$val = 'Нет';
										}
													
?>	
										
										<td class="swiper-block"><?=$val?></td>									
									<?endforeach?>
									
								</tr>
			
			<?
			$n++;
			endforeach;?>
		

		</tbody>
							<!-- If we need scrollbar -->
			<div class="swiper-pagination js-swiper-pagination"></div>
			<div class="swiper-button-prev js-swiper-prev  special-offers_prev"></div>
			<div class="swiper-button-next js-swiper-next special-offers_next"></div>
</table>

</div>



