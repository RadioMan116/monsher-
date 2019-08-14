<?include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");
$ibType = "mn_catalog";


$iblock_info = 31;
$iblock_info_element_id = 2038;
$iblock_info_prop_code = 'DAYS_TO_REMOVE_LABEL_NEW';

$arData = CStatic::getElement($iblock_info_element_id, $iblock_info, false);

$days_remove = 182;
if($arData["PROPERTIES"][$iblock_info_prop_code]["VALUE"]) {
	$days_remove = $arData["PROPERTIES"][$iblock_info_prop_code]["VALUE"];	
	//PRE($days_remove);
}

$halfYearUnix = $days_remove*60*60*24;
$curTime = time();

$ibs = CIBlock::GetList(array(), array("TYPE" => $ibType, "ACTIVE" => "Y"));
$count = 0;
while($ib = $ibs->fetch()){
	
	
	$arFilter = array(
		"IBLOCK_ID" => $ib['ID'],
		//"ACTIVE" => "Y",
		"PROPERTY_S_NEW" => 'Y'
	);
	
	//PRE($arFilter);
	
    $elems = CIBlockElement::GetList(array(),$arFilter);
    while($elem = $elems->GetNextElement()){
        $arElement = $elem->GetFields();
        $arElement["PROPERTIES"] = $elem->GetProperties();
        
        $createTime = $arElement['DATE_CREATE_UNIX'];
        $createTime = $halfYearUnix + $createTime;
		
/*
ECHO '<BR/> #######################<BR/>';			  
PRE( $arElement["PROPERTIES"]["S_NEW"]["VALUE"]);
*/
				

        if($createTime < $curTime){
            CIBlockElement::SetPropertyValuesEx($arElement['ID'],$arElement['IBLOCK_ID'], array("S_NEW" => ""));
        }
    }
    
}
echo 'Убрали лейбл у '.$count.' товара(ов)';