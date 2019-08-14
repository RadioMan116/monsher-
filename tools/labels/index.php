<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

require(dirname(__file__) . "/../config.php");


$currentFilePath = dirname(__file__) . "/tmp/current.csv";
$fileMissingProductsFilePath = dirname(__file__) . "/tmp/missing_products_file.csv";
$siteMissingProductsFilePath = dirname(__file__) . "/tmp/missing_products_site.csv";
$fileMissingProductsLink = "/tools/labels/tmp/missing_products_file.csv";
$siteMissingProductsLink = "/tools/labels/tmp/missing_products_site.csv";

$catalogIblockType = $GLOBALS["IBLOCK_TYPE"];
//$offersIblockType = $GLOBALS["SITE_CONFIG"]["IBLOCK"]["OFFERS"];
$labelsIblockID = $GLOBALS["LABELS_IBLOCK_ID"];
$brandsIblockID = $GLOBALS["BRANDS_IBLOCK_ID"];


$propBrandCode = $GLOBALS["BRAND_CODE"];
$articulCode = $GLOBALS["ART_CODE"];
$modelCode = $GLOBALS["MODEL_CODE"];
$storeCode = $GLOBALS["CATALOG_STORE_CODE"];


$label_promo = 1422;


if(!($USER->IsAdmin() || CSite::InGroup(array(6))))
	die("Access denied");

if($labelsIblockID <= 0)
	die("Labels iblock undefined");

if($brandsIblockID <= 0)
	die("Brands iblock undefined");

if(strlen($catalogIblockType) == 0)
	die("catalogIblockType undefined");

$startTime = getmicrotime();

CModule::IncludeModule("iblock");

$arLabels = array();
$resEl = CIBlockElement::GetList(array("NAME" => "ASC"), array("IBLOCK_ID" => $labelsIblockID, "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "CODE"));
while($arFieldsEl = $resEl->Fetch())
{
	$arLabels[$arFieldsEl["ID"]] = array(
		"ID" => $arFieldsEl["ID"],
		"NAME" => $arFieldsEl["NAME"],
		"CODE" => $arFieldsEl["CODE"],
	);
}
// нам не нужны оттуда лейблы !!!!!
$arLabels = array();

$arLabels2["S_NEW"] = array(
		"ID" => "S_NEW",
		"NAME" => "Новинка [Чекбокс]",
		"CODE" => "S_NEW",
	);

$arLabels2["S_HIT"] = array(
		"ID" => "S_HIT",
		"NAME" => "Хит продаж [Чекбокс]",
		"CODE" => "S_HIT",
	);

$arLabels2["S_SALE"] = array(
		"ID" => "S_SALE",
		"NAME" => "Спецпредложение [Чекбокс]",
		"CODE" => "S_SALE",
	);


$arBrands = array();
$resEl = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $brandsIblockID, "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME"));
while($arFieldsEl = $resEl->Fetch())
{
	$brand = trim($arFieldsEl["NAME"]);
	$brand = strtoupper(preg_replace("~[^a-zA-Z0-9]~is", "", $brand));
	$arBrands[$arFieldsEl["ID"]] = $brand;
}

$action = !empty($_REQUEST["action"]) ? htmlspecialcharsbx($_REQUEST["action"]) : "";
$searchMode = !empty($_REQUEST["search_mode"]) ? htmlspecialcharsbx($_REQUEST["search_mode"]) : "model";
$currentLabelID = !empty($_REQUEST["label"]) ? $_REQUEST["label"] : "";

$propModelCode = $searchMode == "artikul" ? $articulCode : $modelCode;


//PRE($_REQUEST);

$arError = array();

if($action == "upload_file")
{
	if(!empty($_FILES["upload_file"]))
	{
		if(!move_uploaded_file($_FILES["upload_file"]["tmp_name"], $currentFilePath))
		{
			$arError[] = "Ошибка при загрузке файла";
		}
		else
		{
			$_SESSION["upload_file"] = true;
			LocalRedirect($_SERVER["PHP_SELF"]);
		}
	}
}

