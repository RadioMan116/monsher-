<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}
//pre($arResult);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
?>
	
	<?if($arResult["ITEMS"]):?>	
	<div class="footer__col">
		<?foreach($arResult["ITEMS"] as $k=>$arItem):?>	
			<?
			
			//pre($arItem["NAME"]);
			
			?>	
			
			<?if($k == '1'):?>
			</div><div class="footer__col">	
			<?endif;?>
			
			
			<nav class="menu-footer">
				<ul class="menu-footer__list">		
					<li class="menu-footer__title js-menu-footer__title"><?=$arItem["NAME"]?></li>			
			
					<?foreach($arItem["ITEMS"] as $arBlock):?>	
					<li class="menu-footer__item">
						<a class="menu-footer__link" href="<?=$arBlock["LIST_PAGE_URL"]?>" title="<?=$arBlock["NAME"]?>"><?=$arBlock["NAME"]?></a>
					</li>
					<?endforeach;?>
				</ul>
			</nav>
	
		<?endforeach;;?>
	</div>	
	<?endif;?>		
<!-- end menu-content -->