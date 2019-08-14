<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if($arResult["ITEMS"]):?>
<div class="practical-features js-practical-features">
							<div class="practical-features__title">
								Практичные функции <?=$arParams["BLOCK_TITLE"]?> LIEBHERR
							</div>
							<div class="swiper-pagination-practical-features"></div>
							<div class="swiper-button-prev practical-features__prev"></div>
							<div class="swiper-button-next practical-features__next"></div>
							<div class="swiper-container">
								<div class="swiper-wrapper">
<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	?>	
	<div class="swiper-slide" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
	
				<?if($arElement["PICTURE"]):?>
					<div class="practical-features__pic">
						<img class="lazyload" data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" />
					</div>
				<?endif;?>
										<div class="practical-features__info">
											<div class="practical-features__subtitle">
												<?=$arElement["NAME"]?>
											</div>
											<div class="practical-features__text">
												<?=$arElement["PREVIEW_TEXT"]?>
											</div>
											<a href="/glossary/" class="practical-features__link">Перейти в глоссарий</a>
										</div>
	</div>	
	
<?endforeach;?>
								</div>
							</div>
						</div>	
<?endif;?>	
