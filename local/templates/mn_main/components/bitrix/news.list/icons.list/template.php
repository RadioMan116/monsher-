<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if($arResult["ITEMS"]):?>
  <div class="detail-benefits">
                        <div class="detail-benefits__inner">
                            <div class="detail-benefits__title detail-desc__title">Преимущества</div>
                            <div class="detail-benefits__row">
<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	?>
	
	<?if($arElement["PICTURE"]):?>
	
	<?
	
	if($_GET["mode"] == 'test') {	
		//pre($arElement["PROPERTIES"]["PROPS_ID"]);
	}

	?>
	 <div data-id="<?=$arElement['ID']?>" class="detail-benefits__col" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
                                    <div class="detail-benefits__item">
                                        <img data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" class="detail-benefits__pic lazyload" width="62" height="62" data-tooltip="tooltip" data-offset="10" data-pos="bottom" data-classname="tooltip-popup_benefits" data-text="<strong><?=$arElement["NAME"]?></strong><?=$arElement["PREVIEW_TEXT"]?>">
                                    </div>
     </div>
	
	
	<?endif;?>
<?endforeach;?>
		  </div>
    </div>
</div>
 <!-- end detail-benefits -->
	
<?endif;?>	
