<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}

?>

<a href="/favorites/" class="header__favorite"><span class="header__number js-GoodsInFavorites"><?=$arResult["TOV_COUNT_FAVORITE"]?></span></a>
<a href="/compare/" title="Перейти в сравнение" class="header__compare"><span class="header__number js-GoodsInCompare"><?=$arResult["TOV_COUNT_COMPARE"]?></span></a>
<a href="/cart/" title="Перейти в корзину" class="header__basket"><span class="header__number js-GoodsInBasket"><?=$arResult["TOV_COUNT_BASKET"]?></span></a>