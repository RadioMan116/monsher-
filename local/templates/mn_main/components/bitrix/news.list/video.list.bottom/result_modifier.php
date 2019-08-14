<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
         
       // $arItem["PREVIEW_PICTURE"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width" => 160, "height" => 128), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
    }
}		  
		

?>