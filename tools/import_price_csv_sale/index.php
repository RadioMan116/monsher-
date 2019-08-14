<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$startTime = getmicrotime();

require(dirname(__file__) . "/config.php");

$action = !empty($_REQUEST["action"]) ? $_REQUEST["action"] : "";

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
	
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	
	function UpdateElPrice($elementID, $priceTypeID, $priceVal)
	{
		//if(strlen($priceVal) > 0)
		{
			$elementID = IntVal($elementID);
			$priceTypeID = IntVal($priceTypeID);
			$priceVal = floatval($priceVal);
			
			$arFieldsPrice = Array(
				"PRODUCT_ID" => $elementID,
				"CATALOG_GROUP_ID" => $priceTypeID,
				"PRICE" => $priceVal,
				"CURRENCY" => "RUB",
				"QUANTITY_FROM" => false,
				"QUANTITY_TO" => false
			);
		
			$resPrice = CPrice::GetList(
				array(),
				array(
					"PRODUCT_ID" => $elementID,
					"CATALOG_GROUP_ID" => $priceTypeID
				)
			);
			if($arr = $resPrice->Fetch())
			{
				if($priceVal){
					if(CPrice::Update($arr["ID"], $arFieldsPrice)) {return true;}
				}
				else {
					if(CPrice::Delete($arr["ID"])) {return true;}
				}
				
			}
			else
			{
				if($priceVal){
					if(CPrice::Add($arFieldsPrice)) {return true;}
				}				
			}
			
			
			return false;
		}
		return false;
	}
	
	$arBrands = array();
	$resEl = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $brandsIblockID, "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME"));
	while($arFieldsEl = $resEl->Fetch())
	{
		$brand = trim($arFieldsEl["NAME"]);
		$brand = strtoupper(preg_replace("~[^a-zA-Z0-9]~is", "", $brand));
		$arBrands[$arFieldsEl["ID"]] = $brand;
	}
	
	$arElements = array();
	
	$resIb = CIBlock::GetList(array(), array("TYPE" => $iblockType, "ACTIVE" => "Y"), false);
	while($arFieldsIb = $resIb->Fetch())
	{
		$resEl = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arFieldsIb["ID"], "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_".$propModelCode, "PROPERTY_YM_EXPORT_DISCOUNT", "PROPERTY_S_SALE_ONLY_MSK", "PROPERTY_".$propBrandCode, "PROPERTY_".$storeCode_1, "PROPERTY_".$storeCode_2, "CATALOG_GROUP_".$priceID));
		while($arFieldsEl = $resEl->GetNext())
		{
			if(!empty($arFieldsEl["PROPERTY_".$propModelCode."_VALUE"]))
			{
				$model = trim($arFieldsEl["PROPERTY_".$propModelCode."_VALUE"]);
				$model = strtoupper(preg_replace("~[^a-zA-Z0-9]~is", "", $model));
				
				$brand = $arBrands[$arFieldsEl["PROPERTY_".$propBrandCode."_VALUE"]];
				
				
				
				if(!empty($model) && !empty($brand))
				{
					$arElements[$brand][$model] = array(
						"ID" => $arFieldsEl["ID"],
						"IBLOCK_ID" => $arFieldsEl["IBLOCK_ID"],
						"NAME" => $arFieldsEl["NAME"],
						"MODEL" => $arFieldsEl["PROPERTY_".$propModelCode."_VALUE"],
						"DETAIL_PAGE_URL" => $arFieldsEl["DETAIL_PAGE_URL"],
						"PRICE" => floatval($arFieldsEl["CATALOG_PRICE_".$priceID]),
						"YM" => $arFieldsEl["PROPERTY_YM_EXPORT_DISCOUNT_VALUE"],
						"SALE_ONLY_MSK" => $arFieldsEl["PROPERTY_S_SALE_ONLY_MSK_VALUE"],
						"STORE_1" => $arFieldsEl["PROPERTY_".$storeCode_1."_VALUE"],
						"STORE_2" => $arFieldsEl["PROPERTY_".$storeCode_2."_VALUE"],
					);
				}
			}
		}
	}
	
	$csv = new CsvReader($currentFilePath);
	$arCsvRaw = $csv->GetCsv();
	
	
	$arKeys = array(		
		"MODEL" => 1,
		"PRICE" => 2,
	);	
	
	
	
	
	$arCsv = array();
	foreach($arCsvRaw as $lineNum => $arLine)
	{
		if(
			$lineNum == 0			
		) {			
			foreach($arLine AS $k=>$title) {
				if(strtoupper($title) == 'CODE') {$arKeys["MODEL"] = $k;}
				else if(strtoupper($title) == 'PRICE') {$arKeys["PRICE"] = $k;}
			}
			continue;			
		}
			
		
		$brand = trim($arLine[0]);
		$brand = strtoupper(preg_replace("~[^a-zA-Z0-9]~is", "", $brand));		
		$brand = 'LIEBHERR';
		
		$model = trim($arLine[$arKeys["MODEL"]]);
		$model = strtoupper(preg_replace("~[^a-zA-Z0-9]~is", "", $model));
		
		
		$modelRow = trim($arLine[$arKeys["MODEL"]]);
		$modelRow = str_replace(array("\n", "\r", "\t"), array("", "", ""), $modelRow);
		$modelRow = preg_replace("~\s+~", " ", $modelRow);
		
		$arCsv[] = array(
			"BRAND" => $brand,			
			"MODEL" => $model,
			"PRICE" => floatval($arLine[$arKeys["PRICE"]]),
			"MODEL_ROW" => $modelRow,
		);
	}
	
	
	
	if($_REQUEST["do"] == "only_msk_add")
	{
		$bClearCache = defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]) ? true : false;
		
		$updateCnt = 0;
		$arUpdatedIblocks = array();
		//$arUpdatedElements = array();
		if(copy($currentFilePath, dirname(__file__) . "/tmp/history/".date("Y.m.d_H.i.s").".csv"))
		{
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
				
				if(!empty($arElement["ID"]))
				{
						CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array($propSaleOnlyMskCode => "Y"));
					
						$updateCnt++;
						$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
						//$arUpdatedElements[$arElement["ID"]] = 1;
						
						// товарный кеш типа "buy" обновляется автоматически по событию OnPriceUpdate, также по событию обновляются и параметры базового товара в случае торгового предложения
						// нужно обновить только кеш компонентов
						if($bClearCache) $GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);
					
				}
			}
			
			if($bClearCache)
			{
				foreach($arUpdatedIblocks as $iblockID => $v)
					$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$iblockID);
			}
			
			//if($updateCnt > 0)
			//	BXClearCache(true);
		}
		else
		{
			$arError[] = "Не удалось сохранить файл в историю загрузок";
		}		
	}
	
	if($_REQUEST["do"] == "only_msk_delete")
	{
		$bClearCache = defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]) ? true : false;
		
		$updateCnt = 0;
		$arUpdatedIblocks = array();
		//$arUpdatedElements = array();
		if(copy($currentFilePath, dirname(__file__) . "/tmp/history/".date("Y.m.d_H.i.s").".csv"))
		{
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
				
				if(!empty($arElement["ID"]))
				{
					CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array($propSaleOnlyMskCode => "N"));
					
						$updateCnt++;
						$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
						//$arUpdatedElements[$arElement["ID"]] = 1;
						
						// товарный кеш типа "buy" обновляется автоматически по событию OnPriceUpdate, также по событию обновляются и параметры базового товара в случае торгового предложения
						// нужно обновить только кеш компонентов
						if($bClearCache) $GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);
					
				}
			}
			
			if($bClearCache)
			{
				foreach($arUpdatedIblocks as $iblockID => $v)
					$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$iblockID);
			}
			
			//if($updateCnt > 0)
			//	BXClearCache(true);
		}
		else
		{
			$arError[] = "Не удалось сохранить файл в историю загрузок";
		}		
	}
	
	
	
	if($_REQUEST["do"] == "ym_add_sale")
	{
		$bClearCache = defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]) ? true : false;
		
		$updateCnt = 0;
		$arUpdatedIblocks = array();
		//$arUpdatedElements = array();
		if(copy($currentFilePath, dirname(__file__) . "/tmp/history/".date("Y.m.d_H.i.s").".csv"))
		{
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
				
				if(!empty($arElement["ID"]))
				{
						CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array($propYMSaleCode => "Y"));
					
						$updateCnt++;
						$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
						//$arUpdatedElements[$arElement["ID"]] = 1;
						
						// товарный кеш типа "buy" обновляется автоматически по событию OnPriceUpdate, также по событию обновляются и параметры базового товара в случае торгового предложения
						// нужно обновить только кеш компонентов
						if($bClearCache) $GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);
					
				}
			}
			
			if($bClearCache)
			{
				foreach($arUpdatedIblocks as $iblockID => $v)
					$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$iblockID);
			}
			
			//if($updateCnt > 0)
			//	BXClearCache(true);
		}
		else
		{
			$arError[] = "Не удалось сохранить файл в историю загрузок";
		}		
	}
	
	if($_REQUEST["do"] == "ym_delete_sale")
	{
		$bClearCache = defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]) ? true : false;
		
		$updateCnt = 0;
		$arUpdatedIblocks = array();
		//$arUpdatedElements = array();
		if(copy($currentFilePath, dirname(__file__) . "/tmp/history/".date("Y.m.d_H.i.s").".csv"))
		{
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
				
				if(!empty($arElement["ID"]))
				{
					CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array($propYMSaleCode => "N"));
					
						$updateCnt++;
						$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
						//$arUpdatedElements[$arElement["ID"]] = 1;
						
						// товарный кеш типа "buy" обновляется автоматически по событию OnPriceUpdate, также по событию обновляются и параметры базового товара в случае торгового предложения
						// нужно обновить только кеш компонентов
						if($bClearCache) $GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);
					
				}
			}
			
			if($bClearCache)
			{
				foreach($arUpdatedIblocks as $iblockID => $v)
					$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$iblockID);
			}
			
			//if($updateCnt > 0)
			//	BXClearCache(true);
		}
		else
		{
			$arError[] = "Не удалось сохранить файл в историю загрузок";
		}		
	}
	
	
	
	if($_REQUEST["do"] == "delete_price_sale")
	{
		$bClearCache = defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]) ? true : false;
		
		$updateCnt = 0;
		$arUpdatedIblocks = array();
		//$arUpdatedElements = array();
		if(copy($currentFilePath, dirname(__file__) . "/tmp/history/".date("Y.m.d_H.i.s").".csv"))
		{
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
				
				if(!empty($arElement["ID"]))
				{
					if(UpdateElPrice($arElement["ID"], $priceID, ''))
					{
						$updateCnt++;
						$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
						//$arUpdatedElements[$arElement["ID"]] = 1;
						
						// товарный кеш типа "buy" обновляется автоматически по событию OnPriceUpdate, также по событию обновляются и параметры базового товара в случае торгового предложения
						// нужно обновить только кеш компонентов
						if($bClearCache)
							$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);
					}
					else
					{
						$arError[] = "Ошибка обновления цены товара [".$arElement["ID"]."] ".$arElement["NAME"];
					}
				}
			}
			
			if($bClearCache)
			{
				foreach($arUpdatedIblocks as $iblockID => $v)
					$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$iblockID);
			}
			
			//if($updateCnt > 0)
			//	BXClearCache(true);
		}
		else
		{
			$arError[] = "Не удалось сохранить файл в историю загрузок";
		}
		
	}
	
	//pre($updateCnt);
	
	if($_REQUEST["do"] == "import")
	{
		$bClearCache = defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]) ? true : false;
		
		$updateCnt = 0;
		$arUpdatedIblocks = array();
		//$arUpdatedElements = array();
		if(copy($currentFilePath, dirname(__file__) . "/tmp/history/".date("Y.m.d_H.i.s").".csv"))
		{
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
				
				if(!empty($arElement["ID"]) && strlen($arCsvItem["PRICE"]) > 0 && $arElement["PRICE"] != $arCsvItem["PRICE"])
				{
					if(UpdateElPrice($arElement["ID"], $priceID, $arCsvItem["PRICE"]))
					{
						$updateCnt++;
						$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;
						//$arUpdatedElements[$arElement["ID"]] = 1;
						
						// товарный кеш типа "buy" обновляется автоматически по событию OnPriceUpdate, также по событию обновляются и параметры базового товара в случае торгового предложения
						// нужно обновить только кеш компонентов
						if($bClearCache)
							$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);
					}
					else
					{
						$arError[] = "Ошибка обновления цены товара [".$arElement["ID"]."] ".$arElement["NAME"];
					}
				}
			}
			
			if($bClearCache)
			{
				foreach($arUpdatedIblocks as $iblockID => $v)
					$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$iblockID);
			}
			
			//if($updateCnt > 0)
			//	BXClearCache(true);
		}
		else
		{
			$arError[] = "Не удалось сохранить файл в историю загрузок";
		}
	}
	
	//echo "<pre>".print_r($arElements, true)."</pre>";
}
?>
<html>
<head>
<title>Импорт цен из csv файла</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=SITE_CHARSET?>" />
<script type="text/javascript" src="jquery-1.8.1.min.js"></script>
<style type="text/css">
.general_container {
	width:1200px;
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
.bold {
	font-weight:bold;
}
</style>
<script type="text/javascript">
$(function() {
	$('.js-action-select').each(function() {
		if($(this).is(':checked') && ($(this).val() == 'check_file' || $(this).val() == 'check_site') || $(this).val() == 'check_site_sale')) {
			$('#js-save-to-file-container').show();
		}
	});
	
	$('.js-action-select').change(function() {
		if($(this).is(':checked') && ($(this).val() == 'check_file' || $(this).val() == 'check_site') || $(this).val() == 'check_site_sale')) {
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
	<h1>Импорт цен из csv файла</h1>
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
?>
	Последний загруженный файл: <b><?=$fileTime?></b><br />
	<br />
	<form method="post" action="<?=$APPLICATION->GetCurPage()?>" enctype="multipart/form-data" id="action_form_processing">
		<input type="hidden" name="action" value="processing" />
		<label><input type="radio" class="js-action-select" name="do" value="compare"<?if((isset($_REQUEST["do"]) && $_REQUEST["do"] == "compare") || !isset($_REQUEST["do"])):?>checked<?endif?> />&nbsp;Сравнить с текущими ценами на сайте</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="check_file"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "check_file"):?>checked<?endif?> />&nbsp;Показать товары на сайте, которых нет в файле</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="check_site"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "check_site"):?>checked<?endif?> />&nbsp;Показать товары в файле, которых нет на сайте</label><br />
		<BR/>
		<label><input type="radio" class="js-action-select" name="do" value="check_site_sale"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "check_site_sale"):?>checked<?endif?> />&nbsp;Показать товары на сайте с акционной ценой</label><br />
		<BR/>
		<label><input type="radio" class="js-action-select" name="do" value="import"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "import"):?>checked<?endif?> />&nbsp;Импортировать акционные цены товарам из файла</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="delete_price_sale"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "delete_price_sale"):?>checked<?endif?> />&nbsp;Удалить акционные цены у товаров из файла</label><br />
		<BR/>
		<label><input type="radio" class="js-action-select" name="do" value="only_msk_add" <?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "only_msk_add"):?>checked<?endif?> />&nbsp; Установить галочку "отображать Sale цену только для Москвы"</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="only_msk_delete" <?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "only_msk_delete"):?>checked<?endif?> />&nbsp; Убрать галочку "отображать Sale цену только для Москвы"</label><br />
		<BR/>
		<label><input type="radio" class="js-action-select" name="do" value="ym_add_sale"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "ym_add_sale"):?>checked<?endif?> />&nbsp; Yandex Market | Установить галочку выгружать акционную цены в YM</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="ym_delete_sale"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "ym_delete_sale"):?>checked<?endif?> />&nbsp; Yandex Market | Убрать галочку выгружать акционную цены в YM</label><br />
		<br />
		<div id="js-save-to-file-container" style="display: none;"><label><input type="checkbox" name="save_to_file" value="Y" />&nbsp;Сохранить в файл</label><br /><br /></div>
		<input type="submit" value="Выполнить" />
	</form>
	<br />
