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

?>

<?if($arResult["ITEMS"]):?>

						<div class="recently-watched js-recently-watched js-catalog_viewed">
							<div class="recently-watched__title">
								Вы недавно смотрели
							</div>
							<div class="swiper-pagination-recently-watched swiper-pagination"></div>
							<div class="swiper-button-prev recently-watched__prev"></div>
							<div class="swiper-button-next recently-watched__next"></div>
							<div class="recently-watched__reset js-viewed_remove-all">Сбросить просмотренное</div>
							<div class="swiper-container js-ecom_product-list" data-list="<? echo $arParams["EC_TYPE"] ? $arParams["EC_TYPE"] : 'Product Detail'?>">
								<div class="swiper-wrapper">


	<?foreach($arResult["ITEMS"] as $arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

	//pre($arElement["CATALOG_PRICE_1"]);

	
	$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);
	
	//$COMPARE_LIST = $arParams["CATALOG_COMPARE_LIST"][$arElement["IBLOCK_ID"]]["ITEMS"];
	
	
	$arShow = CStatic::CheckViewParams($arElement);
	?>

	
	<div class="swiper-slide js-catalog_item js-ecom_product-item" data-id="<?=$arElement['ID']?>"  >
										<div class="recently-watched__pic" >
											<img class="lazyload" data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" />
										</div>
										<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="recently-watched__text">
											<?=$arElement["NAME"]?>
										</a>
										<div class="recently-watched__close js-viewed_remove"></div>
												
		<?/*$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_product.php',
			 Array("PRODUCT" => $arElement),
			 Array("MODE"=>"php")
		 )*/?>									
												
												
	</div>
	

	<?endforeach;?>
								
								</div>
							</div>
						</div>
					
					
<?endif;?>




