<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

// убираем лишние пробелы

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "TrimElNameSpaces");
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "TrimElNameSpaces");

function TrimElNameSpaces(&$arFields)
{
	if(array_key_exists("NAME", $arFields))
		$arFields["NAME"] = trim($arFields["NAME"]);
	if(array_key_exists("CODE", $arFields))
		$arFields["CODE"] = trim($arFields["CODE"]);
}
?>