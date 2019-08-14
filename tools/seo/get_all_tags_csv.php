<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

$IBLOCK_TYPE = 'mn_catalog';

//echo "<pre>".print_r($arBrands, true)."</pre>";

function conv_to_write2($n) { 
	return iconv("UTF-8","CP1251", $n); 
} 

function GetBlockUrls($arData) {
	

	$arUrl = array();
	if($arData["PROPERTIES"]["BLOCK_ID"]["VALUE"]) {
		$resIb = CIBlock::GetList(array(), array("ID" => $arData["PROPERTIES"]["BLOCK_ID"]["VALUE"]), false);
		while($arFieldsIb = $resIb->Fetch())	
		{
			$arUrl[] = "https://".$_SERVER["SERVER_NAME"].'/liebherr/'.$arFieldsIb["CODE"].'/';
		}
	}
	if($arData["PROPERTIES"]["BLOCK_URL"]["VALUE"]) {
		
		if(!is_array($arData["PROPERTIES"]["BLOCK_URL"]["VALUE"])) $arData["PROPERTIES"]["BLOCK_URL"]["VALUE"] = array($arData["PROPERTIES"]["BLOCK_URL"]["VALUE"]);
		foreach($arData["PROPERTIES"]["BLOCK_URL"]["VALUE"] as $url) {
			$arUrl[] = "https://".$_SERVER["SERVER_NAME"].$url;
		}
	}
	return $arUrl;
}
function GetFilterDop() {

	
		$filter = array();		
	
		switch(SITE_ID) {	
			
			case "s1":
				$filter["SECTION_ID"] = 32;
			break;
			case "s2":
				$filter["SECTION_ID"] = 33;
			break;			
			
		}
		
		 if(strstr($_SERVER["SERVER_NAME"], 'saint-petersburg.')) {
			  $filter["PROPERTY_CITY_SPB"] = 'Y';		  
		 }
		 
		 return $filter;
}	



Cmodule::IncludeModule('asd.iblock');

ob_start();
$fp = fopen("php://output", "w");
fputcsv($fp, array("URL_NEW", "URL_OLD", "NAME", "CATEGORY", "SEO_H1", "SEO_TITLE", "SEO_KEYWORDS", "SEO_DESCRIPTION", "TEXT_1", "TEXT_2", "PRODUCTS COUNT", "FREQ"), ";", '"');


	$arrFilter = array("IBLOCK_ID" => 16, "ACTIVE" => "Y");	
	$arrFilter = array_merge($arrFilter, GetFilterDop());
	
	//pre($arrFilter);
	$db_res = CIBlockElement::GetList(array("NAME" => "asc", "id" => "desc"), $arrFilter, false, false, array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL"));
	while($ob_res = $db_res->GetNextElement())
	{
		$arElement = $ob_res->GetFields();
		$arElement["PROPERTIES"] = $ob_res->GetProperties();
		
		
		$arCategoryUrls = GetBlockUrls($arElement);
		
		//pre($arCategoryUrls);
		$row = array(
			"https://".$_SERVER["SERVER_NAME"].$arElement["NAME"], 
			"https://".$_SERVER["SERVER_NAME"].$arElement["PROPERTIES"]["OLD_URL"]["VALUE"], 
			$arElement["PROPERTIES"]["TAG_TITLE"]["VALUE"],
			implode(', ',$arCategoryUrls),
			$arElement["PROPERTIES"]["H1"]["VALUE"],
			$arElement["PROPERTIES"]["TITLE"]["VALUE"],
			$arElement["PROPERTIES"]["KEYWORDS"]["VALUE"],
			$arElement["PROPERTIES"]["DESCRIPTION"]["VALUE"],
			strip_tags($arElement["PROPERTIES"]["SEO_TEXT_1"]["VALUE"]),
			strip_tags($arElement["PROPERTIES"]["SEO_TEXT_2"]["VALUE"]),
			$arElement["PROPERTIES"]["COUNT"]["VALUE"],
			$arElement["PROPERTIES"]["FREQ"]["VALUE"]
		);
		
		//pre($row);
		
		$arRowConvert = array_map("conv_to_write2", $row);
		
		
		fputcsv($fp, $arRowConvert, ";", '"');
	}


fclose($fp);  

// Send the raw HTTP headers
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=".$_SERVER["SERVER_NAME"]."_all_tags.csv");
header("Expires: 0");  
header("Cache-Control: no-cache");
header("Content-Length: ". ob_get_length());

ob_end_flush();
?>