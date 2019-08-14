<?php
use \Bitrix\Main;


class CityChange extends CBitrixComponent
{	
	
	
	
	
	
	
    public function onPrepareComponentParams($arParams)
    {		
		return $arParams;		
    }

    public function executeComponent()
    { 		
			global $APPLICATION;
	
	
			$arFilter = array(
				"IBLOCK_ID" => 94,
			);
			
			$db_res = CIBlockElement::getList(
				array("SORT" => "ASC"),
				$arFilter,
				false, false,
				array("ID", "IBLOCK_ID", "NAME", "CODE")
			);
			$arItems = array();
			while ($ob_res = $db_res->GetNextElement()) {
				$arElement = $ob_res->GetFields();
				$arElement["PROPERTIES"] = $ob_res->GetProperties();
				
				
				$arElement["URL"] = 'https://'.$arElement["PROPERTIES"]["WWW"]["VALUE"].$APPLICATION->GetCurUri('K_REGION='.$arElement["CODE"]);
				

				
				$arItems[$arElement["CODE"]] = $arElement;
			}
	
	
	
	
	
		$this->arResult["ITEMS"] = $arItems;
		
		
		$this->IncludeComponentTemplate();
    }
}
