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
						"ID" => $arItem["PROPERTIES"]["TOV_ID"]["VALUE"]
					 );
					
					//pre($arFilterP);

					
					$db_res = CIBlockElement::GetList(
						array(),
						$arFilterP,
						false,
						false,
						array(
							"*",	
						)
					);
					
				if ($ob_res = $db_res->GetNextElement()) {					
					
						$arTovs = $ob_res->GetFields();
						$arTovs["PROPERTIES"] = $ob_res->GetProperties();
	
						//pre($arTovs["NAME"]);
	
						$arPrices = CStatic::GetPrice($arTovs["ID"], true);						
						$arTovs["MIN_PRICE"] = reset($arPrices);						
						$arTovs["PRICES"] = $arPrices;						
						
						$arTovs = CStatic::CheckSalePrice($arTovs);
	
				
					
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
						$arTovs["PICTURE"] = CFile::ResizeImageGet($picture, array('width'=>205, 'height'=>205), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];
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