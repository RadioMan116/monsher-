<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
         
        if($arItem["PREVIEW_PICTURE"]) {
		   $arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 360, "height" => 288), BX_RESIZE_IMAGE_EXACT, true);
		   $arItem["PICTURE"] = $arImg["src"];
		 }
    }
}		  
		

?>