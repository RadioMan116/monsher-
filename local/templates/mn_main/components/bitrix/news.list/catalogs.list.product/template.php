<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if($arResult["ITEMS"]):?>
								<div class="download-catalogs">
									<div class="download-catalogs__title">Скачать каталоги</div>
									<div class="download-catalogs__items swiper-container js-download-catalogs">
										<div class="swiper-wrapper">
<?foreach($arResult["ITEMS"] as $i => $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	
	
	
	
	
	$rsFile = CFile::GetByID($arElement["PROPERTIES"]["DOCS"]["VALUE"]);
	$arFile = $rsFile->Fetch();
	
	$files_path = '/upload/'.$arFile["SUBDIR"].'/'.$arFile["FILE_NAME"];
												
												$path_info = pathinfo($files_path);	
												
												$ext = 'pdf';	
												if($path_info["extension"]!= 'pdf') $ext = 'jpg';	 
													
												//$ext = $path_info["extension"];
												
												$size = FBytes($arFile["FILE_SIZE"]);
												$file_name = $arFile["ORIGINAL_NAME"];
												if($arFile["DESCRIPTION"]) $file_name = $arFile["DESCRIPTION"].'.'.$ext;
	
	?>
	<div class="download-catalogs__item swiper-slide" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
												<a href="<?=$files_path?>" title="<?=$arElement["NAME"]?>" class="download-catalogs__link" target="_blank">
													<div class="download-catalogs__pic">
														<img class="lazyload" data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" />
													</div>
													<div class="download-catalogs__text">
														<?=$arElement["NAME"]?>
														<i><?=$ext?>, <?=$size?></i>
													</div>
												</a>
	</div>
	
<?endforeach;?>
		  </div>
    </div>
</div>	
<?endif;?>	
