<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
         if($arItem["PROPERTIES"]["ICON"]["VALUE"]) {			 
			  $arItem["ICON"] = CFile::GetPath($arItem["PROPERTIES"]["ICON"]["VALUE"]);
		 }
		 
		 if($arItem["DETAIL_PICTURE"]) {
			  $arImg = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 286, "height" => 286), BX_RESIZE_IMAGE_EXACT, true);
			  $arItem["PICTURE"] = $arImg["src"];
		 }
     
    }
}		  
		

?>