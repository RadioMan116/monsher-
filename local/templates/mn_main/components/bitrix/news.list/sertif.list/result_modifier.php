<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {		 
		
			  $arImg = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 790, "height" => 790), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			  $arItem["PICTURES"]["BIG"] = $arImg["src"];
	
		
			  $arImg = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 181, "height" => 300), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			  $arItem["PICTURES"]["SMALL"] = $arImg["src"];
	
		
		 
		 
		 
     
    }
}		  
		

?>