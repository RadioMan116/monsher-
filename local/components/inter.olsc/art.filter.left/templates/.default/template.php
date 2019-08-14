<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}

?>

<form class="filter__form filter closed filter__article js-form" action="" method="post">
								<input type="hidden" name="action" value="changeTypeArt" />

								
								<div class="filter__item">
									<div class="filter__title">Тип</div>
									<div class="filter__checkbox">
										<label class="checkbox checkbox_oferta">
											<input name="ART_TYPE[]" value="Статья" class="checkbox__input js-art_type-change" type="checkbox" <?if(in_array('Статья',$_SESSION["ART_TYPE"])):?>checked="checked"<?endif;?>>
											<span class="checkbox__icon"></span>
											<span class="checkbox__text">Статьи</span>	
										</label>
										<label class="checkbox checkbox_oferta">
											<input name="ART_TYPE[]" value="Акция" class="checkbox__input js-art_type-change" type="checkbox" <?if(in_array('Акция',$_SESSION["ART_TYPE"])):?>checked="checked"<?endif;?>>
											<span class="checkbox__icon"></span>
											<span class="checkbox__text">Акции</span>											
										</label>
									</div>
								</div>							
								
							<?$APPLICATION->IncludeComponent(
								"bitrix:catalog.section.list",
								"art.menu.left",
								Array(
									"Alb_SECTIONS_CHAIN" => "Y",
									"CACHE_GROUPS" => "Y",
									"CACHE_TIME" => "36000000",
									"CACHE_TYPE" => "A",
									"COMPONENT_TEMPLATE" => "catalog.category.list",
									"COUNT_ELEMENTS" => "Y",
									"IBLOCK_ID" => 83,
									"IBLOCK_TYPE" => "mn_content",
									"SECTION_CODE_ACTIVE" => "",
									"SECTION_CODE" => "",
									"SECTION_FIELDS" => array("CODE","NAME",""),
									"SECTION_ID" => "",
									"SECTION_URL" => "",
									"SECTION_USER_FIELDS" => array("",""),
									"SHOW_PARENT_NAME" => "Y",
									"TOP_DEPTH" => "1",
									"VIEW_MODE" => "LINE"
								)
							);?>	
								
							</form>