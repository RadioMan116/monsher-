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

$count_all = $arResult["NAV_RESULT"]->NavRecordCount;

?>

<?if($arResult["ITEMS"]):?>
				<div class="new-items special-offers">
					<div class="container">
						<div class="new-items__caption">
							<span class="special-offers__title new-items__title">Новинки</span>
							<span class="new-items__how"><i><?=$count_all?></i><?=declOfNum(count($arResult["ITEMS"]), array('новинка', 'новинки', 'новинок'))?></span>
						</div>

						<!-- Slider main container -->
						<div class="swiper-container swiper-container-three js-swiper-three">
							<!-- Additional required wrapper -->
							<div class="swiper-wrapper special-offers__wrapper">
	<?foreach($arResult["ITEMS"] as $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

	//pre($arElement["PROPERTIES"]["S_NEW"]);

	
	$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
	
	$COMPARE_LIST = $arParams["CATALOG_COMPARE_LIST"][$arElement["IBLOCK_ID"]]["ITEMS"];
	
	
	$arShow = CStatic::CheckViewParams($arElement);
	?>
	
	<div class="swiper-slide special-offers__slide new-items__slide js-ecom_product-item" data-id="<?=$arElement['ID']?>" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
									<a class="special-offers__pic" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">
										<img data-src="<?=$arElement["IMG_1"]?>" data-srcset="<?=$arElement["IMG_2"]?> 2x,<?=$arElement["IMG_3"]?> 3x" class="special-offers__img lazyload" />
									</a>
									<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="special-offers__text"><?=$arElement["NAME"]?></a>
									<div class="special-offers__sale">
									
										<?if($arShow["PRICE"]):?>
											<span class="special-offers__price"><?=number_format($arElement["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '.', ' ')?> <i class="special-offers__rub">руб.</i></span>
										<?endif;?>
											
										<?if($arShow["BUY"]):?>
											<a href="" title="купить" class="js-add2basket special-offers__buy" data-id="<?=$arElement["ID"]?>">купить</a>
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
									
									
									
									
									
	</div>	

	<?endforeach;?>
	
	</div>


							<!-- If we need navigation buttons -->

							<!-- If we need scrollbar -->
							<div class="swiper-scrollbar"></div>
						</div>
						<div class="swiper-pagination_3"></div>
						
						<a href="/liebherr/type-novelty/" title="Посмотреть все новинки" class="new-items__link">Посмотреть все новинки</a>
						
						<div class="swiper-button-prev new-items_prev"></div>
						<div class="swiper-button-next new-items_next"></div>
					</div>

				</div>
	
	
<?endif;?>




