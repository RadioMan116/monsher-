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

				<div class="text-default__title">Адреса специализированных сервис-центров</div>
			</div>
			<!-- end text-default -->
			
			
				<!-- begin service-address  -->
					<div class="garants-map">
						<div class="garants-map__row">
							<div class="garants-map__map" id="js-garants-map__map" data-center="55.656905, 37.624337"></div>
							<div class="garants-map__desc">
								
								<div class="baron baron__root baron__clipper">
									<div class="baron__scroller">
										
										<div class="garants-map__section">
											<div class="row">

	<?foreach($arResult["ITEMS"] as $i=>$arItem):?>
<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));		
?>

				<div class="col-xs-24 col-sm-12 col-lg-24" id="<?=$this->GetEditAreaId($arElement['ID']);?>">

													<div class="garants-map__field js-garants-map__field" data-coords="<?=$arItem["PROPERTIES"]["COORD_XY"]["VALUE"]?>">
														<div class="garants-map__item">
															<div class="garants-map__text">
																<strong><?=$arItem["NAME"]?></strong>
																<?=$arItem["DISPLAY_PROPERTIES"]["ADDR"]["DISPLAY_VALUE"]?><br>
																<?=implode(', ',$arItem["PROPERTIES"]["PHONES"]["VALUE"])?>
															</div>
															<a href="//maps.yandex.ru/?text=<?=$arItem["DISPLAY_PROPERTIES"]["ADDR"]["DISPLAY_VALUE"]?>&sll=<?=$arItem["PROPERTIES"]["COORD_XY"]["VALUE"]?>" target="_blank" class="garants-map__link">Показать на карте</a>
														</div>
													</div>

				</div>

					
					
	<?endforeach;?>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
		<!-- end service-address  -->


