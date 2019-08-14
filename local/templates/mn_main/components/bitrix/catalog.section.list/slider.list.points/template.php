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
		<div class="gallery__item js-gallery__item  closed">
			<div class="swiper-wrapper">


<?foreach ($arResult['SECTIONS'] as &$arSection):?>
<?		
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
	
	
	
	//pre($arSection);	
	
	$dd = '';
	if($request->offsetGet("SECTION_CODE") == $arSection['CODE']) $dd = 'active';
	?>
	<div class="swiper-slide">
									<img src="<?=$arSection["PICTURE"]["SRC"]; ?>" alt="<?=$arSection["NAME"]; ?>" />
									
									<?foreach($arSection["ITEMS"] as $arPoint):?>									
									
									<?
									$dd_coord_x = $arPoint["PROPERTIES"]["X_TYPE"]["VALUE_XML_ID"].': '.$arPoint["PROPERTIES"]["X_VALUE"]["VALUE"].$arPoint["PROPERTIES"]["X_VALUE_TYPE"]["VALUE"].';';
									$dd_coord_y = $arPoint["PROPERTIES"]["Y_TYPE"]["VALUE_XML_ID"].': '.$arPoint["PROPERTIES"]["Y_VALUE"]["VALUE"].$arPoint["PROPERTIES"]["X_VALUE_TYPE"]["VALUE"].';';
									
									?>
									
									<div class="gallery__point" style="<?=$dd_coord_x?><?=$dd_coord_y?>">
										<div class="gallery__description"><?=$arPoint["PREVIEW_TEXT"]?></div>
									</div>
									<?endforeach;?>									
									
	</div>
    
<?endforeach;?>
			</div>
			<span class="button__open js-button__open"></span>
		</div>
<?endif;?>