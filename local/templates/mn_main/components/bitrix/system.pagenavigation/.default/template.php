<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();



?>
<!-- begin nav -->

<div class="pagination">
<?
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>

<?
$paramsToDelete = array('IBLOCK_CODE','SECTION_CODE','STYLE_CODE','TYPE_CODE', 'PAGEN_1','PAGEN_2','PAGEN_3','PAGEN_4','PAGEN_5','PAGEN_6','PAGEN_7','PAGEN_8','PAGEN_9','PAGEN_10','clear_cache');

//echo '<br/>'.$strNavQueryString;
//echo '<br/>'.$strNavQueryStringFull;
$strNavQueryString = str_replace($arResult["sUrlPath"],'', $APPLICATION->GetCurPageParam("", $paramsToDelete)); 
$strNavQueryStringFull = str_replace($arResult["sUrlPath"],'', $APPLICATION->GetCurPageParam("", $paramsToDelete)); 


if(substr($strNavQueryString, 0,1) == '?') $strNavQueryString = substr($strNavQueryString,1, strlen($strNavQueryString)-1).'&';

if($_SERVER['REQUEST_URI_REAL']) {		
	$page_site_current = current(explode('?',$_SERVER['REQUEST_URI_REAL']));
	$arResult["sUrlPath"] = $page_site_current;
}
if (CModule::IncludeModule("primelab.urltosef") && CPrimelabUrlToSEF::isHasSEF()) {
	$strNavQueryString = '';
	$strNavQueryStringFull = '';
}


//echo '<br/>'.$strNavQueryString;
//echo '<br/>'.$strNavQueryStringFull;



if($request->offsetGet('PAGEN_1') || $request->offsetGet('PAGEN_2') || $request->offsetGet('PAGEN_3') || $request->offsetGet('PAGEN_4')) {	

		if($request->offsetGet('PAGEN_1') == '1' || $request->offsetGet('PAGEN_2') == '1' || $request->offsetGet('PAGEN_3') == '1' || $request->offsetGet('PAGEN_4') == '1') {	
			LocalRedirect($_SERVER["REDIRECT_URL"].$strNavQueryStringFull, false, '301 Moved permanently');
		}
	
		//pre('https://'.$_SERVER["HTTP_HOST"].$_SERVER["REDIRECT_URL"]);		
		//$APPLICATION->AddHeadString('<link rel="canonical" href="https://'.$_SERVER["HTTP_HOST"].$_SERVER["REDIRECT_URL"].'" />',true);
	}



if($arResult["bDescPageNumbering"] === true):
	$bFirst = true;
	if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
		if($arResult["bSavePage"]):
?>			
			<a class="pagination__prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_prev")?></a>
<?
		else:
			if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):
?>
			<a class="pagination__prev" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_prev")?></a>
<?
			else:
?>
			<a class="pagination__prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_prev")?></a>
<?
			endif;
		endif;
		
		if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
			$bFirst = false;
			if($arResult["bSavePage"]):?>
			<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>">1</a>
<?else:?>
			<a class="pagination__item" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
<?endif;
			if ($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)):?>	
			<a class="pagination__more" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=intVal($arResult["nStartPage"] + ($arResult["NavPageCount"] - $arResult["nStartPage"]) / 2)?>">...</a>
<?
			endif;
		endif;
	endif;
	do
	{
		$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
		
		if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
?>
		<span class="pagination__item pagination__item_state_current"><?=$NavRecordGroupPrint?></span>		
<?
		elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a>
<?
		else:
?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"<?
			?>><?=$NavRecordGroupPrint?></a>
<?
		endif;
		
		$arResult["nStartPage"]--;
		$bFirst = false;
	} while($arResult["nStartPage"] >= $arResult["nEndPage"]);
	
	if ($arResult["NavPageNomer"] > 1):
		if ($arResult["nEndPage"] > 1):
			if ($arResult["nEndPage"] > 2):
?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] / 2)?>">...</a>
<?
			endif;
?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=$arResult["NavPageCount"]?></a>
<?
		endif;
	
?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_next")?></a>
<?
	endif; 

else:
	$bFirst = true;

	if ($arResult["NavPageNomer"] > 1):
		if($arResult["bSavePage"]):
?>
			<a class="pagination__prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"></a>
<?
		else:
			if ($arResult["NavPageNomer"] > 2):
?>
			<a class="pagination__prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"></a>
<?
			else:
?>
			<a class="pagination__prev" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"></a>
<?
			endif;
		
		endif;
		
		if(in_array($arResult["NavTitle"],array('Товары','Отзывы страница'))) {
			
			if(($arResult["NavPageNomer"]-1) == 1) {
				$dd_p = '';
				if($strNavQueryString) $dd_p = '?'.$strNavQueryString;
				
				//$APPLICATION->AddHeadString('<link rel="prev" href="https://'.$_SERVER["HTTP_HOST"].$arResult["sUrlPath"].$dd_p.'" />',true);
			}
			else {
				//$APPLICATION->AddHeadString('<link rel="prev" href="https://'.$_SERVER["HTTP_HOST"].$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1).'" />',true);
			}
		}
		
		if ($arResult["nStartPage"] > 1):
			$bFirst = false;
			if($arResult["bSavePage"]):
?>
			<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a>
<?
			else:
?>
			<a class="pagination__item" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
<?
			endif;
			if ($arResult["nStartPage"] > 2):
/*?>
			<span>...</span>
<?*/
?>
			<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nStartPage"] / 2)?>">...</a>
<?
			endif;
		endif;
	endif;

	do
	{
		if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>		
		<span class="pagination__item pagination__item_state_current"><?=$arResult["nStartPage"]?></span>		
<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
<?else:?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
<?
		endif;
		$arResult["nStartPage"]++;
		$bFirst = false;
	} while($arResult["nStartPage"] <= $arResult["nEndPage"]);
	
	if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
		if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
			if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2)?>">...</a>
<?
			endif;
?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a>
<?
		endif;
?>
		<a class="pagination__next" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"></a>
<?		
		if(in_array($arResult["NavTitle"],array('Товары','Отзывы страница'))) {
			//$APPLICATION->AddHeadString('<link rel="next" href="https://'.$_SERVER["HTTP_HOST"].$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1).'" />',true);
		}

	endif;
endif;

if (false && $arResult["bShowAll"]):
	if ($arResult["NavShowAll"]):
?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0"><?=GetMessage("nav_paged")?></a>
<?
	else:
?>
		<a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_all")?></a>
<?
	endif;
endif
?>
</div>