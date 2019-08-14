<?
// prolog
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include($_SERVER["DOCUMENT_ROOT"]."/.top.menu.php");
$aMenuLinks_TOP = $aMenuLinks;
include($_SERVER["DOCUMENT_ROOT"]."/.bottom.menu.php");
$aMenuLinks_BOTTOM = $aMenuLinks;
include($_SERVER["DOCUMENT_ROOT"]."/.left.menu.php");
$aMenuLinks_LEFT = $aMenuLinks;

$aMenuLinks = array();
//echo '<br> menu top:';
foreach($aMenuLinks_TOP as &$menu) {
	
	//pre($menu);
	
	IF(!$aMenuLinks[$menu[1]]) $aMenuLinks[str_replace('/','',$menu[1])] = $menu;
}
//echo '<br> menu bottom:';
foreach($aMenuLinks_BOTTOM as &$menu) {
	
	//pre($menu);
	IF(!$aMenuLinks[$menu[1]]) $aMenuLinks[str_replace('/','',$menu[1])] = $menu;
}

//echo '<br> menu left:';
foreach($aMenuLinks_LEFT as &$menu) {
	
	//pre($menu);
	IF(!$aMenuLinks[$menu[1]]) $aMenuLinks[str_replace('/','',$menu[1])] = $menu;
}
//echo '<br> menu all:';
//PRE($aMenuLinks);

function GetPagePages ($site_url, $url, $page_kol, $count, $pagen = false, $time = false) {
	
	if(!$time) $time = ConvertTimeStamp(time(), "FULL");	
	if(!$pagen) $pagen = 1;	
	$htmlOut = '';	
	//echo '<br/>'.$url;
	//echo '<br/>'.$count.' > '.$page_kol;
	
	
	if($count > $page_kol) {
		$page_max = ceil($count/$page_kol);
				
		$page = 1;
		while($page < $page_max) {
			
			//echo '<br/>'.$page.' > '.$page_max;
			$page++;
			$htmlOut .= CMNTSitemap::getUrlItem($site_url, $url.'?PAGEN_'.$pagen.'='.$page, $time);				
		}			
	}
	
	return $htmlOut;
}

set_time_limit(600);

// check access
if(!((isset($bCron) && $bCron == true) || $USER->IsAdmin()))
	die();

// check working directories
$WORK_DIR = dirname(__file__);

if(!file_exists($WORK_DIR."/tmp"))
{
	if(!mkdir($WORK_DIR."/tmp", BX_DIR_PERMISSIONS))
		die("unable to create directory ".$WORK_DIR."/tmp");
}

if(!file_exists($WORK_DIR."/tmp/log"))
{
	if(!mkdir($WORK_DIR."/tmp/log", BX_DIR_PERMISSIONS))
		die("unable to create directory ".$WORK_DIR."/tmp/log");
}

// options
$STATE_FILE = $WORK_DIR."/tmp/state.txt";
$UPDATE_FILE = $WORK_DIR."/tmp/update.txt";
$READY_XML_FILE = $_SERVER["DOCUMENT_ROOT"]."/sitemap.xml";
//$READY_XML_FILE = $_SERVER["DOCUMENT_ROOT"]."/tools/sitemap.xml";
$LOG_FILE = $WORK_DIR."/tmp/log/log_sitemap_".date("Y.m").".txt";

$UPDATE_TIMEOUT = "43200"; // 12 часов
//$UPDATE_TIMEOUT = "3600"; // час

//include "../config.php";
include $WORK_DIR . "/../config.php";



$SITE_ID = $GLOBALS["SITE_ID"];
$IBLOCK_TYPE = $GLOBALS["IBLOCK_TYPE"];

// work mode
$workMode = "manual";
$bStartProcessing = false;

if((isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "cron") || (isset($bCron) && $bCron == true))
{
	$workMode = "cron";
	$bStartProcessing = true;
}

if(isset($_REQUEST["start"]))
	$bStartProcessing = true;


// проверка необходимости обновления
$arUpdateStatus = CMNTStateFile::Read($UPDATE_FILE);

$bNoUpdateStatus = empty($arUpdateStatus) ? true : false;
$bUpdateTimeout = false;

