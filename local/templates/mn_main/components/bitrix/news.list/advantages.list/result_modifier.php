<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//Make all properties present in order
//to prevent html table corruption
//$labels = CMNTLabelsData::get();
foreach($arResult["ITEMS"] as $key => &$arElement)
{	
	
			if($arElement["PREVIEW_PICTURE"]) {	
				$arImg = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array('width'=>110, 'height'=>155), BX_RESIZE_IMAGE_EXACT, true);	
			

				$arElement["PICTURE"] = $arImg["src"];
			}
			
	
	
	
}

 
       




?>