<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}
//pre($arResult);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
?>
	
	<?if($arResult["ITEMS"]):?>	
	
		<?foreach($arResult["ITEMS"] as $k=>$arItem):?>	
			<?
			
			//pre($arItem["NAME"]);
			
			?>	
			
			<nav class="dropdown-menu">
				<span class="dropdown-menu__title js-dropdown-menu__title"><?=$arItem["NAME"]?></span>	
				<div class="dropdown-menu__parent">
				<ul class="dropdown-menu__list">
					<?foreach($arItem["ITEMS"] as $arBlock):?>						
					<li class="dropdown-menu__item">
						<a class="dropdown-menu__link" href="<?=$arBlock["LIST_PAGE_URL"]?>" title="<?=$arBlock["NAME"]?>"><?=$arBlock["NAME"]?></a>
						
						<?if($arBlock["SECTIONS"]):?>
						<ul class="sub-menu__list">
							<?foreach($arBlock["SECTIONS"] as $arSection):?>
											<li class="sub-menu__item">∙&nbsp;<a class="sub-menu__link" title="<?=$arSection["NAME"]?>" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a></li>
							<?endforeach;?>
						</ul>
						<?endif;?>						
						
					</li>
					<?endforeach;?>
				</ul>
				</div>
			</nav>
	
		<?endforeach;;?>
		
		<nav class="dropdown-menu">
			<span class="dropdown-menu__title last">
				<a href="/catalog/type-nov/" title="Новинки">Новинки</a>
			</span>
		</nav>
		<nav class="dropdown-menu">
			<span class="dropdown-menu__title last">
				<a href="/guarantee/#services-list" title="Сервис">Сервис</a>
			</span>
		</nav>
		
	<?endif;?>		
<!-- end menu-content -->