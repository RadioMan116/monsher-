<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

$arServiceLinks = array(
	"s1" => "/service/", // Neff
	"s3" => "/service/", // Smeg
	"s4" => "/service/", // Gorenje
	"s5" => "/service/", // Liebherr
);

$APPLICATION->IncludeComponent(
	"mnteam:api",
	"claim_page_3",
	Array(
		"SERVICE_LINKS" => $arServiceLinks,
		"IBLOCK_TYPE" => "common",
		"IBLOCK_CODE" => "text",
		"ELEMENT_CODE" => "pravila-priema-reklamatsionnykh-obrashcheniy-3",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
	),
	false
);
?>