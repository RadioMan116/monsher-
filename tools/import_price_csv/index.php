<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$startTime = getmicrotime();

require(dirname(__file__) . "/config.php");
include(dirname(__file__) . "/class.csv.php");

$action = !empty($_REQUEST["action"]) ? $_REQUEST["action"] : "";

$arError = array();

$arCountries = GetListElement($countryIblockID, array());


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
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");

	function UpdateElPrice($elementID, $priceTypeID, $priceVal)
	{
		if(strlen($priceVal) > 0)
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
				if(CPrice::Update($arr["ID"], $arFieldsPrice))
					return true;
			}
			else
			{
				if(CPrice::Add($arFieldsPrice))
					return true;
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
		$resEl = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arFieldsIb["ID"], "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_".$propCountryCode, "PROPERTY_".$propModelCode, "PROPERTY_".$propBrandCode, "PROPERTY_".$storeCode, "CATALOG_GROUP_".$priceID));
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
						"DETAIL_PAGE_URL" => $arFieldsEl["DETAIL_PAGE_URL"],
						"PRICE" => floatval($arFieldsEl["CATALOG_PRICE_".$priceID]),
						"STORE" => $arFieldsEl["PROPERTY_".$storeCode."_VALUE"],
						"COUNTRY" => $arCountries[$arFieldsEl["PROPERTY_".$propCountryCode."_VALUE"]],
					);
				}
			}
		}
	}


//	pre($arElements);


	$csv = new CsvReader($currentFilePath);
	$arCsvRaw = $csv->GetCsv();

	$arCsv = array();
	foreach($arCsvRaw as $lineNum => $arLine)
	{
		if(
			$lineNum == 0
			&& $arLine[0] == "brand_name"
			&& $arLine[1] == "model"
			&& $arLine[2] == "country"
			&& $arLine[3] == "price"

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
			"PRICE" => floatval($arLine[3]),
			"COUNTRY" => conv_to_read($arLine[2]),
			"MODEL_ROW" => $modelRow,
		);
	}


	//pre($arCsv);


	if($_REQUEST["do"] == "import_country")
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

				//echo '<br/>[[ '.$arElement["COUNTRY"].' != '.$arCsvItem["COUNTRY"];
				if(!empty($arElement["ID"]) && $arCsvItem["COUNTRY"]!='' && $arElement["COUNTRY"] != $arCsvItem["COUNTRY"])
				{
					$country_id = array_search($arCsvItem["COUNTRY"], $arCountries);
					//pre($country_id);
					CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array($propCountryCode => $country_id));

					$updateCnt++;
					$arUpdatedIblocks[$arElement["IBLOCK_ID"]] = 1;

					//$arUpdatedElements[$arElement["ID"]] = 1;

					// нужно обновить только кеш компонентов
					if($bClearCache) $GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$arElement["ID"]);

				}
			}

			if($bClearCache)
			{
				foreach($arUpdatedIblocks as $iblockID => $v)
					$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$iblockID);
			}
		}
		else
		{
			$arError[] = "Не удалось сохранить файл в историю загрузок";
		}
	}

	if($_REQUEST["do"] == "import")
	{
		$bClearCache = defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]) ? true : false;

		/*mod reset prop is_mrc*/
			$products = MnLibElementElement::getForIBlockType(
				$iblockType,
				array(),
				array('ID', 'IBLOCK_ID'),
				array('NO_ADDITIONS' => 'Y', 'NO_PROPS' => 'Y', 'ACTIVE' => null)
			);
			foreach($products as $item){
				CIBlockElement::SetPropertyValuesEx($item["ID"], $item["IBLOCK_ID"], array('IS_MRC' => ""));
			}
		/*end mod reset prop is_mrc*/

		$updateCnt = 0;
		$arUpdatedIblocks = array();
		//$arUpdatedElements = array();
		if(copy($currentFilePath, dirname(__file__) . "/tmp/history/".date("Y.m.d_H.i.s").".csv"))
		{
			foreach($arCsv as $k => $arCsvItem)
			{
				$arElement = $arElements[$arCsvItem["BRAND"]][$arCsvItem["MODEL"]];

				/*mod set prop is_mrc to Y*/
				if( !empty($arElement["ID"]) ){
					CIBlockElement::SetPropertyValuesEx($arElement["ID"], $arElement["IBLOCK_ID"], array('IS_MRC' => "Y"));
				}
				/*end mod set prop is_mrc to Y*/

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

			/* mod update t50 */
			if( $updateCnt > 0 ){
				$urlSync = "http://t50.su/scripts/remote/update_mrc_by_price.php?SHOP_UNID=LHR";
				$t50 = new T50HTTP;
				if( $t50->syncOk($urlSync) )
					$syncT50Message = "<font color='#0F0'>Синхронизировано с t50</font>";
				else
					$syncT50Message = "<font color='#F00'>C t50 не синхронизировано</font>";
			}
			/* end mod update t50 */
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
		<label><input type="radio" class="js-action-select" name="do" value="country"<?if((isset($_REQUEST["do"]) && $_REQUEST["do"] == "country")):?>checked<?endif?> />&nbsp;Сравнить с текущими странами на сайте</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="check_file"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "check_file"):?>checked<?endif?> />&nbsp;Показать товары на сайте, которых нет в файле</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="check_site"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "check_site"):?>checked<?endif?> />&nbsp;Показать товары в файле, которых нет на сайте</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="import"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "import"):?>checked<?endif?> />&nbsp;Импортировать цены на сайт</label><br />
		<label><input type="radio" class="js-action-select" name="do" value="import_country"<?if(isset($_REQUEST["do"]) && $_REQUEST["do"] == "import_country"):?>checked<?endif?> />&nbsp;Импортировать страны на сайт</label><br />
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
			<td>Цена на сайте</td>
			<td>Цена из csv</td>
			<td nowrap>Наличие на складе</td>
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
			<td nowrap><?=(!empty($arElement["STORE"])?$arElement["STORE"]:"-")?></td>
		</tr>
