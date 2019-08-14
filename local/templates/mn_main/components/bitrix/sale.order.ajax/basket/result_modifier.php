<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	
if(0 < count($arResult["BASKET_ITEMS"]))
{
	
	//pre($arResult);
	$arResult["FORGET_LIST"] = array();
	$arBlocksId = array();
    foreach($arResult["BASKET_ITEMS"] as $index => &$arItem)
    {      		
	
		//pre($arItem);
	
	
	
			$arElement = CStatic::getElement($arItem["PRODUCT_ID"]);
			
			if($arElement["PROPERTIES"]["CML2_LINK"]["VALUE"]) {
				$arElement = CStatic::getElement($arElement["PROPERTIES"]["CML2_LINK"]["VALUE"]);
			}	
			
			
			if($arElement["PROPERTIES"]["ACC"]["VALUE"]) {
				
				
				$arResult["FORGET_LIST"] = array_merge($arResult["FORGET_LIST"], $arElement["PROPERTIES"]["ACC"]["VALUE"]);
			}
			
			$arBlocksId[] = $arElement["BLOCK_ID"];
			
			
			
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
						$arElement["IMG_1"] = CFile::ResizeImageGet($picture, array('width'=>120, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
						$arElement["IMG_2"] = CFile::ResizeImageGet($picture, array('width'=>240, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
						$arElement["IMG_3"] = CFile::ResizeImageGet($picture, array('width'=>360, 'height'=>360), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];
					}	
			
			
			/*
			$NO_POLS = array();
			$arProps = array();
			// Собираем свойства из товара
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
					if(!in_array($arProp["CODE"], $NO_POLS)) {
						$arProps[] = $arProp["CODE"];						
					}
				}
				
				if($arProps)	{
					foreach($arProps as $pid)
					{						
						$arRes[$pid] = CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"][$pid], "catalog_out");
					}
				}
					*
			
			$arElement["DISPLAY_PROPERTIES"] = $arRes;	
			*/		
			
			$arItem["PRODUCT"] = $arElement;			
    }
	
	//$arResult["FORGET_LIST"] = array_merge($arResult["FORGET_LIST"], CStatic::SearchByBlockAcc($arBlocksId));
	$arResult["FORGET_LIST"] = array_unique($arResult["FORGET_LIST"]);
	
	
	
	
	// Объеденяем типы свойств и сортируем как нам нужно.
	function cmp($a, $b) {
		$num = '1';				
		if($a["SORT"] < $b["SORT"]) 
			$num = '-1';		
           
        return $num;
	}	
	
	$arResult["ORDER_PROP"]["USER_PROPS_ALL"] = array_merge($arResult["ORDER_PROP"]["USER_PROPS_N"], $arResult["ORDER_PROP"]["USER_PROPS_Y"]);
	usort($arResult["ORDER_PROP"]["USER_PROPS_ALL"], "cmp");

	

	
	
	
	
	
	
	
	
	
	
}	

		

?>