if($action == "save_url")
{
	$url = trim($_REQUEST["url"]);
	if($data = file_get_contents($url))
	{
		if(strlen($data) > 0)
		{
			file_put_contents($currentFilePath, $data);
			$_SESSION["save_url"] = true;
			LocalRedirect($_SERVER["PHP_SELF"]);
		}
		else
		{
			$arErrors[] = "Ошибка загрузки файла";
		}
	}
	else
	{
		$arErrors[] = "Ошибка загрузки файла";
	}
}

if($action == "processing")
{
	include(dirname(__file__) . "/class.csv.php");
	
	CModule::IncludeModule("catalog");
	
	if(in_array($_REQUEST["do"], array("compare", "import", "remove", "show_all_with_label", "remove_label_from_all")))
	{
		if(empty($currentLabelID))
		{
			$arError[] = "Не выбран лейбл";
		}
	}
	
	if(empty($arError))
	{
		//$bClearCache = defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]) ? true : false;
		$bClearCache = false;
		
		$arElements = array();
		$arBaseElementIDs = array();
		
		if(!empty($offersIblockType))
		{
			$resIb = CIBlock::GetList(array(), array("TYPE" => $offersIblockType, "ACTIVE" => "Y"), false);
			while($arFieldsIb = $resIb->Fetch())
			{
				$arElementsFilter = array("IBLOCK_ID" => $arFieldsIb["ID"], "ACTIVE" => "Y");
				
				$arElementsSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_S_HIT", "PROPERTY_S_NEW", "PROPERTY_S_SALE", "PROPERTY_".$propModelCode, "PROPERTY_".$propBrandCode, "PROPERTY_".$storeCode, "PROPERTY_CML2_LINK");
				
				$resEl = CIBlockElement::GetList(array(), $arElementsFilter, false, false, $arElementsSelect);
				while($obEl = $resEl->GetNextElement())
				{
					$arFieldsEl = $obEl->GetFields();
					$arFieldsEl["LABELS"] = $obEl->GetProperty("LABELS");
					
					$arBaseElementIDs[] = $arFieldsEl["PROPERTY_CML2_LINK_VALUE"];
					
					if(!empty($arFieldsEl["PROPERTY_".$propModelCode."_VALUE"]))
					{
						$model = trim($arFieldsEl["PROPERTY_".$propModelCode."_VALUE"]);
						$model = strtoupper(preg_replace("~[^a-zA-Z0-9]~is", "", $model));
						
						$brand = $arBrands[$arFieldsEl["PROPERTY_".$propBrandCode."_VALUE"]];
						
						if(!empty($model) && !empty($brand))
						{
							$arElementLabels = array();
							foreach($arFieldsEl["LABELS"]["VALUE"] as $k => $labelID) {
								$arElementLabels[$arFieldsEl["LABELS"]["PROPERTY_VALUE_ID"][$k]] = $labelID;
							}
								
							$arElementLabels2 = array(
								"S_HIT" => $arFieldsEl["PROPERTY_S_HIT_VALUE"],
								"S_NEW" => $arFieldsEl["PROPERTY_S_NEW_VALUE"],
								"S_SALE" => $arFieldsEl["PROPERTY_S_SALE_VALUE"]
							);	
							
							$arElements[$brand][$model] = array(
								"ID" => $arFieldsEl["ID"],
								"IBLOCK_ID" => $arFieldsEl["IBLOCK_ID"],
								"NAME" => $arFieldsEl["NAME"],
								"DETAIL_PAGE_URL" => $arFieldsEl["DETAIL_PAGE_URL"],
								"STORE" => $arFieldsEl["PROPERTY_".$storeCode."_VALUE"],
								"IS_SKU" => "Y",								
								"LABELS" => $arElementLabels,
								"LABELS2" => $arElementLabels2,
							);
						}
					}
				}
			}
		}
		
		if(!empty($arBaseElementIDs))
			$arBaseElementIDs = array_unique($arBaseElementIDs);
		
		$resIb = CIBlock::GetList(array(), array("TYPE" => $catalogIblockType, "ACTIVE" => "Y"), false);
		while($arFieldsIb = $resIb->Fetch())
		{
			$arElementsFilter = array("IBLOCK_ID" => $arFieldsIb["ID"], "ACTIVE" => "Y");
			
			//pre($arElementsFilter);
			
			$arElementsSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL","PROPERTY_S_HIT", "PROPERTY_S_NEW","PROPERTY_S_SALE", "PROPERTY_".$propModelCode, "PROPERTY_".$propBrandCode, "PROPERTY_".$storeCode);
			
			$resEl = CIBlockElement::GetList(array(), $arElementsFilter, false, false, $arElementsSelect);
			while($obEl = $resEl->GetNextElement())
			{
				$arFieldsEl = $obEl->GetFields();
				
				//echo '<BR/>'.$propModelCode.' = '.$arFieldsEl["PROPERTY_".$propModelCode."_VALUE"];
				
				if(!empty($arFieldsEl["PROPERTY_".$propModelCode."_VALUE"]) && !in_array($arFieldsEl["ID"], $arBaseElementIDs))
				{
					
					$arFieldsEl["LABELS"] = $obEl->GetProperty("LABELS");
					
					$model = trim($arFieldsEl["PROPERTY_".$propModelCode."_VALUE"]);
					$model = strtoupper(preg_replace("~[^a-zA-Z0-9]~is", "", $model));
					
					$brand = $arBrands[$arFieldsEl["PROPERTY_".$propBrandCode."_VALUE"]];
					
					if(!empty($model) && !empty($brand))
					{
						$arElementLabels = array();
						foreach($arFieldsEl["LABELS"]["VALUE"] as $k => $labelID) {
							$arElementLabels[$arFieldsEl["LABELS"]["PROPERTY_VALUE_ID"][$k]] = $labelID;
						}
						
						$arElementLabels2 = array(
							"S_HIT" => $arFieldsEl["PROPERTY_S_HIT_VALUE"],
							"S_NEW" => $arFieldsEl["PROPERTY_S_NEW_VALUE"],
							"S_SALE" => $arFieldsEl["PROPERTY_S_SALE_VALUE"]
						);
						
						$arElements[$brand][$model] = array(
							"ID" => $arFieldsEl["ID"],
							"IBLOCK_ID" => $arFieldsEl["IBLOCK_ID"],
							"NAME" => $arFieldsEl["NAME"],
							"DETAIL_PAGE_URL" => $arFieldsEl["DETAIL_PAGE_URL"],							
							"STORE" => $arFieldsEl["PROPERTY_".$storeCode."_VALUE"],							
							"LABELS" => $arElementLabels,
							"LABELS2" => $arElementLabels2,
						);
					}
				}
			}
		}
		
		
		//pre($arElements);
		
		$csv = new CsvReader($currentFilePath);
		$arCsvRaw = $csv->GetCsv();
		
		$arCsv = array();
		foreach($arCsvRaw as $lineNum => $arLine)
		{
			if(
				$lineNum == 0
				&& ($arLine[0] == "brand_name" || $arLine[0] == "brend_name") 
				&& $arLine[1] == "model" 
			)
				continue;
			
			$brand = trim($arLine[0]);
			$brand = strtoupper(preg_replace("~[^a-zA-Z0-9]~is", "", $brand));
			
			$model = trim($arLine[1]);
			$model = strtoupper(preg_replace("~[^a-zA-Z0-9]~is", "", $model));
			
			$modelRow = trim($arLine[1]);
			$modelRow = str_replace(array("\n", "\r", "\t"), array("", "", ""), $modelRow);
			$modelRow = preg_replace("~\s+~", " ", $modelRow);
			
			$arCsv[] = array(
				"BRAND" => $brand,
				"MODEL" => $model,
				"MODEL_ROW" => $modelRow,
			);
		}
		
		$arUpdatedIblocks = array();
		
		$obElement = new CIblockElement;
		
		if($_REQUEST["do"] == "import")
		{
			if(empty($currentLabelID))
			{
				$arError[] = "Не выбран лейбл";
			}
			else
			{
				$updateCnt = 0;
				if(copy($currentFilePath, dirname(__file__) . "/tmp/history/".date("Y.m.d_H.i.s")."_u".$USER->GetID().".csv"))
				{
					
					
					foreach($arCsv as $k => $arCsvItem)
					{
						$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
					
						if($arElement["LABELS2"][$currentLabelID])
						{
							
							//echo '<br/>'.$arElement["ID"].' '.$currentLabelID.' == Y';
							
							
							CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array($currentLabelID => "Y"));
							$updateCnt++;
							$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
						}
						else if(is_array($arElement["LABELS"]) && !in_array($currentLabelID, $arElement["LABELS"]))
						{
							$arUpdate = $arElement["LABELS"];
							$arUpdate["n0"] = $currentLabelID;
							
							//CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("LABELS" => $arUpdate));
							CIBlockElement::SetPropertyValues($arElement["ID"], $arElement["IBLOCK_ID"], $arUpdate, "LABELS");
							$obElement->Update($arElement["ID"], array("NAME"=>$arElement["NAME"]));
							
							// если это лейбл промо, тогда добавляем еще один доп параметр.								
								if($currentLabelID == $label_promo) {
									CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("TYPE_PROMO" => "Y"));
								}
							
							
							$updateCnt++;
							
							$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
							
							if($bClearCache)
							{
								CMNTProductCache::ClearByID(array("ID" => $arElement["ID"], "PRODUCT_CACHE_TYPES" => array("list")));
								$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);
							}
						}
					}
					
					if($updateCnt > 0)
						BXClearCache(true);
				}
				else
				{
					$arError[] = "Не удалось сохранить файл в историю загрузок";
				}
			}
		}
		
		if($_REQUEST["do"] == "remove")
		{
			if(empty($currentLabelID))
			{
				$arError[] = "Не выбран лейбл";
			}
			else
			{
				$updateCnt = 0;
				if(copy($currentFilePath, dirname(__file__) . "/tmp/history/".date("Y.m.d_H.i.s")."_u".$USER->GetID().".csv"))
				{
					foreach($arCsv as $k => $arCsvItem)
					{
						$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
						
						
						if($arElement["LABELS2"][$currentLabelID])
						{
							//echo '<br/>'.$arElement["ID"].' '.$currentLabelID.' == N';
							
							CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array($currentLabelID => "N"));
							$updateCnt++;
							$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
						}
						else if(is_array($arElement["LABELS"]) && in_array($currentLabelID, $arElement["LABELS"]))
						{
								$arUpdate = $arElement["LABELS"];
								
								foreach($arUpdate as $labelKey => $labelID)
								{
									if($currentLabelID == $labelID)
									{
										unset($arUpdate[$labelKey]);
										break;
									}
								}
								
								if(empty($arUpdate))
									$arUpdate = "";
								
								//CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("LABELS" => $arUpdate));
								CIBlockElement::SetPropertyValues($arElement["ID"], $arElement["IBLOCK_ID"], $arUpdate, "LABELS");
								$obElement->Update($arElement["ID"], array("NAME"=>$arElement["NAME"]));
								
								
								// если это лейбл промо, тогда удаляем еще один доп параметр.								
								if($currentLabelID == $label_promo) {
									CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("TYPE_PROMO" => "N"));
								}
								
								$updateCnt++;
								
								$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
								
								if($bClearCache)
								{
									CMNTProductCache::ClearByID(array("ID" => $arElement["ID"], "PRODUCT_CACHE_TYPES" => array("list")));
									$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);
								}
							
						
						}
					}
					
					if($updateCnt > 0)
						BXClearCache(true);
				}
				else
				{
					$arError[] = "Не удалось сохранить файл в историю загрузок";
				}
			}
		}
		
		if($_REQUEST["do"] == "remove_label_from_all")
		{
			if(empty($currentLabelID))
			{
				$arError[] = "Не выбран лейбл";
			}
			else
			{
				$updateCnt = 0;
				
				foreach($arElements as $brand => $arModels)
				{
					if(is_array($arModels))
					{
						foreach($arModels as $model => $arElement)
						{
							
							if($arElement["LABELS2"][$currentLabelID])
							{
								//echo '<br/>'.$arElement["ID"].' '.$currentLabelID.' == N';
								
								
								CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array($currentLabelID => "N"));
								$updateCnt++;
								$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
							}
							elseif(is_array($arElement["LABELS"]) && in_array($currentLabelID, $arElement["LABELS"]))
							{
									$arUpdate = $arElement["LABELS"];
									
									foreach($arUpdate as $labelKey => $labelID)
									{
										if($currentLabelID == $labelID)
										{
											unset($arUpdate[$labelKey]);
											break;
										}
									}
									
									if(empty($arUpdate))
										$arUpdate = "";
									
									//CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("LABELS" => $arUpdate));
									CIBlockElement::SetPropertyValues($arElement["ID"], $arElement["IBLOCK_ID"], $arUpdate, "LABELS");
									$obElement->Update($arElement["ID"], array("NAME"=>$arElement["NAME"]));
									
									// если это лейбл промо, тогда удаляем еще один доп параметр.								
									if($currentLabelID == $label_promo) {
										CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array("TYPE_PROMO" => "N"));
									}
									
									
									$updateCnt++;
									
									$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
									
									if($bClearCache)
									{
										CMNTProductCache::ClearByID(array("ID" => $arElement["ID"], "PRODUCT_CACHE_TYPES" => array("list")));
										$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);
									}								
							
							}
						}
					}
				}
			}
		}
		
		if($bClearCache && is_array($arUpdatedIblocks))
		{
			foreach($arUpdatedIblocks as $iblockID => $v)
				$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$iblockID);
		}
	}
	
	//echo "<pre>".print_r($arElements, true)."</pre>";
}
?>
<html>
<head>
<title>Лейблы</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=SITE_CHARSET?>" />
<script type="text/javascript" src="jquery-1.8.1.min.js"></script>
<style type="text/css">
.general_container {
	width:800px;
	padding:30px;
	margin:10px;
	border:1px solid #eee;
	font-size:12px;
	font-family:arial;
}
h1 {
	font-size:16px;
}
a {
	color: #256191;
}
table {
	cell-spacing: 2px;
	font-size:12px;
}
table td {
	padding: 2px 5px;
	border: 1px solid #eee;
}
table td.bg {
	background: #eee;
}
.green {
	color:green;
}
.red {
	color:red;
}
.bold {
	font-weight:bold;
}
</style>
<script type="text/javascript">
$(function() {
	$('.js-action-select').each(function() {
		if($(this).is(':checked') && ($(this).val() == 'check_file' || $(this).val() == 'check_site')) {
			$('#js-save-to-file-container').show();
		}
	});
	
	$('.js-action-select').change(function() {
		if($(this).is(':checked') && ($(this).val() == 'check_file' || $(this).val() == 'check_site')) {
			$('#js-save-to-file-container').show();
		} else {
			$('#js-save-to-file-container').hide();
		}
	});
});
</script>
</head>
<body>
<div class="general_container">
	<h1>Лейблы</h1>
	<? /*
	<p>
		<a href="/tools/labels/show_label_exceptions.php">Исключения для лейблов</a><br />
	</p>
	*/ ?>
	<form method="post" action="<?=$APPLICATION->GetCurPage()?>" enctype="multipart/form-data" id="action_form_file">
		<input type="hidden" name="action" value="upload_file" />
		Загрузить файл с компьютера: <input name="upload_file" type="file" /><br />
		<input type="submit" value="Загрузить файл" />
	</form>
	<br />
