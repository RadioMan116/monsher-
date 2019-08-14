<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//Make all properties present in order
//to prevent html table corruption
$arProps = array();

		$arResult["G_PROPS_ALL"] = CStatic::DescPropAll();

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
	
			
			
					$picture = '';
					if($arElement["PREVIEW_PICTURE"]) {						
						$picture = $arElement["PREVIEW_PICTURE"];
					}
					else if($arElement["DETAIL_PICTURE"]) {	
						$picture = $arElement["DETAIL_PICTURE"];
					}
					else if($arElement["PROPERTIES"]["PHOTOS"]["VALUE"]) {								
						$picture = $arElement["PROPERTIES"]["PHOTOS"]["VALUE"][0];
					}
					
					//pre($picture);
					if($picture) {
						$arElement["IMG_1"] = CFile::ResizeImageGet($picture, array('width'=>205, 'height'=>205), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
						$arElement["IMG_2"] = CFile::ResizeImageGet($picture, array('width'=>410, 'height'=>410), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
						$arElement["IMG_3"] = CFile::ResizeImageGet($picture, array('width'=>615, 'height'=>615), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];
					}	
			
			
			$arElement["LABELS"] = CStatic::GetLabelsInfo($arElement);
			$arElement["LABELS_DOP"] = CStatic::GetLabelsInfoDop($arElement);
				
			$arElement = CStatic::CheckSalePrice($arElement);
			
			
	
}

 
       




?>