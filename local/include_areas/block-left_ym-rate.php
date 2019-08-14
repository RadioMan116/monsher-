<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$arData = CStatic::getElement(CStatic::$DataId, 31);
?>

<div class="yandex">
								<div class="yandex__inner">
									<div class="yandex__logo">
										<img src="<?=CStatic::$pathV?>images/ylogo.png" alt="" class="yandex__img" />
									</div>
									<div class="yandex__ratings">
										<img src="<?=CStatic::$pathV?>images/5-stars.png" alt="" />
									</div>
									<a href="<?=$arData["PROPERTIES"]["YM_REVIEWS_ADD"]["VALUE"]?>" target="_blank" class="yandex__button">Оставить отзыв</a>
									<a href="<?=$arData["PROPERTIES"]["YM_REVIEWS_LIST"]["VALUE"]?>" target="_blank" class="yandex__button">Читать все отзывы</a>
								</div>
</div>
<? /*
<div class="yandex">
	<img src="<?=$arParams["TPL_PATH_FRONT"]?>images/yandex__rate.png" alt="" class="yandex__pic" />
	<a href="<?=$arData["PROPERTIES"]["YM_REVIEWS_ADD"]["VALUE"]?>" target="_blank" class="yandex__link yandex__link_review"></a>
	<a href="<?=$arData["PROPERTIES"]["YM_REVIEWS_ADD"]["VALUE"]?>" target="_blank" class="yandex__link yandex__link_form">оставить отзыв</a>
</div>
*/ ?>