<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["ITEMS"]):?>

<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	 <div class="catalog-seo" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
                    <div class="catalog-seo__inner">
                        <picture class="catalog-seo__pic catalog-seo__pic_animate">
                            <source class="lazyload" media="(max-width: 991px)" data-srcset="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>">
                            <img class="lazyload" data-src="<?=$arItem["DETAIL_PICTURE"]["SRC"];?>" alt="<?=$arParams["P_TITLE"]?>" />
                        </picture>
                        <div class="catalog-seo__desc">
                            <h1 class="catalog-seo__title">
                                <?=$arParams["P_TITLE"]?>
                            </h1>
                            <div class="catalog-seo__text">
                                <? echo $arItem["PREVIEW_TEXT"] ? $arItem["PREVIEW_TEXT"] : html_entity_decode($arParams["P_DESC"])?>
                            </div>
                        </div>
                    </div>
   </div>
<?endforeach;?>

<?else:?>
	
	
	<div class="catalog-seo">
	
						<div class="catalog-seo__inner">						
						
							 <picture class="catalog-seo__pic">
								<source class="lazyload" media="(max-width: 991px)" data-srcset="<?=$arParams["P_PIC"]?>" />
								<img class="lazyload" data-src="<?=$arParams["P_PIC"]?>" alt="<?=$arParams["P_TITLE"]?>" />
							</picture>

							<div class="catalog-seo__desc">
								<h1 class="catalog-seo__title">
									<?=$arParams["P_TITLE"]?>
								</h1>
								<div class="catalog-seo__text">
									<?=html_entity_decode($arParams["P_DESC"])?>
								</div>
							</div>
							
						</div>
	</div>
	
	<?endif;?>
