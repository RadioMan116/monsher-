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
$i=0;
//var_dump($arParams["CUR_DIR"]);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

?>

<?if($arResult["SECTIONS"]):?>
<div class="tags">
	<ul class="tags__list">
<?foreach ($arResult['SECTIONS'] as &$arSection):?>
<?		
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
	
	//var_dump($arSection['RELATIVE_DEPTH_LEVEL']);	
	
	$dd = '';
	if($request->offsetGet("SECTION_CODE") == $arSection['CODE']) $dd = 'active';
	?>
	<li class="tags__item <?=$dd?>">
		<a href="<?=$arSection["SECTION_PAGE_URL"]; ?>" title="<?=$arSection["NAME"]; ?>" class="tags__link"><?=$arSection["NAME"];?></a>
	</li>
<?endforeach;?>
	</ul>
</div>
<?endif;?>