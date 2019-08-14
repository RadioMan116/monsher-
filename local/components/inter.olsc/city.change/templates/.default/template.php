<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}

?>
<div class="city-change js-city-change">
								<div class="city-change__title"><?=$arResult["ITEMS"][$_COOKIE["K_REGION"]]["NAME"]?></div>
								<div class="city-change__bullet"></div>
								<div class="city-change__popup">
									<?foreach($arResult["ITEMS"] as $arItem):?>
										<a class="city-change__link js-city_change" data-code="<?=$arItem["CODE"]?>" href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a>
									<?endforeach;?>
								</div>
</div>