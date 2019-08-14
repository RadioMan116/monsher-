<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

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
	  // pre($rSec);	  
	  $arSec[] = $rSec;	  
  }
  
  $arResult["SECTIONS"] = $arSec;


if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
       $arResult["LIST_PAGE_URL"] = $arItem["LIST_PAGE_URL"];
       $arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 385, "height" => 310), BX_RESIZE_IMAGE_EXACT, true);
	   $arItem["PICTURE"] = $arImg["src"];
	   
	   $arResult["ITEMS_BY_SECTION"][$arItem["IBLOCK_SECTION_ID"]][] = $arItem;
	   
    }
}		  
		

?>