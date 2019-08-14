<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

$currentFilePath = dirname(__file__) . "/tmp/current.csv";
$fileMissingProductsFilePath = dirname(__file__) . "/tmp/missing_products_file.csv";
$siteMissingProductsFilePath = dirname(__file__) . "/tmp/missing_products_site.csv";
$fileMissingProductsLink = "/tools/import_price_csv/tmp/missing_products_file.csv";
$siteMissingProductsLink = "/tools/import_price_csv/tmp/missing_products_site.csv";
$iblockType = "mn_catalog";
$brandsIblockID = 3;
$countryIblockID = 18;
$propModelCode = "MODEL";
$propBrandCode = "_BRAND";
$propCountryCode = "COUNTRY";
$priceID = "1";
$storeCode = "MSK";
?>