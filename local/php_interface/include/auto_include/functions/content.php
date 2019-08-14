<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

function phone()
{
	return $GLOBALS["SITE_CONFIG"]["SESSION_PARAMS"]["REGION_INFO"]["PHONE"];
}

function phone_free()
{
	return $GLOBALS["SITE_CONFIG"]["SESSION_PARAMS"]["REGION_INFO"]["PHONE_FREE"];
}

function email()
{
	return $GLOBALS["SITE_CONFIG"]["SESSION_PARAMS"]["REGION_INFO"]["EMAIL"];
}
?>