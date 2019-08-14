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
<nav class="menu-vert">
								
			<?foreach ($arResult["SECTIONS"] as &$arSection):?>	
						<?if($request->offsetGet("SECTION_CODE") == $arSection["CODE"]):?>
							<div class="menu-vert__button visible-sm visible-xs" id="js-menu-vert__button"><?=$arSection["NAME"]; ?></div>
						<?endif;?>		
			<?endforeach;?>		

	<ul class="menu-vert__list">

<?foreach ($arResult['SECTIONS'] as &$arSection):?>
<?		
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
	
	//var_dump($arSection['RELATIVE_DEPTH_LEVEL']);	
	
	$dd = '';
	if($request->offsetGet("SECTION_CODE") == $arSection['CODE']) $dd = 'active';
	?>
		<li class="menu-vert__item <?=$dd?>">
			<a href="<?=$arSection["SECTION_PAGE_URL"]; ?>" title="<?=$arSection["NAME"]; ?>" class="menu-vert__link"><?=$arSection["NAME"];?></a>
		</li>							
    
<?endforeach;?>
	</ul>

</nav>

<?endif;?>