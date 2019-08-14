<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$RATING_INFO = CStatic::GetReviewsRating(false, false);

$arFilter = array(
	"ACTIVE" => "Y",
	"IBLOCK_ID" => CStatic::$catalogIdBlock,
	">CATALOG_PRICE_".CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]] => 0,
);
$arPrices = CStatic::GetElementsPrice($arFilter);

$arData = CStatic::getElement(CStatic::$DataIdByRegion[$_COOKIE["K_REGION"]], 31);

$phone_1 = $arData["PROPERTIES"]["PHONE_1"]["VALUE"];
	$phone_1_f = preg_replace("([^0-9])", "", $phone_1);
	
	$phone_2 = $arData["PROPERTIES"]["PHONE_2"]["VALUE"];
	$phone_2_f = preg_replace("([^0-9])", "", $phone_2);
?>
<div itemscope itemtype="http://schema.org/Store">
	<div class="hide">
		<a href="https://<?=$_SERVER["SERVER_NAME"]?>" itemprop="url"><span itemprop="name">Специализированный магазин Liebherr</span></a>
		<meta itemprop="brand" content="De Dietrich">
		<img itemprop="logo" src="https://<?=$_SERVER["SERVER_NAME"]?>/mockup/templates/main/build/images/liebherr.png" alt="Liebherr" />
		<img itemprop="image" src="https://<?=$_SERVER["SERVER_NAME"]?>/mockup/templates/main/build/images/liebherr.png" alt="Liebherr" />
		<meta itemprop="legalName" content="ООО «НТК Трейд»" />
		<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
			<meta itemprop="streetAddress" content="проспект Маршала Жукова, дом 59" />
			<meta itemprop="postalCode" content="123103" />
			<meta itemprop="addressLocality" content="Москва" />
		</div>
		<meta itemprop="openingHours" content="Mo-Fr 08:00-22:00" />		
		<meta itemprop="openingHours" content="Sa-Su 09:00-22:00" />
		<meta itemprop="telephone" content="<?=$phone_1_f?>" />
		<meta itemprop="telephone" content="<?=$phone_2_f?>" />
		<meta itemprop="email" content="shop@<?=$_SERVER["SERVER_NAME"]?>" />
		<meta itemprop="taxID" content="7734379272" />
		<meta itemprop="vatID" content="773401001" />
		<meta itemprop="PriceRange" content="<?=$arPrices["MIN"]?>-<?=$arPrices["MAX"]?>RUB" />
		<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
			<meta itemprop="ratingValue" content="<?=$RATING_INFO["RATE"]?>" />
			<meta itemprop="reviewCount" content="<?=$RATING_INFO["COUNT"]?>" />
		</div>
	</div>
</div>
