<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult["DATA"]["RATING_INFO"] = CStatic::GetReviewsRating(false, false, false ,CStatic::$ReviewsStoreIdBlock , CStatic::$ReviewsStoreIdSec );	
	
krsort($arResult["DATA"]["RATING_INFO"]["LIST"]);

if(0 < count($arResult["ITEMS"]))
{
	

	
	
	
   foreach($arResult["ITEMS"] as $i => &$arItem) {	
	  
	  
   }
   
   
}		  
		

?>