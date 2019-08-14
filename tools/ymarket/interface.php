<?
define("NEED_AUTH", true);
ini_set('opcache.enable',0);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!($USER->IsAdmin() || $USER->GetID() == 2811)) {die();}

$WORK_FILE = dirname(__file__)."/tmp/options.php";
$SELF_URL = "/tools/ymarket/interface.php";

$IBLOCK_TYPE = 'elica_catalog';

require($WORK_FILE);

CModule::IncludeModule("iblock");

$arResult["TREE"] = array();

// инфоблоки
$arResult["IBLOCKS"] = array();
$res = CIBlock::GetList(array("sort" => "asc"), Array("TYPE" => $IBLOCK_TYPE, "ACTIVE" => "Y"), true);
while($arRes = $res->Fetch())
{
	$arResult["IBLOCKS"][$arRes["ID"]] = array(
		"ID" => $arRes["ID"],
		"NAME" => $arRes["NAME"],
	);
	$arResult["TREE"][$arRes["ID"]] = array(
		"ID" => $arRes["ID"],
		"NAME" => $arRes["NAME"],
		"SECTIONS" => array(),
		"ELEMENTS" => array(),
	);
}

// разделы
$arResult["SECTIONS"] = array();
$arResult["SECTIONS_4_RECURSIVE"] = array();
$dbSection = CIBlockSection::GetList(
	Array(
		"LEFT_MARGIN" => "ASC",
		"SORT" => "ASC",
		"NAME" => "ASC"
	),
	Array(
		"IBLOCK_TYPE" => $IBLOCK_TYPE,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE" => "Y",
		"GLOBAL_ACTIVE" => "Y",
	),
	false
);
while($arSection = $dbSection->GetNext())
{
	if(empty($arResult["TREE"][$arSection["IBLOCK_ID"]]))
		continue;
	
	$parentID = intval($arSection["IBLOCK_SECTION_ID"]);
	$arResult["SECTIONS_4_RECURSIVE"][$arSection["IBLOCK_ID"]][$parentID][] = array(
		"ID" => $arSection["ID"],
		"NAME" => $arSection["NAME"],
		"IBLOCK_ID" => $arSection["IBLOCK_ID"],
		"IBLOCK_SECTION_ID" => $arSection["IBLOCK_SECTION_ID"],
		"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
	);
	
	$arResult["SECTIONS"][$arSection["ID"]] = array(
		"ID" => $arSection["ID"],
		"NAME" => $arSection["NAME"],
		"IBLOCK_ID" => $arSection["IBLOCK_ID"],
		"IBLOCK_SECTION_ID" => $arSection["IBLOCK_SECTION_ID"],
	);
	
	$arResult["TREE"][$arSection["IBLOCK_ID"]]["SECTIONS"][$arSection["ID"]] = array(
		"ID" => $arSection["ID"],
		"NAME" => $arSection["NAME"],
		"ELEMENTS" => array(),
	);
}
//echo "<pre>".print_r($arSectionsID, true)."</pre>";

