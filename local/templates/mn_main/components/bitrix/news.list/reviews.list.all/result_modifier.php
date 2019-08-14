<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
	
	//$arTovs = array();
	if($arParams["PRODUCT"]) {				
		$arResult["DATA"]["PRODUCT"] = CStatic::getElement($arParams["PRODUCT"]);	

		$arResult["DATA"]["PRODUCT"]["MIN_PRICE"] = reset(CStatic::GetPrice($arResult["DATA"]["PRODUCT"]["ID"]));
		
		
		$arPhotos = array();
		$picture = '/tpl/img/new/nophoto-small.jpg';
		if($arResult["DATA"]["PRODUCT"]["DETAIL_PICTURE"]) {	
			$arPhotos[] = $arResult["DATA"]["PRODUCT"]["DETAIL_PICTURE"];			
		}
		if($arResult["DATA"]["PRODUCT"]["PROPERTIES"]["PHOTOS"]["VALUE"]) {	
			$arPhotos = $arResult["DATA"]["PRODUCT"]["PROPERTIES"]["PHOTOS"]["VALUE"];			
		}
		
		
		$arResult["DATA"]["PRODUCT"]["MORE_PHOTO"] = $arPhotos;
		
		
		$arResult["DATA"]["RATING_INFO"] = CStatic::GetReviewsRating($arParams["PRODUCT"]);		
	}
	else if($arParams["BLOCK"]) {				
		$arResult["DATA"]["BLOCK"] = getArIblock('lb_catalog', false, $arParams["BLOCK"]);
		$arResult["DATA"]["SEOXML_TITLE"] = $arResult["DATA"]["BLOCK"]["NAME"];
		
		
		
		if($arParams["SECTION"]) {			
			$arResult["DATA"]["SECTION"] = getArSection($arResult["DATA"]["BLOCK"]["ID"], false, $arParams["SECTION"]);
			$arResult["DATA"]["SEOXML_TITLE"] = $arResult["DATA"]["SECTION"]["NAME"];
		}



		
		$arResult["DATA"]["RATING_INFO"] = CStatic::GetReviewsRating(false, $arParams["BLOCK"], $arParams["SECTION"]);		
	}
	else {	
		$arResult["DATA"]["RATING_INFO"] = CStatic::GetReviewsRating(false, false);		
	}
	
	krsort($arResult["DATA"]["RATING_INFO"]["LIST"]);
	
	
   foreach($arResult["ITEMS"] as $i => &$arItem) {	   
	   
	  $arItem["PRODUCT"] = CStatic::getElement($arItem["PROPERTIES"]["TOV_ID"]["VALUE"]);
	  
	   $picture = '/tpl/img/new/nophoto-small.jpg';
		if($arItem["PRODUCT"]["PREVIEW_PICTURE"]) {	
			$arImg = CFile::ResizeImageGet($arItem["PRODUCT"]["PREVIEW_PICTURE"], array('width'=>260, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);	
			$picture = $arImg["src"];			
		}
		else if($arItem["PRODUCT"]["DETAIL_PICTURE"]) {	
		
			$arImg = CFile::ResizeImageGet($arItem["PRODUCT"]["DETAIL_PICTURE"], array('width'=>260, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);	
			$picture = $arImg["src"];
		}
		else if($arItem["PRODUCT"]["PROPERTIES"]["PHOTOS"]["VALUE"]) {	
			$arImg = CFile::ResizeImageGet($arItem["PRODUCT"]["PROPERTIES"]["PHOTOS"]["VALUE"][0], array('width'=>260, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);	
			$picture = $arImg["src"];
		}
		$arItem["PRODUCT"]["PICTURE"] = $picture;
	  
	  
	  
   }
   
   
}		  
		

?>