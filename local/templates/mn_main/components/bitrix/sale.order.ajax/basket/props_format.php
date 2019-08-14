<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!function_exists("showFilePropertyField"))
{
	function showFilePropertyField($name, $property_fields, $values, $max_file_size_show=50000)
	{
		$res = "";

		if (!is_array($values) || empty($values))
			$values = array(
				"n0" => 0,
			);

		if ($property_fields["MULTIPLE"] == "N")
		{
			$res = "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
		}
		else
		{
			$res = '
			<script type="text/javascript">
				function addControl(item)
				{
					var current_name = item.id.split("[")[0],
						current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
						next_id = parseInt(current_id) + 1;

					var newInput = document.createElement("input");
					newInput.type = "file";
					newInput.name = current_name + "[" + next_id + "]";
					newInput.id = current_name + "[" + next_id + "]";
					newInput.onchange = function() { addControl(this); };

					var br = document.createElement("br");
					var br2 = document.createElement("br");

					BX(item.id).parentNode.appendChild(br);
					BX(item.id).parentNode.appendChild(br2);
					BX(item.id).parentNode.appendChild(newInput);
				}
			</script>
			';

			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
			$res .= "<br/><br/>";
			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[1]\" id=\"".$name."[1]\" onChange=\"javascript:addControl(this);\"></label>";
		}

		return $res;
	}
}

