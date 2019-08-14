<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

AddEventHandler("main", "OnEpilog", "Redirect404");

function Redirect404()
{
	define("PATH_TO_404", "/404.php");
	if(
		!defined("ADMIN_SECTION") && 
		defined("ERROR_404") && 
		//defined("CONFIRM_ERROR_404") && 
		defined("PATH_TO_404") && 
		file_exists($_SERVER["DOCUMENT_ROOT"].PATH_TO_404)
	) {
		//LocalRedirect("/404.php", "404 Not Found");
		
		$templatePath = SITE_TEMPLATE_PATH;
		
		global $APPLICATION;
		$APPLICATION->RestartBuffer();
		
		global $USER;
		if(!is_set($USER))
			$USER = new CUSER;
		$menu_tip = 'nofind';
		include($_SERVER["DOCUMENT_ROOT"].$templatePath."/header.php");
		include($_SERVER["DOCUMENT_ROOT"].PATH_TO_404);
		include($_SERVER["DOCUMENT_ROOT"].$templatePath."/footer.php");
	}
}
?>