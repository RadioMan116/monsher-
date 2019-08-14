<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
        // Ресайзим картинки превью
		
		if($arItem["PREVIEW_PICTURE"]) {
			$arItem["IMG_1"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>272, 'height'=>168), BX_RESIZE_IMAGE_EXACT, true)["src"];	
			$arItem["IMG_2"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>272, 'height'=>168), BX_RESIZE_IMAGE_EXACT, true)["src"];	
			$arItem["IMG_3"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>272, 'height'=>168), BX_RESIZE_IMAGE_EXACT, true)["src"];
		}
		
    }
}		  
		

?>