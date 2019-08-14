<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["ITEMS"]):?>
				<div class="articles">
					<div class="container">
						<span class="articles__title">Статьи</span>
						<a href="/news/" title="Все Статьи" class="articles__link">Все Статьи</a>
						
						<div class="articles__items">
<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>		
	<div class="articles__item" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>" class="articles__pic" >
									<img data-src="<?=$arItem["IMG_1"]?>" data-srcset="<?=$arItem["IMG_2"]?> 2x,<?=$arItem["IMG_3"]?> 3x" class="articles__img lazyload" />
								</a>
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>" class="articles__text"><?=$arItem["NAME"]?></a>
								<span class="articles__date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></span>
								<span class="articles__info"><?=$arItem["PREVIEW_TEXT"]?></span>
	</div>	
	
<?endforeach;?>
		</div>	
		
		
	</div>	
</div>	

<?endif;?>	
