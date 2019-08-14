<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$count = 0;

if(0 < count($arResult["ITEMS"]))
{
	$arProps = array();	
	
	$arNo = array();
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {      
	
	
					$arFilterP = array(
						"ACTIVE" => "Y",						
						"PROPERTY_".$GLOBALS["K_EXIST_CODE"]."_VALUE" => "В наличии"						
					 );
					 if($arItem["PROPERTIES"]["TOVS_ID"]["VALUE"]) {
						  $arFilterP["ID"] = $arItem["PROPERTIES"]["TOVS_ID"]["VALUE"];						 
					 }
					 elseif($arItem["PROPERTIES"]["BLOCK_ID"]["VALUE"]) {
						  $arFilterP["IBLOCK_ID"] = $arItem["PROPERTIES"]["BLOCK_ID"]["VALUE"];						 
					 }					 
					 
					 if($arNo) {
						  $arFilterP["!ID"] = $arNo;
					 }
					
					//pre($arFilterP);

					
					$db_res = CIBlockElement::GetList(
						array("propertysort_".$GLOBALS["K_EXIST_CODE"] => "ASC","SORT"=>"DESC"),
						$arFilterP,
						false,
						false,
						array(
							"*",	
						)
					);
					
				if (!$ob_res = $db_res->GetNextElement()) {
				
					$arFilterP = array(
						"ACTIVE" => "Y",						
						"PROPERTY_".$GLOBALS["K_EXIST_CODE"]."_VALUE" => "В наличии"						
					 );
					 if($arItem["PROPERTIES"]["BLOCK_ID"]["VALUE"]) {
						  $arFilterP["IBLOCK_ID"] = $arItem["PROPERTIES"]["BLOCK_ID"]["VALUE"];						 
					 				
					 
						 if($arNo) {
								  $arFilterP["!ID"] = $arNo;
						 }
							
							
							//echo '<br/>Не нашли пробуем еще ';
							//pre($arFilterP);

							
							$db_res = CIBlockElement::GetList(
								array("propertysort_".$GLOBALS["K_EXIST_CODE"] => "ASC","SORT"=>"DESC"),
								$arFilterP,
								false,
								false,
								array(
									"*",	
								)
							);					
							$ob_res = $db_res->GetNextElement();
					}	
					
				}
				
				
				
				if($ob_res) {
				
				
					
						$arTovs = $ob_res->GetFields();
						$arTovs["PROPERTIES"] = $ob_res->GetProperties();
	
						//pre($arTovs["NAME"]);
						
						$arPrices = CStatic::GetPrice($arTovs["ID"], true);						
						$arTovs["MIN_PRICE"] = reset($arPrices);						
						$arTovs["PRICES"] = $arPrices;						
						
						$arTovs = CStatic::CheckSalePrice($arTovs);	
			
						$arNo[] = $arTovs["ID"];
				
					/*
					if( ++ $count > $arParams["NEWS_COUNT_REAL"] ){
						unset($arResult["ITEMS"][$index]);
						continue;
					}	
					*/
					$picture = '';
					if($arTovs["PREVIEW_PICTURE"]) {						
						$picture = $arTovs["PREVIEW_PICTURE"];
					}
					else if($arTovs["DETAIL_PICTURE"]) {	
						$picture = $arTovs["DETAIL_PICTURE"];
					}
					else if($arTovs["PROPERTIES"]["PHOTOS"]["VALUE"]) {								
						$picture = $arTovs["PROPERTIES"]["PHOTOS"]["VALUE"][0];
					}
					
					//pre($picture);
					if($picture) {
						$arTovs["IMG_1"] = CFile::ResizeImageGet($picture, array('width'=>205, 'height'=>205), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
						$arTovs["IMG_2"] = CFile::ResizeImageGet($picture, array('width'=>410, 'height'=>410), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
						$arTovs["IMG_3"] = CFile::ResizeImageGet($picture, array('width'=>615, 'height'=>615), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];
					}	
					
					
					
					
					
						
					
					
					// labels detail
					$arTovs["LABELS"] = CStatic::GetLabelsInfo($arTovs);
					
					$arItem = $arTovs;			
					
				}
				else {
					unset($arResult["ITEMS"][$index]);
				}
					
				
				
        
	}
}
//pre(count($arResult["ITEMS"]));
	
	
		

?>