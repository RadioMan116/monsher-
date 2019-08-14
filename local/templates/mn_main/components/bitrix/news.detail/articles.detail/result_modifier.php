<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule("iblock");

if( !empty($arResult["DETAIL_PICTURE"]["SRC"]) ){	
	//$arResult["DETAIL_PICTURE"] = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array('width'=>800, 'height'=>500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
}


					$picture = '';
					if($arTovs["PREVIEW_PICTURE"]) {						
						$picture = $arTovs["PREVIEW_PICTURE"];
					}
					else if($arTovs["DETAIL_PICTURE"]) {	
						$picture = $arTovs["DETAIL_PICTURE"];
					}					


		if($picture) {
			$arItem["IMG_1"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>875, 'height'=>410), BX_RESIZE_IMAGE_EXACT, true)["src"];	
			$arItem["IMG_2"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>1750, 'height'=>820), BX_RESIZE_IMAGE_EXACT, true)["src"];	
			$arItem["IMG_3"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>2625, 'height'=>1230), BX_RESIZE_IMAGE_EXACT, true)["src"];
		}



?>