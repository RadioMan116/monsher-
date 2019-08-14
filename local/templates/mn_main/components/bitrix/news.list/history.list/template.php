<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if($arResult["ITEMS"]):?>
	<div class="history__menu">
        <div class="content-fluid">
            <ul class="history__menu_list">
<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));		
	?>		
	<li class="history__menu_item" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
        <a class="history__menu_link js-tabs-menu__link" href="#js-tabs-<?=$arElement["ID"]?>">
             <?=$arElement["NAME"]?>
        </a>
    </li>	
<?endforeach;?>
			</ul>
		</div>
	</div>							
	<div class="history__tabs">
<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$title = $arElement["NAME"];
	if($arElement["PROPERTIES"]["TITLE_2"]["VALUE"]) $title = $arElement["PROPERTIES"]["TITLE_2"]["VALUE"];
	?>
	<div class="history__tabs_inner js-tabs__inner" id="js-tabs-<?=$arElement['ID'];?>">
                            <div class="history__tabs_info">
                                <div class="content-fluid">
									
                                    <div class="history__tabs_info-desc">	
										<?if($arElement["PREVIEW_PICTURE"]):?>
										<div class="history__tabs_info-pic">
											<img data-src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arElement["NAME"]?>" class="history__tabs_info-img lazyload" />
										</div>
										<?endif;?>
									
                                        <div class="history__tabs_info-desc_title">
                                           <?=$title?>
                                        </div>
                                        <div class="history__tabs_info-desc_text">
                                           <?=$arElement["PREVIEW_TEXT"]?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="history__tabs_desc">
                                <div class="content-fluid">
                                    <div class="history__tabs_text">
										<?if($arElement["PROPERTIES"]["TITLE_2"]["VALUE"]):?>
                                        <div class="history__tabs_text-title">
                                            <?=$arElement["PROPERTIES"]["TITLE_2"]["VALUE"]?>
                                        </div>
										<?endif;?>
                                        <?=$arElement["DETAIL_TEXT"]?>
                                    </div>
									<?if($arElement["DETAIL_PICTURE"]):?>
                                    <div class="history__tabs_pic">
                                        <img data-src="<?=$arElement["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arElement["NAME"]?>" class="history__tabs_img lazyload" />
                                    </div>
									<?endif;?>
                                </div>
                            </div>
     </div>	
<?endforeach;?>						
	</div>	
<?endif;?>	
