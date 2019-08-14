<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$count_all = $arResult["NAV_RESULT"]->NavRecordCount;


$arData =  $arResult["DATA"];


$rating_all = 0;
$rating_all = $arData["RATING_INFO"]["RATE"];
$rating_count = $arData["RATING_INFO"]["COUNT"];
$arRateStars = $arData["RATING_INFO"]["LIST"];

$url_all = '/reviews-store/';

?>


<?if($arResult["ITEMS"]):?>




<div class="reviews reviews-main js-reviews-main" id="microdata-shop-reviews">

							<div itemscope="" itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating">
								<meta itemprop="ratingValue" content="<?=$rating_all?>" />
								<meta itemprop="bestRating" content="5" />
								<meta itemprop="reviewCount" content="<?=$arResult["NAV_RESULT"]->NavRecordCount?>" />
							</div>



					<div class="container">
						<div class="reviews-main__title">
							Отзывы об интернет-магазине L-RUS
						</div>
						<div class="reviews__section">
							<div class="ratings">
								<?
										$k = 0;
										while($k++ < 5):?>
											<div class="ratings__star <?if($k > $rating_all):?>ratings__none<?endif;?>"></div>
								<?
								endwhile;?>
								
								<b class="ratings__number"><?=$rating_all?></b>
							</div>
							<a href="<?=$url_all?>" class="reviews__all"><?=$rating_count?> <?=declOfNum($rating_count, array('отзыв', 'отзыва', 'отзывов'))?></a>
						</div>
						<div class="swiper-pagination-reviews-main"></div>
						<div class="swiper-button-prev reviews-main__prev"></div>
						<div class="swiper-button-next reviews-main__next"></div>

						<div class="swiper-container">
							<div class="swiper-wrapper">
								
								
<?foreach($arResult["ITEMS"] as $i => $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		
		//pre($arItem["DISPLAY_PROPERTIES"]);
		
		$arDate = explode('.',$arItem["DATE_ACTIVE_FROM"]);
		
		$date_time = $arDate[2].'-'.$arDate[1].'-'.$arDate[0];
		
		
		$arProduct = $arItem["PRODUCT"];
		
		?>							
								
								<div class="swiper-slide reviews-main__item" itemscope="" itemprop="review" itemtype="https://schema.org/Review">
									<div class="reviews-list__field reviews-list__field_header" id="<? echo $this->GetEditAreaId($arItem['ID']); ?>">
										<div class="reviews-list__col" itemprop="author" itemscope itemtype="http://schema.org/Person" >
											<strong itemprop="name"><?=$arItem["DISPLAY_PROPERTIES"]["NAME"]["DISPLAY_VALUE"]?></strong>
										</div>
										<div class="reviews-list__col reviews-list__col_desc">
											<div class="ratings ratings_small">											
												<?
												$k = 0;
												while($k++ < 5):?>
													<div class="ratings__star <?if($k > $arItem["PROPERTIES"]["RATING"]["VALUE"]):?>ratings__none<?endif;?>"></div>
												<?
												endwhile;?>												
											</div>
										</div>
										<div class="reviews-list__col reviews-list__col_date">
											<meta itemprop="datePublished" content="<?=$date_time?>" />
											<?=$arItem["DISPLAY_ACTIVE_FROM"]?>
										</div>
									</div>
									<div class="reviews-list__field reviews-list__field_footer" itemprop="reviewBody"><?=$arItem["PREVIEW_TEXT"]?></div>
									
									
									<a href="<?=$url_all?>" itemprop="url" class="hide" ></a>
									
									<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
											 <meta itemprop="worstRating" content="1"/>
											 <meta itemprop="bestRating" content="5"/>
											 <meta itemprop="ratingValue" content="<?=$arItem["PROPERTIES"]["RATING"]["VALUE"]?>"/>
									</span>
									
									
									
									
									
									
								</div>
								
<?endforeach;?>								
								
							</div>
						</div>
						<a href="<?=$url_all?>" title="Посмотреть все отзывы" class="reviews-main__link">Посмотреть все отзывы</a>
					</div>

				</div>










<?endif;?>

