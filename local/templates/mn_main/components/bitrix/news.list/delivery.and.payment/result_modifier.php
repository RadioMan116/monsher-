<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
        // Ресайзим картинки превью
		
		$arItem["PDF"] = CFile::GetPath($arItem["PROPERTIES"]["PDF"]["VALUE"]);
		
		
        
    }
}		  
	
?>