<?
}
?>
<?
if($action == "processing")
{
	if($_REQUEST["do"] == "compare") {
?>
	<table>
		<tr>
			<td>Бренд</td>
			<td>Модель</td>
			<td>Товар на сайте</td>
			<td>Акционная цена на сайте</td>
			<td>Акционная цена из csv</td>
			<td>Выгружать в YM</td>
			<td nowrap>Sale цена только для Москвы</td>
			<td nowrap>Наличие на складе | Москва</td>
			<td nowrap>Наличие на складе | СПб</td>
		</tr>
<?
		foreach($arCsv as $k => $arCsvItem)
		{
			$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
?>
		<tr>
			<td><?=$arCsvItem["BRAND"]?></td>
			<td><?if(!empty($arElement["DETAIL_PAGE_URL"])):?><a href="<?=$arElement["DETAIL_PAGE_URL"]?>" target="_blank"><?=$arCsvItem["MODEL"]?></a><?else:?><?=$arCsvItem["MODEL"]?><?endif?></td>
			<td>
<?
			if(!empty($arElement)) {
?>
				<?=$arElement["NAME"]?>&nbsp;[<a href="/bitrix/admin/iblock_element_edit.php?WF=Y&ID=<?=$arElement["ID"]?>&type=<?=$iblockType?>&lang=ru&IBLOCK_ID=<?=$arElement["IBLOCK_ID"]?>&find_section_section=-1" target="_blank"><?=$arElement["ID"]?></a>]
<?
			} else {
?>
				-
<?
			}
?>
			</td>
			<td<?if(!empty($arElement) && $arElement["PRICE"] != $arCsvItem["PRICE"]):?> class="bg"<?endif?>><?if(!empty($arElement)):?><?=$arElement["PRICE"]?><?else:?>-<?endif?></td>
			<td><?=$arCsvItem["PRICE"]?></td>
			<td><? echo ($arElement["YM"] == 'Y') ? 'Да' : 'Нет';?></td>
			<td><? echo ($arElement["SALE_ONLY_MSK"] == 'Y') ? 'Да' : 'Нет';?></td>
			<td nowrap><?=(!empty($arElement["STORE_1"])?$arElement["STORE_1"]:"-")?></td>
			<td nowrap><?=(!empty($arElement["STORE_2"])?$arElement["STORE_2"]:"-")?></td>
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
			<td>Цена на сайте</td>
			<td>Выгружать в YM</td>
			<td nowrap>Sale цена только для Москвы</td>
			<td nowrap>Наличие на складе | Москва</td>
			<td nowrap>Наличие на складе | СПб</td>
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
				<?=$arElement["NAME"]?>&nbsp;[<a href="/bitrix/admin/iblock_element_edit.php?WF=Y&ID=<?=$arElement["ID"]?>&type=<?=$iblockType?>&lang=ru&IBLOCK_ID=<?=$arElement["IBLOCK_ID"]?>&find_section_section=-1" target="_blank"><?=$arElement["ID"]?></a>]
			</td>
			<td><?=$arElement["PRICE"]?></td>
			<td><? echo ($arElement["YM"] == 'Y') ? 'Да' : 'Нет';?></td>
			<td><? echo ($arElement["SALE_ONLY_MSK"] == 'Y') ? 'Да' : 'Нет';?></td>
			<td nowrap><?=(!empty($arElement["STORE_1"])?$arElement["STORE_1"]:"-")?></td>
			<td nowrap><?=(!empty($arElement["STORE_2"])?$arElement["STORE_2"]:"-")?></td>
		</tr>
<?
			}
		}
?>
	</table>
<?
	}
	
	if($_REQUEST["do"] == "check_site_sale")
	{
		
		
		
?>
	<table>
		<tr>
			<td>Бренд</td>
			<td>Модель</td>
			<td>Товар на сайте</td>
			<td>Цена на сайте</td>
			<td>Выгружать в YM</td>
			<td nowrap>Sale цена только для Москвы</td>
			<td nowrap>Наличие на складе | Москва</td>
			<td nowrap>Наличие на складе | СПб</td>
		</tr>
<?
		foreach($arElements as $brandName => $arModels)
		{
			foreach($arModels as $modelName => $arElement)
			{
				if(!$arElement["PRICE"]) continue;
				
?>
		<tr>
			<td><?=$brandName?></td>
			<td><a href="<?=$arElement["DETAIL_PAGE_URL"]?>" target="_blank"><?=$modelName?></a></td>
			<td>
				<?=$arElement["NAME"]?>&nbsp;[<a href="/bitrix/admin/iblock_element_edit.php?WF=Y&ID=<?=$arElement["ID"]?>&type=<?=$iblockType?>&lang=ru&IBLOCK_ID=<?=$arElement["IBLOCK_ID"]?>&find_section_section=-1" target="_blank"><?=$arElement["ID"]?></a>]
			</td>
			<td><?=$arElement["PRICE"]?></td>
			<td><? echo ($arElement["YM"] == 'Y') ? 'Да' : 'Нет';?></td>
			<td><? echo ($arElement["SALE_ONLY_MSK"] == 'Y') ? 'Да' : 'Нет';?></td>
			<td nowrap><?=(!empty($arElement["STORE_1"])?$arElement["STORE_1"]:"-")?></td>
			<td nowrap><?=(!empty($arElement["STORE_2"])?$arElement["STORE_2"]:"-")?></td>
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
			$arExportData[] = array("Бренд", "Модель", "Исходная модель", "Цена");
			
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];
				if(empty($arElement))
					$arExportData[] = array($arCsvItem["BRAND"], $arCsvItem["MODEL"], $arCsvItem["MODEL_ROW"], $arCsvItem["PRICE"]);
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
			<td>Цена</td>
			<td>Выгружать в YM</td>
			<td nowrap>Sale цена только для Москвы</td>
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
			<td><?=$arCsvItem["PRICE"]?></td>
			<td><? echo ($arElement["YM"] == 'Y') ? 'Да' : 'Нет';?></td>
			<td><? echo ($arElement["SALE_ONLY_MSK"] == 'Y') ? 'Да' : 'Нет';?></td>
		</tr>
<?
			}
		}
?>
	</table>
<?
	}

	if($updateCnt || $arError)
	{
		if(empty($arError))
		{
			echo "<span class='bold green'>Обновлено товаров: ".$updateCnt."</span><br />";
		}
		else
		{
			foreach($arError as $error)
				echo $error."<br />";
			
			echo "<br />";
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