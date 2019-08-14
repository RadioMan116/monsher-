<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(count($arResult['ITEMS'])>0):?>    
	
			<?//$_SESSION["SEO_COUNTER"] = 1;?>
            <?foreach($arResult['ITEMS'] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					
			?>
				<!-- begin seo-text -->
					<div id="<?=$this->GetEditAreaId($arItem['ID']);?>">					
						<div class="description__title"><?=$arItem["NAME"]?> </div>
						<div class="description__text"><?=$arItem["PREVIEW_TEXT"]?></div>					
					</div>
				<!-- end seo-text -->				
            <?endforeach;?>    	
	
<?endif;?>