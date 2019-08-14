<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
	
	$arResult["BLOCK"] = getArIblock('elica_content', false, $arParams["IBLOCK_ID"]);
	$arResult["BLOCK"]["PICTURE"] = CFile::GetPath($arResult["BLOCK"]["PICTURE"]);
	
	
	
	
	
	
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
      
		 if($arItem["PREVIEW_PICTURE"]) {
			  $arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 514, "height" => 700), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
			  $arItem["PICTURE"] = $arImg["src"];
		 }
		 
		 
		 if($arItem["PROPERTIES"]["TOVS_ID"]["VALUE"]) {
			 
			 $arResult["TOVS_LIST"] = array();
			 foreach($arItem["PROPERTIES"]["TOVS_ID"]["VALUE"] as $tov_id) {			 
				 
				 $arProduct = CStatic::getElement($tov_id);
				 $arItem["TOVS_LIST"][] = $arProduct;				 
			 }
		 }
     
    }
}		  
		

?>