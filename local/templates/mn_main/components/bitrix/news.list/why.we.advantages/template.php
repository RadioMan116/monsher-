<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>




<?if($arResult["ITEMS"]):?>
		<div class="insikate-advantages content-fluid">
             <div class="insikate-advantages__row">      
                <div class="insikate-menu">
						 <div class="insikate-menu__inner" >
									 <img data-src="<?=$arResult["BLOCK"]["PICTURE"]?>" alt="" class="insikate-menu__pic lazyload" />
								
<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	
	
						$left = (int)$arElement["PROPERTIES"]["LEFT"]["VALUE"];
						$top = (int)$arElement["PROPERTIES"]["TOP"]["VALUE"];
						
						$dd_pos = 'style="left: '.$left.'%; top: '.$top.'%;"';
						
						
	
	?>	
	
        <a <?=$dd_pos?> class="insikate-menu__link js-tabs-menu__link" href="#js-tabs-<?=$arElement["ID"]?>" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
            <?=$arElement["NAME"]?>
        </a>
<?endforeach;?>
						</div>
				</div>							
				<div class="tabs insikate-tabs">
<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	
	
	?>
<div class="tabs__inner js-tabs__inner" id="js-tabs-<?=$arElement["ID"]?>">
                                        <div class="insikate__inner">
                                            <div class="insikate__inner_text">
                                                <h3 class="insikate__inner_title">
                                                    <?=$arElement["NAME"]?>
                                                </h3>
                                                <?=$arElement["PREVIEW_TEXT"]?>
												
												<?if($arElement["TOVS_LIST"]):?>
                                                <h4>
                                                    Модели с данной технологией:
                                                </h4>
                                                <p>
													<?foreach($arElement["TOVS_LIST"] as $k=>$arProduct):?>
														<?if($k):?>, <?endif;?>
														<a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" title="<?=$arProduct["NAME"]?>"><?=$arProduct["PROPERTIES"]["MODEL"]["VALUE"]?></a>
													<?endforeach;?>													
                                                </p>
												<?endif;?>
                                            </div>
                                            <div class="insikate__inner_photo">
												<?if($arElement["PICTURE"]):?>
													<img class="lazyload" data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" />
												<?endif;?>
                                            </div>
                                        </div>
 </div>
<?endforeach;?>		

				  <div class="insikate-navigation">
                                        <a href="/" class="prev js-insikate-navigation__arrow" data-trigger="prev">
                                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                                        </a>
                                        <a href="/" class="next js-insikate-navigation__arrow" data-trigger="next">
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </a>
                  </div>

					
				</div>				
			</div>
		</div>	
<?endif;?>	
