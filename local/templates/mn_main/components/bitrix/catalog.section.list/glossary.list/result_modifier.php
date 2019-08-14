<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	
foreach ($arResult['SECTIONS'] as $k=>&$arSection) {
	
	//pre($arSection);
	
	if(!$arSection["ELEMENT_CNT"]) {unset($arResult['SECTIONS'][$k]); continue;}
	
	
	$arFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => $arSection["ID"],
		"ACTIVE" => "Y",		
	);
	$arSection["ITEMS"] = CStatic::GetListElement(false, $arFilter, $arOrder = array("SORT" => "ASC"), true);
	
	
	
}

?>