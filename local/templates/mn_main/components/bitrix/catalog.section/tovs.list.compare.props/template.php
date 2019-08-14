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

$COMPARE_LIST = $arParams["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"];
$count_all = $arResult["NAV_RESULT"]->NavRecordCount;


//echo '['.count($arResult["ITEMS"]).']';

//pre($arParams["PROPS_CODE"]);
?>



<?if(count($arResult["ITEMS"]) > 1):?>


						<div class="diagram" id="diagram">
							<div class="diagram__title">Похожие на Liebherr <?=$arParams["PRODUCT_MODEL"]?></div>
							<div class="diagram__subtitle">
								Данные товары можно сравнить более детально, нажав на кнопку «Сравнить эти модели». Вы будете перенаправлены на страницу сравнения.
								<a href="" class="diagram__comparison js-compare_all" data-products="<?=implode('_',$arResult["PRODUCTS_ID"])?>">Сравнить эти модели</a>
							</div>
							<div class="diagram__items js-ecom_product-list" data-list="<? echo $arParams["EC_TYPE"] ? $arParams["EC_TYPE"] : 'Product Detail'?>" >

								<div class="diagram__sidebar">
									<div class="diagram__tabs js-diagram__param1 active">Стоимость товара</div>
									
									<?
									$k2 = 2;
									foreach($arParams["PROPS_NAME"] as $kp=>$name):?>
									<div class="diagram__tabs js-diagram__param<?=$k2?>"><?=$name?></div>
									<?
									$k2++;
									endforeach?>
								</div>
								<div class="diagram__slider js-diagram__slider">
									<div class="swiper-container">
										<div class="swiper-wrapper">


	<?foreach($arResult["ITEMS"] as $k=>$arElement):?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

	//pre($arElement);

	$discountPercent = round(($arElement["MIN_PRICE"]["VALUE"]-$arElement["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arElement["MIN_PRICE"]["VALUE"]);

	$arShow = CStatic::CheckViewParams($arElement);
	//pre($arElement["DISPLAY_PROPERTIES"]);
	
	
	$dd_param = ' data-param1='.$arElement["MIN_PRICE"]["DISCOUNT_VALUE"];
	
	$k2 = 2;
	foreach($arParams["PROPS_CODE"] as $k=>$code) {
		$arProp = $arElement["PROPERTIES"][$code];
		$dd_param.= ' data-param'.$k2.'='.(int)$arProp["VALUE"];
		$k2++;	
	}
	
	/*
	foreach($arElement["PROPERTIES"] as $code=>$arProp) {		
		if(!in_array($code, $arParams["PROPS_CODE"])) continue;
		$dd_param.= ' data-param'.$k2.'='.(int)$arProp["VALUE"];	

		$k2++;	
	}  */
	$title = $arElement["NAME"];
	if($arElement["ID"] == $arParams["PRODUCT"]["ID"]) $title = 'Текущая модель';
	?>	
	
	<div class="swiper-slide js-ecom_product-item" data-id="<?=$arElement['ID']?>" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
												<div class="schedule">
													<div class="diagram__text"></div>
												</div>
												<div class="diagram__item">
													<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" <?=$dd_param?> class="diagram__pic">
														<img class="lazyload" data-src="<?=$arElement["PICTURE"]?>" alt="<?=$arElement["NAME"]?>" />
													</a>
													<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" title="<?=$arElement["NAME"]?>" class="diagram__name">
														<?=$title?>
													</a>
												</div>
	</div>

	<?endforeach;?>
										</div>
									</div>

								</div>
								<div class="diagram__maxvalue"></div>

							</div>
							<div class="swiper-pagination diagram__pagination"></div>
							<div class="diagram__next swiper-button-next"></div>
							<div class="diagram__prev swiper-button-prev"></div>
						</div>


<?endif?>
