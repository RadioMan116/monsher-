<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$page_site = $APPLICATION->GetCurPage();

?>

<div class="shipping-payment__items  technical-documentation">

								<div class="shipping-payment__item">
									<h3>Параметры поиска:</h3>
									<div class="technical-documentation__element">
										<form class="technical-documentation__form js-form" action="<?=$page_site?>" method="GET">
											<div class="selectbox">
												<select name="IBLOCK_ID" class="js-tech_select">
													<option value="">Выберите категорию ...</option>
													<?foreach($arResult["BLOCKS"] as $arBlock):?>
													<?
													$dd = '';
													if($request->offsetGet("IBLOCK_ID") == $arBlock["ID"]) {$dd = ' selected="selected" ';}													
													?>
													<option value="<?=$arBlock["ID"]?>" <?=$dd?>><?=$arBlock["NAME"]?></option>
													<?endforeach?>	
												</select>
											</div>
											<div class="selectbox js-product_blk">
												<select name="PRODUCT_ID" class="js-tech_select">
													<option value="">Выберите продукт ...</option>
													<?if($request->offsetGet("IBLOCK_ID")):?>
													<?
													$arProducts = $arResult["PRODUCTS"];
													?>
														<?foreach($arProducts as $arProduct):?>
														<?
														$dd = '';
														if($request->offsetGet("PRODUCT_ID") == $arProduct["ID"]) $dd = ' selected="selected" ';													
														?>
														<option value="<?=$arProduct["ID"]?>" <?=$dd?>><?=$arProduct["NAME"]?></option>
														<?endforeach?>	
													<?endif?>	
												</select>
											</div>
										</form>
									</div>
								</div>		
								<?if($request->offsetGet("PRODUCT_ID") && $arResult["PRODUCTS"][$request->offsetGet("PRODUCT_ID")]):?>
								<?
								$arProduct = $arResult["PRODUCTS"][$request->offsetGet("PRODUCT_ID")];
								
								//pre($arResult["PRODUCTS"]);
								
								?>								
									<div class="shipping-payment__item documentation">
										<h3><?=$arProduct["NAME"]?>:</h3>										
										<div class="documents__container">
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
											<div class="load_doc">
												<a href="<?=$files_path?>" title="<?=$arFile["DESCRIPTION"]?>" target="_blank" class="doc_loading doc_loading_first">
													<i class="icon_doc"></i>
													<span><?=$arFile["DESCRIPTION"]?></span>
													<?=$ext?>, <?=$size?>
												</a>
											</div>																			
											<?endforeach;?>	
										<?endif;?>
										</div>
									</div>
								<?endif;?>	
</div>

