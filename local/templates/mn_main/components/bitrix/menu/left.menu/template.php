<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


?>
<?if (!empty($arResult)):?>
		<ul class="work-link">
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

						<li class="work-link__item">
							<a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>" class="work-link__a"><?=$arItem["TEXT"]?></a>
						</li>

				<?
				$LAST_LEVEL = $arItem["DEPTH_LEVEL"];

				endforeach?>
		</ul>
<?endif;?>