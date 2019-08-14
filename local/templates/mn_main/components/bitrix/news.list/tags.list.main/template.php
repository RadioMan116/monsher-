<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arParams);


$page = $_SERVER["REDIRECT_URL"];


?>
<?if($arResult["ITEMS"]):?>
	<div class="tags main-tags">
					<div class="tags__title">Популярные подборки</div>
					<div class="tags__container">
						<ul class="tags__list">
		
	<?foreach($arResult["ITEMS"] as $i => $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

		$title = $arItem["PROPERTIES"]["TAG_TITLE"]["VALUE"];
		
		
		if(!$title) $title = $arItem["PROPERTIES"]["TITLE"]["VALUE"];
		?>		
		<li class="tags__item <?if($arParams["PAGE_SITE_CURRENT"] == $arItem["NAME"]):?>active<?endif;?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<a href="<?=$arItem["NAME"]?>" title="<?=$title?>" class="tags__link"><?=$title?></a>
		</li>		
	<?endforeach;?>
	
		<?if(count($arResult["ITEMS"]) < $arResult["NAV_RESULT"]->NavRecordCount):?>
		<?
		$url_all = str_replace('/catalog/','/tags/',$arParams["PAGE_SITE"]);
		?>			
		
		<?endif;?>
	
	</ul>
					</div>

					<a class="tags__all" title="Все теги" href="/tags/holodilniki/">Все теги</a>

				</div>
	
	
<?endif;?>

		