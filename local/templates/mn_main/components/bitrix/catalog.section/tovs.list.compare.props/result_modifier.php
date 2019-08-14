<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//pre(count($arResult["ITEMS"]));
$arResult["ITEMS"] = array_merge(array($arParams["PRODUCT"]), $arResult["ITEMS"]);
//pre(count($arResult["ITEMS"]));


$arResult["PRODUCTS_ID"] = array();

foreach($arResult["ITEMS"] as $key => &$arElement)
{	

			$arResult["PRODUCTS_ID"][] = $arElement["ID"];

	
			if($arElement["PREVIEW_PICTURE"]) {	
				$arImg = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPERTIONAL, true);	
				$picture = $arImg["src"];	
			}
			else if($arElement["DETAIL_PICTURE"]) {
				$arImg = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPERTIONAL, true);	
				$picture = $arImg["src"];
			}
			else if($arElement["PROPERTIES"]["PHOTOS"]["VALUE"]) {	
				$arImg = CFile::ResizeImageGet($arElement["PROPERTIES"]["PHOTOS"]["VALUE"][0], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPERTIONAL, true);	
				$picture = $arImg["src"];
			}
	
	
	$arElement["PICTURE"] = $picture;
	
	$arElement = CStatic::CheckSalePrice($arElement);
	
}


	


?>