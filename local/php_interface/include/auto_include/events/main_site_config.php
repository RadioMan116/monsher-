<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(defined("SITE_ID"))
	AddEventHandler("main", "OnProlog", "SetSiteConfig");

function SetSiteConfig()
{
	include_once($_SERVER["DOCUMENT_ROOT"].BITRIX_PERSONAL_PHP_INTERFACE."/include/site_config.php");
}
?>