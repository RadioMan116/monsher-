<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if($arResult["ITEMS"]):?>
 <div class="catalog__prop">
	<h6>Ключевые особенности</h6>
<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	?>
	
	<?if($arElement["ICON"]):?>
	
	<div class="link-pop-glossary js-link-pop-glossary" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
										<img data-src="<?=$arElement["ICON"]?>" class="icons-prop-item lazyload" title="<?=strip_tags($arElement["PREVIEW_TEXT"])?>" />
										<div class="popup-prop">
											<?if($arElement["PICTURE"]):?>
												<img data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" class="popup-prop__img lazyload" />
											<?endif;?>
											
											<div class="popup-prop__title"><?=$arElement["NAME"]?></div>
											<div class="popup-prop__text"></div>
											
											<a href="/glossary/" title="Перейти в глоссарий" class="popup-prop__link">Перейти в глоссарий</a>
										</div>
	</div>
	
	<?endif;?>
	
<?endforeach;?>
</div>
 <!-- end detail-benefits -->
	
<?endif;?>	
