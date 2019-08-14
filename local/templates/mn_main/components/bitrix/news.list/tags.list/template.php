<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arParams);


$page = $_SERVER["REDIRECT_URL"];

//pre(count($arResult["ITEMS"]));
?>
<?if($arResult["ITEMS"]):?>
	<div class="tags">
		<ul class="tags__list">
		
			<?if(!(in_array($arParams["BLOCK_ID"], array(35,36,37)) && !$arParams["SECTION_ID"] && !$arParams["TAG_PAGE"])):?>
			<li class="tags__item <?if($arParams["PAGE_SITE_CURRENT"] == $arParams["PAGE_SITE_BACK"]):?>active<?endif;?>" >
				<a href="<?=$arParams["PAGE_SITE_BACK"]?>" title="Все" class="tags__link">Все</a>
			</li>
			<?endif;?>
			
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
			<li class="tags__item" >
				<a href="<?=$url_all?>" title="Показать все" class="tags__link">Показать все</a>
			</li>
		
		<?endif;?>
	
		</ul>
	</div>
<?endif;?>

		