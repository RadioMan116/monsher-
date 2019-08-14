<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult['ITEMS']):?>
		<div class="shipping-payment__items">
        <?foreach($arResult['ITEMS'] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>	
			<div class="shipping-payment__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<?if($arItem["PREVIEW_PICTURE"]):?><img data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" class="shipping-payment__pic lazyload" /><?endif?>
				<h3 class="shipping-payment__header"><?=$arItem["NAME"]?></h3>		
				<?=$arItem["PREVIEW_TEXT"]?>		
			</div>

		<?endforeach;?>
		</div>
<?endif;?>