// элементы
$arResult["ELEMENTS"] = array();
foreach($arResult["IBLOCKS"] as $arIblock)
{
	$resEl = CIBlockElement::GetList(
		Array("ID" => "ASC"),
		array(
			"IBLOCK_ID" => $arIblock["ID"],
			"ACTIVE" => "Y",
			"SECTION_ACTIVE" => "Y",
			"SECTION_GLOBAL_ACTIVE" => "Y",
			array(
				"LOGIC" => "OR",
				array("PROPERTY_MSK_VALUE" => "В наличии"),
				array("PROPERTY_MSK_VALUE" => "Под заказ"),
			),
		),
		false,
		false,
		Array(
			"ID",
			"IBLOCK_ID",
			"IBLOCK_SECTION_ID",
			"NAME",
			"PROPERTY_MSK",
			"CATALOG_GROUP_1",
		)
	);
	while($obEl = $resEl->GetNextElement())
	{
		$arFieldsEl = $obEl->GetFields();
		
		$arResult["ELEMENTS"][$arFieldsEl["ID"]] = array(
			"ID" => $arFieldsEl["ID"],
			"NAME" => $arFieldsEl["NAME"],
			"IBLOCK_ID" => $arFieldsEl["IBLOCK_ID"],
			"IBLOCK_SECTION_ID" => $arFieldsEl["IBLOCK_SECTION_ID"],
			"STORE" => $arFieldsEl["PROPERTY_MSK_VALUE"],
			"PRICE" => $arFieldsEl["CATALOG_PRICE_1"],
		);
		
		if(empty($arFieldsEl["IBLOCK_SECTION_ID"]))
		{
			$arResult["TREE"][$arFieldsEl["IBLOCK_ID"]]["ELEMENTS"][$arFieldsEl["ID"]] = array(
				"ID" => $arFieldsEl["ID"],
				"NAME" => $arFieldsEl["NAME"],
				"STORE" => $arFieldsEl["PROPERTY_MSK_VALUE"],
				"PRICE" => $arFieldsEl["CATALOG_PRICE_1"],
			);
		}
		else
		{
			$arResult["TREE"][$arFieldsEl["IBLOCK_ID"]]["SECTIONS"][$arFieldsEl["IBLOCK_SECTION_ID"]]["ELEMENTS"][$arFieldsEl["ID"]] = array(
				"ID" => $arFieldsEl["ID"],
				"NAME" => $arFieldsEl["NAME"],
				"STORE" => $arFieldsEl["PROPERTY_MSK_VALUE"],
				"PRICE" => $arFieldsEl["CATALOG_PRICE_1"],
			);
			
			$key = !empty($arResult["SECTIONS"][$arFieldsEl["IBLOCK_SECTION_ID"]]["IBLOCK_SECTION_ID"]) ? $arResult["SECTIONS"][$arFieldsEl["IBLOCK_SECTION_ID"]]["IBLOCK_SECTION_ID"] : 0 ;
			$arSections = $arResult["SECTIONS_4_RECURSIVE"][$arFieldsEl["IBLOCK_ID"]][$key];
			foreach($arSections as $k => $v)
			{
				if($v["ID"] == $arFieldsEl["IBLOCK_SECTION_ID"])
				{
					$arResult["SECTIONS_4_RECURSIVE"][$arFieldsEl["IBLOCK_ID"]][$key][$k]["ELEMENTS"][$arFieldsEl["ID"]] = array(
						"ID" => $arFieldsEl["ID"],
						"NAME" => $arFieldsEl["NAME"],
						"STORE" => $arFieldsEl["PROPERTY_MSK_VALUE"],
						"PRICE" => $arFieldsEl["CATALOG_PRICE_1"],
					);
					break;
				}
			}
		}
	}
}

function RecursiveTree(&$rs, $parent)
{
	$out = array();
	
	if(!isset($rs[$parent]))
		return $out;
	
	foreach($rs[$parent] as $row)
	{
		$childs = RecursiveTree($rs, $row["ID"]);
		if($childs)
			$row["SECTIONS"] = $childs;
		
		$out[$row["ID"]] = $row;
	}
	
	return $out;
}

foreach($arResult["IBLOCKS"] as $iblockID => $arIblock)
{
	$arResult["TREE"][$iblockID]["SECTIONS_RECURSIVE"] = RecursiveTree($arResult["SECTIONS_4_RECURSIVE"][$iblockID], 0);
	//echo "<pre>".print_r($arResult["TREE"][$iblockID]["SECTIONS_RECURSIVE"], true)."</pre>";
}