<?
		}
?>
	</table>
<?
	}

	//pre($arCountries);


	if($_REQUEST["do"] == "country") {
?>
	<table>
		<tr>
			<td>Бренд</td>
			<td>Модель</td>
			<td>Товар на сайте</td>
			<td>Страна на сайте</td>
			<td>Страна из csv</td>
			<td nowrap>Наличие на складе</td>
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
			<td<?if(!empty($arElement) && strtolower($arElement["COUNTRY"]) != strtolower($arCsvItem["COUNTRY"])):?> class="bg"<?endif?>><?if(!empty($arElement)):?><?=$arElement["COUNTRY"]?><?else:?>-<?endif?></td>
			<td><?=$arCsvItem["COUNTRY"]?></td>
			<td nowrap><?=(!empty($arElement["STORE"])?$arElement["STORE"]:"-")?></td>
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
				<?=$arElement["NAME"]?>&nbsp;[<a href="/bitrix/admin/iblock_element_edit.php?WF=Y&ID=<?=$arElement["ID"]?>&type=<?=$iblockType?>&lang=ru&IBLOCK_ID=<?=$arElement["IBLOCK_ID"]?>&find_section_section=-1" target="_blank"><?=$arElement["ID"]?></a>]
			</td>
			<td><?=$arElement["PRICE"]?></td>
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
		</tr>
<?
			}
		}
?>
	</table>
<?
	}

	if($_REQUEST["do"] == "import")
	{
		if(empty($arError))
		{
			echo "<span class='bold green'>Обновлено цен: ".$updateCnt."</span><br />";
			if( !empty($syncT50Message) )
				echo $syncT50Message . "<br/>";
		}
		else
		{
			foreach($arError as $error)
				echo $error."<br />";

			echo "<br />";
		}
	}

	if($_REQUEST["do"] == "import_country")
	{
		if(empty($arError))
		{
			echo "<span class='bold green'>Обновлено стран у товаров: ".$updateCnt."</span><br />";
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