<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>

<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

	if(!$arElement["DETAIL_PICTURE"]) continue;
	
	$dd_bg = '';
	//if($arElement["DETAIL_PICTURE"]) $dd_bg = 'style="background-image: url('.$arElement["DETAIL_PICTURE"]["SRC"].');"';
	if($arElement["DETAIL_PICTURE"]) $dd_bg = ' data-bg="url('.$arElement["DETAIL_PICTURE"]["SRC"].')" ';
	?>	
	
	<a id="<?=$this->GetEditAreaId($arElement['ID']);?>" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="category_banner" <?=$dd_bg?>"></a>
	
	
<?endforeach;?>
