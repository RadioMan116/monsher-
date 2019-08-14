<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


?>
<?if (!empty($arResult)):?>
<div class="footer__col">
	<nav class="menu-footer">
		<ul class="menu-footer__list">
			<li class="menu-footer__title js-menu-footer__title">Информация</li>
				<?
				$LAST_LEVEL = 1;
				foreach ($arResult as $k=>$arItem):?>
					<?if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;?>
						<?
						$dd_s = '';
						if($arItem["SELECTED"]) {$dd_s = 'active';}

						$CURRENT_LEVEL = $arItem["DEPTH_LEVEL"];

						$dd_cls = '';
						if($CURRENT_LEVEL > 1) $dd_cls = 'dropdown-';

						//pre($arItem);
						?>

						<li class="menu-footer__item">
							<a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>" class="menu-footer__link"><?=$arItem["TEXT"]?></a>
						</li>

				<?
				$LAST_LEVEL = $arItem["DEPTH_LEVEL"];

				endforeach?>
		</ul>
	</nav>
</div>		
<?endif;?>