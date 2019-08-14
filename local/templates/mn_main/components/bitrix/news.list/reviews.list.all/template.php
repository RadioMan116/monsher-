<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$count_all = $arResult["NAV_RESULT"]->NavRecordCount;

$arData =  $arResult["DATA"];


$rating_all = 0;
$rating_all = $arData["RATING_INFO"]["RATE"];
$rating_count = $arData["RATING_INFO"]["COUNT"];
$arRateStars = $arData["RATING_INFO"]["LIST"];


//pre($arResult);


//pre( $arResult["RATING_INFO"]);
$BACK_URL = '/';

//PRE($arData["BLOCK"]);

?>

<?if($arResult["ITEMS"]):?>



<?if($arData["PRODUCT"]):?>
<?
$BACK_URL = $arData["PRODUCT"]["DETAIL_PAGE_URL"];
?>
<div itemscope itemtype="http://schema.org/Product">
<?$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_product-detail.php',
			 Array("PRODUCT" => $arData["PRODUCT"]),
			 Array("MODE"=>"php")
		 )?>


<?else:?>
<?
if($arData["BLOCK"]) $BACK_URL =  '/liebherr/'.$arData["BLOCK"]["CODE"].'/';
if($arData["SECTION"]) $BACK_URL =  $arData["SECTION"]["SECTION_PAGE_URL"];
?>

		<?
		if(!$arData["SEOXML_TITLE"]) $arData["SEOXML_TITLE"] = 'Техника';
		?>
		<div itemscope itemtype="http://schema.org/Product">				
			<meta itemprop="name" content="<?=$arData["SEOXML_TITLE"]?> Liebherr" />
			<meta itemprop="brand" content="Liebherr" />
			
			<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">							 
				<meta itemprop="ratingValue" content="<?=$rating_all?>" />
				<meta itemprop="reviewCount" content="<?=$arResult["NAV_RESULT"]->NavRecordCount?>" />								
			</div>

<?endif;?>









