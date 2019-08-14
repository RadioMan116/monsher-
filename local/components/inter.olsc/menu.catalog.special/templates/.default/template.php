<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}
//pre($arResult);
?>
<?if($arResult["MENU"]):?>

	<div class="catalog-banner">
	<?foreach($arResult["MENU"] as $arMenu):?>
		<a href="<?=$arMenu["LINK"]?>" title="<?=$arMenu["TITLE"]?>" class="catalog-banner__link"><?=$arMenu["TITLE"]?></a>
	<?endforeach;?>
	</div>
	
<?endif;?>
