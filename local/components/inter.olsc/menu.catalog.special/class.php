<?php
use \Bitrix\Main;


class MenuCatalogSpecial extends CBitrixComponent
{
	
	 public $arBlock;
	 public $arSection;
	 public $arNames;
	 
	 
	 public $arTypes = array(
		array(
			"TITLE" => "Новинки", 
			"CODE" => "nov"
		),		
		array(
			"TITLE" => "Акции", 
			"CODE" => "akcii"
		),
		array(
			"TITLE" => "Лучшие", 
			"CODE" => "hit"
		),
		
	);
	
	
	public $arTypesKey = array(		
		"nov" => "Новинки",	
		"akcii" => "Акции",
		"hit" => "Лучшие", 		
	);
	
	
	
	
    public function onPrepareComponentParams($arParams)
    {		
		return $arParams;		
    }
	
	public function GetSpecialCount($arFilterN) {
				
				$arFilter = array(
					"ACTIVE" => "Y",
					"!PROPERTY_MSK_VALUE" => array("Нет в наличии", "Снят с производства"),
				);				
				$arFilter = array_merge($arFilter, $arFilterN);				
				
				//pre($arFilter);
				$count = CIBlockElement::GetList(
					array(),
					$arFilter,
					array(),
					false,
					array()
				);
				
				return $count;				
	}

    public function GetFilter($TYPE) {
		
		
		
		
		switch($TYPE) {
			case "nov": 				
				$arFilter = array(
					"PROPERTY_S_NEW" => "Y"
				);
			break;	
			case "akcii": 			
				$arFilter = array(
					"PROPERTY_S_SALE" => "Y"
				);
			break;	
			case "hit": 			
				$arFilter = array(
					"PROPERTY_S_HIT" => "Y"
				);
			break;
		}		
		
		return $arFilter;		
	}
	
    public function GetSort($TYPE) {
		
		switch($TYPE) {
			case "nov": 				
				$arSort = array(
					"NAME" => "DATE_CREATE",
					"ORDER" => "DESC"
				);
			break;	
			case "akcii": 			
				$arSort = array(
					"NAME" => "CATALOG_PRICE_1",
					"ORDER" => "ASC"
				);
			break;	
			case "hit": 			
				$arSort = array(
					"NAME" => "shows",
					"ORDER" => "DESC"
				);
			break;
		}		
		
		return $arSort;		
	}
	
	public function GetTitle($TYPE) {		
				
		
			switch($TYPE) {
				case "akcii": 
				
					$title = strtolower($this->arNames[$this->arBlock["UF_NAME_ID"]]["PROPERTIES"]["KOGO_MORE"]["VALUE"]);	
					if($this->arSection) {
						if($this->arSection["UF_NAME_ID"]) $title = strtolower($this->arNames[$this->arSection["UF_NAME_ID"]]["PROPERTIES"]["KOGO_MORE"]["VALUE"]).' '.$title.' Liebherr';
						else $title = $title.' Liebherr '.strtolower($this->arSection["NAME"]);					
					}
					else {
						$title.=" Liebherr";
					}
				
				
				
					$title = "Распродажа ".$title;
				break;	
				case "nov": 	

					$title = strtolower($this->arBlock["NAME"]);	
					if($this->arSection) {
						if($this->arSection["UF_NAME_ID"]) $title = strtolower($this->arNames[$this->arSection["UF_NAME_ID"]]["NAME"]).' '.$title.' Liebherr';
						else $title = $title.' Liebherr '.strtolower($this->arSection["NAME"]);					
					}
					else {
						$title.=" Liebherr";
					}

				
					$title = "Лучшие ".$title;
				break;
				case "hit": 	

					$title = strtolower($this->arBlock["NAME"]);	
					if($this->arSection) {
						if($this->arSection["UF_NAME_ID"]) $title = strtolower($this->arNames[$this->arSection["UF_NAME_ID"]]["NAME"]).' '.$title.' Liebherr';
						else $title = $title.' Liebherr '.strtolower($this->arSection["NAME"]);					
					}
					else {
						$title.=" Liebherr";
					}

				
					$title = "Лучшие ".$title;
				break;
			}		
			
			return $title;		
	}
	
	
	
