<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


//pre($arResult["SHOW_PROPERTIES"]);

if($arResult["ITEMS"]) {

	
	unset($prop); // remove link
	
					$arFilter = array(
						"ID" => $arParams["SECTIONS_ID"],
						"ACTIVE" => "Y"
					);
					$db_res = CIBlockSection::GetList(
						array("SORT"=> "ASC","NAME" => "ASC"),
						$arFilter						
					);
					$active = false;
					while ($arSec = $db_res->GetNext())
					{			
						if(!$arResult["SECTIONS"][$arSec["IBLOCK_ID"]]) $arResult["SECTIONS"][$arSec["IBLOCK_ID"]] = array();
						
						if($arParams["CATALOG_COMPARE_SECTION_ACTIVE"]) {
							if($arParams["CATALOG_COMPARE_SECTION_ACTIVE"] == $arSec["ID"]) {
								$arSec["S_ACTIVE"] = 'Y';
								$active = true;
							}
						}						
						
						$arResult["SECTIONS"][$arSec["IBLOCK_ID"]][] = $arSec;
					}
	
					if(!$active) {
						$arResult["SECTIONS"][$arParams["IBLOCK_ID"]][0]["S_ACTIVE"] = 'Y';
						$_SESSION["CATALOG_COMPARE_SECTION_ACTIVE"] = $arParams["CATALOG_COMPARE_SECTION_ACTIVE"] = $arResult["SECTIONS"][$arParams["IBLOCK_ID"]][0]["ID"];
					}
	
	//PRE(COUNT($arResult["ITEMS"]));

	//pre($arResult["SHOW_PROPERTIES"]);



	// remove last empty groups
	$codes = array_reverse(array_keys($arResult["SHOW_PROPERTIES"]));
	
	
	//PRE($codes);
	foreach($codes as $code){
		if( $arResult["SHOW_PROPERTIES"][$code]["IS_GROUP"] )
			unset($arResult["SHOW_PROPERTIES"][$code]);
		else	
			break;
	}

	foreach($arResult["ITEMS"] as $index => &$arElement){
		
		
			if($arParams["CATALOG_COMPARE_SECTION_ACTIVE"]) {
				if($arElement["IBLOCK_SECTION_ID"]!=$arParams["CATALOG_COMPARE_SECTION_ACTIVE"]) {
					unset($arResult["ITEMS"][$index]);
					continue;
				}
			}
		
		
		
		
		
		
		
			//$arElement["DETAIL_PAGE_URL"] = str_replace('/catalog','/catalog/'.$arBlock[$arElement["IBLOCK_ID"]]["CODE"], $arElement["DETAIL_PAGE_URL"]);
		
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
							$arElement["PICTURE"] = CFile::ResizeImageGet($picture, array('width'=>230, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
						}	
			
			$arElement = CStatic::CheckSalePrice($arElement);
			
			// set new url
			
								
			
	}
	unset($arElement); // remove link
	
	
	
	
	
	
	
	
		$arItem = reset($arResult["ITEMS"]);


	//pre($arParams["PROPERTY_CODE"]);

	$arResult["SHOW_PROPERTIES"] = array();
	foreach($arItem["PROPERTIES"] as $code => &$prop){
		
		if(in_array($code, $arParams["PROPERTY_CODE"])) {
			$prop["IS_GROUP"] = ( $prop["DEFAULT_VALUE"] == "+" || substr($code, 0, 5) == "TITLE" );
			$prop["IS_CHECKBOX"] = ( $prop["PROPERTY_TYPE"] == "L" && $prop["LIST_TYPE"] == "C" );	
			
			$arResult["SHOW_PROPERTIES"][$code] = $prop;
		}
	}
	
	//pre($arResult['EMPTY_PROPERTIES']);
	
	foreach($arResult["SHOW_PROPERTIES"] as $code=>$prop) {
		if(!$prop["IS_GROUP"] &&  $arResult['EMPTY_PROPERTIES'][$code]) {
			unset($arResult["SHOW_PROPERTIES"][$code] );
		}
	}
	
	
	
}

?>