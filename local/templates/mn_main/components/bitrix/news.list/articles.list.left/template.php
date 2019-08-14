<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


?>



<?if($arResult["ITEMS"]):?>
<div class="news-side">
	<div class="news-side__title">Новости</div>
	<div class="news-side__section">

<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	
	$title_1 = $title_2 = $arItem["NAME"];
	if(strip_tags($arItem["DISPLAY_PROPERTIES"]["TITLE_1"]["DISPLAY_VALUE"])!='') $title_1 = $arItem["DISPLAY_PROPERTIES"]["TITLE_1"]["DISPLAY_VALUE"];
	if(strip_tags($arItem["DISPLAY_PROPERTIES"]["TITLE_2"]["DISPLAY_VALUE"])!='') $title_2 = $arItem["DISPLAY_PROPERTIES"]["TITLE_2"]["DISPLAY_VALUE"];
	
	?>	
	
	<div class="news-side__item" id="<? echo $this->GetEditAreaId($arItem['ID']); ?>">
										<div class="news-side__photo">
											<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>">
												<img data-src="<?=$arItem["PICTURE"]?>" alt="<?=$arItem["NAME"]?>" class="news-side__pic lazyload" />
											</a>
										</div>
										<div class="news-side__content">
											<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>" class="news-side__name"><?=$arItem["NAME"]?></a>
											<div class="news-side__text">
												<?=$arItem["PREVIEW_TEXT"]?>
											</div>
										</div>
	</div>
	

	
<?endforeach;?>
	</div>
</div>
<?endif;?>