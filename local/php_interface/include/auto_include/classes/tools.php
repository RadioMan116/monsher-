<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

class CMNT_Tools
{
	function isPhone()
	{
		if( stristr($_SERVER['HTTP_USER_AGENT'],'windows') && !stristr($_SERVER['HTTP_USER_AGENT'],'windows ce') ){
		return false;
		}
		if( eregi('up.browser|up.link|windows ce|iemobile|mini|mmp|symbian|midp|wap|phone|pocket|mobile|pda|psp|ipad|iphone|android', $_SERVER['HTTP_USER_AGENT']) ){
			return true;
		}
		return false;
	}
}
?>