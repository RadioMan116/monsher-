<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
        // Ресайзим картинки превью
        //$arItem["PREVIEW_PICTURE_1"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 160, "height" => 128), BX_RESIZE_IMAGE_EXACT);
    }
}		  
		

?>