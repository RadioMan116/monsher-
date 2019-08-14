<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
        // Ресайзим картинки превью
        $arItem["PICTURE"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 272, "height" => 150), BX_RESIZE_IMAGE_EXACT)["src"];
    }
}		  
		

?>