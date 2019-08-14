<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

AddEventHandler("search", "BeforeIndex", Array("CMNTCommonSearchEvents", "AddTitleNoSpacesStr"));

class CMNTCommonSearchEvents
{
	function AddTitleNoSpacesStr($arFields)
	{
		$arCatalogIbType = $GLOBALS["SITE_CONFIG"]["IBLOCK_TYPES"]["CATALOG"];
		
		if($arFields["MODULE_ID"] == "iblock" && $arFields["PARAM1"] == $arCatalogIbType && $arFields["PARAM2"] > 0)
		{
			// в TITLE добавляется модель без пробелов
			
			$modelCode = $GLOBALS["SITE_CONFIG"]["PRODUCT_PARAMS"]["MODEL"];
			
			if(strlen($modelCode) > 0)
			{
				$resEl = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arFields["PARAM2"], "ID" => $arFields["ITEM_ID"]), false, array("nTopCount" => "1"), array("ID", "IBLOCK_ID", "PROPERTY_".$modelCode));
				if($arFieldsEl = $resEl->Fetch())
				{
					$arFieldsEl["PROPERTY_".$modelCode."_VALUE"] = trim($arFieldsEl["PROPERTY_".$modelCode."_VALUE"]);
					if(strlen($arFieldsEl["PROPERTY_".$modelCode."_VALUE"]) > 0)
					{
						$arFieldsEl["PROPERTY_".$modelCode."_VALUE"] = str_replace(array("-"), array(""), $arFieldsEl["PROPERTY_".$modelCode."_VALUE"]);
						$strNoSpaces = preg_replace("~\s~", "", $arFieldsEl["PROPERTY_".$modelCode."_VALUE"]);
						$arFields["TITLE"] .= " __ ".$strNoSpaces;
						
						//AddMessage2Log("Common BeforeIndex AddTitleNoSpacesStr: ".print_r($arFields, true));
					}
				}
			}
		}
		
		return $arFields;
	}
}
?>