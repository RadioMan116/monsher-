<?php
use \Bitrix\Main;


class ArtFilterLeft extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {		
		return $arParams;		
    }

    public function executeComponent()
    { 		
	
		$arParams = $this->arParams;
	
	
		$FAVORITE_LIST = explode('|',$arParams["FAVORITE_LIST"]);
		$arId = array_diff($FAVORITE_LIST, array(''));	
		
		if($arId) {
			$arrFilterV = array(
				"ID" => $arId,
				"ACTIVE" => "Y",
				"IBLOCK_ID" => CStatic::$catalogIdBlock,
			);
			$arSecIds = array();
			$arSecIdsCount = array();
			
			$this->arResult["COUNT_ALL"] = 0;
			
			if($arProducts = CStatic::GetListElement(false, $arrFilterV, array(), false)) {
				
				foreach($arProducts as $arProduct) {
					$arSecIds[] = $arProduct["IBLOCK_SECTION_ID"];
					
					if(!$arSecIdsCount[$arProduct["IBLOCK_SECTION_ID"]]) $arSecIdsCount[$arProduct["IBLOCK_SECTION_ID"]] = 0;
					$arSecIdsCount[$arProduct["IBLOCK_SECTION_ID"]]++;
					
					$this->arResult["COUNT_ALL"]++;
				}		

				$arSecIds = array_unique($arSecIds);	
			}
		}
		
		$arSections = array();
		if($arSecIds) {			
			
					$arFilter = array(							
						"IBLOCK_ID" => $catalogIdBlock,						
						"ID" => $arSecIds,					
						"ACTIVE" => "Y"						
					);	
					$db_res = CIBlockSection::GetList(
						array(),
						$arFilter						
					);
					while ($arSec = $db_res->GetNext())
					{	
						$arSec["COUNT"] = $arSecIdsCount[$arSec["ID"]];	
				
				
				
						$arSections[] = $arSec;
					}			
		}
		//PRE($arSecIds);
		
		$this->arResult["SECTIONS"] = $arSections;
		
		
		$this->IncludeComponentTemplate();
    }
}
