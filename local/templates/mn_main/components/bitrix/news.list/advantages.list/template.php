<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
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
<!-- begin out-benefits -->


<?
?>

					<div class="out-benefits">
						<div class="out-benefits__inner">
							<div class="out-benefits__row">

		<?foreach($arResult["ITEMS"] as $k=>$arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>

								<div class="out-benefits__col" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<div class="out-benefits__item">

										<?if($k == '0'):?>
											<div class="out-benefits__title">
												<?=$arItem["DISPLAY_PROPERTIES"]["TITLE"]["DISPLAY_VALUE"]?>
											</div>
											<div class="out-benefits__text">
												<?=$arItem["DISPLAY_PROPERTIES"]["TEXT"]["DISPLAY_VALUE"]?>
											</div>
										<?else:?>

											<div class="out-benefits__photo">
											
												<?if($arItem["PICTURE"]):?>
													<a class="fancybox certificate" href="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" title="<?=$arItem["NAME"]?>" data-fancybox="group1" data-width="638" data-height="790">
														<img data-src="<?=$arItem["PICTURE"]?>" alt="<?=$arItem["NAME"]?>" class="certificate__pic lazyload" />
													</a>												
												<?else:?>
													<div class="out-benefits__round">
														<?//=$arraySVG[$k - 1]?>
														<?=$arItem["PREVIEW_TEXT"]?>
													</div>
												
												<?endif?>
											
											</div>
											<div class="out-benefits__desc">
												<div class="out-benefits__name">
													<?=$arItem["DISPLAY_PROPERTIES"]["TITLE"]["DISPLAY_VALUE"]?>
												</div>
												<div class="out-benefits__text">
													<?=$arItem["DISPLAY_PROPERTIES"]["TEXT"]["DISPLAY_VALUE"]?>
												</div>
											</div>


										<?endif;?>

									</div>
								</div>

		<?endforeach;?>

						</div>
					</div>
				</div>
		<!-- end service-address -->
<?endif;?>

