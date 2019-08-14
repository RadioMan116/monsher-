<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}
//pre($arResult);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
?>
	
<!-- begin menu-content -->

	
	<?if($arResult["ITEMS"]):?>	
<div class="catalog-all">
                        <!-- begin catalog -->
                    <div class="catalog js-catalog" data-count="2,4">
                        <div class="catalog__inner">
                            <ul class="catalog__list">
		<?foreach($arResult["ITEMS"] as $arItem):?>	
				
				<li class="catalog__item catalog__item_search">

                                    <!-- begin catalog-thumb -->
                                    <div class="catalog-all-thumb js-catalog-thumb">
                                        <div class="catalog-all-thumb__inner">
											<?/*if($arItem["PICTURE_V"]):?>
                                            <a href="<?=$arItem["LIST_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>" class="catalog-all-thumb__photo">
                                                <img data-src="<?=$arItem["ICON"]?>" alt="<?=$arItem["NAME"]?>" class="catalog-all-thumb__pic lazyload" />
                                            </a>
											<?endif;*/?>                                            <div class="catalog-thumb__content">
                                                <div class="catalog-all-thumb__name js-catalog-thumb__name">
                                                    <a href="<?=$arItem["LIST_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>" class="catalog-all-thumb__namelink">
                                                        <?=$arItem["NAME"]?>
                                                    </a>
                                                </div>
                                                <div class="catalog-all-thumb__footer js-catalog-thumb__footer">
												
												<?if($arItem["SECTIONS"]):?>	
													<div class="catalog-all-thumb__links">
													<?foreach($arItem["SECTIONS"] as $arItem2):?>														
														
														<a href="<?=$arItem2["SECTION_PAGE_URL"]?>" title="<?=$arItem2["NAME"]?>" class="catalog-all-thumb__link"><?=$arItem2["NAME"]?></a>														
																		
													<?endforeach;?>
													</div>
												<?endif;?>												
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end catalog-thumb -->

                </li>
		<?endforeach;?>
							</ul>
						</div>
					</div>
</div>
	<?endif;?>		
		
	
<!-- end menu-content -->