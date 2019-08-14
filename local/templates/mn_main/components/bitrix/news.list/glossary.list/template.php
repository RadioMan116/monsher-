<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$PAGE_URL = $arResult["LIST_PAGE_URL"];

//pre($arResult["SECTIONS"]);

$arBlock = getArIblock('jetair_content', false, 607);
?>



	 <!-- begin glossary-header-->
                        <div class="glossary-header">
							<?if($arBlock["PICTURE"]):?>
							<?
							$arBlock["PICTURE"] = CFile::GetPath($arBlock["PICTURE"]);
							?>
                            <div class="glossary-header__pic">
                                <img class="lazyload" data-src="<?=$arBlock["PICTURE"]?>" alt="Глоссарий" />
                            </div>
							<?endif;?>	
                            <div class="glossary-header__nav">
                                <ul class="glossary-header__nav-list">
                                    <li class="glossary-header__nav-item <?if(!$arParams["PARENT_SECTION_CODE"]):?>active<?endif;?>">
										<a href="<?=$PAGE_URL?>" title="Все">Все</a>
									</li>
									<?foreach($arResult["SECTIONS"] as $section):?>
									<li class="glossary-header__nav-item <?if($arParams["PARENT_SECTION_CODE"] == $section["CODE"]):?>active<?endif;?>">
										<a href="<?=$section["SECTION_PAGE_URL"]?>" title="<?=$section["NAME"]?>">
											<?=$section["NAME"]?>
										</a>
									</li>
									<?endforeach;?>                                   
                                </ul>
                            </div>
                        </div>
                        <!-- end glossary-header-->




<?if(count($arResult["ITEMS"]) > 0):?>
<div class="glossary-content " >
    <ul class="glossary-content__list">
<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	//pre($arItem["PROPERTIES"]["CODE"]);
	
	$tar =  parse_url($arItem["PROPERTIES"]["CODE"]["VALUE"]);	
	$arr = explode('/',$tar["path"]);	
	$video_code = '//www.youtube.com/watch?v='.end($arr);
	?>	
	<li class="glossary-content__item " id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                                    <div class="glossary-content__item-top">
                                        <div class="item__icon">
                                            <img class="lazyload" data-src="<?=$arItem["PICTURE"]?>" alt="<?=$arItem["NAME"]?>" />
                                        </div>
                                        <div class="item__title">
                                            <h3><?=$arItem["NAME"]?></h3>
											<?if($arItem["DISPLAY_PROPERTIES"]["DESC"]["DISPLAY_VALUE"]):?>
												<?=$arItem["DISPLAY_PROPERTIES"]["DESC"]["DISPLAY_VALUE"]?>
											<?endif;?>											
                                        </div>
                                    </div>
                                    <div class="glossary-content__item-bottom js-catalog-thumb">
										<?if($arItem["PREVIEW_PICTURE"]):?>
                                        <div class="glossary-content__pic">
                                            <img class="glossary-content__img lazyload" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" />
                                        </div>
										<?endif;?>
                                        <div class="glossary-content__text">
                                            <?=$arItem["PREVIEW_TEXT"]?>
											<?if($arItem["TOVS_LIST"]):?>											
											<div class="glossary-content__model">
												<?foreach($arItem["TOVS_LIST"] as $k2=>$arProduct):?>
												<?
												if($k2 > 4) break;
												
												if($k2):?>, <?endif?>
												<a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" title="<?=$arProduct["NAME"]?>" class="glossary-content__model_link"><?=$arProduct["PROPERTIES"]["MODEL"]["VALUE"]?></a>
												<?endforeach?>
											</div>
											<?endif;?>
                                        </div>
                                    </div>
    </li>			
<?endforeach;?>				
	</ul>
 </div>
 
 <?=$arResult["NAV_STRING"]?>	
 
<?endif;?>	








