<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



	if(0 < count($arResult["ITEMS"]))
	{
		foreach($arResult["ITEMS"] as $index => &$arItem)
		{							 
				 $arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 250, "height" => 300), BX_RESIZE_IMAGE_PROPORTIONAL, true);
				 $arItem["PICTURE"] = $arImg["src"];	
		}
	}	
?>