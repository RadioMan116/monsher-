<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//Make all properties present in order
//to prevent html table corruption
$arProps = array();


//PRE($arResult["ITEMS"]);


foreach($arResult["ITEMS"] as $key => &$arElement)
{
	$arElement2 = CStatic::getElement($arElement["ID"],$arElement["IBLOCK_ID"]);
	$arElement["PROPERTIES"] = $arElement2["PROPERTIES"];	
			
			
	
	// ############################################################################
			// ############################################################################	

			if(!$arProps[$arElement["IBLOCK_ID"]])
			{
				$rProp = CIBlockProperty::GetList(
				Array("SORT" => "ASC"), 
				Array(
					"ACTIVE" => "Y", 
					"IBLOCK_ID" => $arElement["IBLOCK_ID"],
					"FILTRABLE" => "Y",			
					)
				);
				while ($arProp = $rProp->GetNext())
				{					
					
					$arProps[$arElement["IBLOCK_ID"]][] = $arProp["CODE"];						
					
				}	
			}
			$arRes = array();
			foreach($arProps[$arElement["IBLOCK_ID"]] as $pid)
			{		
				$arRes[$pid] = CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"][$pid], "catalog_out");
			}
			$arElement["DISPLAY_PROPERTIES"] = $arRes;			
			
			
	// ############################################################################
	// ############################################################################	
	
			
			
			$picture = SITE_TEMPLATE_PATH.'/img/nophoto-small.png';			
			if($arElement["PREVIEW_PICTURE"]) {	
				$arImg = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array('width'=>180, 'height'=>180), BX_RESIZE_IMAGE_PROPORTIONAL, true);	
				$picture = $arImg["src"];
			}
			else if($arElement["DETAIL_PICTURE"]) {	
			
				$arImg = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], array('width'=>180, 'height'=>180), BX_RESIZE_IMAGE_PROPORTIONAL, true);	
				$picture = $arImg["src"];
			}
			else if($arElement["PROPERTIES"]["PHOTOS"]["VALUE"]) {	
				$arImg = CFile::ResizeImageGet($arElement["PROPERTIES"]["PHOTOS"]["VALUE"][0], array('width'=>180, 'height'=>180), BX_RESIZE_IMAGE_PROPORTIONAL, true);	
				$picture = $arImg["src"];	
			}
			$arElement["PICTURE"]["SRC"] = $picture;
			
			
			$arElement["LABELS"] = CStatic::GetLabelsInfo($arElement);
				
			$arElement = CStatic::CheckSalePrice($arElement);
			
			
	
}

 
       




?>