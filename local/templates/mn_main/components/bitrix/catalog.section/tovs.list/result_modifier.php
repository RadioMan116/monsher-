<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();





	if($arParams["PROMO"]) {
		$arPromo = $arParams["PROMO"];
		
		$arPromo["IMG_1"] = CFile::ResizeImageGet($arPromo["PREVIEW_PICTURE"], array('width'=>273, 'height'=>657), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
		$arPromo["IMG_2"] = CFile::ResizeImageGet($arPromo["PREVIEW_PICTURE"], array('width'=>546, 'height'=>1314), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
		$arPromo["IMG_3"] = CFile::ResizeImageGet($arPromo["PREVIEW_PICTURE"], array('width'=>819, 'height'=>1971), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];		
		
		$arResult["PROMO"] = $arPromo;
	}
	




		$arResult["G_PROPS_ALL"] = CStatic::DescPropAll();
		
		
		

foreach($arResult["ITEMS"] as $key => &$arElement)
{	
	
			if($arElement["PREVIEW_PICTURE"]) {	
				$arImg = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array('width'=>245, 'height'=>230), BX_RESIZE_IMAGE_PROPERTIONAL, true);	
				$picture = $arImg["src"];	
			}
			else if($arElement["DETAIL_PICTURE"]) {
				$arImg = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], array('width'=>245, 'height'=>230), BX_RESIZE_IMAGE_PROPERTIONAL, true);	
				$picture = $arImg["src"];
			}
			else if($arElement["PROPERTIES"]["PHOTOS"]["VALUE"]) {	
				$arImg = CFile::ResizeImageGet($arElement["PROPERTIES"]["PHOTOS"]["VALUE"][0], array('width'=>245, 'height'=>230), BX_RESIZE_IMAGE_PROPERTIONAL, true);	
				$picture = $arImg["src"];
			}
	
	
	$arElement["PICTURE"] = $picture;
	
	$arElement = CStatic::CheckSalePrice($arElement);
	
	
	$arElement["LABELS"] = CStatic::GetLabelsInfo($arElement);
	$arElement["LABELS_DOP"] = CStatic::GetLabelsInfoDop($arElement);
	
	
	
}


	
	
	if($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]!='' && $arParams["IBLOCK_ID"] == 389)  $arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] = $arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"].' вытяжки Elica';

/* ######################## Для правил ЧПУ, заносим колво товароов на странице ###################### */
CModule::IncludeModule("iblock");
if (CModule::IncludeModule("primelab.urltosef") && CPrimelabUrlToSEF::isHasSEF()/* && $USER->IsAdmin()*/) {
	$arRule = CPrimelabUrlToSEF::isHasSEFGetData();
	$ruleId = $arRule["ID"];
	$count_all = $arResult["NAV_RESULT"]->NavRecordCount;
	if($arRule["PROPERTY_COUNT_VALUE"]!=$count_all) {
		//pre($count_all);
		//pre($ruleId);
		
		CIBlockElement::SetPropertyValuesEx(
			$ruleId,
			16,
			array('COUNT' => $count_all)	
		);
	}	
}
/* ######################## Для правил ЧПУ, заносим колво товароов на странице END ###################### */


?>