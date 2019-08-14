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

<div class="remember">

	<div class="basket-form__title"><?=$arParams["ADD_TITLE"]?></div>

		<div class="scroll-view scroll-view_catalog">
			<div class="scroll-view__section">
				<div class="scroll-view__list js-scroll-view__list">

	<?foreach($arResult["ITEMS"] as $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
	
	//pre($arElement["CATALOG_PRICE_1"]);
	
	
	$arShow = CStatic::CheckViewParams($arElement);
	
	?>	
	<div class="scroll-view__item" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
										<div class="detail-items">
										
											<!-- begin label -->
											<?if($arShow["LABELS"]):?>
															<div class="label">
																<?if($arElement["LABELS"]["LABEL_FREE_CONNECTION"]):?>
																	<div class="label__item"><img data-src="/tpl/images/label_connection.svg" alt="Бесплатное подключение" class="label__pic lazyload" width="192" height="31" /></div>
																<?endif;?>
																<?if($arElement["LABELS"]["LABEL_FREE_DELIVERY"]):?>
																	<div class="label__item"><img data-src="/tpl/images/label_delivery.svg" alt="Бесплатная доставка" class="label__pic lazyload" width="172" height="31" /></div>
																<?endif;?>
																<?if($arElement["LABELS"]["S_NEW"]):?>
																	<div class="label__item"><img data-src="/tpl/images/new.png" alt="Новинка" class="label__pic lazyload" /></div>
																<?endif;?>
																<?if($arElement["LABELS"]["S_HIT"]):?>
																	<div class="label__item"><img data-src="/tpl/images/hit.png" alt="Хит" class="label__pic lazyload" /></div>
																<?endif;?>
															</div>
															<!-- end label -->
											<?endif;?>
											
											
											<div class="detail-items__photo">
											
												<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="detail-items__cell">
													<img data-src="<?=$arElement["PICTURE"]["SRC"]?>"
														 alt="<?=$arElement["NAME"]?>"
														 title="<?=$arElement["NAME"]?>"
														 class="detail-items__pic lazyload" />
												</a>

											</div>
											<?
														$dd_exist = $arElement["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"];
														if($arShow["PRICE_MESS"]) $dd_exist = $arShow["PRICE_MESS"];
											?>
														
											<div class="detail-items__stock <?=CStatic::$arExist[$arElement["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE_XML_ID"]]?>"><span><?=$dd_exist?></span></div>
											<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="detail-items__name"><?=$arElement["NAME"]?></a>
											<div class="detail-items__footer">

												<div class="detail-items__summ">
												
												
												<?if($arShow["PRICE"]):?>												
												
													<?if(count($arElement["PRICES"]) > 1 && $arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]] && $arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]] ):?>	
															
																<?															
																$discountPercent = round(($arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]-$arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]);
																?>
																<div class="detail-items__old-price"><?=number_format($arElement["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> руб.</div>
																<div class="detail-items__price detail-items__price_color"><?=number_format($arElement["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> руб.</div>
																
													<?else:?>
														
																<?															
																$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
																?>	

																<?if($arElement["MIN_PRICE"]["DISCOUNT_VALUE"]!=$arElement["MIN_PRICE"]["VALUE"]):?>
																	<div class="detail-items__old-price"><?=number_format($arElement["MIN_PRICE"]["VALUE"], 0, '.', ' ')?> руб.</div>	
																<?endif;?>
																<div class="detail-items__price <?if($arElement["MIN_PRICE"]["DISCOUNT_VALUE"]!=$arElement["MIN_PRICE"]["VALUE"]):?>detail-items__price_color<?endif;?>"><?=number_format($arElement["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '.', ' ')?> руб.</div>
																															
													<?endif;?>														
													
												<?endif;?>
												
												
												
												
												</div>
												
												<?if($arShow["BUY"]):?>
													<?if($arParams["CAN_BUY"] == 'Y'):?>
														<div class="detail-items__button">
															<a href="#" class="detail-items__buy js-add2basket" data-id="<?=$arElement["ID"]?>">В корзину</a>
														</div>
													<?endif;?>												
												<?endif;?>	

		<?$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_product.php',
			 Array("PRODUCT" => $arElement),
			 Array("MODE"=>"php")
		 )?>

											</div>
										</div>
									</div>
	
	
	<?endforeach;?>
					
					</div>
				</div>
	</div>
</div>
<?endif;?>