<?
if($action == "upload_file" && !empty($arError))
{
	foreach($arError as $error)
		echo $error."<br />";
	
	echo "<br />";
}

if(isset($_SESSION["upload_file"]))
{
	unset($_SESSION["upload_file"]);
	echo "<span class='bold green'>Файл загружен</span><br /><br />";
}
?>
	<form method="post" action="<?=$APPLICATION->GetCurPage()?>" enctype="multipart/form-data" id="action_form_url">
		<input type="hidden" name="action" value="save_url" />
		Загрузить файл по адресу: <input name="url" type="text" style="width:400px;" /><br />
		<input type="submit" value="Загрузить файл" />
	</form>
	<br />
<?
if($action == "save_url" && !empty($arError))
{
	foreach($arError as $error)
		echo $error."<br />";
	
	echo "<br />";
}

if(isset($_SESSION["save_url"]))
{
	unset($_SESSION["save_url"]);
	echo "<span class='bold green'>Файл загружен</span><br /><br />";
}
?>
<?
if(file_exists($currentFilePath))
{
	$fileTime = date("Y.m.d H:i:s", filemtime($currentFilePath));
	
	
	//pre($currentLabelID);
	
?>
	Последний загруженный файл: <b><?=$fileTime?></b><br />
	<br />
	<form method="post" action="<?=$APPLICATION->GetCurPage()?>" enctype="multipart/form-data" id="action_form_processing">
		<input type="hidden" name="action" value="processing" />
		Лейбл:&nbsp;&nbsp;&nbsp;<select name="label">
			<option value="">...</option>
<?
	foreach($arLabels2 as $labelID => &$arLabel) {
?>
			<option value="<?=$labelID?>"<?if($labelID == $currentLabelID):?> selected="selected"<?endif?>><?=$arLabel["NAME"]?></option>
<?
	}
?><?
	foreach($arLabels as $labelID => &$arLabel) {
?>
			<option value="<?=$labelID?>"<?if($labelID == $currentLabelID):?> selected="selected"<?endif?>><?=$arLabel["NAME"]?></option>
<?
	}
?>
		</select>
		<br />
		<br />
		Режим поиска по:<br />
		<label><input type="radio" class="js-search-mode-select" name="search_mode" value="model"<?if($searchMode == "model"):?>checked<?endif?> />&nbsp;Модели</label><br />
		<label><input type="radio" class="js-search-mode-select" name="search_mode" value="artikul"<?if($searchMode == "artikul"):?>checked<?endif?> />&nbsp;Артикулу</label><br />
		<br />
		Что сделать:<br />
		<label><input type="radio" class="js-action-select" name="do" value="compare"<?if((isset($_REQUEST["do"]) && $_REQUEST["do"] == "compare") || !isset($_REQUEST["do"])):?>checked<?endif?> />&nbsp;Сравнить с текущими лейблами на сайте</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="check_file"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "check_file"):?>checked<?endif?> />&nbsp;Показать товары на сайте, которых нет в файле</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="check_site"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "check_site"):?>checked<?endif?> />&nbsp;Показать товары в файле, которых нет на сайте</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="import"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "import"):?>checked<?endif?> />&nbsp;Добавить лейбл</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="remove"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "remove"):?>checked<?endif?> />&nbsp;Удалить лейбл</label><br />
		<br />
		<label><input type="radio" class="js-action-select" name="do" value="show_all_with_label"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "show_all_with_label"):?>checked<?endif?> />&nbsp;Показать все товары с выбранным лейблом</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="remove_label_from_all"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "remove_label_from_all"):?>checked<?endif?> /><span style="color: red;">&nbsp;Удалить выбранный лейбл у всех товаров</span></label><br />
		<br />
		<div id="js-save-to-file-container" style="display: none;"><label><input type="checkbox" name="save_to_file" value="Y" />&nbsp;Сохранить в файл</label><br /><br /></div>
		<br />
		<input type="submit" value="Выполнить" />
	</form>
	<br />
