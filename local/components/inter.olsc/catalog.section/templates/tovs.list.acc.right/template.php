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
								<div class="upsale swiper-container-upsale js-container-upsale">
									<span class="upsale__title">Аксессуары для</span>
									<span class="upsale__subtitle"><?=$arParams["PRODUCT_MODEL"]?></span>
									<div class="upsale__items swiper-wrapper">
	<?foreach($arResult["ITEMS"] as $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

	//pre($arElement["PROPERTIES"]["S_NEW"]);

	
	$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
	
	$COMPARE_LIST = $arParams["CATALOG_COMPARE_LIST"][$arElement["IBLOCK_ID"]]["ITEMS"];
	
	
	$arShow = CStatic::CheckViewParams($arElement);
	?>	
	<div class="upsale__item swiper-slide" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
											<div class="upsale__pic">												
												<a class="upsale__parent" href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>">
													<img data-src="<?=$arElement["IMG_1"]?>" data-srcset="<?=$arElement["IMG_2"]?> 2x,<?=$arElement["IMG_3"]?> 3x" alt="<?=$arElement["NAME"]?> preview 1" title="<?=$arElement["NAME"]?> фото 1" class="upsale__img lazyload" />
												</a>
											</div>
											
											<?
											//pre($arElement["PROPERTIES"]["BLOCK_ID"]);
											?>
											
											<div class="upsale__aside">
												<div class="upsale__text">
													<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="upsale__description"><?=$arElement["NAME"]?></a>
												</div>
												<div class="upsale__info">
												
													<?if($arShow["PRICE"]):?>
													<span class="upsale__sale"><?=number_format($arElement["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '.', ' ')?> <i>руб.</i></span>
													<?endif;?>													
													
													<?if($arShow["BUY"]):?>
													<a href="" class="upsale__link upsale__link_two js-add2basket" data-id="<?=$arElement["ID"]?>"><b>купить</b></a>
													<?endif;?>
													
		<?/*$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_product.php',
			 Array("PRODUCT" => $arElement),
			 Array("MODE"=>"php")
		 )*/?>
													
												</div>
										</div>
	</div>
	

	<?endforeach;?>
			</div>
			<div class="swiper-pagination-upsale"></div>
			<div class="swiper-button-prev upsale__prev"></div>
			<div class="swiper-button-next upsale__next"></div>
	</div>
<?endif;?>




