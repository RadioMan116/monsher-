<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if($arResult["ITEMS"]):?>

<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	?>
	
	<div class="certificate" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
                                    <a href="<?=$arElement["PICTURES"]["BIG"]?>" title="<?=$arElement["NAME"]?>" class="certificate__link" data-fancybox="group1">
                                        <img data-src="<?=$arElement["PICTURES"]["SMALL"]?>" alt="<?=$arElement["NAME"]?>" class="certificate__pic lazyload" />
                                    </a>
    </div>
	
<?endforeach;?>
	
<?endif;?>	