<div class="all-reviews">
									<div class="reviews">
									
									
									<div class="b-crumbs">
										<a href="<?=$BACK_URL?>" title="Назад в каталог">Назад в каталог</a>
									</div>
									
									
										<div class="reviews__top all-reviews__top all-reviews__top-first">
											<div class="reviews__section">
												<span class="all-reviews__text">Средняя оценка</span>
												<div class="ratings">
													<?
													$k = 0;
													while($k++ < 5):?>
														<div class="ratings__star <?if($k > $rating_all):?>ratings__none<?endif;?>"></div>
													<?
													endwhile;?>												
												</div>
											</div>
											<div class="reviews__section">
												<span class="all-reviews__text">Отзывы с оценкой</span>
												<div class="all-reviews__tag">
													<a href="<?=$arParams["PAGE"]?>" class="all-reviews__link">Любой</a>
													<a href="<?=$arParams["PAGE"]?>?TYPE=PLUS" class="all-reviews__link">Только положительные</a>
													<a href="<?=$arParams["PAGE"]?>?TYPE=MINUS" class="all-reviews__link">Только отрицательные</a>
												</div>
											</div>
											<div class="all-reviews__border"></div>
										</div>
										<div class="reviews__top all-reviews__top all-reviews__top-last">
										
										
										<?
										$k = 1;
										$proc_end = 100;
										foreach($arRateStars as $star=>$count):?>
										<?						
										
										$proc = (int)(($count/$rating_count)*100);
										IF($k == count($arRateStars) && $proc_end!=$proc) $proc = $proc_end;										
										$proc_end = $proc_end - $proc;
									
										?>
										
											<div class="reviews__section">
												<div class="ratings">
													<?
													$k = 0;
													while($k++ < 5):?>
														<div class="ratings__star <?if($k > $star):?>ratings__none<?endif;?>"></div>
													<?
													endwhile;?>													
												</div>
												<span class="all-reviews__text">
													<?=$proc?>%
													<a href="<?=$arParams["PAGE"]?>?STARS=<?=$star?>" class="filter__link"><?=$count?> <?=declOfNum($count, array('отзыв', 'отзыва', 'отзывов'))?></a>
												</span>
											</div>
										
										<?
										$k++;
										endforeach;?>
								
										</div>
										<div class="reviews-list">



										
	<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	
	//pre($arItem["DISPLAY_PROPERTIES"]);
	
	$arDate = explode('.',$arItem["DATE_ACTIVE_FROM"]);
	
	$date_time = $arDate[2].'-'.$arDate[1].'-'.$arDate[0];
	
	if(!$arItem["DISPLAY_PROPERTIES"]["PLUS"]["DISPLAY_VALUE"]) $arItem["DISPLAY_PROPERTIES"]["PLUS"]["DISPLAY_VALUE"] = '-';
	if(!$arItem["DISPLAY_PROPERTIES"]["MINUS"]["DISPLAY_VALUE"]) $arItem["DISPLAY_PROPERTIES"]["MINUS"]["DISPLAY_VALUE"] = '-';
	
	?>								
										
		<div class="reviews-list__item" itemprop="review" itemscope itemtype="https://schema.org/Review" id="<? echo $this->GetEditAreaId($arItem['ID']); ?>">
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
									<div class="reviews-list__field">
										<div class="reviews-list__col">Модель:</div>
										<div class="reviews-list__col reviews-list__col_desc">
											<a href="<?=$arItem["PRODUCT"]["DETAIL_PAGE_URL"]?>" title="<?=$arItem["PRODUCT"]["NAME"]?>" target="_blank" class="reviews-list__model"><?=$arItem["PRODUCT"]["NAME"]?></a>
										</div>
									</div>
									<!-- /.reviews-list__field -->
									<div class="reviews-list__field">
										<div class="reviews-list__col">
											Достоинства:
										</div>
										<div class="reviews-list__col reviews-list__col_desc"><?=$arItem["DISPLAY_PROPERTIES"]["PLUS"]["DISPLAY_VALUE"]?></div>
									</div>
									<!-- /.reviews-list__field -->
									<div class="reviews-list__field">
										<div class="reviews-list__col">
											Недостатки:
										</div>
										<div class="reviews-list__col reviews-list__col_desc"><?=$arItem["DISPLAY_PROPERTIES"]["MINUS"]["DISPLAY_VALUE"]?></div>
									</div>
									<!-- /.reviews-list__field -->
									<div class="reviews-list__field">
										<div class="reviews-list__col">
											Комментарий:
										</div>
										<div class="reviews-list__col reviews-list__col_desc" itemprop="reviewBody"><?=$arItem["PREVIEW_TEXT"]?></div>
									</div>
									
									<meta itemprop="datePublished" content="<?=$date_time?>" />
									<a href="<?=$url_all?>" itemprop="url" class="hide" ></a>
								
										<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
												 <meta itemprop="worstRating" content="1"/>
												 <meta itemprop="bestRating" content="5"/>
												 <meta itemprop="ratingValue" content="<?=$arItem["PROPERTIES"]["RATING"]["VALUE"]?>"/>
										</span>
										
										<div itemprop="itemReviewed" itemscope itemtype="http://schema.org/Product">
										
												<meta itemprop="name" content="<?=$arItem["PRODUCT"]["NAME"]?>"/>							
												<a href="https://<?=$_SERVER["SERVER_NAME"]?><?=$arItem["PRODUCT"]["DETAIL_PAGE_URL"]?>" itemprop="url" class="hide" ></a>									
												<meta itemprop="image" content="https://<?=$_SERVER["SERVER_NAME"]?><?=$arItem["PRODUCT"]["PICTURE"]?>" />
												
										</div>
									
		</div>		
										
										
<?endforeach;?>										
								


										</div>
			</div>
</div>


</div>



<?=$arResult["NAV_STRING"]?>

<?endif;?>