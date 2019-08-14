<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if($arResult["ITEMS"]):?>
<div class="popular-sections">
							<div class="popular-sections__title">
								Популярные разделы
							</div>
							<div class="popular-sections__items">
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
	<a href="<?=$arElement["PROPERTIES"]["LINK"]["VALUE"]?>" title="<?=$arElement["NAME"]?>" class="popular-sections__item" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
									<div class="popular-sections__pic">
										<img class="lazyload" data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" />
									</div>
									<div class="popular-sections__subtitle"><?=$arElement["NAME"]?></div>
	</a>	
<?endforeach;?>
	</div>
</div>
<?endif;?>	
