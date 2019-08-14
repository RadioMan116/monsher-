<?php
use \Bitrix\Main;


class TechDocs extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {		
		return $arParams;		
    }

	public function GetData() {
		
			$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();		
		
		
			$arParams = $this->arParams;
		
			$propCode = 'DOCUMENTATION';
			if($arParams["PROP_CODE"]) $propCode = $arParams["PROP_CODE"];
		
		
			$arBlocksList = array();					
			$arProductsList = array();	
			$arItemsList = array();	
					
			$arFilter = array(
				"ACTIVE" => "Y",
				"ID" => CStatic::$catalogIdBlock
			);
			$rsIB = CIBlock::GetList(
                        Array("SORT" => "ASC"),
                        $arFilter,
                        false
            );
            while($arBlock = $rsIB->GetNext()) {				
				$filter = array(
					"IBLOCK_ID" => $arBlock["ID"],
					"ACTIVE" => "Y",
					"!PROPERTY_".$propCode => false
				);
				
				if(CStatic::getElementCount($filter)) {
					$arBlock["ITEMS"] = array();
					$arBlocksList[$arBlock["ID"]] = $arBlock;
				}				
			}	
			
		
			if((int)$arParams["IBLOCK_ID"]) $block_id = (int)$arParams["IBLOCK_ID"];
			if((int)$arParams["SECTION_ID"]) $section_id = (int)$arParams["SECTION_ID"];
			
			//pre($arParams);

			if($block_id) {					
				
				$filter = array(
					"IBLOCK_ID" => $block_id,
					"ACTIVE" => "Y",
					"!PROPERTY_".$propCode => false
				);	

				if($section_id) $filter["IBLOCK_SECTION_ID"] = $section_id;

				
				//PRE($filter);
				$limit = false;
				$sort = array("SORT" => "ASC","NAME" => "ASC");
				if($arParams["LIMIT"]) $limit = array("nTopCount" => $arParams["LIMIT"]);
				if($arParams["RAND"]) $sort = array("RAND" => "");
				
				$db_res = CIBlockElement::GetList(
						$sort,
						$filter,
						false,
						$limit,
						array(
							"ID",
							"NAME",
							"IBLOCK_ID",
							//"PROPERTY_DOCUMENTATION",
						)
				);
				
				while ($ob_res = $db_res->GetNextElement())
				{
					$arProduct = $ob_res->GetFields();
					$arProduct['PROPERTIES'] = $ob_res->GetProperties();

					if(!is_array($arProduct["PROPERTIES"][$propCode]["VALUE"])) $arProduct["PROPERTIES"][$propCode]["VALUE"] = array($arProduct["PROPERTIES"][$propCode]["VALUE"]);

					//pre($arProduct["PROPERTIES"][$propCode]);

					$arItemsList = array_merge($arItemsList, $arProduct["PROPERTIES"][$propCode]["VALUE"]);
					
					$arProductsList[$arProduct["ID"]] = $arProduct;				
				}			

			}
			
					
		$this->arResult["ITEMS_ALL"] = $arItemsList;
		$this->arResult["PRODUCTS"] = $arProductsList;
		$this->arResult["BLOCKS"] = $arBlocksList;
	}
	
	
    public function executeComponent()
    { 		
		$this->GetData();
		
		
		if($this->arParams["GET_INFO"]) {
			return $this->arResult["ITEMS_ALL"];
		}
		else {
			$this->IncludeComponentTemplate();
		}
    }
}
