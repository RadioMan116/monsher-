<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("CMNTYmarketEvents", "OnAfterIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("CMNTYmarketEvents", "OnAfterIBlockElementAddHandler"));
AddEventHandler("iblock", "OnIBlockElementDelete", Array("CMNTYmarketEvents", "OnIBlockElementDeleteHandler"));

class CMNTYmarketEvents
{
	function OnAfterIBlockElementUpdateHandler(&$arFields)
	{
		if($arFields["RESULT"])
			CMNTStateFile::Event("OnAfterIBlockElementUpdate");
	}
	
	function OnAfterIBlockElementAddHandler(&$arFields)
	{
		if($arFields["ID"] > 0)
			CMNTStateFile::Event("OnAfterIBlockElementAdd");
	}
	
	function OnIBlockElementDeleteHandler($PRODUCT_ID)
	{
		if($PRODUCT_ID > 0)
			CMNTStateFile::Event("OnIBlockElementDelete");
	}
}
?>