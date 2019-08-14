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
CUtil::InitJSCore(Array());
$this->setFrameMode(true);

global $USER;

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/colors.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

/* ############################################################# */
$arPropsNoView = array();
 
$arFilter = array(
	"IBLOCK_ID" => 60,
	"ACTIVE" => "Y",
	"PROPERTY_BLOCK_ID" => $arParams["IBLOCK_ID"],
	"!PROPERTY_PROPS_ID" => false
);

if($arData = CStatic::GetElementList($arFilter, false, array("ID" => "ASC"), true)) {
	$arData = reset($arData);	
	$arPropsNoView = $arData["PROPERTIES"]["PROPS_ID"]["VALUE"];
}

/*
if($USER->IsAdmin()) {
	echo '<br/>ДО:';
	$arKeys = array_keys($arResult["ITEMS"]);
	pre($arKeys);
	
	pre($arPropsNoView);
}
*/

$groupID = 6;
if (!(in_array($groupID,$USER->GetUserGroupArray()) || $USER->IsAdmin())) {
	
	//echo '<br/>Обрезаем';	
	// то неотображаем все
	if($arPropsNoView) {
		foreach($arPropsNoView as $id) {
			unset($arResult["ITEMS"][$id]);
		}
	}
}
/*
if($USER->IsAdmin()) {	
	echo '<br/>После:';
	$arKeys = array_keys($arResult["ITEMS"]);
	pre($arKeys);
}
*/
/* ############################################################# */


