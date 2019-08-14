<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}
if(!$_SESSION["F_SECTIONS"]) $_SESSION["F_SECTIONS"] = array();



//pre($arResult);

?>

<div class="js-favorite_filter">
<?if($arResult["SECTIONS"]):?>
<form class="filter__form js-form" action="" method="post" >
								<input type="hidden" name="action" value="changeSectionsFavorites" />
								<div class="filter__item">
									<div class="filter__title js-filter__title">Категории</div>
									<div class="filter__checkbox">
									
										<label>
											<input class="checkbox filter_checkbox pers_data_check js-art_type-change_all" type="checkbox" name="F_SECTIONS_ALL" value="Y" <?if(!$_SESSION["F_SECTIONS"]):?>checked="checked"<?endif;?> />
											<span class="checkbox-custom">Все</span>
										</label>
									
										
										<?foreach($arResult["SECTIONS"] as $arSection):?>
										<label>
											<input class="checkbox filter_checkbox pers_data_check js-art_type-change" type="checkbox" name="F_SECTIONS[]" value="<?=$arSection["ID"]?>" <?if(in_array($arSection["ID"], $_SESSION["F_SECTIONS"])):?>checked="checked"<?endif;?> />
											<span class="checkbox-custom"><?=$arSection["NAME"]?></span>
											<span class="checkbox-number"><?=$arSection["COUNT"]?></span>
										</label>
										<?endforeach;?>
										
										
									</div>
									<? /*<a href="" class="catalog__simile">Показать еще</a>*/ ?>
								</div>
							</form>
<?endif;?>
</div>