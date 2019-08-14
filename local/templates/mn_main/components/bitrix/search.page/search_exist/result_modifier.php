<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//echo "<pre>".print_r($arResult, true)."</pre>";

if(count($arResult["SEARCH"]) > 0)
{
	$arResult["ITEMS"] = array();
	$arResult["ITEMS_ID_IBLOCK"] = array();
	
	
	$arResult["TOVS_ID"] = array();
	
	
	foreach($arResult["SEARCH"] as $arSearchItem)
	{
		$elementID = intval($arSearchItem["ITEM_ID"]);
		
		
		$iblockID = intval($arSearchItem["PARAM2"]);		
		$arTovs = CStatic::getElement($elementID, $iblockID);		
		
		if($arTovs)
		{		
			if($arTovs["ACTIVE"] == 'Y') $arResult["TOVS_ID"][] = $elementID;
		}
		
	}
}
?>