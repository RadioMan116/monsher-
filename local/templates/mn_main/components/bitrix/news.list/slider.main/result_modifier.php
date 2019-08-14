<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
	$arProps = array();	
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {      
	
		//pre($arItem);
	
	
		if($arItem["PROPERTIES"]["TOV_ID"]["VALUE"]>0)
		{
			$arTovs = CStatic::getElement($arItem["PROPERTIES"]["TOV_ID"]["VALUE"]);
			$arTovs["MIN_PRICE"] = current(CStatic::GetPrice($arTovs["ID"]));			
			
			
			if(!$arItem["PROPERTIES"]["URL"]["VALUE"]) $arItem["PROPERTIES"]["URL"]["VALUE"] = $arTovs["DETAIL_PAGE_URL"];
			
			$arItem["PRODUCT"] = $arTovs;	
			
			
		}
		
		
		
		//pre($arItem["PRODUCT"]);
        
    }
	
	
	
	
}	
		

?>