<?
}
?>
<?
if($action == "processing")
{
	if(!empty($arError))
	{
		echo "<p style=\"color: red;\">Ошибка:<br />";
		
		foreach($arError as $error)
			echo $error."<br />";
		
		echo "</p>";
	}
	else
	{
		if($_REQUEST["do"] == "compare") {
?>
	<table>
		<tr>
			<td>Бренд</td>
			<td>Модель</td>
			<td>Товар на сайте</td>
			<td>Лейбл "<?=$arLabels[$currentLabelID]["NAME"]?><?=$arLabels2[$currentLabelID]["NAME"]?>"</td>
		</tr>
<?
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
?>
		<tr>
			<td><?=$arCsvItem["BRAND"]?></td>
			<td><?if(!empty($arElement["DETAIL_PAGE_URL"])):?><a title="Товар на сайте" href="<?=$arElement["DETAIL_PAGE_URL"]?>" target="_blank"><?=$arCsvItem["MODEL"]?></a><?else:?><?=$arCsvItem["MODEL"]?><?endif?></td>
			<td>
<?
				if(!empty($arElement)) {
?>
				<?=$arElement["NAME"]?>&nbsp;[<a title="Редактировать" href="/bitrix/admin/iblock_element_edit.php?WF=Y&ID=<?=$arElement["ID"]?>&type=<?=($arElement["IS_SKU"] == "Y" ? $offersIblockType : $catalogIblockType)?>&lang=ru&IBLOCK_ID=<?=$arElement["IBLOCK_ID"]?>&find_section_section=-1" target="_blank"><?=$arElement["ID"]?></a>]<?if($arElement["IS_SKU"] == "Y"):?> [SKU]<?endif?><?if(!empty($arElement["STORE"])):?> [<?=str_replace(" ", "&nbsp;", $arElement["STORE"])?>]<?endif?>
<?
				} else {
?>
				-
<?
				}
?>
			</td>
			<td <?if(!(!empty($arElement) && (in_array($currentLabelID, $arElement["LABELS"]) || $arElement["LABELS2"][$currentLabelID] == 'Y'))):?> class="bg"<?endif?>>
<?
				$find = false;
				if(!empty($arElement["LABELS"]))
				{
					foreach($arElement["LABELS"] as $k => &$labelID) {
						echo $arLabels[$labelID]["NAME"]."<br />";
						$find = true;
					}
				}
				if(!empty($arElement["LABELS2"]))
				{					
					foreach($arElement["LABELS2"] as $key => &$val) {
						if($val == 'Y') {
							echo $arLabels2[$key]["NAME"]."<br />";
							$find = true;
						}
					}
					
					
					//pre($arElement["LABELS2"]);
				}
				
				//dump($find);
				
				if(!$find)
				{
?>
				-
<?
				}
?>
			</td>
		</tr>
<?
			}
?>
	</table>
<?
		}

		if($_REQUEST["do"] == "check_file")
		{
			$arElementsNotIntFile = $arElements;
			foreach($arCsv as $k => $arCsvItem)
				unset($arElementsNotIntFile[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]]);
			
			if(isset($_REQUEST["save_to_file"]))
			{
				$arExportData = array();
				$arExportData[] = array("Бренд", "Модель", "Товар на сайте");
				
				foreach($arElementsNotIntFile as $brandName => $arModels)
				{
					foreach($arModels as $modelName => $arElement)
					{
						$arExportData[] = array($brandName, $modelName, $arElement["NAME"]." [".$arElement["ID"]."]");
					}
				}
				
				$csv = new CsvWriter($fileMissingProductsFilePath, $arExportData);
				
				if($csv->SaveCsv()) {
?>
	<div><a href="<?=$fileMissingProductsLink?>?<?=filemtime($fileMissingProductsFilePath)?>">Скачать файл</a></div><br />
<?
				} else {
?>
	<div>Ошибка: <?=$csv->error?></div><br />
<?
				}
			}
?>
	<table>
		<tr>
			<td>Бренд</td>
			<td>Модель</td>
			<td>Товар на сайте</td>
			<td nowrap>Наличие на складе</td>
		</tr>
<?
			foreach($arElementsNotIntFile as $brandName => $arModels)
			{
				foreach($arModels as $modelName => $arElement)
				{
?>
		<tr>
			<td><?=$brandName?></td>
			<td><a href="<?=$arElement["DETAIL_PAGE_URL"]?>" target="_blank"><?=$modelName?></a></td>
			<td>
				<?=$arElement["NAME"]?>&nbsp;[<a href="/bitrix/admin/iblock_element_edit.php?WF=Y&ID=<?=$arElement["ID"]?>&type=<?=($arElement["IS_SKU"] == "Y" ? $offersIblockType : $catalogIblockType)?>&lang=ru&IBLOCK_ID=<?=$arElement["IBLOCK_ID"]?>&find_section_section=-1" target="_blank"><?=$arElement["ID"]?></a>]<?if($arElement["IS_SKU"] == "Y"):?> [SKU]<?endif?>
			</td>
			<td nowrap><?=(!empty($arElement["STORE"])?$arElement["STORE"]:"&nbsp;")?></td>
		</tr>
<?
				}
			}
?>
	</table>
<?
		}
		
		if($_REQUEST["do"] == "check_site")
		{
			$arElementsNotIntFile = $arElements;
			foreach($arCsv as $k => $arCsvItem)
				unset($arElementsNotIntFile[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]]);
			
			if(isset($_REQUEST["save_to_file"]))
			{
				$arExportData = array();
				$arExportData[] = array("Бренд", "Модель");
				
				foreach($arCsv as $k => $arCsvItem)
				{
					$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
					if(empty($arElement))
						$arExportData[] = array($arCsvItem["BRAND"], $arCsvItem["MODEL"], $arCsvItem["MODEL_ROW"]);
				}
				
				$csv = new CsvWriter($siteMissingProductsFilePath, $arExportData);
				
				if($csv->SaveCsv()) {
?>
	<div><a href="<?=$siteMissingProductsLink?>?<?=filemtime($siteMissingProductsFilePath)?>">Скачать файл</a></div><br />
<?
				} else {
?>
	<div>Ошибка: <?=$csv->error?></div><br />
<?
				}
			}
