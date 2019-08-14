<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>

<?if($arResult["ITEMS"]):?>
<div class="swiper-container-main js-swiper-main">
	<!-- Additional required wrapper -->
		<div class="swiper-wrapper">

		<?foreach($arResult["ITEMS"] as $i => $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

		
		IF(!$arItem["PREVIEW_PICTURE"]["SRC"]) $arItem["PREVIEW_PICTURE"]["SRC"] = $arItem["DETAIL_PICTURE"]["SRC"];
		IF(!$arItem["DETAIL_PICTURE"]["SRC"]) $arItem["DETAIL_PICTURE"]["SRC"] = $arItem["PREVIEW_PICTURE"]["SRC"];
		
		?>		
		
		
		<div class="swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
								<img class="lazyload" data-src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" />								
								
								<?if($arItem["PROPERTIES"]["VIEW_TEXT"]["VALUE"] == 'Y'):?>
								<div class="swiper__item">
									<div class="swiper__title">
										<?if($arItem["DISPLAY_PROPERTIES"]["TEXT_1"]["DISPLAY_VALUE"]):?><span class="span span__small"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT_1"]["DISPLAY_VALUE"]?></span><?endif?>
										<?if($arItem["DISPLAY_PROPERTIES"]["TEXT_2"]["DISPLAY_VALUE"]):?><span class="span span__big"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT_2"]["DISPLAY_VALUE"]?></span><?endif?>
										<?if($arItem["PROPERTIES"]["URL"]["VALUE"]):?>
											<a href="<?=$arItem["PROPERTIES"]["URL"]["VALUE"]?>" title="<?=$arItem["NAME"]?>" class="swiper__link">Посмотреть</a>
										<?endif?>
									</div>
								</div>
								<?endif?>
		</div>		

		<?endforeach;?>
		
		</div>
						<!-- If we need pagination -->
						<div class="swiper-pagination"></div>

						<!-- If we need navigation buttons -->
						<div class="swiper-button-prev swiper-button-main main_prev"></div>
						<div class="swiper-button-next swiper-button-main main_next"></div>

						<!-- If we need scrollbar -->
						<div class="swiper-scrollbar"></div>
</div>
<?endif;?>
