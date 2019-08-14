<?php
error_reporting(E_ERROR);

//require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/class_ipgeo.php');



include_once(dirname(__FILE__)."/include/tools.php");
include_once(dirname(__FILE__)."/include/functions.php");



CModule::AddAutoloadClasses(
    '',
    array(
        'CStatic' => '/local/php_interface/include/class/CStatic.php',
    )
);

AddEventHandler("sale", "OnOrderNewSendEmail", Array("CStatic", "MailOrderAddProps"));

//AddEventHandler("main", "OnPageStart", Array("CStatic", "RedirectInit"));
AddEventHandler("main", "OnPageStart", Array("CStatic", "RedirectByData"));

AddEventHandler("main", "OnBeforeProlog", Array("CStatic", "RegionChange"));
AddEventHandler("main", "OnBeforeProlog", Array("CStatic", "RegionInit"));

AddEventHandler("main", "OnEpilog", "Redirect404");
AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", "OnSaleComponentOrderOneStepCompleteHandler");
AddEventHandler("main", "OnEpilog",  Array("CStatic", "PageCanonical"));

/* add SEARCH_INDEX and reindex */
	$ListenAddElementFile = $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/auto_include/events/ListenAddElement.php";
	AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("ListenAddElement", "main"), 100, $ListenAddElementFile);
	AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("ListenAddElement", "main"), 100, $ListenAddElementFile);
/* (add SEARCH_INDEX and reindex) */

/* Filter Props Display */
	AddEventHandler("iblock", "OnAfterIBlockElementAdd",Array("CStatic", "FilterPropsDisplayAdd"), 110 );
	AddEventHandler("iblock", "OnBeforeIBlockElementUpdate",Array("CStatic", "FilterPropsDisplayUpdate"), 110);
	AddEventHandler("iblock", "OnBeforeIBlockElementDelete",Array("CStatic", "FilterPropsDisplayDelete"), 110);
/* Filter Props Display end */


	//AddEventHandler("iblock", "OnAfterIBlockElementAdd",Array("CStatic", "SortByWordstat"), 110 );
	//AddEventHandler("iblock", "OnAfterIBlockElementUpdate",Array("CStatic", "SortByWordstat"), 110);


function OnSaleComponentOrderOneStepCompleteHandler($id){
	return $id;
}



## sync orders and other legacy for t50 ##
require_once(dirname(__FILE__) . "/include/auto_include/index.php");


require_once(dirname(__FILE__) . "/include/Mn/Service/Bootstrap/Bootstrap.php");
$bootstrap = new MnServiceBootstrapBootstrap();
$bootstrap
    ->initLib()
    ->initService()
;

require_once($_SERVER["DOCUMENT_ROOT"]."/ext/init.php");


require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events.php');
#-------------------------------------------
