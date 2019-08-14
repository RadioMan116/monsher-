<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$arData =  $arResult["DATA"];

$count_all = $arResult["NAV_RESULT"]->NavRecordCount;

$rating_all = 0;
$rating_all = $arData["RATING_INFO"]["RATE"];



?>
<?if($arResult["ITEMS"] || $arData["PRODUCT"]):?>
	<div class="reviews" id="reviews">						
<?



								if($arData["BLOCK"]){
									
									$title_all = 'Отзывы на '.mb_lcfirst($arData["BLOCK"]["NAME"]).' Либхер';
									$url_all = '/reviews/category-'.$arData["BLOCK"]["CODE"].'/';
									
									
									if($arData["SECTION"]) {										
										$title_all = 'Отзывы на '.mb_lcfirst($arData["SECTION"]["NAME"]).' Либхер';
										$url_all = $url_all.'razdel-'.$arData["SECTION"]["CODE"].'/';
									}
								}	
								elseif($arData["PRODUCT"]) {
										$title_all = 'Отзывы о Либхер '.$arData["PRODUCT"]["PROPERTIES"]["MODEL"]["VALUE"];
										$url_all = '/reviews/product-'.$arData["PRODUCT"]["CODE"].'/';
								}
										
									
?>									
						
							<div class="reviews__title"><?=$title_all?></div>
							<div class="reviews__top">
							
								<?if($arData["PRODUCT"]):?>		
								
									<a href="" class="reviews__link js-viewForm" data-action="reviewsAdd" data-id="<?=$arData["PRODUCT"]["ID"]?>">оставить отзыв</a>
									
									<?/*$APPLICATION->IncludeFile(
										 '/local/include_areas/micro_product.php',
										 Array("PRODUCT" => $arData["PRODUCT"]),
										 Array("MODE"=>"php")
									 )*/?>

								<?endif;?>
							
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
									<?if($arResult["NAV_RESULT"]->NavRecordCount > CStatic::$ReviewsLimit):?>
										<a href="<?=$url_all?>" class="reviews__all" target="_blank"><?=$arResult["NAV_RESULT"]->NavRecordCount?> <?=declOfNum($count_all, array('отзыв', 'отзыва', 'отзывов'))?></a>
									<?endif;?>
								</div>
								
								
								
								
								<?if($arResult["NAV_RESULT"]->NavRecordCount > CStatic::$ReviewsLimit):?>
									<a href="<?=$url_all?>" class="<? echo ($arData["PRODUCT"]) ? 'reviews__main-link':'reviews__link'?>">Посмотреть все отзывы</a>
								<?endif;?>
								
								
							</div>
						
						
						
	<?if(!$arData["PRODUCT"]):?>
								
									<div itemscope itemtype="http://schema.org/Product">				
										<meta itemprop="name" content="<?=$arData["SEOXML_TITLE"]?> Liebherr" />
										<meta itemprop="description" content="<?=$arData["SEOXML_TITLE"]?> Liebherr" />
										<meta itemprop="brand" content="Liebherr" />
										
										<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">							 
											<meta itemprop="ratingValue" content="<?=$rating_all?>" />
											<meta itemprop="reviewCount" content="<?=$count_all?>" />								
										</div>	

										<div itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
											<meta itemprop="lowPrice" content="<?=$arData["MIN_PRICE"]?>" />		
											<meta itemprop="highPrice" content="<?=$arData["MAX_PRICE"]?>" />		
											<meta itemprop="priceCurrency" content="RUB" />		
											<meta itemprop="offerCount" content="<?=$count_all?>" />	
										</div>																
								
								<?endif;?>						
							

	<?if($arResult["ITEMS"]):?>	
	
	
	
	
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
									
									<?if(!$arData["PRODUCT"]):?>
									<?
									//$url_prod = '/reviews/product-'.$arItem["PRODUCT"]["CODE"].'/';									
									?>
									<div class="reviews-list__field">
										<div class="reviews-list__col">Модель:</div>
										<div class="reviews-list__col reviews-list__col_desc">
											<a href="<?=$arItem["PRODUCT"]["DETAIL_PAGE_URL"]?>" title="<?=$arItem["PRODUCT"]["NAME"]?>" target="_blank" class="reviews-list__model"><?=$arItem["PRODUCT"]["NAME"]?></a>
										</div>
									</div>
									<?endif;?>
									
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
										<div class="reviews-list__col reviews-list__col_desc" ><?=$arItem["DISPLAY_PROPERTIES"]["MINUS"]["DISPLAY_VALUE"]?></div>
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
	<?endif;?>	



	<?if(!$arData["PRODUCT"]):?>
		</div>
	<?endif;?>





	
</div>			
<?endif;?>