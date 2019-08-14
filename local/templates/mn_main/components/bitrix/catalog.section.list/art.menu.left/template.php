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
<div class="filter__item">
	<div class="filter__title">Теги</div>
	<div class="filter__checkbox">


<?foreach ($arResult['SECTIONS'] as &$arSection):?>
<?		
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
	
	
	
	//pre($arSection);	
	
	$dd = '';
	if($request->offsetGet("SECTION_CODE") == $arSection['CODE']) $dd = 'active';
	?>
	
											
		<a class="filter__tags" href="<?=$arSection["SECTION_PAGE_URL"]; ?>" title="<?=$arSection["NAME"]; ?>">
			<span class="checkbox-custom"><?=$arSection["NAME"]; ?></span>													
			<span class="checkbox-number"><?=$arSection["ELEMENT_CNT"]?></span>
		</a>
	
    
<?endforeach;?>
	</div>
</div>
<?endif;?>