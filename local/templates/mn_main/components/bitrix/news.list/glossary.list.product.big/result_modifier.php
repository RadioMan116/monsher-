<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
        /* if($arItem["PROPERTIES"]["ICON"]["VALUE"]) {			 
			  $arItem["ICON"] = CFile::GetPath($arItem["PROPERTIES"]["ICON"]["VALUE"]);
		 }*/
		 
		 if($arItem["PREVIEW_PICTURE"]) {
			  $arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 226, "height" => 500), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			  $arItem["PICTURE"] = $arImg["src"];
		 }
     
    }
}		  
		

?>