<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if($arResult["ITEMS"]):?>
<div class="advantages js-advantages">
							<div class="swiper-pagination-advantages"></div>
							<div class="swiper-button-prev advantages__prev"></div>
							<div class="swiper-button-next advantages__next"></div>
							<div class="advantages__title">
								Преимущества <?=$arParams["BLOCK_TITLE"]?> LIEBHERR
							</div>
							<div class="advantages__container swiper-container">
								<div class="advantages__wrapper swiper-wrapper">
<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	?>	
	<div class="advantages__slide swiper-slide" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
										
				<?if($arElement["PICTURE"]):?>					
					<img class="lazyload" data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" />
				<?endif;?>
										
										<div class="advantages__item">
											<div class="advantages__subtitle">
												<?=$arElement["NAME"]?>
											</div>
											<a href="/glossary/" title="Перейти в глоссарий" class="advantages__link">
												Перейти в глоссарий
											</a>
										</div>
	</div>
	
	
<?endforeach;?>
								</div>
							</div>
						</div>
<?endif;?>	
