<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//AddMessage2Log("autoinclude/index.php");

$includePathParent = dirname(__file__) . "/../../../../bitrix/php_interface/include/auto_include/classes";
$arFiles = glob($includePathParent."/*.php", GLOB_BRACE);
foreach($arFiles as $filePath)
	include_once($filePath);
	

$includePath = dirname(__file__);
$arFiles = glob($includePath."/{configs,classes,functions,events,modules}/*.php", GLOB_BRACE);

foreach($arFiles as $filePath)
	include_once($filePath);
?>