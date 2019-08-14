<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}
//pre($arResult);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$arIcons = array(
	355 => 'duh',
	357 => 'hoods',
	358 => 'oven',
	359 => 'microwaves',
	360 => 'double-boiler',
	361 => 'dishwasher',
	362 => 'refrige',
	363 => 'heaters',
	364 => 'washing',
	365 => 'coffee',
	366 => 'accessories',

);

?>
	
<div class="gallery-menu">
    <div class="gallery-menu_inner">                                    

                                        <div class="gallery-menu-horiz menu-horiz-sub">
                                            <ul class="gallery-menu-horiz__list menu-horiz-sub__list">
											
	<?if($arResult["ITEMS"]):?>	
	
		<?foreach($arResult["ITEMS"] as $k=>$arItem):?>			
				

				<li class="gallery-menu-horiz__item menu-horiz-sub__item">
                     <a href="<?=$arItem["LIST_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>" class="gallery-menu-horiz-sub__link menu-horiz-sub__link menu-horiz-sub__<?=$arIcons[$arItem["ID"]]?>"><?=$arItem["NAME"]?></a>
					 
					 <?if($arItem["SECTIONS"]):
								
								//pre($arItem["SECTIONS"]);
								
								
								?>	
									<div class="gallery-dropdown-menu__sub dropdown-menu__sub">
                                        <ul class="gallery-dropdown-menu__list dropdown-menu__list">
									<?foreach($arItem["SECTIONS"] as $arItem2):?>	
									
										<li class="gallery-dropdown-menu__item dropdown-menu__item <?if($arItem2["CODE"] == $request->offsetGet("SECTION_CODE")):?>active<?endif;?>">
											<a href="<?=$arItem2["SECTION_PAGE_URL"]?>" title="<?=$arItem2["NAME"]?>" class="dropdown-menu__link"><?=$arItem2["NAME"]?></a>
										</li>
														
									<?
									$k++;
									endforeach;?>
										</ul>
									</div>
								<?
								
								endif;?>
					 
                </li>
	
		<?endforeach;;?>
		
	<?endif;?>		
		
		
		
		
		
			</ul>
		</div>
		
	</div>
</div>
	
