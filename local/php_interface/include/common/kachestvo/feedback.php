<?
$bAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ? true : false ;

$uploadDirectory = "/upload/assets/kachestvo/tmp/";

if($bAjax)
{
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
	//foreach($_REQUEST["SUPPORT"] as $k => $v)
	//{
	//	if(!is_array($v))
	//		$_REQUEST["SUPPORT"][$k] = iconv('utf-8', 'windows-1251', $v);
	//}
	
	//AddMessage2Log("_REQUEST[SUPPORT]: ".print_r($_REQUEST["SUPPORT"], true));
}
else
{
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
		die();
	
	// удаление старых файлов из временной директории
	$arFiles = scandir($_SERVER["DOCUMENT_ROOT"].$uploadDirectory);
	if(is_array($arFiles))
	{
		$curTime = time();
		
		foreach($arFiles as $file)
		{
			// текущий и родительский каталог
			if(($file == '.') || ($file == '..'))
				continue;
			
			$filePath = $_SERVER["DOCUMENT_ROOT"].$uploadDirectory.$file;
			$fileModTime = filemtime($filePath);
			
			// удаляются файлы старше часа
			if($curTime - $fileModTime > 3600)
				unlink($filePath);
			
			//echo $file." [".($curTime - $fileModTime)."]<br />";
		}
	}
}

$iblockType = "common";
$iblockCode = "claims";
//$iblockID = "175";
$iblockID = "171";

//$resIb = CIBlock::GetList(array("id" => "desc"), array("TYPE" => $iblockType, "ACTIVE" => "Y", "CODE" => $iblockCode), false);
//if($arFieldsIb = $resIb->Fetch())
//	$iblockID = $arFieldsIb["ID"];

//$arPropsPreset = array("STATUS" => "7768");
$arPropsPreset = array("STATUS" => "7881");

if(!empty($iblockID))
{
	$APPLICATION->IncludeComponent(
		"mnteam:form.feedback.new",
		"claim_3",
		Array(
			"SITE_LINK" => "SITE",
			"SITE_NAME" => $_SERVER["SERVER_NAME"]." [".SITE_ID."]",
			"ELEMENT_NAME_TEMPLATE" => $_SERVER["SERVER_NAME"]." [#NAME#] [#EMAIL#]",
			"CREATE_ELEMENT" => "Y",
			"ELEMENT_ACTIVE" => "Y",
			"PROPS_PRESET" => $arPropsPreset,
			"IS_AJAX" => $bAjax,
			"IBLOCK_TYPE" => $iblockType,
			"IBLOCK_ID" => $iblockID,
			//"CATALOG_IBLOCK_TYPE" => $GLOBALS["SITE_CONFIG"]["IBLOCK_TYPES"]["CATALOG"],
			"USER_SECTION_SELECT" => "N",
			"SECTION_ID" => "",
			"ELEMENT_ID_LINK" => "PRODUCT",
			"USER_ID_LINK" => "USER",
			"VAR_NAME" => "SUPPORT",
			"USE_CAPTCHA" => "N",
			"CHECK_AUTH" => "N",
			"OK_TEXT" => "Cообщение отправлено.",
			"GENERAL_FIELDS" => array("NAME", "EMAIL", "PHONE", "ORDER", "MESSAGE", "FILES"),
			"GENERAL_FIELDS_REQUIRED" => array("NAME", "EMAIL", "PHONE", "MESSAGE"),
			"GENERAL_FIELDS_NAME" => array("NAME" => "Как к вам обращаться", "EMAIL" => "Электронная почта", "PHONE" => "Телефон", "ORDER" => "№ заказа (если есть)", "MESSAGE" => "Опишите ситуацию", "FILES" => "Загрузите приложения"),
			"GENERAL_FIELDS_FILE" => array("FILES"),
			"GENERAL_FIELDS_MULTIPLE" => array("FILES"),
			"AJAX_FILE_UPLOAD" => "Y",
			"UPLOAD_DIRECTORY" => $uploadDirectory,
			"FIELD_TITLE" => "",
			"FIELD_TEXT" => "",
			"FIELD_EMAIL" => "EMAIL",
			"FIELD_PHONE" => "PHONE",
			"ELEMENT_ID" => "",
			"EVENT_TYPE_ID" => "CLAIM_FORM",
			"MAX_USER_REQUESTS" => "0",
		),
	false
	);
}

if($bAjax)
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>