if (!function_exists("PrintPropsForm"))
{
	function PrintPropsForm($arSource = array(), $locationTemplate = ".default")
	{
		if (!empty($arSource))
		{		
			
			?>		
					
				<?
				$k = 0;
				$city_val = 'MSK';
				$lift_val = 'BIG';
				foreach ($arSource as $key=>$arProperties)
				{
					//pre($key);
					
					/// for k50
					if(in_array($arProperties['CODE'],array('TRACK_SID','TRACK_UUID','BICONTENT'))):?>
					<?					
					if($arProperties["CODE"] == 'BICONTENT') {
						$arProperties["VALUE"] = 'None';
						if($_COOKIE["Bicontent"]) $arProperties["VALUE"] = $_COOKIE["Bicontent"];
					}
					?>
					<input type="hidden" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" class="noCheck js-<?=strtolower($arProperties["CODE"])?>" />
					<?
					continue;
					endif;
					
					if($arProperties["CODE"] == 'CREDIT') {continue;}
					
					$minInput = array(
						"HOUSE",
						"APARTMENT",
						"APPROACH",
						"INTERCOM",
						"FLOOR",
					);
					
					$dd_cell = 'col-md-12';
					if(in_array($arProperties["CODE"], $minInput)) {						
						$dd_cell = 'col-md-6';
						$k--;
					}
					$dd_s = '';
					switch($arProperties["CODE"]) {
						case "CITY": 
							$dd_s = 'basket-form__field_city';
						break;
						case "ADDR": 
							$dd_s = 'basket-form__field_address';
						break;
						case "LIFT": 
							$dd_s = 'basket-form__field_lift';
						break;
						case "FLOOR": 
							$dd_s = 'js-basket_floor';
							if($lift_val == 'NO') $dd_s.= ' hide';
						break;						
					}
					
					//if($k>1) {echo '</div><div class="row">'; $k = 0;}
					
					$dd_display = '';
					if($arProperties["TYPE"] == 'LOCATION') $dd_display = ' style="display: none;" ';
					if($arProperties["CODE"] == "PHONE") {$arProperties["NAME"] = "Телефон";}
					
					
					
					?>
					
								
					
						<div <?=$dd_display?> class="form__row <?=$dd_s?>">
						
							<label><?=$arProperties["NAME"]?> <?if($arProperties["REQUIED_FORMATED"]=="Y"):?><span class="sof-req">*</span><?endif?></label>
					
						
						<?
						
						if ($arProperties["TYPE"] == "CHECKBOX")
						{
							?>
							<div class="bx_block r1x3 pt8">
								<input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">
								<input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
								<?if (strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
									<div class="bx_description"><?=$arProperties["DESCRIPTION"]?></div>
								<?endif?>
							</div>
							<?
						}
						elseif ($arProperties["TYPE"] == "TEXT")
						{
							
							
							$dd_type = "text";							
							$dd_s = "";							
							$dd_s2 = 'maxlength="250"';							
							if($arProperties["CODE"] == "PHONE") {$dd_s.= 'js-phone_mask'; $dd_type = "tel";}
							if($arProperties["CODE"] == "EMAIL") {$dd_s.= 'typeEmail'; $dd_type = "email";}
							if($arProperties["CODE"] == "METRO") {
								$dd_s.= 'js-basket_metro';
								if($city_val!='MSK') $dd_s.= ' hide';
								$dd_s2.= ' placeholder="'.$arProperties["DESCRIPTION"].'"';							
							}
							if($arProperties["CODE"] == "CITY2") {
								$dd_s.= 'js-basket_city';
								if($city_val=='MSK') $dd_s.= ' hide';
								$dd_s2.= ' placeholder="'.$arProperties["DESCRIPTION"].'"';							
							}
							if($arProperties["CODE"] == "FLOOR") {
								$dd_s2 = ' maxlength="2" ';
								$dd_s = 'basket-form__input_small';
							}
							
							if($arProperties["CODE"] == "DELIVERY_DATE") {
								$dd_type = 'calendar';
								$dd_s = 'js-datapicker';
							}
							
							?>
							
								<input type="text" <?=$dd_s2?> class="basket-form__input <?=$dd_s?> <?if($arProperties["REQUIED_FORMATED"]!="Y"):?>noCheck<?endif;?>" size="<?=$arProperties["SIZE1"]?>" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" />
								<?if (strlen(trim($arProperties["DESCRIPTION"])) > 0 && !in_array($arProperties["CODE"],array("METRO","CITY2"))):?>
									<small class="basket-form__hint"><?=$arProperties["DESCRIPTION"]?></small>
								<?endif?>
							
							<?
						}
						elseif ($arProperties["TYPE"] == "SELECT")
						{
							
							
							
							$dd_cs = "";							
							if($arProperties["CODE"] == "CITY") {								
								$dd_sd = 'city';
								$dd_cs = 'js-basket_change-city';
							}
							if($arProperties["CODE"] == "LIFT") {	
								$dd_cs = 'js-basket_change-lift';
							}
							
							
							
							
							
							
							?>
								<select class="<?=$dd_cs?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
									<?foreach($arProperties["VARIANTS"] as $arVariants):?>
										<option value="<?=$arVariants["VALUE"]?>"<?=$arVariants["SELECTED"] == "Y" ? " selected" : ''?>><?=$arVariants["NAME"]?></option>
									<?endforeach?>
								</select>
								<?if (strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
									<div class="bx_description"><?=$arProperties["DESCRIPTION"]?></div>
								<?endif?>
							<?
						}
						elseif ($arProperties["TYPE"] == "MULTISELECT")
						{
							?>
							<div class="bx_block r3x1">
								<select multiple name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
									<?foreach($arProperties["VARIANTS"] as $arVariants):?>
										<option value="<?=$arVariants["VALUE"]?>"<?=$arVariants["SELECTED"] == "Y" ? " selected" : ''?>><?=$arVariants["NAME"]?></option>
									<?endforeach?>
								</select>
								<?if (strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
									<div class="bx_description"><?=$arProperties["DESCRIPTION"]?></div>
								<?endif?>
							</div>
							<?
						}
						elseif ($arProperties["TYPE"] == "TEXTAREA")
						{
							$rows = ($arProperties["SIZE2"] > 10) ? 4 : $arProperties["SIZE2"];
							?>
								<textarea rows="<?=$rows?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
								<?if (strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
									<div class="bx_description"><?=$arProperties["DESCRIPTION"]?></div>
								<?endif?>
							<?
						}
						elseif ($arProperties["TYPE"] == "LOCATION")
						{
							?>
							<div class="bx_block r3x1">
								<?
								$value = 0;
								if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0)
								{
									foreach ($arProperties["VARIANTS"] as $arVariant)
									{
										if ($arVariant["SELECTED"] == "Y")
										{
											$value = $arVariant["ID"];
											break;
										}
									}
								}

								// here we can get '' or 'popup'
								// map them, if needed
								if(CSaleLocation::isLocationProMigrated())
								{
									$locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
									$locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps' : $locationTemplateP; // force to "steps"
								}
								?>

								<?if($locationTemplateP == 'steps'):?>
									<input type="hidden" id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" value="<?=($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])] ? '1' : '0')?>" />
								<?endif?>

								<?CSaleLocation::proxySaleAjaxLocationsComponent(array(
									"AJAX_CALL" => "N",
									"COUNTRY_INPUT_NAME" => "COUNTRY",
									"REGION_INPUT_NAME" => "REGION",
									"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
									"CITY_OUT_LOCATION" => "Y",
									"LOCATION_VALUE" => $value,
									"ORDER_PROPS_ID" => $arProperties["ID"],
									"ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
									"SIZE1" => $arProperties["SIZE1"],
								),
									array(
										"ID" => $value,
										"CODE" => "",
										"SHOW_DEFAULT_LOCATIONS" => "Y",

										// function called on each location change caused by user or by program
										// it may be replaced with global component dispatch mechanism coming soon
										"JS_CALLBACK" => "submitFormProxy",

										// function window.BX.locationsDeferred['X'] will be created and lately called on each form re-draw.
										// it may be removed when sale.order.ajax will use real ajax form posting with BX.ProcessHTML() and other stuff instead of just simple iframe transfer
										"JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),

										// an instance of this control will be placed to window.BX.locationSelectors['X'] and lately will be available from everywhere
										// it may be replaced with global component dispatch mechanism coming soon
										"JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),

										"DISABLE_KEYBOARD_INPUT" => "Y",
										"PRECACHE_LAST_LEVEL" => "Y",
										"PRESELECT_TREE_TRUNK" => "Y",
										"SUPPRESS_ERRORS" => "Y"
									),
									$locationTemplateP,
									true,
									'location-block-wrapper'
								)?>

								<?if (strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
									<div class="bx_description"><?=$arProperties["DESCRIPTION"]?></div>
								<?endif?>
							</div>
							<?
						}
						elseif ($arProperties["TYPE"] == "RADIO")
						{
							
							
							$dd_sd = "radio";							
							$dd_cs = "";							
							if($arProperties["CODE"] == "CITY") {								
								$dd_sd = 'city';
								$dd_cs = 'js-basket_change-city';
							}
							if($arProperties["CODE"] == "LIFT") {	
								$dd_cs = 'js-basket_change-lift';
							}
							
							?>
							
							
							<div class="basket-<?=$dd_sd?>">
							
								<?
								if (is_array($arProperties["VARIANTS"]))
								{
									foreach($arProperties["VARIANTS"] as $arVariants):
									
									
									if($arVariants["CHECKED"] == "Y") {
										if($arProperties["CODE"] == "CITY") $city_val = $arVariants["VALUE"];
										if($arProperties["CODE"] == "LIFT") $lift_val = $arVariants["VALUE"];										
									}
									
									
									?>
									<?if($arProperties["CODE"] == 'CITY'):?>
										<div class="basket-<?=$dd_sd?>__col">
									<?endif;?>	
										
											<label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>" class="basket-<?=$dd_sd?>__label">
											
												<input
													type="radio"
													name="<?=$arProperties["FIELD_NAME"]?>"
													class="basket-<?=$dd_sd?>__input <?=$dd_cs?>"
													id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"
													value="<?=$arVariants["VALUE"]?>" <?if($arVariants["CHECKED"] == "Y"):?>checked="checked"<?endif;?> 
												/>
											
												<?if($arProperties["CODE"] == 'CITY'):?>
													<span class="basket-<?=$dd_sd?>__bg"></span>
												<?endif;?>	
													<span class="basket-<?=$dd_sd?>__icon"></span>
													<span class="basket-<?=$dd_sd?>__text"><?=$arVariants["NAME"]?></span>
											</label>
											
									<?if($arProperties["CODE"] == 'CITY'):?>	
										</div>
									<?endif;?>
									<?
									endforeach;
								}
								?>
								<?/*if (strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
									<div class="bx_description"><?=$arProperties["DESCRIPTION"]?></div>
								<?endif*/?>
							</div>
							<?
						}
						elseif ($arProperties["TYPE"] == "FILE")
						{
							?>
							<div class="bx_block r3x1">
								<?=showFilePropertyField("ORDER_PROP_".$arProperties["ID"], $arProperties, $arProperties["VALUE"], $arProperties["SIZE1"])?>
								<?if (strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
									<div class="bx_description"><?=$arProperties["DESCRIPTION"]?></div>
								<?endif?>
							</div>
							<?
						}
						elseif ($arProperties["TYPE"] == "DATE")
						{
							?>
							<div>
								<?
								global $APPLICATION;

								$APPLICATION->IncludeComponent('bitrix:main.calendar', '', array(
									'SHOW_INPUT' => 'Y',
									'INPUT_NAME' => "ORDER_PROP_".$arProperties["ID"],
									'INPUT_VALUE' => $arProperties["VALUE"],
									'SHOW_TIME' => 'N'
								), null, array('HIDE_ICONS' => 'N'));
								?>
								<?if (strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
									<div class="bx_description"><?=$arProperties["DESCRIPTION"]?></div>
								<?endif?>
							</div>
							<?
						}
						?>
						
					</div>			
<?
						/*if(in_array($arProperties["CODE"],array("APPROACH","FLOOR"))) {							
							echo '</div></div>';	
						}*/
						
?>						

					<?if(CSaleLocation::isLocationProEnabled()):?>

					<?
					$propertyAttributes = array(
						'type' => $arProperties["TYPE"],
						'valueSource' => $arProperties['SOURCE'] == 'DEFAULT' ? 'default' : 'form' // value taken from property DEFAULT_VALUE or it`s a user-typed value?
					);

					if(intval($arProperties['IS_ALTERNATE_LOCATION_FOR']))
						$propertyAttributes['isAltLocationFor'] = intval($arProperties['IS_ALTERNATE_LOCATION_FOR']);

					if(intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']))
						$propertyAttributes['altLocationPropId'] = intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']);

					if($arProperties['IS_ZIP'] == 'Y')
						$propertyAttributes['isZip'] = true;
					?>

						<script>

							<?// add property info to have client-side control on it?>
							(window.top.BX || BX).saleOrderAjax.addPropertyDesc(<?=CUtil::PhpToJSObject(array(
									'id' => intval($arProperties["ID"]),
									'attributes' => $propertyAttributes
								))?>);

						</script>
					<?endif?>

					
					<?
					
					
					if($arProperties["TYPE"]!= "LOCATION")	$k++;
				}
				?>
				
			
			
			<?
		}
	}
}
?>