$curTime = getmicrotime();
if(!empty($arUpdateStatus["MICROTIME"]) && $curTime - $arUpdateStatus["MICROTIME"] > $UPDATE_TIMEOUT)
	$bUpdateTimeout = true;

// если обновление не требуется, то обработка не запускается при вызове скрипта по крон
if($workMode == "cron" && !$bNoUpdateStatus && !$arUpdateStatus["UPDATE"] && !$bUpdateTimeout)
	$bStartProcessing = false;

if($workMode == "cron" && !$bStartProcessing)
	CMNTLog::Add("\nMODE: cron\nОбновление не требуется", $LOG_FILE);

$arMessages = array();
$arErrors = array();

// export xml data
if($bStartProcessing)
{
	// processing timer
	$startTime = getmicrotime();
	
	if(!CModule::IncludeModule("iblock"))
		die();
	
	// site info
	$rsSites = CSite::GetByID(SITE_ID);
	$arSite = $rsSites->Fetch();
	$arSiteInfo["SITE_URL"] = "https://".$arSite["SERVER_NAME"];
	
	$xmlOut = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlOut .= "<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	
	
	// пункты меню
	array_unshift($aMenuLinks, array("Главная", "/", array(), array(), ""));
	foreach($aMenuLinks as $arItem)
	{
		$url = $arItem[1];
		if($url) {
			$filename = $_SERVER["DOCUMENT_ROOT"].$url;
			if(preg_match("~^.*/$~", $filename))
				$filename .= "index.php";
			
			//if(file_exists($filename))
			{
				$time = ConvertTimeStamp(filemtime($filename), "FULL");
				$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], $url, $time);
			}
		}
	}
	
	// catalog
	$arCatalogAll["SECTIONS"] = array();
	$arCatalogAll["BLOCKS"] = array();
	$PAGE_KOL = 12;
	$resIb = CIBlock::GetList(array("sort" => "asc"), array("TYPE" => $IBLOCK_TYPE, "ACTIVE" => "Y", "CNT_ACTIVE" => "Y"), false);
	while($arFieldsIb = $resIb->Fetch())
	{
				
					$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], '/liebherr/'.$arFieldsIb["CODE"].'/', $arFieldsIb["TIMESTAMP_X"]);
				
					##################################### типовые страницы #######################################
					
					
					// SALES
					$arrFilter = array(
						"IBLOCK_ID" => $arFieldsIb["ID"],
						//"PROPERTY_S_SALE" => "Y"
					);
					if($count = CStatic::GetSpecialCount($arrFilter)) {				
						$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], '/liebherr/'.$arFieldsIb["CODE"].'/type-akcii/', $arFieldsIb["TIMESTAMP_X"]);

						if($count > $PAGE_KOL) {
							//$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], '/liebherr/'.$arFieldsIb["CODE"].'/type-akcii/', $PAGE_KOL, $count, 2);
						}
					}
					
					// HIT
					$arrFilter = array(
						"IBLOCK_ID" => $arFieldsIb["ID"],
						//"PROPERTY_S_HIT" => "Y"
					);
					if($count = CStatic::GetSpecialCount($arrFilter)) {				
						$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], '/liebherr/'.$arFieldsIb["CODE"].'/type-hit/', $arFieldsIb["TIMESTAMP_X"]);

						if($count > $PAGE_KOL) {
							//$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], '/liebherr/'.$arFieldsIb["CODE"].'/type-hit/', $PAGE_KOL, $count, 2);
						}
					}
					
					//$DATE_LAST_YEAR = date('d.m.Y', mktime(0, 0, 0, (int)date('m'), (int)date("d"), (int)(date("Y")-1)));						
					
					$arrFilter = array(
						"IBLOCK_ID" => $arFieldsIb["ID"],
						//"PROPERTY_S_NEW" => "Y"
					);		
					if($count = CStatic::GetSpecialCount($arrFilter)) {				
						$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], '/liebherr/'.$arFieldsIb["CODE"].'/type-nov/', $arFieldsIb["TIMESTAMP_X"]);
						
						if($count > $PAGE_KOL) {
							//$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], '/liebherr/'.$arFieldsIb["CODE"].'/type-nov/', $PAGE_KOL, $count, 2);
						}
					}
					
				
					##################################### типовые страницы END #######################################
					##################################### постраничка инфоблоков #######################################
					
					$arrFilter = array(
						"IBLOCK_ID" => $arFieldsIb["ID"],
						"ACTIVE" => "Y"
					);
					$count = CStatic::GetSpecialCount($arrFilter);
					if($count > $PAGE_KOL) {
						$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], '/liebherr/'.$arFieldsIb["CODE"].'/', $PAGE_KOL, $count, 2);
					}
					
					
					##################################### постраничка инфоблоков end #######################################
					
					##################################### Разделы каталога ###########################################
				
					$resSec = CIBlockSection::GetList(array("left_margin" => "asc"), array("IBLOCK_ID" => $arFieldsIb["ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"), false, array("UF_*"));
					while($arFieldsSec = $resSec->GetNext()) {
						$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], $arFieldsSec["SECTION_PAGE_URL"], $arFieldsIb["TIMESTAMP_X"]);
					
						$arCatalogAll["SECTIONS"][$arFieldsSec["ID"]] = $arFieldsSec;
						
						
						##################################### постраничка РАЗДЕЛОВ #######################################			
						$arrFilter = array(
							"IBLOCK_ID" => $arFieldsIb["ID"],
							"SECTION_ID" => $arFieldsSec["ID"],
							"ACTIVE" => "Y"
						);
						$count = CStatic::GetSpecialCount($arrFilter);						
							
						if($count > $PAGE_KOL) {
							
							$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], $arFieldsSec["SECTION_PAGE_URL"], $PAGE_KOL, $count, 2);
						}	
						##################################### постраничка РАЗДЕЛОВ end #######################################
						
						##################################### РАЗДЕЛЫ special #######################################
						
						
						##################################### типовые страницы #######################################
					
						
						// SALES
						$arrFilter = array(
							"IBLOCK_ID" => $arFieldsIb["ID"],
							"SECTION_ID" => $arFieldsSec["ID"],
							//"PROPERTY_S_SALE" => "Y"
						);
						if($count = CStatic::GetSpecialCount($arrFilter)) {				
							$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], $arFieldsSec["SECTION_PAGE_URL"].'type-akcii/', $arFieldsIb["TIMESTAMP_X"]);

							if($count > $PAGE_KOL) {
								//$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], $arFieldsSec["SECTION_PAGE_URL"].'type-akcii/', $PAGE_KOL, $count, 2);
							}
						}
						
						// HIT
						$arrFilter = array(
							"IBLOCK_ID" => $arFieldsIb["ID"],
							"SECTION_ID" => $arFieldsSec["ID"],
							//"PROPERTY_S_HIT" => "Y"
						);
						if($count = CStatic::GetSpecialCount($arrFilter)) {				
							$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], $arFieldsSec["SECTION_PAGE_URL"].'type-hit/', $arFieldsIb["TIMESTAMP_X"]);

							if($count > $PAGE_KOL) {
								//$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], $arFieldsSec["SECTION_PAGE_URL"].'type-hit/', $PAGE_KOL, $count, 2);
							}
						}
						
						//$DATE_LAST_YEAR = date('d.m.Y', mktime(0, 0, 0, (int)date('m'), (int)date("d"), (int)(date("Y")-1)));						
						
						$arrFilter = array(
							"IBLOCK_ID" => $arFieldsIb["ID"],
							"SECTION_ID" => $arFieldsSec["ID"],
							//"PROPERTY_S_NEW" => "Y"
						);		
						if($count = CStatic::GetSpecialCount($arrFilter)) {				
							$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], $arFieldsSec["SECTION_PAGE_URL"].'type-nov/', $arFieldsIb["TIMESTAMP_X"]);
							
							if($count > $PAGE_KOL) {
								//$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], $arFieldsSec["SECTION_PAGE_URL"].'type-nov/', $PAGE_KOL, $count, 2);
							}
						}
						
					
						##################################### типовые страницы END #######################################
						
					}
					##################################### Разделы каталога END #######################################	
					
					##################################### Товары каталога ############################################
					
					
					$arrFilter = array("IBLOCK_ID" => $arFieldsIb["ID"], "ACTIVE" => "Y");
					$resEl = CIBlockElement::GetList(array("sort" => "asc", "id" => "desc"), $arrFilter, false, false, array("ID", "IBLOCK_ID", "CODE", "TIMESTAMP_X", "DETAIL_PAGE_URL"));
					while($arFieldsEl = $resEl->GetNext())
					{						
						$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], $arFieldsEl["DETAIL_PAGE_URL"], $arFieldsEl["TIMESTAMP_X"]);						
					}
							
					##################################### Товары каталога end ##########################################	
						
					$arCatalogAll["BLOCKS"][$arFieldsIb["ID"]] = $arFieldsIb;	


			
	}
	
	
	
	
				##################################### постраничка статей #######################################			
				$arrFilter = array(
					"IBLOCK_ID" => 41,					
					"ACTIVE" => "Y"
				);			
				$count = CStatic::GetSpecialCount($arrFilter);
				if($count > $PAGE_KOL) {
					$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], "/news/", $PAGE_KOL, $count, 1);
				}	
				##################################### постраничка СТАТЕЙ end #######################################
				
	$PAGE_KOL = 12;
	// Акции и статьи
	$resEl = CIBlockElement::GetList(array("sort" => "asc", "id" => "desc"), array("IBLOCK_ID" => 41, "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "CODE", "TIMESTAMP_X", "DETAIL_PAGE_URL"));
	while($arFieldsEl = $resEl->GetNext())
	{
		$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], $arFieldsEl["DETAIL_PAGE_URL"], $arFieldsEl["TIMESTAMP_X"]);						
	}	
	
	
	// теговые страницы
	
	
	$arTagPages = array();
	$arTagPagesIds = array();
	
	$PAGE_KOL = 12;
	$resEl = CIBlockElement::GetList(array("sort" => "asc", "id" => "desc"), array("IBLOCK_ID" => 16, "SECTION_ID" => 33, "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "TIMESTAMP_X", "PROPERTY_COUNT"));
	while($res = $resEl->GetNextElement())
	{
		
		$arFieldsEl = $res->GetFields();
		$arFieldsEl["PROPERTIES"] = $res->GetProperties();
		
		
		$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], $arFieldsEl["NAME"], $arFieldsEl["TIMESTAMP_X"]);	

				##################################### постраничка тегов #######################################	
				$count = (int)$arFieldsEl["PROPERTIES"]["COUNT"]["VALUE"];
				
				$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], $arFieldsEl["NAME"], $PAGE_KOL, $count, 2);				
				##################################### постраничка тегов end #######################################
				
			if($arFieldsEl["PROPERTIES"]["BLOCK_URL"]["VALUE"]) {
				if(!is_array($arFieldsEl["PROPERTIES"]["BLOCK_URL"]["VALUE"])) $arFieldsEl["PROPERTIES"]["BLOCK_URL"]["VALUE"] = array($arFieldsEl["PROPERTIES"]["BLOCK_URL"]["VALUE"]);
				
				foreach($arFieldsEl["PROPERTIES"]["BLOCK_URL"]["VALUE"] as $page_url) {
					
					$key_url = str_replace('/','_',$page_url);
					
					if(!$arTagPages[$key_url]) $arTagPages[$key_url] = array();
					$arTagPages[$key_url][] = $arFieldsEl["ID"];
				}
			}
			
			if($arFieldsEl["PROPERTIES"]["BLOCK_ID"]["VALUE"]) {
				if(!is_array($arFieldsEl["PROPERTIES"]["BLOCK_ID"]["VALUE"])) $arFieldsEl["PROPERTIES"]["BLOCK_ID"]["VALUE"] = array($arFieldsEl["PROPERTIES"]["BLOCK_ID"]["VALUE"]);
				
				foreach($arFieldsEl["PROPERTIES"]["BLOCK_ID"]["VALUE"] as $blk_id) {
					
					$arBlock = $arCatalogAll["BLOCKS"][$blk_id];
					$key_url = '_liebherr_'.$arBlock["CODE"].'_';		
					
					if(!$arTagPages[$key_url]) $arTagPagesIds[$key_url] = array();					
					$arTagPagesIds[$key_url][] = $arFieldsEl["ID"];
				}
			}
		
	}
	
	if($arTagPages) {
		foreach($arTagPages as $page=>$ids)
		{
			$ids = array_unique($ids);			
			if(count($ids) > 5) {
				$page_url = str_replace('_','/',$page);
				$page_url = str_replace('/liebherr/','/tags/',$page_url);
				$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], $page_url, false);
			}
		}
	}
	
	
	
	
	//PRE($arTagPages);
	
	
	
	
	
	
	
	######################################### отзывы ##################################################
	
	$arReviewsTovsIdsCount = array();
	$resEl = CIBlockElement::GetList(array("sort" => "asc", "id" => "desc"), array("IBLOCK_ID" => CStatic::$ReviewsIdBlock, "SECTION_ID" => CStatic::$ReviewsIdSec, "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "PROPERTY_TOV_ID", "TIMESTAMP_X"));
	while($arFieldsEl = $resEl->GetNext())
	{
		if(!$arReviewsTovsIdsCount[$arFieldsEl["PROPERTY_TOV_ID_VALUE"]]) $arReviewsTovsIdsCount[$arFieldsEl["PROPERTY_TOV_ID_VALUE"]] = 1;	
		else $arReviewsTovsIdsCount[$arFieldsEl["PROPERTY_TOV_ID_VALUE"]]++;		
	}
	
	//pre($arReviewsTovsIdsCount);
	
	$arReviewsTovsIds = array_keys($arReviewsTovsIdsCount);
	$arReviewsBlocksIds = array();
	$arReviewsSectionsIds = array();
	
	//RE($arReviewsTovsIds);	
	$PAGE_KOL = 10;	
	//pre(array("IBLOCK_TYPE" => $IBLOCK_TYPE, "ID" => $arReviewsTovsIds, "ACTIVE" => "Y"));
	$resEl = CIBlockElement::GetList(array("sort" => "asc", "id" => "desc"), array("IBLOCK_TYPE" => $IBLOCK_TYPE, "ID" => $arReviewsTovsIds, "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "CODE", "IBLOCK_SECTION_ID", "TIMESTAMP_X"));
	while($arFieldsEl = $resEl->GetNext())
	{		

		if(!$arReviewsBlocksIds[$arFieldsEl["IBLOCK_ID"]]) $arReviewsBlocksIds[$arFieldsEl["IBLOCK_ID"]] = $arReviewsTovsIdsCount[$arFieldsEl["ID"]];	
		else $arReviewsBlocksIds[$arFieldsEl["IBLOCK_ID"]] = $arReviewsBlocksIds[$arFieldsEl["IBLOCK_ID"]] + $arReviewsTovsIdsCount[$arFieldsEl["ID"]];

		if($arFieldsEl["IBLOCK_SECTION_ID"]) {
			if(!$arReviewsSectionsIds[$arFieldsEl["IBLOCK_SECTION_ID"]]) $arReviewsSectionsIds[$arFieldsEl["IBLOCK_SECTION_ID"]] = $arReviewsTovsIdsCount[$arFieldsEl["ID"]];	
			else $arReviewsSectionsIds[$arFieldsEl["IBLOCK_SECTION_ID"]] = $arReviewsSectionsIds[$arFieldsEl["IBLOCK_SECTION_ID"]] + $arReviewsTovsIdsCount[$arFieldsEl["ID"]];
		}

		
		$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], '/reviews/product-'.$arFieldsEl["CODE"].'/', $arFieldsEl["TIMESTAMP_X"]);
		
		// ПОСТРАНИЧКА ТОВАРА		
		$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], '/reviews/product-'.$arFieldsEl["CODE"].'/', $PAGE_KOL, $count = $arReviewsTovsIdsCount[$arFieldsEl["ID"]], 1);
	}	
	
	//PRE($arCatalogAll);	
	
	foreach($arReviewsBlocksIds as $block_id => &$count) {	
		
		
		$arBlock = $arCatalogAll["BLOCKS"][$block_id];		
		$time = ConvertTimeStamp(time(), "FULL");		
		$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], '/reviews/category-'.$arBlock["CODE"].'/', $time);		
			
			
		// ПОСТРАНИЧКА инфоблока		
		$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], '/reviews/category-'.$arBlock["CODE"].'/', $PAGE_KOL, $count, 1);	
			
	}
	if($arReviewsSectionsIds) {
		foreach($arReviewsSectionsIds as $sec_id => &$count) {				
			
			$arSection = $arCatalogAll["SECTIONS"][$sec_id];		
			$arBlock = $arCatalogAll["BLOCKS"][$arSection["IBLOCK_ID"]];	
			
			$time = ConvertTimeStamp(time(), "FULL");		
			$xmlOut .= CMNTSitemap::getUrlItem($arSiteInfo["SITE_URL"], '/reviews/category-'.$arBlock["CODE"].'/razdel-'.$arSection["CODE"].'/', $time);
			
			// ПОСТРАНИЧКА инфоблока		
			$xmlOut .= GetPagePages($arSiteInfo["SITE_URL"], '/reviews/category-'.$arBlock["CODE"].'/razdel-'.$arSection["CODE"].'/', $PAGE_KOL, $count, 1);				
		}
	}
	
	
	######################################### отзывы END ##################################################
	
	

	$xmlOut .= "</urlset>\n";
	
	$arMessages[] = "Processing complete";
	
	// saving xml file
	$handle = fopen($READY_XML_FILE, "w+");
	if(fwrite($handle, $xmlOut) === FALSE)
		$arErrors[] = "Processing complete / Error saving xml";
	
	fclose($handle);
	
	if(file_exists($READY_XML_FILE))
	{
		$fileSize = CMNTGeneral::FileSizeFormatEn(filesize($READY_XML_FILE));
		$arMessages[] = "File size: ".$fileSize;
	}
	
	// saving state
	$arState = array(
		"NAME" => "Processing complete",
		"MODE" => $workMode,
		"ERRORS" => $arErrors,
		"FILE_SIZE" => $fileSize,
	);
	
	if(!CMNTStateFile::Save($STATE_FILE, $arState))
		$arErrors[] = "Processing complete / Error saving current state.";
	
	// если статус обновления не изменился за время создания xml файла, устанавливаем "обновление не требуется"
	if(empty($arErrors))
	{
		$arUpdateStatusCur = CMNTStateFile::Read($UPDATE_FILE);
		if($arUpdateStatusCur["TIME"] == $arUpdateStatus["TIME"])
		{
			if(!CMNTStateFile::Save($UPDATE_FILE, array("UPDATE" => 0, "EVENT" => "SITEMAP_GENERATOR")))
				$arErrors[] = "Processing complete / Error saving update status";
		}
	}
	
	$arMessages[] = "Processing time: ".round(getmicrotime()-$startTime, 3)." sec.";
	
	// запись в лог
	$strLog = "";
	
	if(is_array($arUpdateStatus) && !empty($arUpdateStatus))
	{
		$strLog .= "\nNEED_UPDATE: ".$arUpdateStatus["UPDATE"]."\n";
		$strLog .= "UPDATE_EVENT: ".$arUpdateStatus["EVENT"]."\n";
		
		if($arUpdateStatus["UPDATE"])
			$strLog .= "Статус обновления: требуется обновление по событию '".$arUpdateStatus["EVENT"]."'\n";
		elseif($bUpdateTimeout)
			$strLog .= "Статус обновления: требуется обновление по таймауту (".$UPDATE_TIMEOUT." сек)\n";
		else
			$strLog .= "Статус обновления: обновление не требуется\n";
	}
	else
	{
		$strLog .= "\nСтатус обновления: не найден\n";
	}
	
	$strLog .= "\nMODE: ".$workMode."\n";
	
	foreach($arMessages as $v)
		$strLog .= $v."\n";
	
	if(!empty($arErrors))
	{
		$strLog .= "\nErrors:"."\n";
		foreach($arErrors as $v)
			$strLog .= $v."\n";
	}
	
	$strLog = preg_replace("~^(.+)\n+$~s", "$1", $strLog);
	
	CMNTLog::Add($strLog, $LOG_FILE);
}

if($workMode == "manual")
	include($WORK_DIR."/index_html.php");
?>