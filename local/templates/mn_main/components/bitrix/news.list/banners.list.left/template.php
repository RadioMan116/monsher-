<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<?if($arResult["ITEMS"]):?>
<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

	?>		
	<?if($arItem["PROPERTIES"]["LINK"]["VALUE"]):?>
		<a href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" title="<?=$arItem["NAME"]?>" class="marking" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	<?else:?>
		<div class="marking" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	<?endif;?>	
	
								<div class="marking__pic">
									<img class="lazyload" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" />
									<div class="marking__desc"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT_1"]["DISPLAY_VALUE"]?></div>
								</div>
								<div class="marking__item">
									<div class="marking__title"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT_2"]["DISPLAY_VALUE"]?></div>
									<div class="marking__subtitle"><?=$arItem["DISPLAY_PROPERTIES"]["TEXT_3"]["DISPLAY_VALUE"]?></div>
								</div>
								
	<?if($arItem["PROPERTIES"]["LINK"]["VALUE"]):?>
		</a>
	<?else:?>
		</div>
	<?endif;?>								
								
								
	
	
<?endforeach;?>
<?endif;?>




