<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//echo "<pre>".print_r($arResult, true)."</pre>";

if(count($arResult["SEARCH"]) > 0)
{
	$arResult["ITEMS"] = array();
	$arResult["ITEMS_ID_IBLOCK"] = array();
	
	
	$arResult["TOV_ID"] = array();
	
	
	foreach($arResult["SEARCH"] as $arSearchItem)
	{
		$elementID = intval($arSearchItem["ITEM_ID"]);
		
		$arResult["TOV_ID"][] = $elementID;
		
		
		
		$iblockID = intval($arSearchItem["PARAM2"]);		
		$arTovs = CStatic::getElement($elementID, $iblockID);		
		
		if($arTovs)
		{
			
			$arPrices = CStatic::GetPrice($arTovs["ID"], true);						
			$arTovs["MIN_PRICE"] = reset($arPrices);						
			$arTovs["PRICES"] = $arPrices;						
						
			$arTovs = CStatic::CheckSalePrice($arTovs);
			
			
			
			
			
			$picture = SITE_TEMPLATE_PATH.'/img/nophoto-small.png';
			$height = '194';	
			 if($arTovs["PREVIEW_PICTURE"]) {			
				$arImg = CFile::ResizeImageGet($arTovs["PREVIEW_PICTURE"], array('width'=>204, 'height'=>194), BX_RESIZE_IMAGE_PROPORTIONAL, true);	
				$picture = $arImg["src"];
				$height = $arImg["height"];
			}
			else if($arTovs["DETAIL_PICTURE"]) {	
			
				$arImg = CFile::ResizeImageGet($arTovs["DETAIL_PICTURE"], array('width'=>204, 'height'=>194), BX_RESIZE_IMAGE_PROPORTIONAL, true);	
				$picture = $arImg["src"];
				$height = $arImg["height"];
			}
			else if($arTovs["PROPERTIES"]["PHOTOS"]["VALUE"]) {	
				$arImg = CFile::ResizeImageGet($arTovs["PROPERTIES"]["PHOTOS"]["VALUE"][0], array('width'=>204, 'height'=>194), BX_RESIZE_IMAGE_PROPORTIONAL, true);	
				$picture = $arImg["src"];
				$height = $arImg["height"];		
			}	
	
			$arTovs["PICTURE"] = $picture;
			
			if(!$arProps[$arTovs["IBLOCK_ID"]])
			{
				$rProp = CIBlockProperty::GetList(
				Array("SORT" => "ASC"), 
				Array(
					"ACTIVE" => "Y", 
					"IBLOCK_ID" => $arTovs["IBLOCK_ID"],
					"FILTRABLE" => "Y",			
					)
				);
				while ($arProp = $rProp->GetNext())
				{					
					if($arProp["CODE"]!='CML2_LINK')
					{
						$arProps[$arTovs["IBLOCK_ID"]][] = $arProp["CODE"];						
					}
				}	
			}
			$arRes = array();
			if($arProps[$arTovs["IBLOCK_ID"]])
			{
				foreach($arProps[$arTovs["IBLOCK_ID"]] as $pid)
				{
					$arRes[$pid] = CIBlockFormatProperties::GetDisplayValue($arTovs, $arTovs["PROPERTIES"][$pid], "catalog_out");
				}
				$arTovs["DISPLAY_PROPERTIES"] = $arRes;
			}
			$arSec = getArSection($arTovs["IBLOCK_ID"], false, $arTovs["IBLOCK_SECTION_ID"]);	
			$arTovs["SECTION_CODE"] = $arSec["CODE"];
			
			$arTovs["LABELS"] = CStatic::GetLabelsInfo($arTovs);
			
			
			$arResult["ITEMS"][] = $arTovs;
			
			
			
			
		}
		
	}
}
?>