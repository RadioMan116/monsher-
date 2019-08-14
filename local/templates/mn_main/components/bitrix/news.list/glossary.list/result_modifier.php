<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



CModule::IncludeModule("iblock");

	$arFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ACTIVE"=> "Y",
	);
	
	
	
					$db_res = CIBlockSection::GetList(
						array("SORT"=>"ASC","NAME"=>"ASC"),
						$arFilter						
					);
					
  while($rSec = $db_res->GetNext())
  {	  
	  $arSec[] = $rSec;	  
  }
  
  $arResult["SECTIONS"] = $arSec;


if(0 < count($arResult["ITEMS"]))
{
	
	
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
       $arResult["LIST_PAGE_URL"] = $arItem["LIST_PAGE_URL"];
	   
	   if($arItem["PREVIEW_PICTURE"]) {
		   $arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 148, "height" => 148), BX_RESIZE_IMAGE_PROPPORTIONAL_ALT, true);
		   $arItem["PREVIEW_PICTURE"]["SRC"] = $arImg["src"];	   
	   }
	   
	   if($arItem["PROPERTIES"]["SVG"]["VALUE"]) {
		   $arItem["PICTURE"] = CFile::GetPath($arItem["PROPERTIES"]["SVG"]["VALUE"]);
	   }
	   
	   if($arItem["PROPERTIES"]["PROPS_ID"]["VALUE"]) {
		   
		   $prop_id = $arItem["PROPERTIES"]["PROPS_ID"]["VALUE"];
		   $prop_id_value = $arItem["PROPERTIES"]["PROPS_ID"]["DESCRIPTION"];
	   
			
			//pre($arItem["PROPERTIES"]["PROPS_ID"]);
			
			
			$res = CIBlockProperty::GetByID($prop_id);
			if($arPropData = $res->GetNext()) {	
				
				//PRE($arPropData);
				
				
				$arrFilter2 = array(
					"ACTIVE" => "Y",
					"IBLOCK_ID" => $arPropData["IBLOCK_ID"],
					"!PROPERTY_".$GLOBALS["K_EXIST_CODE"]."_VALUE" => array("Нет в наличии", "Снят с производства"),
				);
				if($arPropData["PROPERTY_TYPE"] == 'L') {
					$arrFilter2['PROPERTY_'.$arPropData["CODE"].'_VALUE'] = $prop_id_value;
				}
				else {
					$arrFilter2['PROPERTY_'.$arPropData["CODE"]] = $prop_id_value;
				}
				
				//PRE($arrFilter2);
				
				$arItem["TOVS_LIST"] = CStatic::GetElementList($arrFilter2, $limit = 4);
			}
			//echo '<br/>'.$index;			
	   
	   }
	  
	   
	  // $arResult["ITEMS_BY_SECTION"][$arItem["IBLOCK_SECTION_ID"]][] = $arItem;
	   
    }
}		  
	

?>