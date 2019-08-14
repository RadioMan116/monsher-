<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$i = 0;
//var_dump($arParams["CUR_DIR"]);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

?>


<?if($arResult["SECTIONS"]):?>
		<div class="glossary closed js-glossary">


<?foreach ($arResult['SECTIONS'] as &$arSection):?>
<?		
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
	
	
	
	//pre($arSection);	
	
	$dd = '';
	if($request->offsetGet("SECTION_CODE") == $arSection['CODE']) $dd = 'active';
	?>
	
	<div class="glossary__title js-glossary__title"><?=$arSection["NAME"]?></div>
	
								<div class="glossary__items js-glossary__items">
								<?foreach($arSection["ITEMS"] as $arItem):?>
									<a href="" class="glossary__item js-glossary__item"><?=$arItem["NAME"]?></a>
								<?endforeach;?>	
								</div>
	
	<div class="glossary__list">
		<?foreach($arSection["ITEMS"] as $arElement):?>

									<div class="glossary__li js-glossary__li">
										<?if($arElement["DETAIL_PICTURE"]):?>
		<?
				$arImg = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true);					
		?>									
										<img class="glossary__img" src="<?=$arImg["src"]?>" alt="<?=$arElement["NAME"]?>" />
										<?endif;?>
										
										<div class="glossary__decs">
											<div class="glossary__b"><?=$arElement["NAME"]?></div>
											<?=$arElement["PREVIEW_TEXT"]?>
										</div>
									</div>
		<?endforeach;?>								
									
	</div>
	
    
<?endforeach;?>
		</div>
<?endif;?>