if(!empty($_REQUEST["action"]))
{
	$arIblocks = array();
	$arSections = array();
	$arElements = array();
	
	foreach($_REQUEST["iblock"] as $iblockId)
		if(intval($iblockId)) $arIblocks[] = $iblockId;
	foreach($_REQUEST["section"] as $sectionId)
		if(intval($sectionId)) $arSections[] = $sectionId;
	foreach($_REQUEST["element"] as $elementId)
		if(intval($elementId)) $arElements[] = $elementId;
	
	$arIblockFilter = $arIblocks;
	$arSectionFilter = array();
	$arElementFilter = array();
	
	foreach($arSections as $sectionId)
	{
		if(!in_array($arResult["SECTIONS"][$sectionId]["IBLOCK_ID"], $arIblockFilter))
			$arSectionFilter[] = $sectionId;
	}
	
	foreach($arElements as $elementId)
	{
		if(empty($arResult["ELEMENTS"][$elementId]["IBLOCK_SECTION_ID"]))
		{
			if(!in_array($arResult["ELEMENTS"][$elementId]["IBLOCK_ID"], $arIblockFilter))
				$arElementFilter[] = $elementId;
		}
		else
		{
			if(!in_array($arResult["ELEMENTS"][$elementId]["IBLOCK_SECTION_ID"], $arSectionFilter) && !in_array($arResult["ELEMENTS"][$elementId]["IBLOCK_ID"], $arIblockFilter))
				$arElementFilter[] = $elementId;
		}
	}
	
	$out = "<?
\$arFilterSectionNot = array(
	'!IBLOCK_ID' => array(#arIblockFilter#),
	'!ID' => array(#arSectionFilter#)
);

\$arFilterElNot = array(
	'!IBLOCK_ID' => array(#arIblockFilter#),
	'!SECTION_ID' => array(#arSectionFilter#),
	'!ID' => array(#arElementFilter#)
);
?>";
	
	$arIblockFilterPrint = "";
	$arSectionFilterPrint = "";
	$arElementFilterPrint = "";
	
	if(!empty($arIblockFilter))
	{
		foreach($arIblockFilter as $key => $val)
		{
			if($key > 0)
				$arIblockFilterPrint .= ", ";
			$arIblockFilterPrint .= $val;
		}
	}
	if(!empty($arSectionFilter))
	{
		foreach($arSectionFilter as $key => $val)
		{
			if($key > 0)
				$arSectionFilterPrint .= ", ";
			$arSectionFilterPrint .= $val;
		}
	}
	if(!empty($arElementFilter))
	{
		foreach($arElementFilter as $key => $val)
		{
			if($key > 0)
				$arElementFilterPrint .= ", ";
			$arElementFilterPrint .= $val;
		}
	}
	
	$out = str_replace(array("#arIblockFilter#", "#arSectionFilter#", "#arElementFilter#"), array($arIblockFilterPrint, $arSectionFilterPrint, $arElementFilterPrint), $out);
	
	$fp = fopen($WORK_FILE, "w");
	fwrite($fp, $out);
	fclose($fp);
	
	$_SESSION["reload"] = "Y";
	LocalRedirect($SELF_URL);


	
	//echo "<pre>arIblockFilter".print_r($arIblockFilter, true)."</pre>";
	//echo "<pre>arSectionFilter".print_r($arSectionFilter, true)."</pre>";
	//echo "<pre>arElementFilter".print_r($arElementFilter, true)."</pre>";
	
	//echo "<pre>".print_r($out, true)."</pre>";
}

//echo "<pre>".print_r($arResult, true)."</pre>";

function printSections(&$arSections, $iblockUnchecked)
{
	global $arFilterElNot, $arFilterSectionNot;
	
?>
				<ul class="section" style="display:none;"<?//=(($iblockUnchecked)?' style="display:none;"':'')?>>
<?
			foreach($arSections as $sectionId => $arSection)
			{
				$sectionUnchecked = false;
				if($iblockUnchecked || in_array($sectionId, $arFilterElNot["!SECTION_ID"]))
					$sectionUnchecked = true;
?>
					<li class="section">
<?
				if(!empty($arSection["ELEMENTS"]) || !empty($arSection["SECTIONS"])) {
?>
						<span class="trigger">+<?//=(($sectionUnchecked)?'+':'-')?></span><input type="checkbox" name="section-<?=$sectionId?>" id="section-<?=$sectionId?>" class="section with-childrens" accesskey="<?=$sectionId?>" value="Y" <?=(($sectionUnchecked)?"":"checked")?> /><label for="section-<?=$sectionId?>"><?=$arSection["NAME"]?></label><input type="hidden" class="hidden" name="section[]" id="input-section-<?=$sectionId?>" value="<?=(($sectionUnchecked)?$sectionId:"")?>" /> / ID: <?=$sectionId?>
<?
					if(!empty($arSection["SECTIONS"]))
						printSections($arSection["SECTIONS"], $iblockUnchecked);
?>
						<ul class="element" style="display:none;"<?//=(($sectionUnchecked)?' style="display:none;"':'')?>>
<?
					foreach($arSection["ELEMENTS"] as $elementId => $arElement)
					{
						$elementUnchecked = false;
						if($iblockUnchecked || $sectionUnchecked || in_array($elementId, $arFilterElNot["!ID"]))
							$elementUnchecked = true;
?>
							<li class="element">
								<span class="spacer"></span><input type="checkbox" name="element-<?=$elementId?>" id="element-<?=$elementId?>" class="element" accesskey="<?=$elementId?>" value="Y" <?=(($elementUnchecked)?"":"checked")?> /><label for="element-<?=$elementId?>"><?=$arElement["NAME"]?></label><input type="hidden" class="hidden" name="element[]" id="input-element-<?=$elementId?>" value="<?=(($elementUnchecked)?$elementId:"")?>" />&nbsp;&nbsp;&nbsp;[<?=$arElement["STORE"]?>]&nbsp;&nbsp;&nbsp;[<?=$arElement["PRICE"]?> р.] / ID: <?=$arElement["ID"]?>
							</li>
<?
					}
?>
						</ul>
<?
				} else {
?>
						<span class="spacer"></span><input type="checkbox" name="section-<?=$sectionId?>" id="section-<?=$sectionId?>" class="section" accesskey="<?=$sectionId?>" value="Y" <?=(($sectionUnchecked)?"":"checked")?> /><label for="section-<?=$sectionId?>"><?=$arSection["NAME"]?></label><input type="hidden" class="hidden" name="section[]" id="input-section-<?=$sectionId?>" value="<?=(($sectionUnchecked)?$sectionId:"")?>" /> / ID: <?=$sectionId?>
<?
				}
?>
					</li>
<?
			}
?>
				</ul>
<?
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<script type="text/javascript" src="/tools/js/jquery-1.8.1.min.js"></script>
	<title>Настройки экспорта в Яндекс-маркет</title>
	<style type="text/css">
		container {font-size:14px;line-height:18px;}
		ul {list-style:none;}
		span.trigger {display:block;width:18px;height:18px;overflow:hidden;float:left;cursor:pointer;}
		span.spacer {display:block;width:18px;height:18px;overflow:hidden;float:left;}
	</style>
	<script type="text/javascript">
<?
	if(!empty($_SESSION["reload"])) {
?>
	setTimeout('window.location.reload()', 1000)
<?
	}
	unset($_SESSION["reload"]);
?>
	$(function() {
		$('span.trigger').click(function() {
			if($(this).text() == '+') {
				$(this).next().next().next().next('ul').show();
				$(this).text('-');
			} else {
				$(this).next().next().next().next('ul').hide();
				$(this).text('+');
			}
		});
		
		$('input[type="checkbox"]').bind('click change', function() {
			
			var check = $(this);
			var id = check.attr('accesskey');
			var hidden = $('#input-'+check.attr('id'));
			var par = check.parent();
			
			if(this.checked)
				hidden.val('');
			else
				hidden.val(id);
			
			if(check.hasClass('with-childrens')) {
				if(this.checked) {
					par.find('input[type="checkbox"]').attr('checked', 'checked');
					par.find('input.hidden').val('');
					check.prev('span.trigger').text('-');
					par.find('ul').show();
					par.find('span.trigger').text('-');
				} else {
					par.find('input[type="checkbox"]').removeAttr('checked');
					par.find('input.hidden').each(function() {
						var newVal = $(this).prev().prev().attr('accesskey');
						$(this).val(newVal);
					});
					check.prev('span.trigger').text('+');
					par.find('ul').hide();
					par.find('span.trigger').text('+');
				}
			}
		});
	});
	</script>
</head>
<body>
	<div id="container">
		<h1>Настройки экспорта в Яндекс-маркет</h1>
		<form action="" method="post" id="options-forms">
		<input type="hidden" name="action" value="Y" />
		<ul class="iblock">
<?
foreach($arResult["TREE"] as $iblockId => $arIblock) {
?>
			<li class="iblock">
<?
	$iblockUnchecked = false;
	if(in_array($iblockId, $arFilterElNot["!IBLOCK_ID"]))
		$iblockUnchecked = true;
	
	if(!empty($arIblock["SECTIONS_RECURSIVE"]) || !empty($arIblock["ELEMENTS"])) {

?>
				<span class="trigger">+<?//=(($iblockUnchecked)?"+":"-")?></span><input type="checkbox" name="iblock-<?=$iblockId?>" id="iblock-<?=$iblockId?>" class="iblock with-childrens" accesskey="<?=$iblockId?>" value="Y" <?=(($iblockUnchecked)?"":"checked")?> /><label for="iblock-<?=$iblockId?>"><?=$arIblock["NAME"]?></label><input type="hidden" class="hidden" name="iblock[]" id="input-iblock-<?=$iblockId?>" value="<?=(($iblockUnchecked)?$iblockId:"")?>" />
<?
		if(!empty($arIblock["SECTIONS_RECURSIVE"]))
			printSections($arIblock["SECTIONS_RECURSIVE"], $iblockUnchecked);
		
		if(!empty($arIblock["ELEMENTS"])) {
?>
				<ul class="element" style="display:none;"<?//=(($sectionUnchecked)?' style="display:none;"':'')?>>
<?
			foreach($arIblock["ELEMENTS"] as $elementId => $arElement) {
			
				$elementUnchecked = false;
				if($iblockUnchecked || $sectionUnchecked || in_array($elementId, $arFilterElNot["!ID"]))
					$elementUnchecked = true;
?>
					<li class="element">
						<span class="spacer"></span><input type="checkbox" name="element-<?=$elementId?>" id="element-<?=$elementId?>" class="element" accesskey="<?=$elementId?>" value="Y" <?=(($elementUnchecked)?"":"checked")?> /><label for="element-<?=$elementId?>"><?=$arElement["NAME"]?></label><input type="hidden" class="hidden" name="element[]" id="input-element-<?=$elementId?>" value="<?=(($elementUnchecked)?$elementId:"")?>" />&nbsp;&nbsp;&nbsp;[<?=$arElement["STORE"]?>]&nbsp;&nbsp;&nbsp;[<?=$arElement["PRICE"]?> р.] / ID: <?=$arElement["ID"]?>
					</li>
<?
			}
?>
				</ul>
<?
		}
	}
	else
	{
?>
				<span class="spacer"></span><input type="checkbox" name="iblock-<?=$iblockId?>" id="iblock-<?=$iblockId?>" class="iblock" accesskey="<?=$iblockId?>" value="Y" <?=(($iblockUnchecked)?"":"checked")?> /><label for="iblock-<?=$iblockId?>"><?=$arIblock["NAME"]?></label><input type="hidden" class="hidden" name="iblock[]" id="input-iblock-<?=$iblockId?>" value="<?=(($iblockUnchecked)?$iblockId:"")?>" />
<?
	}
?>
			</li>
<?
}
?>
		</ul>
		<br />
		<input type="submit" value="Сохранить" /><br />
		<br />
		</form>
	</div>
</body>
</html>
