<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main;





class blockList extends CBitrixComponent
{

    public $arResult;
    public $FILTER = array();

    public function onPrepareComponentParams($arParams)
    {       	   
		return $arParams;
    }



	public function GetData()  {
		
				Cmodule::IncludeModule('asd.iblock');
				
				$arFilter = Array(
						'TYPE'=>'mn_catalog', 
						'SITE_ID'=> SITE_ID, 
						'ACTIVE'=>'Y', 						
						'!CODE'=> false, 						
				);
		
				//pre($arFilter);
		
				$res = CIBlock::GetList(
					Array("SORT" => "ASC"), 
					$arFilter, 
					false
				);
				while($arBlock = $res->GetNext())
				{												
						//pre($arBlock);	
						$arBlock["LIST_PAGE_URL"] = '/catalog/'.$arBlock["CODE"].'/';						
						$arFields = CASDiblockTools::GetIBUF($arBlock["ID"]);
						
						
						if($arFields["UF_PICTURE_ICO"]) {
							$arImg = CFile::ResizeImageGet($arFields["UF_PICTURE_ICO"], array("width" => 245, "height" => 340), BX_RESIZE_IMAGE_PROPORTIONAL, true);
							$arBlock["PICTURE_V"] = $arImg["src"];
						}
						
						//$arBlock["SECTIONS"] = CStatic::GetSectionList($arBlock["ID"]);		
						
						//pre($arBlock);
						
						
						$arResult[] = $arBlock;	
				}
				
				$arResultSec = CStatic::GetSectionList(CStatic::$accIdBlock);		
				
				
				
				$arBlockN = array();
				if(in_array($this->arParams["PLACE"], array('BOTTOM', 'TOP'))) {
					
					$arBlockItem = array(
						"NAME" => "Техника",
						"LIST_PAGE_URL" => "#",
						"CODE" => "#",
						"ITEMS" => array()
					);
					
					//$arId = array(86,73,74,75,76);
					foreach($arResult as $k=>$arBlock) {
						//if(in_array($arBlock["ID"], $arId)) 
						{
							$arBlockItem["ITEMS"][] = $arBlock;
						}						
					}
					
					$arBlockN[] = $arBlockItem;
					
					
					
					/*
					$arBlockItem = array(
						"NAME" => "Аксессуары",
						"LIST_PAGE_URL" => "/catalog/aksessuary-monsher/",
						"CODE" => "aksessuary-monsher",
						"ITEMS" => array()
					);
					
					foreach($arResultSec as $k=>$arSection) {	

						$arSection["LIST_PAGE_URL"] = $arSection["SECTION_PAGE_URL"];
						$arBlockItem["ITEMS"][] = $arSection;												
					}
					$arBlockN[] = $arBlockItem;					
					*/
					
					
					
					$arResult = $arBlockN;
					
					
					//array_splice($arResult, 8, 0, [$arBlockN]);
					//array_unshift($arResult, $arBlockN);
					
				}
				
				
				
				//PRE($arResult);
				
				$this->arResult["ITEMS"] = $arResult;
		
	}



    public function executeComponent()
    {
        
		$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        CModule::IncludeModule('iblock');  
   
        global $USER;        
        global $APPLICATION;    
			
			
            $this->GetData();
			
            $this->IncludeComponentTemplate();
			
        
    }
}