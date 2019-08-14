<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$count_all = $arResult["NAV_RESULT"]->NavRecordCount;

$arData =  $arResult["DATA"];


$rating_all = 0;
$rating_all = $arData["RATING_INFO"]["RATE"];
$rating_count = $arData["RATING_INFO"]["COUNT"];
$arRateStars = $arData["RATING_INFO"]["LIST"];


// номер текущей страницы
$curPage = $arResult["NAV_RESULT"]->NavPageNomer;
// всего страниц - номер последней страницы
$totalPages = $arResult["NAV_RESULT"]->NavPageCount;
// номер постраничной навигации на странице
$navNum = $arResult["NAV_RESULT"]->NavNum;

//pre($arResult);


//pre( $arResult["RATING_INFO"]);
$BACK_URL = '/';
$url_all = '/reviews-store/'
//PRE($arData["BLOCK"]);

?>


<? /*

		<div itemscope itemtype="http://schema.org/Product">				
			<meta itemprop="name" content="<?=$arData["SEOXML_TITLE"]?> Liebherr" />
			<meta itemprop="brand" content="Liebherr" />
			
			<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">							 
				<meta itemprop="ratingValue" content="<?=$rating_all?>" />
				<meta itemprop="reviewCount" content="<?=$arResult["NAV_RESULT"]->NavRecordCount?>" />								
			</div>			
		</div>
*/ ?>



<?if($arParams["AJAX"] != "Y"):?>

<div class="all-reviews" id="microdata-shop-reviews">


	<div itemscope="" itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating">
								<meta itemprop="ratingValue" content="<?=$rating_all?>" />
								<meta itemprop="bestRating" content="5" />
								<meta itemprop="reviewCount" content="<?=$arResult["NAV_RESULT"]->NavRecordCount?>" />
							</div>


	<div class="reviews">
	
		<div class="reviews__section">
									<div class="ratings">
										<b class="ratings__title">Средняя оценка:</b>
										
										<?
											$k = 0;
											while($k++ < 5):?>
												<div class="ratings__star <?if($k > $rating_all):?>ratings__none<?endif;?>"></div>
										<?endwhile;?>
										
										<b class="ratings__number"><?=$rating_all?></b>
										<span class="reviews__all"><?=$rating_count?> <?=declOfNum($rating_count, array('отзыв', 'отзыва', 'отзывов'))?></span>
									</div>
									<div class="reviews-all__header">
										<span class="reviews-all__text">Отзывы с оценкой:</span>
										
										<a href="<?=$url_all?>" title="Любой" class="reviews-all__link">Любой</a>
										<a href="<?=$url_all?>?TYPE=PLUS" title="Только положительные" class="reviews-all__link <?if($arParams["TYPE"] == 'PLUS'):?>active<?endif;?>">Положительные</a>
										<a href="<?=$url_all?>?TYPE=MINUS" title="Только отрицательные" class="reviews-all__link <?if($arParams["TYPE"] == 'MINUS'):?>active<?endif;?>">Отрицательные</a>										
										
									</div>									
									
									<?
										$k = 1;
										$proc_end = 100;
										foreach($arRateStars as $star=>$count):?>
										<?						
										
										$proc = (int)(($count/$rating_count)*100);
										if($k == count($arRateStars) && $proc_end!=$proc) $proc = $proc_end;										
										$proc_end = $proc_end - $proc;
									
										?>		
												<div class="ratings">
													<?
													$k = 0;
													while($k++ < 5):?>
														<div class="ratings__star <?if($k > $star):?>ratings__none<?endif;?>"></div>
													<?
													endwhile;?>													
												</div>
												<a href="<?=$arParams["PAGE"]?>?STARS=<?=$star?>" class="reviews__all"><?=$count?> <?=declOfNum($count, array('отзыв', 'отзыва', 'отзывов'))?></a>	
										<?
										$k++;
										endforeach;?>
									
		</div>
		<a href="" class="reviews__link js-viewForm" data-action="reviewsAdd" title="оставить отзыв" >оставить отзыв</a>
	
		<div class="js-news" >
			<div class="reviews__items js-news__inner">
	<?endif;?>
	
	<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	
	//pre($arItem["DISPLAY_PROPERTIES"]);
	
	$arDate = explode('.',$arItem["DATE_ACTIVE_FROM"]);
	
	$date_time = $arDate[2].'-'.$arDate[1].'-'.$arDate[0];	
	
	?>									
		<div class="reviews__item" itemprop="review" itemscope itemtype="https://schema.org/Review" id="<? echo $this->GetEditAreaId($arItem['ID']); ?>">
									<div class="reviews-list__field reviews-list__field_header">
										<div class="reviews-list__col" itemprop="author" itemscope itemtype="http://schema.org/Person">
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
										<div class="reviews-list__col reviews-list__col_date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
									</div>
									<div class="reviews-list__field reviews-list__field_footer" itemprop="reviewBody"><?=$arItem["PREVIEW_TEXT"]?></div>
									
									<meta itemprop="datePublished" content="<?=$date_time?>" />
									<a href="<?=$url_all?>" itemprop="url" class="hide" ></a>
								
										<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
												 <meta itemprop="worstRating" content="1"/>
												 <meta itemprop="bestRating" content="5"/>
												 <meta itemprop="ratingValue" content="<?=$arItem["PROPERTIES"]["RATING"]["VALUE"]?>"/>
										</span>
										
		</div>		
										
										
<?endforeach;?>
	<?if($arParams["AJAX"] != "Y"):?>
	
			</div>
		</div>
		
		<div class="all-video__bottom">
			<?if($totalPages > 1 && $curPage < $totalPages):?>	
						
						
						<a href="#" id="load-items" class="load-more">Загрузить еще</a>
						<script>
						$(function(){
							var newsSetLoader = new newsLoader({
								root: '.js-news',
								newsBlock: '.js-news__inner',
								newsLoader: '#load-items',
								ajaxLoader: '#ajax-loader img',
								loadSett:{
									endPage: <?=$totalPages?>,
									navNum: <?=$navNum?>,
									curPage: <?=$curPage?>
								}	
							});
							newsSetLoader.init();
						});
						</script>
						
			<?endif;?>

			<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
				<?=$arResult["NAV_STRING"]?>
			<?endif?>
		</div> 
		
		
		
	</div>
</div>

	<?endif?>
	