?>
	<table>
		<tr>
			<td>Бренд</td>
			<td>Модель</td>
			<td>Исходная модель</td>
		</tr>
<?
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
				if(empty($arElement)) {
?>
		<tr>
			<td><?=$arCsvItem["BRAND"]?></td>
			<td><?=$arCsvItem["MODEL"]?></td>
			<td><?=$arCsvItem["MODEL_ROW"]?></td>
		</tr>
<?
				}
			}
?>
	</table>
<?
		}
		
		if($_REQUEST["do"] == "show_all_with_label") {
?>
	<table>
		<tr>
			<td>Бренд</td>
			<td>Модель</td>
			<td>Товар на сайте</td>
			<td nowrap>Наличие на складе</td>
		</tr>
<?
			foreach($arElements as $brandName => $arModels)
			{
				foreach($arModels as $modelName => $arElement)
				{
					if(is_array($arElement["LABELS"]) && in_array($currentLabelID, $arElement["LABELS"])) {
?>
		<tr>
			<td><?=$brandName?></td>
			<td><a href="<?=$arElement["DETAIL_PAGE_URL"]?>" target="_blank"><?=$modelName?></a></td>
			<td>
				<?=$arElement["NAME"]?>&nbsp;[<a href="/bitrix/admin/iblock_element_edit.php?WF=Y&ID=<?=$arElement["ID"]?>&type=<?=($arElement["IS_SKU"] == "Y" ? $offersIblockType : $catalogIblockType)?>&lang=ru&IBLOCK_ID=<?=$arElement["IBLOCK_ID"]?>&find_section_section=-1" target="_blank"><?=$arElement["ID"]?></a>]<?if($arElement["IS_SKU"] == "Y"):?> [SKU]<?endif?>
			</td>
			<td nowrap><?=(!empty($arElement["STORE"])?$arElement["STORE"]:"&nbsp;")?></td>
		</tr>
<?
					}
				}
			}
?>
	</table>
<?
		}
		
		if($_REQUEST["do"] == "import" || $_REQUEST["do"] == "remove" || $_REQUEST["do"] == "remove_label_from_all")
		{
			echo "<span class='bold green'>Обновлено товаров: ".$updateCnt."</span><br />";
		}
	}
}
?>
<?
if(!empty($action))
	echo "<br />time: ".round(getmicrotime()-$startTime, 3)."<br /><br />";
?>
</div>
</body>
</html>