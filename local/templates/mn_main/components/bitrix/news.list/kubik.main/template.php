<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>

<?if($arResult["ITEMS"]):?>
<div class="cubik-rubik">
	<div class="cubik-rubik__inner">					
		<?foreach($arResult["ITEMS"] as $i => $arElement):?>
		<?				
		$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		
		
		$dd_bg = '';
		//if($arElement["PREVIEW_PICTURE"]) $dd_bg = 'style="background-image: url('.$arElement["PREVIEW_PICTURE"]["SRC"].');"';
		if($arElement["PREVIEW_PICTURE"]) $dd_bg = ' data-bg="url('.$arElement["PREVIEW_PICTURE"]["SRC"].')" ';
		
		
		?>		
		
		<div class="cubik-rubik__<?=$arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"]?>" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
								
								<?if($arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"] != 'middle'):?>								
								
									<div class="cubik-rubik__<?=$arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"]?>-desc">
										<div class="cubik-rubik__<?=$arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"]?>-text">
											<?if($arElement["PROPERTIES"]["TITLE"]["VALUE"]):?>
											
												<?if($arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"] == 'top'):?>
													<h1 class="cubik-rubik__<?=$arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"]?>-title">
														<?=$arElement["PROPERTIES"]["TITLE"]["VALUE"]?>
													</h1>												
												<?else:?>
													<div class="cubik-rubik__<?=$arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"]?>-title">
														<?=$arElement["PROPERTIES"]["TITLE"]["VALUE"]?>
													</div>
												<?endif;?>
											
											<?endif;?>
											<?=$arElement["PREVIEW_TEXT"]?>
										</div>
									</div>
								
								<?endif;?>								
								
								<div class="cubik-rubik__<?=$arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"]?>-photo" <?=$dd_bg?>></div>
								
								<?if($arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"] == 'middle'):?>
								
									<div class="cubik-rubik__<?=$arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"]?>-desc">
										<div class="cubik-rubik__<?=$arElement["PROPERTIES"]["POSITION"]["VALUE_XML_ID"]?>-text">										
											<?=$arElement["PREVIEW_TEXT"]?>
										</div>
									</div>
								
								<?endif;?>
								
								
								
								
								
								
		</div>
		
		
		<?endforeach;?>			
	</div>						
</div>	
<?endif;?>