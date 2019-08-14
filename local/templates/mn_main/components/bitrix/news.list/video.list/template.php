<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$PAGE_URL = $arResult["LIST_PAGE_URL"];
$this->setFrameMode(true);

// номер текущей страницы
$curPage = $arResult["NAV_RESULT"]->NavPageNomer;
// всего страниц - номер последней страницы
$totalPages = $arResult["NAV_RESULT"]->NavPageCount;
// номер постраничной навигации на странице
$navNum = $arResult["NAV_RESULT"]->NavNum;

?>
						

<?if(count($arResult["ITEMS"]) > 0):?>

	<?if($arParams["AJAX"] != "Y"):?> 
	<div class="js-news">
	<div class="all-video js-news__inner">
	<?endif;?>

<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));	
	//pre($arItem["PROPERTIES"]["CODE"]);
	
	$tar =  parse_url($arItem["PROPERTIES"]["CODE"]["VALUE"]);	
	$arr = explode('/',$tar["path"]);	
	$video_code = '//www.youtube.com/watch?v='.end($arr);
	$video_code2 = '//www.youtube.com/embed/'.end($arr);
	?>
	
	<div class="all-video__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<a data-fancybox="gallery" data-type="iframe" href="<?=$video_code2?>">
										<? /*<img data-src="images/product-card_2.jpg" data-srcset="images/product-card_2@2x.jpg 2x,images/product-card_2@3x.jpg 3x" class="special-offers__img catalog__img lazyload">*/ ?>
										<iframe class="lazyload" width="100%" height="100%" data-src="<?=$video_code2?>" frameborder="0" allowfullscreen=""></iframe>
										<div class="all-video__link"> <?=$arItem["NAME"]?></div>
									</a>
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
	 
		<?=$arResult["NAV_STRING"]?>	
	</div>
	  
	<?endif;?>	
<?endif;?>	








