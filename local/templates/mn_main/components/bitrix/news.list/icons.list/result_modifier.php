<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
         if($arItem["PROPERTIES"]["SVG"]["VALUE"]) {			 
			  $arItem["PICTURE"] = CFile::GetPath($arItem["PROPERTIES"]["SVG"]["VALUE"]);
		 }
		 else if($arItem["PREVIEW_PICTURE"]) {
			  $arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 74, "height" => 74), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
			  $arItem["PICTURE"] = $arImg["src"];
		 }
     
    }
}		  
		

?>