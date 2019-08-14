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
$templateLibrary = array('popup');
$currencyList = '';
if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BASIS_PRICE' => $strMainID.'_basis_price',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'BASKET_ACTIONS' => $strMainID.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);



	
	$COMPARE_LIST = $arParams["CATALOG_COMPARE_LIST"][$arResult["IBLOCK_ID"]]["ITEMS"];
	
	//pre($_SESSION["CATALOG_COMPARE_LIST"]);

	
	$FAVORITE_LIST = array();
	if($arParams["FAVORITE_LIST"]) $FAVORITE_LIST = explode('|',$arParams["FAVORITE_LIST"]);
	
	
	//pre($arResult['MORE_PHOTO']);
	
	//pre($arResult['MORE_PHOTO']);
	$arImgText = false;
	
	$arShow = CStatic::CheckViewParams($arResult);
	
	
	
	
	
	$arResult["D_PROPS_TOP"] = array(
		"GUARANTEE",
		"COUNTRY",
		"SERIES"
	);
	
	if($_GET["mode"]) {
				//pre($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]);
				//PRE($arParams["PRICE_CODE"]);
			}
	
	//Pre($arResult["PROPERTIES"]["FAST_DELIVERY"]);
?>








						<h1 class="product-card__title"><? echo (
		isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
		? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
		: $arResult["NAME"]
	); ?>
	</h1>
	<div class="product-card js-ecom_product-detail" itemscope itemtype="http://schema.org/Product" data-id="<?=$arResult["ID"]?>">
	<?
		$arResult["MICRO_DESC"] = $arResult["PROPERTIES"]["DESCRIPTION"]["VALUE"];
		?>					
		<?$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_product-detail.php',
			 Array("PRODUCT" => $arResult),
			 Array("MODE"=>"php")
		 )?>
	
						<div class="product-card__slider">
							<div class="swiper-pagination gallery-top__pagination"></div>
							<div class="swiper-container gallery-top js-gallery-top">
								<div class="swiper-wrapper">
								
								
