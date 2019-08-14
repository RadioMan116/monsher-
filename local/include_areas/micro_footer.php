<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


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
	<div itemscope itemtype="https://schema.org/LocalBusiness" <?if(in_array($arParams["MENU_TIP"], ARRAY('main','reviews_store'))):?>itemref="microdata-shop-reviews"<?endif;?>>
		<div class="hide">
			<a href="https://<?=$_SERVER["SERVER_NAME"]?>" itemprop="url"><span itemprop="name">Специализированный магазин Monsher</span></a>		
			<img itemprop="logo" src="https://<?=$_SERVER["SERVER_NAME"]?>/mockup/templates/main/build/images/liebherr.png" alt="Monsher" />
			<img itemprop="image" src="https://<?=$_SERVER["SERVER_NAME"]?>/mockup/templates/main/build/images/liebherr.png" alt="Monsher" />
			<meta itemprop="legalName" content="ООО «НТК Трейд»" />
			<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<meta itemprop="streetAddress" content="проспект Маршала Жукова, дом 59" />
				<meta itemprop="postalCode" content="123103" />
				<meta itemprop="addressLocality" content="Москва" />
			</div>
			<meta itemprop="PriceRange" content="<?=$arPrices["MIN"]?>-<?=$arPrices["MAX"]?>RUB" />
			<meta itemprop="openingHours" content="Mo-Fr 08:00-22:00" />		
			<meta itemprop="openingHours" content="Sa-Su 09:00-22:00" />		
			<meta itemprop="telephone" content="<?=$phone_1_f?>" />
			<meta itemprop="telephone" content="<?=$phone_2_f?>" />		
		</div>
	</div>