?>
<?if(count($arResult["ITEMS"]) > 0):?>

		<?/*$this->SetViewTarget('view_filter');?>
			<a class="filter_popup tablet mobile" href="#">Фильтр</a>
		<?$this->EndViewTarget();*/?>

	<div class="filter__form closed" id="products-filter">
		<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="js-filter_form  bx_filter smartfilter">
			
			
			<?foreach($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />			
			<?endforeach;?>
			
			
			<?
			//prices
			
			$watch = false;
			
			
			foreach($arResult["ITEMS"] as $key=>$arItem)
			{
				$key = $arItem["ENCODED_ID"];
				if(isset($arItem["PRICE"])):
					if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
						continue;
						
					$arItem["VALUES"]["MIN"]["VALUE"] = (int)$arItem["VALUES"]["MIN"]["VALUE"];
					$arItem["VALUES"]["MAX"]["VALUE"] = (int)$arItem["VALUES"]["MAX"]["VALUE"];
					if($arItem["VALUES"]["MIN"]["HTML_VALUE"] == '') $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["VALUE"];
					if($arItem["VALUES"]["MAX"]["HTML_VALUE"] == '') $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["VALUE"];	
					
					
					$watch = true;
					//if($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["FILTERED_VALUE"];
					//if($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["FILTERED_VALUE"];
					?>
					
						<div class="filter__item filter__field bx_filter_parameters_box active">
							<span class="bx_filter_container_modef"></span>
							<div class="filter__title" onclick="smartFilter.hideFilterProps(this)">Цена, руб</div>
							
							<div class="bx_filter_block">
								<div class="bx_filter_parameters_box_container">
							
									<div class="filter__container filter-slider-container">
											<div class="filter__price filter-price">
												<span>
													от
													<input
														class="input_min"
														type="text"
														name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
														id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
														value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
														size="5"
														onkeyup="smartFilter.keyup(this)"
													/>												
												</span>
												<span>
													до
													<input
														class="input_max"
														type="text"
														name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
														id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
														value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
														size="5"
														onkeyup="smartFilter.keyup(this)"
													/>
												</span>
											</div>
											<div id="slider-range" class="filter__slider slider__filter_range filter-slider slider-filter_range slider_filter_range ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"  
												min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" 
												max="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>" 									
												minval="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>" 
												maxval="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>" 
												aria-disabled="false"
											>
												<div class="ui-slider-range ui-widget-header ui-corner-all"></div>
												<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left:0"></a>
												<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" ></a>
											</div>
									</div>							
								</div>					
							</div>					
						</div>					
					
				<?endif;
			}?>
			
			
			
			<?if(!in_array($arParams["IBLOCK_ID"], array(38,39))):?>			
				<?
				$URL_TYPE = '/liebherr/'.$arParams["BLOCK_CODE"].'/';
				if($arParams["SECTION_CODE"]) $URL_TYPE.= $arParams["SECTION_CODE"].'/';
				?>
				<div class="filter__item">
								<div class="filter__title">Подборки</div>
								<div class="filter__checkbox">									
									<a href="<?=$URL_TYPE?>type-nov/" class="filter-collections <?if($arParams["TYPE_CODE"] == 'nov'):?>active<?endif;?>">Новинки</a>
									<a href="<?=$URL_TYPE?>type-hit/" class="filter-collections <?if($arParams["TYPE_CODE"] == 'hit'):?>active<?endif;?>">Лучшие</a>
									<a href="<?=$URL_TYPE?>type-akcii/" class="filter-collections <?if($arParams["TYPE_CODE"] == 'akcii'):?>active<?endif;?>">Акции</a>
								</div>
				</div>
			
			<?endif;?>
			
			<?

			//not prices
			$k = 1;
			foreach($arResult["ITEMS"] as $key=>$arItem)
			{
				
				if(
					empty($arItem["VALUES"])
					|| isset($arItem["PRICE"])
				)
					continue;

				if (
					$arItem["DISPLAY_TYPE"] == "A"
					&& (
						$arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
					)
				)
					continue;
					
					
					
					
					
					$watch = true;
					//pre($arItem);
				?>
				
				
				
				
				<div class="filter__item bx_filter_parameters_box active">
					<span class="bx_filter_container_modef"></span>
					<div class="filter__title" onclick="smartFilter.hideFilterProps(this)">
					<?=$arItem["NAME"]?>
					<?if(strip_tags($arItem["FILTER_HINT"])):?>
						<span class="filter-tip" title="<?=$arItem["FILTER_HINT"]?>"></span>
					<?endif;?>
					</div>
					<div class="bx_filter_block">
						<div class="bx_filter_parameters_box_container">
						<?
						$arCur = current($arItem["VALUES"]);
						switch ($arItem["DISPLAY_TYPE"])
						{							
							case "A"://NUMBERS_WITH_SLIDER
							
							
							
							$arItem["VALUES"]["MIN"]["VALUE"] = (int)$arItem["VALUES"]["MIN"]["VALUE"];
							$arItem["VALUES"]["MAX"]["VALUE"] = (int)$arItem["VALUES"]["MAX"]["VALUE"];
							if($arItem["VALUES"]["MIN"]["HTML_VALUE"] == '') $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["VALUE"];
							if($arItem["VALUES"]["MAX"]["HTML_VALUE"] == '') $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["VALUE"];	
							
							if($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["FILTERED_VALUE"];
							if($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["FILTERED_VALUE"];
							
							
							
							
								?>
								
								<div class="filter__container filter-slider-container">
									<div class="filter__price filter-price">
										<span>
											от
											<input
												class="input_min"
												type="text"
												name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
												size="5"
												onkeyup="smartFilter.keyup(this)"
											/>											
										</span>
										<span>
											до
											<input
												class="input_max"
												type="text"
												name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
												size="5"
												onkeyup="smartFilter.keyup(this)"
											/>
										</span>
									</div>
									<div id="slider-range" class="filter__slider slider__filter_range filter-slider slider-filter_range slider_filter_range ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" 
									min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" 
									max="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>" 									
									minval="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>" 
									maxval="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>" 
									aria-disabled="false">
										<div class="ui-slider-range ui-widget-header ui-corner-all">
										</div>
										<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left:0"></a>
										<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="margin-right: 15px;"></a>
									</div>
								</div>								
								
								<?
								break;
							case "B"://NUMBERS
								?>
								
								<div class="filter__container filter-slider-container" itemkey="316177">
									<div class="filter__price filter-price">
										<span>
											от
											<input
												class="input_min"
												type="text"
												name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
												size="5"
												onkeyup="smartFilter.keyup(this)"
											/>											
										</span>
										<span>
											до
											<input
												class="input_max"
												type="text"
												name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
												size="5"
												onkeyup="smartFilter.keyup(this)"
											/>
										</span>
									</div>
								</div>
								
								<?
								break;
							case "G"://CHECKBOXES_WITH_PICTURES
								?>
								
								
								<div class="filter-color">										
											<?foreach($arItem["VALUES"] as $val => $ar):?>											
											<?											
											if (!isset($ar["FILE"]) && empty($ar["FILE"]["SRC"])) continue;
											?>
											<div class="filter-color__item">

															<!-- begin filter-checkbox -->
															<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="filter-color__label" title="<?=$ar["VALUE"];?>" for="<? echo $ar["CONTROL_ID"] ?>">
																
																<input
																	class="filter-color__input"
																	type="checkbox"
																	value="<? echo $ar["HTML_VALUE"] ?>"
																	name="<? echo $ar["CONTROL_NAME"] ?>"
																	id="<? echo $ar["CONTROL_ID"] ?>"
																	<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
																	onclick="smartFilter.click(this)"
																/>	
																<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
																<span class="filter-color__color" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
																<?endif?>
															</label>
															<!-- end filter-checkbox -->

											</div>		
											<?endforeach;?>	
								</div>
								
								
								
								
								<?/*foreach ($arItem["VALUES"] as $val => $ar):?>
									<input
										style="display: none"
										type="checkbox"
										name="<?=$ar["CONTROL_NAME"]?>"
										id="<?=$ar["CONTROL_ID"]?>"
										value="<?=$ar["HTML_VALUE"]?>"
										<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
									/>
									<?
									$class = "";
									if ($ar["CHECKED"])
										$class.= " active";
									if ($ar["DISABLED"])
										$class.= " disabled";
									?>
									<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx_filter_param_label dib<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'active');">
										<span class="bx_filter_param_btn bx_color_sl">
											<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
											<span class="bx_filter_btn_color_icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
											<?endif?>
										</span>
									</label>
								<?endforeach*/?>
								<?
								break;
							case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
								?>
								<?foreach ($arItem["VALUES"] as $val => $ar):?>
									<input
										style="display: none"
										type="checkbox"
										name="<?=$ar["CONTROL_NAME"]?>"
										id="<?=$ar["CONTROL_ID"]?>"
										value="<?=$ar["HTML_VALUE"]?>"
										<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
									/>
									<?
									$class = "";
									if ($ar["CHECKED"])
										$class.= " active";
									if ($ar["DISABLED"])
										$class.= " disabled";
									?>
									<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx_filter_param_label<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'active');">
										<span class="bx_filter_param_btn bx_color_sl">
											<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
												<span class="bx_filter_btn_color_icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
											<?endif?>
										</span>
										<span class="bx_filter_param_text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
										if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
											?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
										endif;?></span>
									</label>
								<?endforeach?>
								<?
								break;
							case "P"://DROPDOWN
							
							
								//PRE($arItem);
								if($arItem["CODE"] == 'WEIGHT') {
									ksort($arItem["VALUES"], SORT_NUMERIC);																	
								}
								if($arItem["CODE"] == 'SIZE') {										
									$arItem["VALUES"] = CStatic::SortProp(26, $arItem["VALUES"]);									
								}
								if($arItem["CODE"] == 'RIGIDITY') {										
									$arItem["VALUES"] = CStatic::SortProp(27, $arItem["VALUES"]);									
								}
							
							
								$checkedItemExist = false;
								?>
								<div class="bx_filter_select_container">
									<div class="bx_filter_select_block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
										<div class="bx_filter_select_text" data-role="currentOption">
											<?
											foreach ($arItem["VALUES"] as $val => $ar)
											{
												if ($ar["CHECKED"])
												{
													echo $ar["VALUE"];
													$checkedItemExist = true;
												}
											}
											if (!$checkedItemExist)
											{
												echo GetMessage("CT_BCSF_FILTER_ALL");
											}
											?>
										</div>
										<div class="bx_filter_select_arrow"></div>
										<input
											style="display: none"
											type="radio"
											name="<?=$arCur["CONTROL_NAME_ALT"]?>"
											id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
											value=""
										/>
										<?foreach ($arItem["VALUES"] as $val => $ar):?>
											<input
												style="display: none"
												type="radio"
												name="<?=$ar["CONTROL_NAME_ALT"]?>"
												id="<?=$ar["CONTROL_ID"]?>"
												value="<? echo $ar["HTML_VALUE_ALT"] ?>"
												<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
											/>
										<?endforeach?>
										<div class="bx_filter_select_popup" data-role="dropdownContent" style="display: none;">
											<ul>
												<li>
													<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx_filter_param_label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
														<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
													</label>
												</li>
											<?
											foreach ($arItem["VALUES"] as $val => $ar):
												$class = "";
												if ($ar["CHECKED"])
													$class.= " selected";
												if ($ar["DISABLED"])
													$class.= " disabled";
											?>
												<li>
													<label for="<?=$ar["CONTROL_ID"]?>" class="bx_filter_param_label<?=$class?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')"><?=$ar["VALUE"]?></label>
												</li>
											<?endforeach?>
											</ul>
										</div>
									</div>
								</div>
								<?
								break;
							case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
								?>
								<div class="bx_filter_select_container">
									<div class="bx_filter_select_block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
										<div class="bx_filter_select_text" data-role="currentOption">
											<?
											$checkedItemExist = false;
											foreach ($arItem["VALUES"] as $val => $ar):
												if ($ar["CHECKED"])
												{
												?>
													<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
														<span class="bx_filter_btn_color_icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
													<?endif?>
													<span class="bx_filter_param_text">
														<?=$ar["VALUE"]?>
													</span>
												<?
													$checkedItemExist = true;
												}
											endforeach;
											if (!$checkedItemExist)
											{
												?><span class="bx_filter_btn_color_icon all"></span> <?
												echo GetMessage("CT_BCSF_FILTER_ALL");
											}
											?>
										</div>
										<div class="bx_filter_select_arrow"></div>
										<input
											style="display: none"
											type="radio"
											name="<?=$arCur["CONTROL_NAME_ALT"]?>"
											id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
											value=""
										/>
										<?foreach ($arItem["VALUES"] as $val => $ar):?>
											<input
												style="display: none"
												type="radio"
												name="<?=$ar["CONTROL_NAME_ALT"]?>"
												id="<?=$ar["CONTROL_ID"]?>"
												value="<?=$ar["HTML_VALUE_ALT"]?>"
												<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
											/>
										<?endforeach?>
										<div class="bx_filter_select_popup" data-role="dropdownContent" style="display: none">
											<ul>
												<li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
													<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx_filter_param_label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
														<span class="bx_filter_btn_color_icon all"></span>
														<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
													</label>
												</li>
											<?
											foreach ($arItem["VALUES"] as $val => $ar):
												$class = "";
												if ($ar["CHECKED"])
													$class.= " selected";
												if ($ar["DISABLED"])
													$class.= " disabled";
											?>
												<li>
													<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx_filter_param_label<?=$class?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
														<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
															<span class="bx_filter_btn_color_icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
														<?endif?>
														<span class="bx_filter_param_text">
															<?=$ar["VALUE"]?>
														</span>
													</label>
												</li>
											<?endforeach?>
											</ul>
										</div>
									</div>
								</div>
								<?
								break;
							case "K"://RADIO_BUTTONS
								?>
								
								
								<div class="filter__checkbox">
								
								<label for="<? echo "all_".$arCur["CONTROL_ID"] ?>">
									
										<input
										    class="checkbox filter_checkbox pers_data_check"
											type="radio"
											value=""
											name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
											id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
											onclick="smartFilter.click(this)"
										/>
										<span class="checkbox-custom"></span><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
								</label>
								<?foreach($arItem["VALUES"] as $val => $ar):?>
													
									
									<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="radiobox filter__label bx_filter_param_label" for="<? echo $ar["CONTROL_ID"] ?>">
										<span class="bx_filter_input_checkbox <? echo $ar["DISABLED"] ? 'disabled': '' ?>">
											<input
											    class="checkbox filter_checkbox pers_data_check"
												type="radio"
												value="<? echo $ar["HTML_VALUE_ALT"] ?>"
												name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
												id="<? echo $ar["CONTROL_ID"] ?>"
												<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
												onclick="smartFilter.click(this)"
											/>
											<span class="checkbox-custom"></span><?=$ar["VALUE"];?>
										</span>
									</label>
								<?endforeach;?>
								
								</div>
								
								<?
								break;
							case "U"://CALENDAR
								?>
								<div class="bx_filter_parameters_box_container_block"><div class="bx_filter_input_container bx_filter_calendar_container">
									<?$APPLICATION->IncludeComponent(
										'bitrix:main.calendar',
										'',
										array(
											'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
											'SHOW_INPUT' => 'Y',
											'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
											'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
											'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
											'SHOW_TIME' => 'N',
											'HIDE_TIMEBAR' => 'Y',
										),
										null,
										array('HIDE_ICONS' => 'Y')
									);?>
								</div></div>
								<div class="col-md-3 filter__dash2">—</div>
								<div class="bx_filter_parameters_box_container_block"><div class="bx_filter_input_container bx_filter_calendar_container">
									<?$APPLICATION->IncludeComponent(
										'bitrix:main.calendar',
										'',
										array(
											'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
											'SHOW_INPUT' => 'Y',
											'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
											'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
											'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
											'SHOW_TIME' => 'N',
											'HIDE_TIMEBAR' => 'Y',
										),
										null,
										array('HIDE_ICONS' => 'Y')
									);?>
								</div></div>
								<?
								break;
								default: //CHECKBOXES
								
								
								
								if($_GET["mode"]) {
	
										//pre($arItem["VALUES"]);
								}
								
								
								
								
								
								
								
								?>
								
								<?if(strstr($arItem["CODE"],'COLOR')):?>
								
								<div class="filter-color">										
											<?foreach($arItem["VALUES"] as $val => $ar):?>											
											<?	
											if (!$arResult["COLOR"][$val]["IMG"] && !$arResult["COLOR"][$val]["CODE"]) continue;
											?>
											<div class="filter-color__item">

															<!-- begin filter-checkbox -->
															<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="filter-color__label" title="<?=$ar["VALUE"];?>" for="<? echo $ar["CONTROL_ID"] ?>">
																
																<input
																	class="filter-color__input"
																	type="checkbox"
																	value="<? echo $ar["HTML_VALUE"] ?>"
																	name="<? echo $ar["CONTROL_NAME"] ?>"
																	id="<? echo $ar["CONTROL_ID"] ?>"
																	<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
																	onclick="smartFilter.click(this)"
																/>	
																<?if ($arResult["COLOR"][$val]["IMG"]):?>
																	<span class="filter-color__color" style="background-image:url('<?=$arResult["COLOR"][$val]["IMG"]?>');"></span>
																<?else:?>
																	<span class="filter-color__color" style="background: #<?=$arResult["COLOR"][$val]["CODE"]?>;"></span>
																<?endif?>
															</label>
															<!-- end filter-checkbox -->

											</div>		
											<?endforeach;?>	
								</div>
								
								<?else:?>								
								
								<div class="filter__checkbox">
								<?foreach($arItem["VALUES"] as $val => $ar):?>
								<?
								$dd_val = $ar["VALUE"];
								if($dd_val == 'N') $dd_val = 'Нет';
								if($dd_val == 'Y') $dd_val = 'Да';
								
								
								
								?>
									<label class="<? echo $ar["DISABLED"] ? 'disabled': '' ?> filter-checkbox__label" data-role="label_<?=$ar["CONTROL_ID"]?>" for="<? echo $ar["CONTROL_ID"] ?>">										
									
											<input
												class="checkbox filter_checkbox pers_data_check"
												type="checkbox"
												value="<? echo $ar["HTML_VALUE"] ?>"
												name="<? echo $ar["CONTROL_NAME"] ?>"
												id="<? echo $ar["CONTROL_ID"] ?>"
												<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
												onclick="smartFilter.click(this)"
											/>
											<span class="checkbox-custom"><?=$dd_val;?></span>
											
									
									</label>
								<?endforeach;?>
								
								</div>
								
								<?endif;?>
						<?
						}
						?>
						</div>
						<div class="clb"></div>
					</div>
				</div>
			<?
			$k++;
			
			}
			?>
			
		
		
			<?if($watch):?>
			
					<div class="filter__item item__close">
						<span class="close__filter">Открыть фильтр</span>
						<span class="close__filter">X Закрыть фильтр</span>
					</div>
					
							<div class="filter__button">
								<input type="hidden" name="set_filter" value="Y" />
								<input type="submit" value="Показать" class="btn-sub">
								<?$page_site = $APPLICATION->GetCurPage();?>
								<a href="<?=$page_site?>" class="filter_reset">Сбросить фильтр</a>
							</div>
							
			<?endif;?>
		
		
		
				
				
			
		<input class="hide bx_filter_search_button" type="submit" id="set_filter" name="set_filter" value="<?=GetMessage("CT_BCSF_SET_FILTER")?>" />	
			
			
			
			<div class="bx_filter_button_box active">
				<div class="bx_filter_block">
					<div class="bx_filter_parameters_box_container">
						
						<div class="bx_filter_popup_result <?=$arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
							<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
							<span class="arrow"></span>
							<a href="<?echo $arResult["FILTER_URL"]?>"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
						</div>
						
					</div>
				</div>
			</div>
		</form>
	</div>
<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', 'vertical');
</script>

<?endif;?>