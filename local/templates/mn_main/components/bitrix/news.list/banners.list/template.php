<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<?if($arResult["ITEMS"]):?>
<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

	?>		
	<div class="services services_guarantee" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	
									<?if($arItem["PROPERTIES"]["LINK"]["VALUE"]):?>
											<a href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" class="services__link">
												<img class="services__bg lazyload" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" />
												<span class="services__content">
													<span class="services__title"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT_1"]["DISPLAY_VALUE"]?></span>
													<span class="services__text"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT_2"]["DISPLAY_VALUE"]?></span>
												</span>
											</a>
									<?else:?>											
											<div class="services__link">
												<img class="services__bg lazyload" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" />
												<span class="services__content">
													<span class="services__title"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT_1"]["DISPLAY_VALUE"]?></span>
													<span class="services__text"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT_2"]["DISPLAY_VALUE"]?></span>
												</span>
											</div>
									<?endif;?>	
	</div>	
	
<?endforeach;?>
<?endif;?>




