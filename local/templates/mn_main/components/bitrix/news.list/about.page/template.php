<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$tpl_path_front = '/mockup/templates/main/build/';
?>

<?if($arResult["ITEMS"]):?>
		<div class="about-us__items">	
		<?foreach($arResult["ITEMS"] as $i => $arElement):?>
		<?				
		$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>			
		
		<div class="about-us__item" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
						
			<?if($arElement["PROPERTIES"]["TITLE"]["VALUE"]):?><h3><?=$arElement["PROPERTIES"]["TITLE"]["VALUE"]?></h3><?endif;?>
			<?if($arElement["PREVIEW_PICTURE"]):?><img data-src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arElement["NAME"]?>" class="about-us__img lazyload" /><?endif;?>
			
			<?if($arElement["PREVIEW_TEXT"]):?>
				<?=$arElement["PREVIEW_TEXT"]?>
			<?endif;?>
			
		</div>				
		
		<?endforeach;?>
		</div>
		
<?endif;?>