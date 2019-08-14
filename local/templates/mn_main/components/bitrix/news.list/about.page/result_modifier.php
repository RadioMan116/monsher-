<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if($arResult["ITEMS"])
	{
		foreach($arResult["ITEMS"] as $k=>&$arItem)
		{					
			if($arItem["PROPERTIES"]["VIDEO"]["VALUE"])
			{
				$arVideo = CStatic::getElement($arItem["PROPERTIES"]["VIDEO"]["VALUE"], CStatic::$videoIdBlock);
				$arVideo["PREVIEW_PICTURE"] = array("SRC" => CFile::GetPath($arVideo["PREVIEW_PICTURE"]));
				
				if(!$arItem["PREVIEW_PICTURE"]) $arItem["PREVIEW_PICTURE"] = $arVideo["PREVIEW_PICTURE"];
				if(!$arItem["PREVIEW_TEXT"]) $arItem["PREVIEW_TEXT"] = $arVideo["PREVIEW_TEXT"];
				$arItem["PROPERTIES"] = array_merge($arItem["PROPERTIES"], $arVideo["PROPERTIES"]);
				
				
				
				//PRE($arItem);
				
				$arItem["VIDEO"] = "Y";
			}		
		}
		
		
		
		
		
		
	}
?>