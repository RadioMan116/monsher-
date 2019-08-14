<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if(count($arResult["ITEMS"]) > 0):?>

<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	
	$dd = 'main';
	if($_SESSION["SEO_COUNTER"] == '1') {$dd = 'help';}
			
	$tar =  parse_url($arItem["PROPERTIES"]["CODE"]["VALUE"]);	
	$arr = explode('/',$tar["path"]);	
	$video_code = '//www.youtube.com/watch?v='.end($arr);		
			
	//pre($arItem["PROPERTIES"]["CODE"]);
	?>
	
	<div class="about-<?=$dd?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<div class="about-<?=$dd?>__row">
							<div class="about-<?=$dd?>__desc">
								<div class="about-<?=$dd?>__title">
									<?=$arItem["NAME"]?>
								</div>
								<div class="about-<?=$dd?>__text">									
									<?=$arItem["PREVIEW_TEXT"]?>									
								</div>
							</div>
							<div class="about-<?=$dd?>__photo">
								<img data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" class="about-<?=$dd?>__pic lazyload" />
								<a href="<?=$video_code?>" data-fancybox="group1" class="about-main__link iframe"></a>
							</div>
						</div>
	</div>
	
	
<?
		if($_SESSION["SEO_COUNTER"] == '1') $_SESSION["SEO_COUNTER"] = 2;
		else $_SESSION["SEO_COUNTER"] = 1;
?>
	
<?endforeach;?>

	
<?endif;?>
		