    public function executeComponent()
    { 		
			$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();	
	
			$this->arBlock = $this->arParams["BLOCK"]; 
			$this->arBlock = array_merge($this->arBlock, CASDiblockTools::GetIBUF($this->arBlock["ID"]));
			
			
			if($this->arParams["SECTION"])	{
				$this->arSection = $this->arParams["SECTION"];				
				if($this->arSection["UF_NAME_ID"]) {$this->arNames[$this->arSection["UF_NAME_ID"]] = CStatic::getElement($this->arSection["UF_NAME_ID"], 21); }				
			}			
			//pre($this->arSection);			
			$this->arNames[$this->arBlock["UF_NAME_ID"]] = CStatic::getElement($this->arBlock["UF_NAME_ID"], 20);
			
			
			$url = $this->arBlock["CODE"];				
			if($this->arSection) {$url = $url.'/'.$this->arSection["CODE"];}
			
			$arMenus = array();
			
			
			
			foreach($this->arTypes as $type) {
				
				//pre($type);
				
				$arrFilter = array(
					"ACTIVE" => "Y",
					"IBLOCK_ID" => $this->arBlock["ID"],
					"INCLUDE_SUBSECTIONS" => "Y"
				);
				if($this->arSection) {
					$arrFilter["SECTION_ID"] = $this->arSection["ID"];
				}				
				$arrFilter = array_merge($arrFilter, $this->GetFilter($type["CODE"]));
				
				
				//pre($arrFilter);
				
				
				if($count = $this->GetSpecialCount($arrFilter)) {
					
					$arMenu = array(				
						"TITLE" => $this->GetTitle($type["CODE"]),
						"LINK" => "/liebherr/".$url."/type-".$type["CODE"]."/",
					);
					
					$this->arResult["MENU"][] = $arMenu;
				}				
			}
			
		if($this->arParams["MODE"] == 'GetData') {
			
			$arResult = array();
			
			$arResult["TITLE"] = $this->GetTitle($this->arParams["TYPE_CODE"]);
			$arResult["TYPE_TITLE"] = $this->arTypesKey[$this->arParams["TYPE_CODE"]];
			
			$arResult["SORT"] = $this->GetSort($this->arParams["TYPE_CODE"]);
			
				###############################  ПРОВЕРКА ######################################
				
				
				
				$arResult["FILTER"] = $this->GetFilter($this->arParams["TYPE_CODE"]);
				
				$arrFilter = array(
					"ACTIVE" => "Y",
					"IBLOCK_ID" => $this->arBlock["ID"],
					"INCLUDE_SUBSECTIONS" => "Y",
					"PROPERTY_MSK_VALUE" => array("В наличии")
				);
				if($this->arSection) {
					$arrFilter["SECTION_ID"] = $this->arSection["ID"];					
				}				
				$arrFilterN = array_merge($arrFilter, $arResult["FILTER"]);	
				//pre($arrFilter);				
				
				if(!$count = $this->GetSpecialCount($arrFilterN)) {
					
					
					
					$arItems = CStatic::GetListElement(false, $arrFilter, array($arResult["SORT"]["NAME"] => $arResult["SORT"]["ORDER"]), false,true);					
					$arItems = array_slice($arItems, 0 ,48, true);
					$arIds = array_keys($arItems);
					
					//PRE($arIds);
					
					$arResult["FILTER"] = array("ID" => $arIds);
				}
				###############################  ПРОВЕРКА END  ######################################
			
			
			
				
			
			
			
			
			
			return $arResult;
		}	 
		else {
			$this->IncludeComponentTemplate();
		}
			
		
    }
}