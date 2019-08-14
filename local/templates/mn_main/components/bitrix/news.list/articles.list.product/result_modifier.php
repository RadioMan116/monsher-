<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
        // Ресайзим картинки превью
		
		if($arItem["PREVIEW_PICTURE"]) {
			$arItem["IMG_1"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>278, 'height'=>170), BX_RESIZE_IMAGE_EXACT, true)["src"];	
			$arItem["IMG_2"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>556, 'height'=>340), BX_RESIZE_IMAGE_EXACT, true)["src"];	
			$arItem["IMG_3"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>834, 'height'=>510), BX_RESIZE_IMAGE_EXACT, true)["src"];
		}
		
    }
}		  
		

?>