<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if(count($arResult["ITEMS"]) > 0):?>
<div class="all-video swiper-container js-all-video">
							<div class="all-video__title">
								Видео
							</div>
							<a href="/video/" class="all-video__more">
								Все видео
							</a>
							<div class="all-video__items swiper-wrapper">

<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	//pre($arItem["PROPERTIES"]["CODE"]);

	$tar =  parse_url($arItem["PROPERTIES"]["CODE"]["VALUE"]);
	$arr = explode('/',$tar["path"]);
	//$video_code = '//www.youtube.com/watch?v='.end($arr);
	$video_code = '//www.youtube.com/embed/'.end($arr);
	?>	
	<div class="all-video__item swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<a data-fancybox="gallery" data-type="iframe" title="<?=$arItem["NAME"]?>" href="<?=$video_code?>">										
										<iframe class="lazyload" width="100%" height="100%" data-src="<?=$video_code?>" frameborder="0" allowfullscreen=""></iframe>
										<div class="all-video__link"><?=$arItem["NAME"]?></div>
									</a>
	</div>	
	
<?endforeach;?>
			</div>
</div>
<?endif;?>