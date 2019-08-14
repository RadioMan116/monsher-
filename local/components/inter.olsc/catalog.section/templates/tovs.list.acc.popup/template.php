<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();/** @var array $arParams */
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

$count_all = $arResult["NAV_RESULT"]->NavRecordCount;

?>

<?if($arResult["ITEMS"]):?>
	<div class="added-cart__items">
		<div class="basket">
			<span class="basket__title">Не забудьте купить</span>
			<div class="basket__items">
	<?foreach($arResult["ITEMS"] as $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

	//pre($arElement["PROPERTIES"]["S_NEW"]);

	
	$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
	
	$COMPARE_LIST = $arParams["CATALOG_COMPARE_LIST"][$arElement["IBLOCK_ID"]]["ITEMS"];
	
	
	$arShow = CStatic::CheckViewParams($arElement);
	?>
	
	<div class="basket__item" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
					<div class="basket__pic">
						<a class="basket__parent" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">
							<img data-src="<?=$arElement["IMG_1"]?>" alt="<?=$arElement["NAME"]?>" class="basket__img lazyload" />
						</a>
					</div>
					<div class="basket__text">
						<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="basket__description"><?=$arElement["NAME"]?></a>
						<div class="basket__info">
							<?if($arShow["PRICE"]):?>
							<span class="basket__sale"><?=number_format($arElement["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '.', ' ')?><i>руб.</i></span>
							<?endif;?>
							<?										
								$dd_exist = $arElement["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"];
								if($arShow["PRICE_MESS"]) $dd_exist = $arShow["PRICE_MESS"];														
							?>
							<span class="catalog__presence product-card__presence"><?=$dd_exist?></span>
						</div>
						<div class="basket__links">
							<a href="" class="basket__link js-add2basket" data-id="<?=$arElement["ID"]?>"><b>купить</b></a>
						</div>

					</div>
	</div>

	<?endforeach;?>
			</div>
		</div>
	</div>
<?endif;?>




