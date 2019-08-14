<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$count_all = $arResult["NAV_RESULT"]->NavRecordCount;

$rating_all = 0;
$rating_all = $arResult["RATING_INFO"]["RATE"];



//pre($arResult["ITEMS"]);
?>			
							
		
	<div class="reviews_article">
						<div class="reviews__top">
							<div class="reviews__section">
								<span class="reviews__all">Комментарии <?=$count_all?></span>
							</div>
							<a href="#comment_form" class="reviews__link">оставить комментарий</a>
						</div>	
		
		
							
		<?if($arResult["ITEMS"]):?>						
							
							<div class="reviews-list">

		<?foreach($arResult["ITEMS"] as $i => $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		
		//pre($arItem["DISPLAY_PROPERTIES"]);
		
		$arDate = explode('.',$arItem["DATE_ACTIVE_FROM"]);
		
		$date_time = $arDate[2].'-'.$arDate[1].'-'.$arDate[0];
		
		?>	
		
		<div class="reviews-list__item" id="<? echo $this->GetEditAreaId($arItem['ID']); ?>">
								<div class="reviews-list__field reviews-list__field_header">
									<div class="reviews-list__col">
										<strong ><?=$arItem["PROPERTIES"]["NAME"]["VALUE"]?></strong>
									</div>
									<div class="reviews-list__col reviews-list__col_date">
										<?=$arItem["DISPLAY_ACTIVE_FROM"]?>
									</div>
								</div>
								<div class="reviews-list__field">
									<div class="reviews-list__col reviews-list__col_desc">
										<?=$arItem["PREVIEW_TEXT"]?>
									</div>
								</div>
		</div>
		
						
		<?endforeach;?>	
						
		</div>
		
		<?endif;?>
		
				<?$APPLICATION->IncludeFile(
					'/local/include_areas/comments_form.php',
					Array(
						"ART_ID" => $arParams["ART_ID"],	
					),
					Array("MODE"=>"php")
				);?>
		
	</div>	
		
		
		
