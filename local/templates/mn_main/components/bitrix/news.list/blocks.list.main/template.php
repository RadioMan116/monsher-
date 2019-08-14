<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<?if($arResult["ITEMS"]):?>
<div class="gallery__tablet">
<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

	?>		
	<div class="gallery__el" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
								<a class="gallery__link" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" title="<?=$arItem["NAME"]?>">
									<img class="lazyload" data-src="<?=$arItem["PICTURE"]?>" alt="<?=$arItem["NAME"]?>" />
									<div class="gallery__parent">
										<span class="gallery__article"><?=$arItem["NAME"]?></span>
										<span class="gallery__text"><?=$arItem["PREVIEW_TEXT"]?></span>
									</div>
								</a>
	</div>
<?endforeach;?>
</div>	
<?endif;?>




