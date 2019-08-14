<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arElement = $arParams["PRODUCT"];
if(!$arElement["MICRO_DESC"]) $arElement["MICRO_DESC"] = $arElement["NAME"];

$stock = 'OutOfStock';
if($arElement["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE_XML_ID"]) {
$stock = CStatic::$arExistMicro[$arElement["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE_XML_ID"]];
}

$arRate = CStatic::GetReviewsRating($arElement["ID"]);

?>

<div itemscope itemtype="http://schema.org/Product" class="hide" >
	
	<meta itemprop="name" content="<?=$arElement["NAME"]?>" />
	
	<?if($arElement["PICTURE"]):?>
		<img itemprop="image" src="<?=$arElement["PICTURE"]?>" />
	<?elseif($arElement["IMG_3"]):?>
		<img itemprop="image" src="<?=$arElement["IMG_3"]?>" />
	<?elseif($arElement["MORE_PHOTO"]):?>
		<?foreach($arElement['MORE_PHOTO'] as $k=>&$arPhoto):?>	
		<?
		if(!$arPhoto["ID"]) continue;		
		$img = CFile::ResizeImageGet($arPhoto["ID"], array('width'=>330, 'height'=>330), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		?>
		<img itemprop="image" src="<?=$img["src"]?>" />
		<?endforeach;?>
	<?endif;?>
	
	<meta itemprop="brand" content="Liebherr" />
	
	<meta itemprop="description" content="<?=$arElement["MICRO_DESC"]?>" />
	
	<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<meta itemprop="price" content="<?=$arElement["MIN_PRICE"]["DISCOUNT_VALUE"]?>" />
		<meta itemprop="priceCurrency" content="RUB" />			

		<meta itemprop="availability" content="http://schema.org/<?=$stock?>" />
		<meta itemprop="url" content="https://<?=$_SERVER["SERVER_NAME"]?><?=$arElement["DETAIL_PAGE_URL"]?>" />
	</div>
	
	<?if($arRate["COUNT"]):?>
	<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">							 
		<meta itemprop="ratingValue" content="<?=$arRate["RATE"]?>" />		
		<meta itemprop="reviewCount" content="<?=$arRate["COUNT"]?>" />							
	</div>
	<?endif;?>	
</div>