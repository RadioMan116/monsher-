<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>

<?if($arResult["ITEMS"]):?>
				
		<?foreach($arResult["ITEMS"] as $i => $arElement):?>
		<?				
		$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>		
		
			<?switch($arElement["PROPERTIES"]["TYPE"]["VALUE_XML_ID"]) {				
				
				case "T1":
				
				?>				
					
					 <div class="why-quote content-fluid" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                            <div class="why-quote__title">
								<?if($arElement["DISPLAY_PROPERTIES"]["TITLE_1"]["DISPLAY_VALUE"]):?>
									<h3><?=$arElement["DISPLAY_PROPERTIES"]["TITLE_1"]["DISPLAY_VALUE"]?></h3>
								<?endif;?>
                               <?=$arElement["DISPLAY_PROPERTIES"]["TITLE_2"]["DISPLAY_VALUE"]?>
                            </div>
                            <div class="why-quote__desc">
                                <?=$arElement["PREVIEW_TEXT"]?>
                            </div>
                    </div>
<?
				break;
				case "T2":
?>

					<div class="why-text content-fluid" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                            <div class="why-text__title">
								<?if($arElement["DISPLAY_PROPERTIES"]["TITLE_1"]["DISPLAY_VALUE"]):?>
                                <h3><?=$arElement["DISPLAY_PROPERTIES"]["TITLE_1"]["DISPLAY_VALUE"]?></h3>
								<?endif;?>
                            </div>
                            <div class="why-text__desc">
                                <?=$arElement["PREVIEW_TEXT"]?>
                            </div>
                    </div>				
					
				<?break;?>	
				<?case "T3":?>
				
						 <div class="why-info" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<div class="why-info__text">
								<?=$arElement["PREVIEW_TEXT"]?>
							</div>
						</div>
							
				<?break;
				
				
				
			}
			
			?>
				
		
		<?endforeach;?>	
		
		
<?endif;?>