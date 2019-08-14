<?php
$menu_tip = 'docs_frame';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

// должен быть указан путь до файла и id товара должно быть положительным числом
if( $request->offsetGet("PRODUCT_ID") > 0 && $request->offsetGet("FILE_ID") > 0){
	
	
	$PRODUCT_ID = $request->offsetGet("PRODUCT_ID");
	$FILE_ID = $request->offsetGet("FILE_ID");
		
		$obCache = new CPHPCache;
		$cache_dir = "/".SITE_ID."/doc_view2/";
		$cache_id = 'docs_'.$PRODUCT_ID.'_'.$FILE_ID;
		$arShopInfo = [];
		if($obCache->InitCache(36000, $cache_id, $cache_dir)) {
			$dataEl = $obCache->GetVars();
			$arShopInfo = $dataEl["DATA"];
		}elseif($obCache->StartDataCache()) {
			CModule::IncludeModule("iblock");
			// получаем инфу о файле
			$res = CFile::GetList(
				array(),
				array(	
					"MODULE_ID"=>"iblock",
					"ID" => $FILE_ID,
				)
			);
			// файл не найден в базе
			if(! $arShopInfo["FILE_INFO"] = $res->GetNext()){
				$obCache->AbortDataCache();
				@define("ERROR_404", "Y");
				@define("CONFIRM_ERROR_404", "Y");
			}else{
				// получаем инфу о товаре
				
				if($arProduct = CStatic::getElement($PRODUCT_ID)) {
					$arShopInfo["PRODUCT"] = $arProduct; 
					
			
					// файла у товара нет
					if( !in_array($arShopInfo["FILE_INFO"]["ID"], $arShopInfo["PRODUCT"]["PROPERTIES"]["DOCUMENTATION"]["VALUE"])){
						$obCache->AbortDataCache();
						@define("ERROR_404", "Y");
						@define("CONFIRM_ERROR_404", "Y");
					}
					// “овара нет
				}else{
					$obCache->AbortDataCache();
					@define("ERROR_404", "Y");
					@define("CONFIRM_ERROR_404", "Y");


				}
				$obCache->EndDataCache( [ "DATA" => $arShopInfo ] );
			}
		}
		if(!empty($arShopInfo["PRODUCT"])){
			
			$arFieldsDop = CASDiblockTools::GetIBUF($arShopInfo["PRODUCT"]["IBLOCK_ID"]);
			$arName = CStatic::getElement($arFieldsDop["UF_NAME_ID"], 20);				
			$name_h = strtolower($arName["PROPERTIES"]["CHEM_ONE"]["VALUE"]);
			
			
			$APPLICATION->SetTitle('Monsher '.$arShopInfo["PRODUCT"]["PROPERTIES"]["MODEL"]["VALUE"].' - '.$arShopInfo["FILE_INFO"]["DESCRIPTION"]);
			
			//$APPLICATION->SetPageProperty('title', $arShopInfo["PRODUCT"]["NAME"].' - '.$arShopInfo["FILE_INFO"]["DESCRIPTION"].', просмотр и скачивание.');
			//$APPLICATION->SetPageProperty('description', $arShopInfo["FILE_INFO"]["DESCRIPTION"].' для Либхер '.$arShopInfo["PRODUCT"]["PROPERTIES"]["MODEL"]["VALUE"].'. Посмотреть или скачать инструкцию по эксплуатации к '.$name_h.' Liebherr '.$arShopInfo["PRODUCT"]["PROPERTIES"]["MODEL"]["VALUE"].'.');
			
			
			
			$arBlock = getArIblock('mn_catalog', false, $arShopInfo["PRODUCT"]["IBLOCK_ID"]);
			
			$APPLICATION->AddChainItem($arBlock["NAME"], '/catalog/'.$arBlock["CODE"].'/');
			
			if($arShopInfo["PRODUCT"]["IBLOCK_SECTION_ID"]) {				
				$arSection = getArSection($arBlock["ID"], false, $arShopInfo["PRODUCT"]["IBLOCK_SECTION_ID"]);
				$APPLICATION->AddChainItem($arSection["NAME"], $arSection["SECTION_PAGE_URL"]);
			}
			$APPLICATION->AddChainItem($arShopInfo["PRODUCT"]["NAME"], $arShopInfo["PRODUCT"]["DETAIL_PAGE_URL"]);
			
			
			
			$APPLICATION->AddChainItem( "Просмотр файла", 1);

			
			$files_path = '/upload/'.$arShopInfo["FILE_INFO"]["SUBDIR"].'/'.$arShopInfo["FILE_INFO"]["FILE_NAME"];

			
		?>
		<div class="docs-frame">
			
				<div class="docs-frame_links">
					<a href="<?=$files_path?>" title="Скачать файл" target="_blank">Скачать файл</a>
					/
					<a href="<?=$arShopInfo["PRODUCT"]["DETAIL_PAGE_URL"]?>">Вернуться назад</a>
				</div>
				
				<?if($arShopInfo["FILE_INFO"]["CONTENT_TYPE"]!= 'application/pdf'):?>
					<img src="<?=$files_path?>" alt="" />
				<?else:?>
					<iframe src="https://drive.google.com/viewerng/viewer?embedded=true&url=https://monsher-store.ru<?=$files_path?>" width="100%" style="border: none;"></iframe>
				<?endif;?>
		</div>
		<?
		}else{
			@define("ERROR_404", "Y");
			@define("CONFIRM_ERROR_404", "Y");
		}

	
}else{
	@define("ERROR_404", "Y");
	@define("CONFIRM_ERROR_404", "Y");
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");