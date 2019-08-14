<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
	
	
	
	
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
      
		 if($arItem["PREVIEW_PICTURE"]) {
			  $arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 311, "height" => 500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
			  $arItem["PREVIEW_PICTURE"]["SRC"] = $arImg["src"];
		 }
		 
		 
		 if($arItem["DETAIL_PICTURE"]) {
			  $arImg = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 600, "height" => 900), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
			  $arItem["DETAIL_PICTURE"]["SRC"] = $arImg["src"];
		 }
		 
		 
     
    }
}		  
		

?>