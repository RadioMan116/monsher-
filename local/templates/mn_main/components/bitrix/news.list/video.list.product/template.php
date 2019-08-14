<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//pre($arResult["ITEMS"]);
?>
<?if(count($arResult["ITEMS"]) > 0):?>



<div class="content-fluid">

                    <div class="gallery-horiz gallery-horiz_card">
                        <div class="gallery-horiz__inner">

                            <div class="detail-vid">

                                <div class="detail-desc__title">
                                    Видео
                                </div>

                            </div>
							<div class="gallery-touch gallery-touch_video">
                                <div class="gallery-touch__inner">
                                    <div class="gallery-touch__list js-gallery-touch__list">

<?foreach($arResult["ITEMS"] as $i => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	//pre($arItem["PROPERTIES"]["CODE"]);

	$tar =  parse_url($arItem["PROPERTIES"]["CODE"]["VALUE"]);
	$arr = explode('/',$tar["path"]);
	$video_code = '//www.youtube.com/watch?v='.end($arr);
	?>
	
	 <div class="gallery-touch__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                                            <!-- begin video-thumb -->
                                            <div class="video-thumb ">
                                                <div class="video-thumb__inner">
                                                    <a href="<?=$video_code?>" title="<?=$arItem["NAME"]?>" data-fancybox="group1" class="video-thumb__link">

                                                        <div class="video-thumb__photo">
                                                           <img data-src="<?=$arItem["PICTURE"]?>" alt="<?=$arItem["NAME"]?>" class="video-thumb__pic lazyload" />
                                                        </div>
                                                        <div class="video-thumb__content">
                                                            <div class="video-thumb__name">
                                                               <?=$arItem["NAME"]?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- end video-thumb -->

                                        </div>
	
<?endforeach;?>

                                </div>
                            </div>

                        </div>

				</div>
			</div>
</div>

<?endif;?>








