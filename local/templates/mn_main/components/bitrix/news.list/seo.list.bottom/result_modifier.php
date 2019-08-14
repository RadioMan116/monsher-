<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	$page = $APPLICATION->GetCurPage();  	
	if($arResult["ITEMS"])
	{
		foreach($arResult["ITEMS"] as $k=>&$arItems)
		{					
			if($arItems["PROPERTIES"]["URL_NOT"]["VALUE"])
			{
				if(in_array($page, $arItems["PROPERTIES"]["URL_NOT"]["VALUE"]))
				{					
					unset($arResult["ITEMS"][$k]);
				}
			}		
		}
		
		 foreach($arResult["ITEMS"] as $index => &$arItem)
		 {
			
				  //$arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 610, "height" => 800), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
				  //$arItem["PICTURE"] = $arImg["src"];
			 
		 }
		
		
		
		
	}
?>