<?foreach($arResult['MORE_PHOTO'] as $k=>&$arPhoto):?>	
<?		$marg = 0;
		if($arPhoto["ID"]) {
			$img_3 = CFile::ResizeImageGet($arPhoto["ID"], array('width'=>1185, 'height'=>1185), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$img_2 = CFile::ResizeImageGet($arPhoto["ID"], array('width'=>790, 'height'=>790), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$img_1 = CFile::ResizeImageGet($arPhoto["ID"], array('width'=>395, 'height'=>395), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			
		}
		else {
			$img_3["src"] = $img_2["src"] = $img_1["src"] = $arPhoto["SRC"];			
		}
		
	/*
		$alt_s = $arResult["NAME"].'  preview '.($k+1);		
		$alt_s = 'Купить '.$alt_s;
	
		if(!$k) $title_s = $arResult["NAME"];		
		else $title_s = ' фото '.($k+1);
	*/
	
	$title_s = $arResult["NAME"].' фото '.($k+1);
	$alt_s = $arResult["NAME"].' preview '.($k+1);
?>
	<div class="swiper-slide">
		<a data-fancybox="gallery" href="<?=$img_1["src"]?>" data-srcset='<?=$img_2["src"]?> 2x,<?=$img_3["src"]?> 3x'>
			<img data-src="<?=$img_1["src"]?>" data-srcset="<?=$img_2["src"]?> 2x,<?=$img_3["src"]?> 3x" alt="<?=$alt_s?>" title="<?=$title_s?>" class="special-offers__img catalog__img lazyload" />	
		</a>		
	</div>
<?endforeach;?>

<?if($arResult['VIDEOS']):?>	
				<?foreach($arResult['VIDEOS'] as $k=>&$arVideo):?>	
				<?
					$tar =  parse_url($arVideo["PROPERTIES"]["CODE"]["VALUE"]);	
					$arr = explode('/',$tar["path"]);	
					//$video_code = '//www.youtube.com/watch?v='.end($arr);
				
				
				?>
					<div class="swiper-slide">
						<a data-fancybox="gallery" data-type="iframe" href="https://www.youtube.com/embed/<?=$arr?>">
							<iframe width="100%" height="100%" class="lazyload" data-src="//www.youtube.com/embed/<?=$arr?>" frameborder="0" allowfullscreen=""></iframe>
						</a>
					</div>
				<?endforeach;?>
<?endif;?>			


								
									
								</div>
								<!-- Add Arrows -->
								<!-- <div class="swiper-button-next swiper-button-white"></div>
				<div class="swiper-button-prev swiper-button-white"></div> -->
							</div>
							<div class="swiper-container gallery-thumbs js-gallery-thumbs">
								<div class="thumbs-next"></div>
								<div class="thumbs-prev"></div>
								<div class="swiper-wrapper">
								
<?foreach($arResult['MORE_PHOTO'] as $k=>&$arPhoto):?>	
<?		$marg = 0;
		if($arPhoto["ID"]) {
			$img_3 = CFile::ResizeImageGet($arPhoto["ID"], array('width'=>330, 'height'=>330), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$img_2 = CFile::ResizeImageGet($arPhoto["ID"], array('width'=>220, 'height'=>220), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$img_1 = CFile::ResizeImageGet($arPhoto["ID"], array('width'=>110, 'height'=>110), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			
		}
		else {
			$img_3["src"] = $img_2["src"] = $img_1["src"] = $arPhoto["SRC"];			
		}
		
	
		$alt_s = $arResult["NAME"].'  preview '.($k+1);		
		$alt_s = 'Купить '.$alt_s;
	
		if(!$k) $title_s = $arResult["NAME"];		
		else $title_s = 'Фото '.($k+1);	
?>
	<div class="swiper-slide">
		<img data-src="<?=$img_1["src"]?>" data-srcset="<?=$img_2["src"]?> 2x,<?=$img_3["src"]?> 3x" class="special-offers__img catalog__img lazyload" />	
	</div>
<?endforeach;?>		
								
	<?if($arResult['VIDEOS']):?>	
				<?foreach($arResult['VIDEOS'] as $k=>&$arVideo):?>	
				<?
					$tar =  parse_url($arVideo["PROPERTIES"]["CODE"]["VALUE"]);	
					$arr = explode('/',$tar["path"]);	
					//$video_code = '//www.youtube.com/watch?v='.end($arr);
				
				
				?>
					<div class="swiper-slide">
						<iframe width="100%" height="100%" class="lazyload" data-src="//www.youtube.com/embed/<?=$arr?>" frameborder="0" allowfullscreen=""></iframe>
					</div>
				<?endforeach;?>
	<?endif;?>									
									
								</div>
							</div>

						</div>
						<div class="product-card__info">
							<div class="product__header product__header_top">
								<?
								$arRate = CStatic::GetReviewsRating($arResult["ID"]);
								$rating_all = $arRate["RATE"];
								
								?>
							
								<div class="ratings ratings_small">
									<?
									$k = 0;
									while($k++ < 5):?>
										<div class="ratings__star <?if($k > $rating_all):?>ratings__none<?endif;?>"></div>
									<?endwhile;?>	
									
									<b class="ratings__number"><?=$rating_all?></b>
								</div>
								<a href="#reviews" data-scroll class="reviews__all"><?=$arRate["COUNT"]?> <?=declOfNum($arRate["COUNT"], array('отзыв', 'отзыва', 'отзывов'))?></a>
								
								<?if($arResult["PROPERTIES"]["_UNID"]["VALUE"]):?><span class="product-card__key">Код товара: <?=$arResult["PROPERTIES"]["_UNID"]["VALUE"]?></span><?endif;?>
																
								<div class="product-card__social">
								
									<a href="#" class="item js-share">											
										<img class="lazyload" data-src="<?=CStatic::$pathV?>images/share.svg" alt="Поделиться" />
										<p>Поделиться</p>
									</a>
										<div class="share__items">
											<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter"></div>
										</div>
								
									
									<?
										$dd_c = '';									
										$dd_text = 'Сравнить';									
										if($COMPARE_LIST[$arResult["ID"]]) {$dd_c = 'active'; $dd_text = 'В сравнении';}							
									?>	
									<a href="" class="item compare js-compare <?=$dd_c?>" data-id="<?=$arResult["ID"]?>"><p><?=$dd_text?></p></a>	
									
									
						<?
							$dd_c = '';
							$dd_text = 'В избранное';
							if(in_array($arResult["ID"],$FAVORITE_LIST)) {$dd_c = 'active'; $dd_text = 'Из избранного';}							
						?>						
							<a href="" title="" class="item favorite <?=$dd_c?> js-add2favorite" data-id="<?=$arResult["ID"]?>" ><p><?=$dd_text?></p></a>
															
									
									
								</div>
							</div>
							<div class="product__header product__header_middle">
							
								<?										
									$dd_exist = $arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"];
									if($arShow["PRICE_MESS"]) $dd_exist = $arShow["PRICE_MESS"];

									$dd_s = '';
									if(in_array($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"], array('Нет в наличии'))) $dd_s = 'red';
									else if(in_array($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"], array('Снят с производства'))) $dd_s = 'out-production';
								?>
								<span class="catalog__presence product-card__presence <?=$dd_s?>"><?=$dd_exist?></span>
								
								<?if($arShow["PRICE_DETAIL"]):?>
								<span class="product-card__date">
									<?if(in_array($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"], array('В наличии', 'Под заказ'))):?>
										Обновление цен от <?=strtolower(FormatDate("j F Y", time()-86400))?>									
									<?else:?>
										Цена действительна на момент последней продажи
									<?endif;?>
								</span>	
								<?endif;?>								
								
							</div>
							<div class="product__header product__header_bottom">
							
							
							<?if($arShow["PRICE_DETAIL"]):?>	
							
							
							<?
							if($USER->IsAdmin()) {
								//pre($arResult["PRICES"]);
							}
							?>
							
							
											<?if(count($arResult["PRICES"]) > 1 && $arResult["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]] && $arResult["PRICES"][$GLOBALS["K_PRICE_CODE"]] ):?>	
															<span class="product-card__price price__dashed">
																<?															
																$discountPercent = round(($arResult["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]-$arResult["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"])*100/$arResult["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"]);
																?>
																<?=number_format($arResult["PRICES"][$GLOBALS["K_PRICE_CODE_SALE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> <b>руб.</b>
																<span class="price__absolute"><?=number_format($arResult["PRICES"][$GLOBALS["K_PRICE_CODE"]]["DISCOUNT_VALUE"], 0, '.', ' ')?> руб.</span>
															</span>	
																	
											<?else:?>
														<span class="product-card__price <?if($arResult["MIN_PRICE"]["DISCOUNT_VALUE"]!=$arResult["MIN_PRICE"]["VALUE"]):?>price__dashed<?endif;?> <?if(in_array($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"], array('Нет наличии')) && $arResult["PROPERTIES"]["ARRIVAL_DATE"]["VALUE"]):?>expected-time<?endif;?>">
																<?															
																$discountPercent = round(($arResult["MIN_PRICE"]["VALUE"]-$arResult["MIN_PRICE"]["DISCOUNT_VALUE"])*100/$arResult["MIN_PRICE"]["VALUE"]);
																?>																
																<?=number_format($arResult["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '.', ' ')?> <b>руб.</b>																
																<?if($arResult["MIN_PRICE"]["DISCOUNT_VALUE"]!=$arResult["MIN_PRICE"]["VALUE"]):?>
																	<span class="price__absolute"><?=number_format($arResult["MIN_PRICE"]["VALUE"], 0, '.', ' ')?> руб.</span>	
																<?endif;?>
																
																<?if(in_array($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"], array('Нет наличии')) && $arResult["PROPERTIES"]["ARRIVAL_DATE"]["VALUE"]):?>
																	<span class="expected-time__text">Ожидаемый срок поставки<br/> <?=$arResult["PROPERTIES"]["ARRIVAL_DATE"]["VALUE"]?></span>
																<?endif;?>
														</span>		
											<?endif;?>								
																
							<?endif;?>	
							
							
							
							
							
							
								<div class="product-card__button">
								
									<?if($arShow["BUY"]):?>
										<a href="" data-action="BuyOneClick" data-id="<?=$arResult["ID"]?>" class="product-card__icons js-viewForm">Купить в 1 клик</a>										
										<a href="" data-id="<?=$arResult["ID"]?>" class="product-card__buy js-add2basket"></a>
									<?elseif(in_array($arResult["PROPERTIES"][$GLOBALS["K_EXIST_CODE"]]["VALUE"], array('Снят с производства'))):?>	
										<a href="#diagram" data-scroll title="Посмотреть аналогичные товары" class="product-card__buy similar-products__button">Посмотреть аналогичные товары</a>										
									<?endif;?>	
									
								</div>
								
								
								
								
								
								
								
							</div>
							
							
							<?if($arShow["LABELS"]):?>
							<div class="product-card__announcing">

								<?
								$mess = 'Удобная доставка';
								if($arResult["LABELS"]["LABEL_FREE_DELIVERY"]) $mess = 'Бесплатная доставка';
								?>								
								<div class="announcing__item">
									<img class="lazyload" data-src="<?=CStatic::$pathV?>images/big/truck.svg" alt="<?=$mess?>" title="<?=$mess?>" />
								</div>								
								<?
								$mess = 'Профессиональное подключение';
								if($arResult["LABELS"]["LABEL_FREE_CONNECTION"]) $mess = 'Бесплатное подключение';
								?>								
								<div class="announcing__item">
									<img class="lazyload" data-src="<?=CStatic::$pathV?>images/big/tools.svg" alt="<?=$mess?>" title="<?=$mess?>" />
								</div>
								
								<?if($arResult["PROPERTIES"]["COUNTRY"]["VALUE"]):?>								
								
								<?if($arCountry = CStatic::getElement($arResult["PROPERTIES"]["COUNTRY"]["VALUE"], 18)):?>								
								
									<?if($arCountry["PROPERTIES"]["IMG"]["VALUE"]):?>
									<?								
									$img = CFile::GetPath($arCountry["PROPERTIES"]["IMG"]["VALUE"]);
									?>
									
									<div class="announcing__item">
										<img class="lazyload" data-src="<?=$img?>" alt="Производство <?=$arCountry["NAME"]?>" title="Производство <?=$arCountry["NAME"]?>" />
									</div>
									<?endif;?>
								<?endif;?>
								<?endif;?>
								
								
								<?if($arResult["LABELS_DOP"]):?>
									<?foreach($arResult["LABELS_DOP"] as $label):?>
										<div class="announcing__item">
											<img class="lazyload" data-src="<?=$label["IMG"]?>" alt="<?=$label["NAME"]?>"  />
										</div>
									<?endforeach;?>
								<?endif;?>
								<? /*
								<div class="announcing__item">
				<img class="lazyload" data-src="images/big/best.svg" alt="Лучшее предложение" title="Лучшее предложение">
			</div>
			<div class="announcing__item">
				<img class="lazyload" data-src="images/big/flag.svg" alt="сделано в Болгарии" title="сделано в Болгарии">
			</div> 
			*/ ?>
							</div>
							<?endif;?>
							
							<?if($arResult["MODELS"]):?>
							<div class="product-card__rel">
								<h4>Модели этой серии</h4>
								<div class="swiper-pagination rel__pagination"></div>
								<div class="swiper-button-prev rel__prev"></div>
								<div class="swiper-button-next rel__next"></div>
								<table class="swiper-container js-product-card__rel">
									<tbody class="swiper-wrapper">
										<?foreach($arResult["MODELS"] as $arModel):?>
										<?
										$arModel["MIN_PRICE"] = reset(CStatic::GetPrice($arModel["ID"]));	
										
										if($arModel["PREVIEW_PICTURE"]) $img_id = $arModel["PREVIEW_PICTURE"];
										else if($arModel["DETAIL_PICTURE"]) $img_id = $arModel["PREVIEW_PICTURE"];	
										else if($arModel["PROPERTIES"]["PHOTOS"]["VALUE"]) $img_id = $arModel["PROPERTIES"]["PHOTOS"]["VALUE"][0];	
			
										$arImg = CFile::ResizeImageGet($img_id, array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPERTIONAL, true);	
	
	
										$arShow_m = CStatic::CheckViewParams($arModel);
	
	
										?>
										<tr class="swiper-slide">
											<th>
												<img class="lazyload" data-src="<?=$arImg["src"]?>" alt="<?=$arModel["PROPERTIES"]["YM_MODEL"]["VALUE"]?>" />
											</th>
											<td>
												<a href="<?=$arModel["DETAIL_PAGE_URL"]?>" title="<?=$arModel["PROPERTIES"]["YM_MODEL"]["VALUE"]?>" target="_blank"><?=$arModel["PROPERTIES"]["YM_MODEL"]["VALUE"]?></a>
												<?if($arShow_m["PRICE"]):?>
													<?=number_format($arModel["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '.', ' ')?> руб.	
												<?endif;?>
											</td>
										</tr>
										<?endforeach;?>
									</tbody>
								</table>
							</div>
							<?endif;?>
							
							<?if($arResult["IBLOCK_ID"]!=CStatic::$accIdBlock):?>
							<div class="product-card__promo">
								<div class="item">
									<img class="lazyload" data-src="<?=CStatic::$pathV?>images/promo.png" alt="Cравни похожие товары!" />
								</div>
								<a href="#diagram" data-scroll class="item">Cравни похожие товары!</a>
							</div>
							<?endif;?>
							
							<?if(strip_tags($arResult["PREVIEW_TEXT"])):?>
								<div class="product-card__description text-default">
									<h2>Описание</h2>								
									<?=$arResult["PREVIEW_TEXT"]?>
									<a href="#new-description" data-scroll class="description__link">Читать полностью</a>
								</div>
							<?endif;?>							
							
						</div>
						
					
						
						
						
						
						
						
						<div class="product-card__middle">
							<div class="middle__header">
								<div class="product-card__back">
								
								
									<div class="catalog__link">
										<a href="" class="catalog-question js-viewForm" data-action="feedbackAdd" data-id="<?=$arResult["ID"]?>">
											Задать вопрос об этом товаре
										</a>
										<a href="<?=$arResult["LIST_PAGE_URL"]?>" title="Вернуться в каталог" class="catalog-back">Вернуться в каталог</a>
									</div>									
									
									<?if($arShow["LABELS"]):?>
											<div class="labels">	
													<?if($arResult["LABELS"]["S_NEW"]):?>
														<img class="lazyload" data-src="<?=CStatic::$pathV?>images/lbl__new.png" alt="Новинка" />
													<?endif;?>
													<?if($arResult["LABELS"]["S_HIT"]):?>
														<img class="lazyload" data-src="<?=CStatic::$pathV?>images/lbl__hit.png" alt="Хит продаж" />														
													<?endif;?>
													
													<?if($arResult["LABELS"]["S_SALE"]):?>
														<img class="lazyload" data-src="<?=CStatic::$pathV?>images/lbl__sale.png" alt="Акция" />													
													<?endif;?>
													
													
													<?/*if($arResult["LABELS_DOP"]):?>
																	<?foreach($arResult["LABELS_DOP"] as $label):?>
																		<div class="label__item"><img data-src="<?=$label["IMG"]?>" alt="<?=$label["NAME"]?>" class="label__pic lazyload" /></div>																
																	<?endforeach;?>
													<?endif;*/?>
										</div>	
									<?endif;?>	
									
								</div>
								
								

						
<?
global $arFilterProp;
$arPropID = array();
foreach($arResult["PROPERTIES"] as $prop) {
	if($arPropDesc = CStatic::DescPropCheck($arResult["G_PROPS_ALL"], $prop["ID"], $prop["VALUE"])) {	
		$arPropID[] = $arPropDesc["ID"];
	}
}
$arFilterProp = array("ID" => false);
if( !empty($arPropID) ){
	$arFilterProp = array(
		"ID" => $arPropID
	);
}



if($_GET["mode"]) {	
//pre($arFilterProp);
}

?>	
<?$APPLICATION->IncludeComponent("bitrix:news.list", "glossary.list.product", array(
        "IBLOCK_TYPE" => "mn_content",
        "IBLOCK_ID" => "89",
        "NEWS_COUNT" => "99",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "NAME",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arFilterProp",
        "FIELD_CODE" => array(
            "ID",
            "NAME",
            "PREVIEW_TEXT",
            "PREVIEW_PICTURE",
        ),
        "PROPERTY_CODE" => array(
            "PROPS_ID",
            "ICON"
        ),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_OPTION_ADDITIONAL" => ""
    ),
    false
);?>								
								
							
							</div>
							<div class="tabs">
								<a href="#" class="tabs__link">Характеристики</a>
								<div class="hide-tabs">
									<div class="tabs__title">
										
										
										<?foreach($arResult["D_PROPS_TOP"] AS $code):?>
										
											<?if($arResult["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]):?>
											<?
											$val = preg_replace('#<a[^>]*>(.*?)</a>#is', '$1', $arResult["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
											?>
											<div class="characteristic__row">
												<span class="characteristic__name series"><?=$arResult["DISPLAY_PROPERTIES"][$code]["NAME"]?></span>
												<span class="characteristic__dashed"></span>
												<span class="characteristic__property"><?=$val?></span>
											</div>
											<?endif;?>
											
										<?endforeach;?>
										
										
										
									</div>	
									
									<?foreach($arResult["DISPLAY_PROPERTIES_BLOCKS"] as $block):?>
															<? if( empty($block["ITEMS"]) ) continue; ?>
																
																		<span class="characteristic__title js-characteristic__title"><?=$block["NAME"]?></span>																		
																						
																		<?foreach($block["ITEMS"] as $itemProp):
																		
																		
																		if(in_array($itemProp["CODE"], $arResult["D_PROPS_TOP"])) continue;
																		
																		//if(in_array(strtolower($itemProp["DISPLAY_VALUE"]),array('да','y'))) {$itemProp["DISPLAY_VALUE"] = '<i class="params-value_check middle"></i>';}
																		?>																		
																		<div class="characteristic__row">
																			<span class="characteristic__name"><?=$itemProp["NAME"]?></span>
																			<span class="characteristic__dashed"></span>
																			<span class="characteristic__property">
																					<?if(is_array($itemProp["DISPLAY_VALUE"])):?>
																						<ul>
																							<?foreach($itemProp["DISPLAY_VALUE"] as $val):?>
																								<li><?=$val?></li>
																							<?endforeach;?>
																						</ul>
																					<?else:?>
																						<?=$itemProp["DISPLAY_VALUE"]?>
																					<?endif;?>
																					
																					
																					<?if($arPropDesc = CStatic::DescPropCheck($arResult["G_PROPS_ALL"], $itemProp["ID"], $itemProp["VALUE"])):?>

																						<div class="characteristic-glossary js-characteristic-glossary">
																							<div class="icon-gloss"></div>
																							<div class="popup-gloss">
																								<?if($arPropDesc["PREVIEW_PICTURE"]):?>
																									<?
																									$arPropDesc["PICTURE"] = CFile::ResizeImageGet($arPropDesc["PREVIEW_PICTURE"], array('width'=>226, 'height'=>400), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
																									?>	
																									<img class="characteristic-glossary__img lazyload" data-src="<?=$arPropDesc["PICTURE"]?>" alt="<?=$arPropDesc["NAME"]?>" />
																								<?endif;?>
																							
																								<div class="characteristic-glossary__title"><?=$arPropDesc["NAME"]?></div>
																								
																								<?if($arPropDesc["PREVIEW_TEXT"]):?>
																									<div class="characteristic-glossary__text"><?=$arPropDesc["PREVIEW_TEXT"]?></div>
																								<?endif;?>
																								
																								<a href="/glossary/" class="characteristic-glossary__link">Перейти в глоссарий</a>
																							</div>
																						</div>

																					<?endif;?>
																			</span>
																		</div>
																																
																		<?endforeach;?>
																		
									<?endforeach?>
									
								</div>
								<span class="characteristic__copyright">
									<?$APPLICATION->IncludeFile('/local/include_areas/product-params_comment.php')?>								
								</span>
							</div>
							<div class="sidebar-right">
							
							
								<?if($arResult["PROPERTIES"]["DOCUMENTATION"]["VALUE"] && count($arResult["PROPERTIES"]["DOCUMENTATION"]["VALUE"]) > 0):?>	
								<div class="documentation">
									<span class="documentation__title">Скачать инструкцию</span>

									
								
										<?foreach($arResult["PROPERTIES"]["DOCUMENTATION"]["VALUE"] as $FileID):?>
										<?
												$rsFile = CFile::GetByID($FileID);
												$arFile = $rsFile->Fetch();
												
												
												$path_info = pathinfo($files_path);	
												
												if($USER->IsAdmin()) {
													//PRE($arFile);
													//PRE($path_info);
												}
												
												$ext = 'pdf';	
												if($arFile["CONTENT_TYPE"]!= 'application/pdf') $ext = 'jpg';	 
													
												
												$size = FBytes($arFile["FILE_SIZE"]);
												$file_name = $arFile["ORIGINAL_NAME"];
												if($arFile["DESCRIPTION"]) $file_name = $arFile["DESCRIPTION"].'.'.$ext;
												
												
												$files_path = '/doc-view/'.$arResult["ID"].'/'.$arFile["ID"].'/';
												
												/*$files_path = '/upload/'.$arFile["SUBDIR"].'/'.$arFile["FILE_NAME"];
												
												if($ext == 'pdf') {
													$files_path = '/doc-view/upload/'.$arFile["SUBDIR"].'/'.str_replace( ".pdf", "",$arFile["FILE_NAME"]).'/'.$arResult["ID"].'/';
												}*/
											
										?>
										<a href="<?=$files_path?>" title="<?=$arFile["DESCRIPTION"]?>" target="_blank" class="doc_loading doc_loading_first">
											<i class="icon_doc"></i>
											<span><?=$arFile["DESCRIPTION"]?></span>
											<?=$ext?>, <?=$size?>
										</a>										
										<?endforeach;?>	

								</div>
								<?endif;?>	
								
								
								<?$APPLICATION->IncludeComponent("bitrix:menu", "left.menu", array(
											"ROOT_MENU_TYPE" => "left",
											"MENU_CACHE_TYPE" => "A",
											"MENU_CACHE_TIME" => "3600",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => "",
											"MAX_LEVEL" => "1",
											"CHILD_MENU_TYPE" => "left",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
										),
										false
								);?>		
								
								
<?
	if($arResult["PROPERTIES"]["ACC"]["VALUE"]) {
		$arrFilterD = array(
			"ID" => $arResult["PROPERTIES"]["ACC"]["VALUE"],
		);
	}
	else {
		$arrFilterD = array(
			"PROPERTY_BLOCK_ID" => $arResult["IBLOCK_ID"]
		);
	}
	
	
		$arFilterArt =  array("PROPERTY_BLOCK_ID" => $arResult["IBLOCK_ID"]);
		if($arResult["IBLOCK_SECTION_ID"]) $arFilterArt["PROPERTY_SECTION_ID"] = $arResult["IBLOCK_SECTION_ID"];	
				
?>			
								
			<?$APPLICATION->IncludeFile('/local/include_areas/block-sidebar_1.php',
									array(
										"ACC_FILTER" =>  $arrFilterD,
										"ART_FILTER" =>  $arFilterArt,
										"ACC_TITLE" => 'Monsher '.$arResult["PROPERTIES"]["MODEL"]["VALUE"]
									),
									array("mode"=>"php")
				);?>													
					
								
				<?$APPLICATION->IncludeFile('/local/include_areas/block-sidebar_2.php',
									array(),
									array("mode"=>"php")
				);?>	
								
							</div>
						</div>
						
						
						<?if(strip_tags($arResult["DETAIL_TEXT"])):?>
						<div class="description text-default" id="new-description">
							<h2 class="description__title">
								Описание модели Monsher <?=$arResult["PROPERTIES"]["MODEL"]["VALUE"]?>
							</h2>							
							<?=$arResult["DETAIL_TEXT"]?>							
						</div>
						<?endif;?>
						
<?
$arBlock_dop = CASDiblockTools::GetIBUF($arResult["IBLOCK_ID"]);
$arName = CStatic::getElement($arBlock_dop["UF_NAME_ID"], 20);
?>					
			
<?$APPLICATION->IncludeComponent("bitrix:news.list", "glossary.list.product.big", array(
        "BLOCK_TITLE" => strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]),
        "IBLOCK_TYPE" => "mn_content",
        "IBLOCK_ID" => "89",
        "NEWS_COUNT" => "99",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "NAME",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arFilterProp",
        "FIELD_CODE" => array(
            "ID",
            "NAME",
            "PREVIEW_TEXT",
            "PREVIEW_PICTURE",
        ),
        "PROPERTY_CODE" => array(
            "PROPS_ID"
        ),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_OPTION_ADDITIONAL" => ""
    ),
    false
);?>			
						
						
				
							
									
<?


global $arFilterT;
$arFilterT = array(
	"PROPERTY_TOV_ID" => $arResult["ID"]
);


$APPLICATION->IncludeComponent("bitrix:news.list", "reviews.list", Array(	
		"PRODUCT" => $arResult["ID"],	
		"IBLOCK_TYPE" => "backform",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => CStatic::$ReviewsIdBlock,	// Код информационного блока
		"NEWS_COUNT" => CStatic::$ReviewsLimit,	// Количество новостей на странице
		"SORT_BY1" => "DATE_ACTIVE_FROM",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
		"SORT_BY2" => "ID",	// Поле для второй сортировки новостей
		"SORT_ORDER2" => "DESC",	// Направление для второй сортировки новостей
		"FILTER_NAME" => "arFilterT",	// Фильтр
		"FIELD_CODE" => array(
            "ID",
            "NAME",                      
            "PREVIEW_TEXT",                      
            "DETAIL_TEXT",                      
            "ACTIVE_FROM",                      
        ),
        "PROPERTY_CODE" => array(
            "NAME",
            "PLUS",
            "MINUS",	            
            "TXT",	            
            "RATING",	            
        ),
		"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
		"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
		"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
		"SET_TITLE" => "N",	// Устанавливать заголовок страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
		"PARENT_SECTION" => "",	// ID раздела
		"PARENT_SECTION_CODE" => "",	// Код раздела
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"PAGER_TITLE" => "",	// Название категорий
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "Y",	// Показывать ссылку "Все"
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
	),
	false
);?>					
				
							
<?		
	global $arrFilter2;
	
	if($arResult["PROPERTIES"]["ANALOG"]["VALUE"]) {
		
		$arrFilter2 = array(			
			"ID" => $arResult["PROPERTIES"]["ANALOG"]["VALUE"],
		);
	}
	else {

		global $arrFilter2;
		$arrFilter2 = array(			
			"!ID" => $arResult["ID"],
			"PROPERTY_".$GLOBALS["K_EXIST_CODE"]."_VALUE" => 'В наличии',			
			"!CATALOG_PRICE_".CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]] => false,			
		);
		
		$arrFilter2_d = CStatic::GetFilterByRelate($arResult, $arResult["CATALOG_PRICE_".CStatic::$arPricesByCode[$GLOBALS["K_PRICE_CODE"]]]);			
		$arrFilter2 = array_merge($arrFilter2,$arrFilter2_d);
		
		//echo '555';
	}
	
	//PRE($_SESSION["CATALOG_COMPARE_LIST"]);
	
	
	//PRE($arrFilter2);
?>
<?if($arPropsInfo = CStatic::GetPropsByCompare($arResult["IBLOCK_ID"], $arResult["IBLOCK_SECTION_ID"] )):?>
<?
//pre($arPropsInfo);
?>


		
		
<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"tovs.list.compare.props",
		Array(			
			"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
			"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
			"PROPS_NAME" => $arPropsInfo["NAME"],
			"PROPS_CODE" => $arPropsInfo["CODE"],
			"PRODUCT_MODEL" => $arResult["PROPERTIES"]["MODEL"]["VALUE"],
			"PRODUCT" => $arResult,
			"BLOCK_TITLE" => 'Похожие модели',
			"EC_TYPE" => 'Similar',
			//"CATALOG_COMPARE_LIST" => $arParams["CATALOG_COMPARE_LIST"],
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BASKET_URL" => "/cart/",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "RAND",
			"ELEMENT_SORT_ORDER" => "",
			"ELEMENT_SORT_FIELD2" => "",
			"ELEMENT_SORT_ORDER2" => "",
			"FILTER_NAME" => "arrFilter2",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => $arResult["IBLOCK_ID"],
			"IBLOCK_TYPE" => "mn_catalog",
			"INCLUDE_SUBSECTIONS" => "Y",
			"LINE_ELEMENT_COUNT" => "3",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_COMPARE" => "Сравнить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_CART_PROPERTIES" => array(),
			"OFFERS_FIELD_CODE" => array(),
			"OFFERS_LIMIT" => "10",
			"OFFERS_PROPERTY_CODE" => array(),
			"OFFERS_SORT_FIELD" => "",
			"OFFERS_SORT_FIELD2" => "",
			"OFFERS_SORT_ORDER" => "",
			"OFFERS_SORT_ORDER2" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => 12,
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_DISPLAY_MODE" => "Y",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PROPERTY_CODE" => array("PHOTOS"),
			"SECTION_CODE" => "",
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => "",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_ALL_WO_SECTION" => "Y",
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"TEMPLATE_THEME" => "blue",
			"USE_PRICE_COUNT" => "N",
			"USE_PRODUCT_QUANTITY" => "N"
		),
		false
	);?>

<?endif;?>	
						
					
										
															
								
<?		
		global $arrFilterP;
		//$arrFilterP["ID"] = false;
		//if($arParams["CATALOG_VIEWS_LIST"])$arrFilterP["ID"] = $arParams["CATALOG_VIEWS_LIST"];
		 
		$arrFilterP["!ID"] = $arResult["ID"];

		//PRE($arrFilterP);
		
	$APPLICATION->IncludeComponent(
		"inter.olsc:catalog.section",
		"tovs.list.popular",
		Array(			
			"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
			"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
			"BLOCK_CLASS" => 'catalog',
			"BLOCK_TITLE" => 'Популярные товары',
			"EC_TYPE" => 'PopularList',
			"CATALOG_COMPARE_LIST" => $arParams["CATALOG_COMPARE_LIST"],
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BASKET_URL" => "/order/",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "propertysort_".$GLOBALS["K_EXIST_CODE"],
			"ELEMENT_SORT_ORDER" => "ASC",
			"ELEMENT_SORT_FIELD2" => "PROPERTY_WORDSTAT",
			"ELEMENT_SORT_ORDER2" => "DESC",
			"FILTER_NAME" => "arrFilterP",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => $arResult["IBLOCK_ID"],
			"IBLOCK_TYPE" => "mn_catalog",
			"INCLUDE_SUBSECTIONS" => "Y",
			"LINE_ELEMENT_COUNT" => "3",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_COMPARE" => "Сравнить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_CART_PROPERTIES" => array(),
			"OFFERS_FIELD_CODE" => array(),
			"OFFERS_LIMIT" => "10",
			"OFFERS_PROPERTY_CODE" => array(),
			"OFFERS_SORT_FIELD" => "",
			"OFFERS_SORT_FIELD2" => "",
			"OFFERS_SORT_ORDER" => "",
			"OFFERS_SORT_ORDER2" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => 4,
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_DISPLAY_MODE" => "Y",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PROPERTY_CODE" => array("PHOTOS"),
			"SECTION_CODE" => "",
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => "",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_ALL_WO_SECTION" => "Y",
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"TEMPLATE_THEME" => "blue",
			"USE_PRICE_COUNT" => "N",
			"USE_PRODUCT_QUANTITY" => "N"
		),
		false
	);?>								
						
						
						
						
						
													
								
<?			
		global $arrFilterD;
		$arrFilterD["ID"] = $arParams["CATALOG_VIEWS_LIST"];
		$arrFilterD["!ID"] = $arResult["ID"];

		//PRE($arrFilterD);
		
	$APPLICATION->IncludeComponent(
		"inter.olsc:catalog.section",
		"tovs.list.viewed",
		Array(
			"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
			"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
			"BLOCK_CLASS" => 'js-catalog_viewed',			
			"EC_TYPE" => 'ViewsList',
			"CATALOG_COMPARE_LIST" => $arParams["CATALOG_COMPARE_LIST"],
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BASKET_URL" => "/order/",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "RAND",
			"ELEMENT_SORT_ORDER" => "",
			"ELEMENT_SORT_FIELD2" => "",
			"ELEMENT_SORT_ORDER2" => "",
			"FILTER_NAME" => "arrFilterD",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => CStatic::$catalogIdBlock,
			"IBLOCK_TYPE" => "mn_catalog",
			"INCLUDE_SUBSECTIONS" => "Y",
			"LINE_ELEMENT_COUNT" => "3",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_COMPARE" => "Сравнить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_CART_PROPERTIES" => array(),
			"OFFERS_FIELD_CODE" => array(),
			"OFFERS_LIMIT" => "10",
			"OFFERS_PROPERTY_CODE" => array(),
			"OFFERS_SORT_FIELD" => "",
			"OFFERS_SORT_FIELD2" => "",
			"OFFERS_SORT_ORDER" => "",
			"OFFERS_SORT_ORDER2" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => 12,
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_DISPLAY_MODE" => "Y",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PROPERTY_CODE" => array("PHOTOS"),
			"SECTION_CODE" => "",
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => "",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_ALL_WO_SECTION" => "Y",
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"TEMPLATE_THEME" => "blue",
			"USE_PRICE_COUNT" => "N",
			"USE_PRODUCT_QUANTITY" => "N"
		),
		false
	);?>					
					

</div>
