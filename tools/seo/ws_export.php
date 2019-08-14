<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

$IBLOCK_TYPE = 'mn_catalog';

//echo "<pre>".print_r($arBrands, true)."</pre>";

function conv_to_write2($n) { 
	return iconv("UTF-8","CP1251", $n); 
} 


Cmodule::IncludeModule('asd.iblock');

ob_start();

$fp = fopen("php://output", "w");

fputcsv($fp, array("URL", "CATEGORY", "TYPEPREFIX", "BRAND", "MODEL" , "NAME", "BRAND_RUS", "NAME_RUS", "STORE", "WORDSTAT", "ID"), ";", '"');



$resIb = CIBlock::GetList(array("sort" => "asc", "id" => "desc"), array("TYPE" => $IBLOCK_TYPE, "ACTIVE" => "Y"), false);
while($arFieldsIb = $resIb->Fetch())	
{
	
	$arBlock_dop = CASDiblockTools::GetIBUF($arFieldsIb["ID"]);
	$arName = CStatic::getElement($arBlock_dop["UF_NAME_ID"], 20);			
	
	$type_prefix = $arName["PROPERTIES"]["ONE"]["VALUE"];
	
	
	$arrFilter = array("IBLOCK_ID" => $arFieldsIb["ID"], "ACTIVE" => "Y");
	
	//pre($arrFilter);
	$resEl = CIBlockElement::GetList(array("NAME" => "asc", "id" => "desc"), $arrFilter, false, false, array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_MODEL","PROPERTY_TYPE_PREFIX","PROPERTY_MSK", "PROPERTY_WORDSTAT"));
	while($arFieldsEl = $resEl->GetNext())
	{
		
		if($arFieldsEl["~PROPERTY_TYPEPREFIX_VALUE"]) $type_prefix = $arFieldsEl["~PROPERTY_TYPEPREFIX_VALUE"];
		
		$NAME = $arFieldsEl["~NAME"].' '.$arFieldsEl["~PROPERTY_MODEL_VALUE"];
		$NAME_RUS = str_replace('Liebherr','Либхер',$NAME);
		
		$row = array(
			"https://lbhr.ret-team.ru".$arFieldsEl["DETAIL_PAGE_URL"], 
			$arFieldsIb["NAME"], 
			$type_prefix,
			'Liebherr',  
			$arFieldsEl["~PROPERTY_MODEL_VALUE"],
			$NAME,
			'Либхер', 
			$NAME_RUS,
			$arFieldsEl["PROPERTY_MSK_VALUE"],
			$arFieldsEl["PROPERTY_WORDSTAT_VALUE"],	
			$arFieldsEl["ID"],	
		);
		$arRowConvert = array_map("conv_to_write2", $row);
		
		
		fputcsv($fp, $arRowConvert, ";", '"');
	}
}

fclose($fp);  

// Send the raw HTTP headers
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=wordstat_lrus_all_products.csv");
header("Expires: 0");  
header("Cache-Control: no-cache");
header("Content-Length: ". ob_get_length());

ob_end_flush();
?>