<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$page_site = $APPLICATION->GetCurPage();


?>




<?if($arResult["PRODUCTS"]):?>

<div class="documentation">
	<span class="documentation__title">Инструкции для <?=$arParams["BLOCK_TITLE"]?> Liebherr</span>

<?foreach($arResult["PRODUCTS"] as $arProduct):?>

			<?if($arProduct["PROPERTIES"]["DOCUMENTATION"]["VALUE"] && count($arProduct["PROPERTIES"]["DOCUMENTATION"]["VALUE"]) > 0):?>									
											<?foreach($arProduct["PROPERTIES"]["DOCUMENTATION"]["VALUE"] as $FileID):?>
											<?
													$rsFile = CFile::GetByID($FileID);
													$arFile = $rsFile->Fetch();
													//PRE($arFile);
													$files_path = '/upload/'.$arFile["SUBDIR"].'/'.$arFile["FILE_NAME"];
													
													$path_info = pathinfo($files_path);	
													
													$ext = 'pdf';	
													if($path_info["extension"]!= 'pdf') $ext = 'jpg';	 
														
													//$ext = $path_info["extension"];
													
													$size = FBytes($arFile["FILE_SIZE"]);
													$file_name = $arFile["ORIGINAL_NAME"];
													if($arFile["DESCRIPTION"]) $file_name = $arFile["DESCRIPTION"].'.'.$ext;
												
											?>
												<a href="<?=$files_path?>" title="<?=$arFile["DESCRIPTION"]?>" target="_blank" class="doc_loading doc_loading_first">
													<i class="icon_doc"></i>
													<span><?=$arProduct["NAME"]?>, <?=mb_lcfirst($arFile["DESCRIPTION"])?></span>
													<?=$ext?>, <?=$size?>
												</a>																		
											<?
											break;
											endforeach;?>	
			<?endif;?>

<?endforeach?>								
								
	<a href="/technical-documentation/" title="Посмотреть все инструкции" class="documentation__link">Посмотреть все инструкции</a>
</div>
<?endif;?>



