<?
class CMNTProductCache extends CMNTProductCacheCommon
{
	public static function GetByIDNoCache($arParamsCurrent = array())
	{
		$arParamsDefault = array(
			"ID" => "",
			"TYPE" => "",
			"IBLOCK_ID" => "",
			"SITE_ID" => defined("SITE_ID") ? SITE_ID : "",
		);
		$arParams = array();
		foreach($arParamsDefault as $optKey => $optVal)
			$arParams[$optKey] = is_array($arParamsCurrent) && array_key_exists($optKey, $arParamsCurrent) ? $arParamsCurrent[$optKey] : $arParamsDefault[$optKey];
		
		$arParams["ID"] = intval($arParams["ID"]);
		if($arParams["ID"] <= 0)
			return false;
		
		if(empty($arParams["SITE_ID"]))
			return false;
		
		if(!in_array($arParams["TYPE"], array("list", "detail", "buy")))
			return false;
		
		if(!CModule::IncludeModule("iblock"))
			return false;
		
		$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
		
		$arResult = array();
		
		switch($arParams["TYPE"])
		{
			case "list":
				$arFilterEl = array(
					"ID" => $arParams["ID"],
					"ACTIVE" => "Y",
				);
				
				if($arParams["IBLOCK_ID"] > 0)
					$arFilterEl["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
				
				$arSelectEl = array(
					"ID",
					"IBLOCK_ID",
					"IBLOCK_SECTION_ID",
					"NAME",
					"CODE",
					"DETAIL_PAGE_URL",
					"PREVIEW_TEXT",
					"PREVIEW_TEXT_TYPE",
				);
				
				$resEl = CIBlockElement::GetList(array(), $arFilterEl, false, array("nTopCount" => 1), $arSelectEl);
				if($obEl = $resEl->GetNextElement())
				{
					$arFieldsEl = $obEl->GetFields();
					$arFieldsEl["PROPERTIES"] = $obEl->GetProperties();
					
					$bSku = CMNTSku::IsSkuIblock($arFieldsEl["IBLOCK_ID"]) ? true : false;
					
					if($bSku)
					{
						$arResult = array(
							"IS_SKU" => "Y",
							"IBLOCK_ID" => $arFieldsEl["IBLOCK_ID"],
							"NAME" => $arFieldsEl["NAME"],
						);
						
						// photos
						$photoID = $arFieldsEl["PROPERTIES"]["PHOTOS"]["VALUE"][0];
						if($photoID > 0)
						{
							$arPhoto = CFile::GetFileArray($photoID);
							$arResult["PHOTO"]["SOURCE"] = !empty($arPhoto["SRC"]) ? $arPhoto["SRC"] : $photoID;
						}
						
						// colors
						if(!empty($arFieldsEl["PROPERTIES"]["COLOR"]["VALUE"]))
						{
							$resElColor = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["DIRECTORY"]["colors"], "ACTIVE" => "Y", "ID" => $arFieldsEl["PROPERTIES"]["COLOR"]["VALUE"]), false, array("nTopCount" => "1"), array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_RGB"));
							if($arFieldsElColor = $resElColor->GetNext())
							{
								if(!empty($arFieldsElColor["PREVIEW_PICTURE"]))
									$arFieldsElColor["PREVIEW_PICTURE"] = CFile::GetFileArray($arFieldsElColor["PREVIEW_PICTURE"]);
								
								$arPicture = "";
								if(is_array($arFieldsElColor["PREVIEW_PICTURE"]))
								{
									$arPicture = array(
										"ID" => $arFieldsElColor["PREVIEW_PICTURE"]["ID"],
										"SRC" => $arFieldsElColor["PREVIEW_PICTURE"]["SRC"],
										"WIDTH" => $arFieldsElColor["PREVIEW_PICTURE"]["WIDTH"],
										"HEIGHT" => $arFieldsElColor["PREVIEW_PICTURE"]["HEIGHT"],
									);
								}
								
								$arResult["COLOR"] = array(
									"NAME" => $arFieldsElColor["NAME"],
									"IMG" => $arPicture,
									"RGB" => $arFieldsElColor["PROPERTY_RGB_VALUE"],
								);
							}
						}
						
						// display properties
						$arDisplayPropsCode = array();
						foreach($arFieldsEl["PROPERTIES"] as $propCode => $arProp)
						{
							if($arProp["SORT"] < 5000)
								continue;
							
							$arDisplayPropsCode[] = $propCode;
						}
						
						$arResult["DISPLAY_PROPERTIES"] = array();
						foreach($arDisplayPropsCode as $propCode)
						{
							$arProp = &$arFieldsEl["PROPERTIES"][$propCode];
							
							if(
								(is_array($arProp["VALUE"]) && count($arProp["VALUE"]) > 0)	|| 
								(!is_array($arProp["VALUE"]) && strlen($arProp["VALUE"]) > 0)
							)
							{
								$arResult["DISPLAY_PROPERTIES"][$propCode] = CIBlockFormatProperties::GetDisplayValue($arFieldsEl, $arProp, "catalog_out");
								
								if($arProp["PROPERTY_TYPE"] == "E")
								{
									if(is_array($arResult["DISPLAY_PROPERTIES"][$propCode]["DISPLAY_VALUE"]))
									{
										foreach($arResult["DISPLAY_PROPERTIES"][$propCode]["DISPLAY_VALUE"] as $k => $v)
											$arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"][$k] = CMNTPropertiesFormat::RemoveLink($v);
									}
									else
									{
										$arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"] = CMNTPropertiesFormat::RemoveLink($arResult["DISPLAY_PROPERTIES"][$propCode]["DISPLAY_VALUE"]);
									}
								}
								
								if($arProp["PROPERTY_TYPE"] == "L" && !is_array($arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"]) && in_array($arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"], array("Есть", "есть", "Да", "да", "Y")))
									$arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"] = "Да";
							}
						}
						
						foreach($arResult["DISPLAY_PROPERTIES"] as $propCode => $arProp)
						{
							$arResult["DISPLAY_PROPERTIES"][$propCode] = array(
								"NAME" => $arProp["NAME"],
								"VALUE" => $arProp["VALUE"],
							);
						}
						
						$productIblockID = CMNTSku::GetProductIblockID($arFieldsEl["IBLOCK_ID"]);
						$productElID = intval($arFieldsEl["PROPERTIES"]["CML2_LINK"]["VALUE"]);
						
						$arResult["PRODUCT_ELEMENT_ID"] = $productElID;
						
						if($productElID > 0 && $productIblockID > 0)
						{
							$resElProduct = CIBlockElement::GetList(array(), array("ID" => $productElID, "IBLOCK_ID" => $productIblockID, "ACTIVE" => ""), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "PROPERTY_GROUP"));
							if($obElProduct  = $resElProduct->GetNextElement())
							{
								$arFieldsElProduct = $obElProduct->GetFields();
								$arFieldsElProduct["PROPERTIES"] = $obElProduct->GetProperties();
								
								$arResult["DETAIL_PAGE_URL"] = $arFieldsElProduct["DETAIL_PAGE_URL"];
								
								if(!empty($arFieldsElProduct["PROPERTIES"]["GROUP"]["VALUE"]))
								{
									$arGroups = CMNTCached::GetCatalogGroups();
									$arGroup = $arGroups[$arFieldsElProduct["PROPERTIES"]["GROUP"]["VALUE"]];
									
									if(is_array($arGroup))
										$arResult["DETAIL_PAGE_URL"] = preg_replace("~^".$GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"]."category/([^/]+)/(.+)\.html$~is", $GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"]."group/".$arGroup["CODE"]."/$1/$2.html", $arFieldsElProduct["DETAIL_PAGE_URL"]);
									
									// standard product params
									foreach($GLOBALS["SITE_CONFIG"]["PRODUCT_PARAMS"] as $standardCode => $iblockPropCode)
									{
										if(!empty($arFieldsElProduct["PROPERTIES"][$iblockPropCode]["VALUE"]))
											$arResult["PRODUCT_PARAMS"][$standardCode] = $arFieldsElProduct["PROPERTIES"][$iblockPropCode]["VALUE"];
									}
									
									if(!empty($arResult["PRODUCT_PARAMS"]["BRAND"]) && intval($arResult["PRODUCT_PARAMS"]["BRAND"]) > 0)
									{
										$arBrands = CMNTCached::GetBrands();
										if(!empty($arBrands[$arResult["PRODUCT_PARAMS"]["BRAND"]]))
											$arResult["PRODUCT_PARAMS"]["BRAND"] = $arBrands[$arResult["PRODUCT_PARAMS"]["BRAND"]];
									}
									
									if(!empty($arResult["PRODUCT_PARAMS"]["COUNTRY"]) && intval($arResult["PRODUCT_PARAMS"]["COUNTRY"]) > 0)
									{
										$arCountries = CMNTCached::GetCountries();
										if(!empty($arCountries[$arResult["PRODUCT_PARAMS"]["COUNTRY"]]))
											$arResult["PRODUCT_PARAMS"]["COUNTRY"] = $arCountries[$arResult["PRODUCT_PARAMS"]["COUNTRY"]];
									}
								}
								
								$arResult["DETAIL_PAGE_URL"] .= "?sku=".$arParams["ID"];
							}
						}
					}
					else
					{
						$arResult = array(
							"IBLOCK_ID" => $arFieldsEl["IBLOCK_ID"],
							"NAME" => $arFieldsEl["NAME"],
							"CODE" => $arFieldsEl["CODE"],
							"DETAIL_PAGE_URL" => $arFieldsEl["DETAIL_PAGE_URL"],
							"COMPARE_PAGE_URL" => $GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"].$GLOBALS["SITE_CONFIG"]["IBLOCKS"][$arFieldsEl["IBLOCK_ID"]]["CODE"]."/compare/",
							"IBLOCK_SECTION_ID" => $arFieldsEl["IBLOCK_SECTION_ID"],
						);
						
						// catalog group
						$arResult["CATALOG_GROUP"] = "";
						if(!empty($arFieldsEl["PROPERTIES"]["GROUP"]["VALUE"]))
						{
							$arGroups = CMNTCached::GetCatalogGroups();
							$arResult["CATALOG_GROUP"] = $arGroups[$arFieldsEl["PROPERTIES"]["GROUP"]["VALUE"]];
							
							//if(is_array($arResult["CATALOG_GROUP"]))
							//	$arResult["DETAIL_PAGE_URL"] = preg_replace("~^".$GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"]."category/([^/]+)/(.+)\.html$~is", $GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"]."group/".$arResult["CATALOG_GROUP"]["CODE"]."/$1/$2.html", $arResult["DETAIL_PAGE_URL"]);
						}
						
						// catalog collection
						$arResult["CATALOG_COLLECTION"] = "";
						if(!empty($arFieldsEl["PROPERTIES"]["COLLECTION"]["VALUE"]))
						{
							$arCollections = CMNTCached::GetCatalogCollections();
							$arResult["CATALOG_COLLECTION"] = $arCollections[$arFieldsEl["PROPERTIES"]["COLLECTION"]["VALUE"]];
						}
						
						// standard product params
						foreach($GLOBALS["SITE_CONFIG"]["PRODUCT_PARAMS"] as $standardCode => $iblockPropCode)
						{
							if(!empty($arFieldsEl["PROPERTIES"][$iblockPropCode]["VALUE"]))
								$arResult["PRODUCT_PARAMS"][$standardCode] = $arFieldsEl["PROPERTIES"][$iblockPropCode]["VALUE"];
						}
						
						if(!empty($arResult["PRODUCT_PARAMS"]["BRAND"]) && intval($arResult["PRODUCT_PARAMS"]["BRAND"]) > 0)
						{
							$arBrands = CMNTCached::GetBrands();
							if(!empty($arBrands[$arResult["PRODUCT_PARAMS"]["BRAND"]]))
								$arResult["PRODUCT_PARAMS"]["BRAND"] = $arBrands[$arResult["PRODUCT_PARAMS"]["BRAND"]];
						}
						
						if(!empty($arResult["PRODUCT_PARAMS"]["COUNTRY"]) && intval($arResult["PRODUCT_PARAMS"]["COUNTRY"]) > 0)
						{
							$arCountries = CMNTCached::GetCountries();
							if(!empty($arCountries[$arResult["PRODUCT_PARAMS"]["COUNTRY"]]))
								$arResult["PRODUCT_PARAMS"]["COUNTRY"] = $arCountries[$arResult["PRODUCT_PARAMS"]["COUNTRY"]];
						}
						
						// flags
						foreach($GLOBALS["SITE_CONFIG"]["PRODUCT_FLAGS"] as $flagName => $propCode)
						{
							if($arFieldsEl["PROPERTIES"][$propCode]["VALUE"] == "Y")
								$arResult["FLAGS"][$flagName] = "Y";
						}
						
						if(!empty($arFieldsEl["~PREVIEW_TEXT"]))
							$arResult["DESCRIPTION"] = $arFieldsEl["~PREVIEW_TEXT"];
						
						// photos
						$photoID = $arFieldsEl["PROPERTIES"]["PHOTOS"]["VALUE"][0];
						if($photoID > 0)
						{
							$arPhoto = CFile::GetFileArray($photoID);
							$arResult["PHOTO"]["SOURCE"] = !empty($arPhoto["SRC"]) ? $arPhoto["SRC"] : $photoID;
	
							$arPhotoThumbs = array(
								"CATALOG_SECTION_LIST",
								"CATALOG_SECTION_TABLE",
								"CATALOG_LIVE_SEARCH",
							);
							
							foreach($arPhotoThumbs as $thumbType)
							{
								if(!empty($arPhoto["SRC"]))
									$arResult["PHOTO"][$thumbType] = CMNTImg::GetImgArray($arPhoto["SRC"], $thumbType);
								//elseif($blankImg = CMNTImg::GetBlankImgArray($thumbType))
								//	$arResult["PHOTO"][$thumbType] = $blankImg;
							}
						}
						
						// labels
						$arLabels = CMNTLabels::GetLabelsForElementList(array("ELEMENT" => $arFieldsEl));
						
						if(!empty($arLabels) && is_array($arLabels))
							$arResult["LABELS"] = $arLabels;
						
						// features
						$arFeatures = CMNTFeatures::GetFeaturesForElement(array("ELEMENT" => $arFieldsEl));
						
						if(!empty($arFeatures) && is_array($arFeatures))
							$arResult["FEATURES"] = $arFeatures;
						
						// display properties
						if(!empty($GLOBALS["SITE_CONFIG"]["IBLOCKS"][$arFieldsEl["IBLOCK_ID"]]["LIST_PROPS"]))
						{
							$arResultProps = array();
							foreach($GLOBALS["SITE_CONFIG"]["IBLOCKS"][$arFieldsEl["IBLOCK_ID"]]["LIST_PROPS"] as $propID => $propCode)
							{
								$arProp = &$arFieldsEl["PROPERTIES"][$propCode];
								if(
									(is_array($arProp["VALUE"]) && count($arProp["VALUE"]) > 0)	|| 
									(!is_array($arProp["VALUE"]) && strlen($arProp["VALUE"]) > 0)
								)
								{
									$arResultProps[$propCode] = CIBlockFormatProperties::GetDisplayValue($arFieldsEl, $arProp, "catalog_out");
									
									if($arProp["PROPERTY_TYPE"] == "E")
									{
										if(is_array($arResultProps[$propCode]["DISPLAY_VALUE"]))
										{
											foreach($arResultProps[$propCode]["DISPLAY_VALUE"] as $k => $v)
												$arResultProps[$propCode]["VALUE"][$k] = CMNTPropertiesFormat::RemoveLink($v);
										}
										else
										{
											$arResultProps[$propCode]["VALUE"] = CMNTPropertiesFormat::RemoveLink($arResultProps[$propCode]["DISPLAY_VALUE"]);
										}
									}
									
									if($arProp["PROPERTY_TYPE"] == "L" && !is_array($arResultProps[$propCode]["VALUE"]) && in_array($arResultProps[$propCode]["VALUE"], array("Есть", "есть", "Да", "да", "Y")))
										$arResultProps[$propCode]["VALUE"] = "Да";
								}
							}
							
							foreach($arResultProps as $propCode => $arProp)
							{
								$arResult["DISPLAY_PROPERTIES"][$propCode] = array(
									"NAME" => $arProp["NAME"],
									"VALUE" => $arProp["VALUE"],
								);
							}
						}
					}
				}
			break;
			case "detail":
				$arFilterEl = array(
					"ID" => $arParams["ID"],
					"ACTIVE" => "Y",
				);
				
				if($arParams["IBLOCK_ID"] > 0)
					$arFilterEl["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
				
				$arSelectEl = array(
					"ID",
					"IBLOCK_ID",
					"IBLOCK_SECTION_ID",
					"NAME",
					"DETAIL_TEXT",
					"DETAIL_TEXT_TYPE",
				);
				
				$resEl = CIBlockElement::GetList(array(), $arFilterEl, false, array("nTopCount" => 1), $arSelectEl);
				if($obEl = $resEl->GetNextElement())
				{
					$arFieldsEl = $obEl->GetFields();
					$arFieldsEl["PROPERTIES"] = $obEl->GetProperties();
					
					$arResult = array();
					
					// SKU
					$bSku = false;
					if(CMNTSku::IsSkuIblock($arFieldsEl["IBLOCK_ID"]))
					{
						$bSku = true;
						
						$arResult["IS_SKU"] = "Y";
						$arResult["IBLOCK_ID"] = $arFieldsEl["IBLOCK_ID"];
						$arResult["NAME"] = $arFieldsEl["NAME"];
						
						if(!empty($arFieldsEl["PROPERTIES"]["COLOR"]["VALUE"]))
						{
							$resElColor = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["DIRECTORY"]["colors"], "ACTIVE" => "Y", "ID" => $arFieldsEl["PROPERTIES"]["COLOR"]["VALUE"]), false, array("nTopCount" => "1"), array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_RGB"));
							if($arFieldsElColor = $resElColor->GetNext())
							{
								if(!empty($arFieldsElColor["PREVIEW_PICTURE"]))
									$arFieldsElColor["PREVIEW_PICTURE"] = CFile::GetFileArray($arFieldsElColor["PREVIEW_PICTURE"]);
								
								$arPicture = "";
								if(is_array($arFieldsElColor["PREVIEW_PICTURE"]))
								{
									$arPicture = array(
										"ID" => $arFieldsElColor["PREVIEW_PICTURE"]["ID"],
										"SRC" => $arFieldsElColor["PREVIEW_PICTURE"]["SRC"],
										"WIDTH" => $arFieldsElColor["PREVIEW_PICTURE"]["WIDTH"],
										"HEIGHT" => $arFieldsElColor["PREVIEW_PICTURE"]["HEIGHT"],
									);
								}
								
								$arResult["COLOR"] = array(
									"NAME" => $arFieldsElColor["NAME"],
									"IMG" => $arPicture,
									"RGB" => $arFieldsElColor["PROPERTY_RGB_VALUE"],
								);
							}
						}
						
						$productIblockID = CMNTSku::GetProductIblockID($arFieldsEl["IBLOCK_ID"]);
						$productElID = intval($arFieldsEl["PROPERTIES"]["CML2_LINK"]["VALUE"]);
						
						$arResult["PRODUCT_ELEMENT_ID"] = $productElID;
						
						if($productElID > 0 && $productIblockID > 0)
						{
							$resElProduct = CIBlockElement::GetList(array(), array("ID" => $productElID, "IBLOCK_ID" => $productIblockID, "ACTIVE" => ""), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "DETAIL_PAGE_URL", "PROPERTY_GROUP"));
							if($obElProduct  = $resElProduct->GetNextElement())
							{
								$arFieldsElProduct = $obElProduct->GetFields();
								$arFieldsElProduct["PROPERTIES"] = $obElProduct->GetProperties();
								
								$arResult["DETAIL_PAGE_URL"] = $arFieldsElProduct["DETAIL_PAGE_URL"]."?sku=".$arParams["ID"];
								
								// standard product params
								foreach($GLOBALS["SITE_CONFIG"]["PRODUCT_PARAMS"] as $standardCode => $iblockPropCode)
								{
									if(!empty($arFieldsElProduct["PROPERTIES"][$iblockPropCode]["VALUE"]))
										$arResult["PRODUCT_PARAMS"][$standardCode] = $arFieldsElProduct["PROPERTIES"][$iblockPropCode]["VALUE"];
								}
								
								if(!empty($arResult["PRODUCT_PARAMS"]["BRAND"]) && intval($arResult["PRODUCT_PARAMS"]["BRAND"]) > 0)
								{
									$arBrands = CMNTCached::GetBrands();
									if(!empty($arBrands[$arResult["PRODUCT_PARAMS"]["BRAND"]]))
										$arResult["PRODUCT_PARAMS"]["BRAND"] = $arBrands[$arResult["PRODUCT_PARAMS"]["BRAND"]];
								}
								
								if(!empty($arResult["PRODUCT_PARAMS"]["COUNTRY"]) && intval($arResult["PRODUCT_PARAMS"]["COUNTRY"]) > 0)
								{
									$arCountries = CMNTCached::GetCountries();
									if(!empty($arCountries[$arResult["PRODUCT_PARAMS"]["COUNTRY"]]))
										$arResult["PRODUCT_PARAMS"]["COUNTRY"] = $arCountries[$arResult["PRODUCT_PARAMS"]["COUNTRY"]];
								}
								
								$arFieldsEl["BASE_PRODUCT_ID"] = $arFieldsElProduct["ID"];
								$arFieldsEl["BASE_PRODUCT_IBLOCK_ID"] = $arFieldsElProduct["IBLOCK_ID"];
								$arFieldsEl["BASE_PRODUCT_IBLOCK_SECTION_ID"] = $arFieldsElProduct["IBLOCK_SECTION_ID"];
								
								// labels
								$arLabels = CMNTLabels::GetLabelsForElementList(array("ELEMENT" => $arFieldsEl));
								
								if(!empty($arLabels) && is_array($arLabels))
									$arResult["LABELS"] = $arLabels;
								
								// features
								$arFeatures = CMNTFeatures::GetFeaturesForElement(array("ELEMENT" => $arFieldsEl));
								
								if(!empty($arFeatures) && is_array($arFeatures))
									$arResult["FEATURES"] = $arFeatures;
							}
						}
					}
					
					// section
					if(!empty($arFieldsEl["IBLOCK_SECTION_ID"]))
					{
						$resSec = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arFieldsEl["IBLOCK_ID"], "ID" => $arFieldsEl["IBLOCK_SECTION_ID"], "ACTIVE" => ""), false);
						if($arFieldsSec = $resSec->GetNext())
						{
							$arResult["SECTION"] = array(
								"ID" => $arFieldsSec["ID"],
								"PATH" => array(),
							);
							$resPath = CIBlockSection::GetNavChain($arFieldsEl["IBLOCK_ID"], $arFieldsEl["IBLOCK_SECTION_ID"]);
							while($arFieldsPath = $resPath->GetNext())
							{
								//$arResult["SECTION"]["PATH"][] = $arFieldsPath;
								$arResult["SECTION"]["PATH"][] = array(
									"ID" => $arFieldsPath["ID"],
									"NAME" => $arFieldsPath["NAME"],
									"CODE" => $arFieldsPath["CODE"],
									"ACTIVE" => $arFieldsPath["ACTIVE"],
									"GLOBAL_ACTIVE" => $arFieldsPath["GLOBAL_ACTIVE"],
									"DEPTH_LEVEL" => $arFieldsPath["DEPTH_LEVEL"],
									"SECTION_PAGE_URL" => $arFieldsPath["SECTION_PAGE_URL"],
								);
							}
						}
					}
					
					// description
					$arResult["DESCRIPTION"] = $arFieldsEl["~DETAIL_TEXT"];
					
					// seo
					$arResult["SEO"] = array(
						"NAME_ACCUSATIF" => $arFieldsEl["PROPERTIES"]["SEOACCUSATIF"]["VALUE"],
					);
					
					// photos
					if(!empty($arFieldsEl["PROPERTIES"]["PHOTOS"]["VALUE"]))
					{
						$arPhoto = CFile::GetFileArray($arFieldsEl["PROPERTIES"]["PHOTOS"]["VALUE"][0]);
						
						if($bSku && !empty($arPhoto))
							$arResult["PHOTO"]["SKU_LIST_ON_DETAIL"] = CMNTImg::GetImgArray($arPhoto["SRC"], "SKU_LIST_ON_DETAIL");
						
						$arPhotoThumbs = array(
							"CATALOG_DETAIL_BIG",
							"CATALOG_DETAIL_MEDIUM",
							"CATALOG_DETAIL_SMALL",
						);
						
						foreach($arFieldsEl["PROPERTIES"]["PHOTOS"]["VALUE"] as $k => $photoID)
						{
							$arPhoto = CFile::GetFileArray($photoID);
							$arResult["PHOTO"]["SOURCE"][$k] = !empty($arPhoto["SRC"]) ? $arPhoto["SRC"] : $photoID;
							
							if(!empty($arPhoto["SRC"]))
							{
								if($k == 0)
									CMNTImg::GetImgArray($arPhoto["SRC"], "CATALOG_LIVE_SEARCH");
								
								foreach($arPhotoThumbs as $thumbType)
									$arResult["PHOTO"][$thumbType][$k] = CMNTImg::GetImgArray($arPhoto["SRC"], $thumbType);
							}
						}
					}
					
					// display properties
					$arDisplayPropsCode = array();
					foreach($arFieldsEl["PROPERTIES"] as $propCode => $arProp)
					{
						if($arProp["SORT"] < 5000)
							continue;
						
						$arDisplayPropsCode[] = $propCode;
					}
					
					$arResult["DISPLAY_PROPERTIES"] = array();
					foreach($arDisplayPropsCode as $propCode)
					{
						$arProp = &$arFieldsEl["PROPERTIES"][$propCode];
						if(CMNTPropertiesGroups::IsGroup($arProp))
						{
							$arResult["DISPLAY_PROPERTIES"][$propCode] = $arProp;
							$arResult["DISPLAY_PROPERTIES"][$propCode]["IS_GROUP"] = true;
						}
						elseif(
							(is_array($arProp["VALUE"]) && count($arProp["VALUE"]) > 0)	|| 
							(!is_array($arProp["VALUE"]) && strlen($arProp["VALUE"]) > 0)
						)
						{
							$arResult["DISPLAY_PROPERTIES"][$propCode] = CIBlockFormatProperties::GetDisplayValue($arFieldsEl, $arProp, "catalog_out");
							
							if($arProp["PROPERTY_TYPE"] == "E")
							{
								if(is_array($arResult["DISPLAY_PROPERTIES"][$propCode]["DISPLAY_VALUE"]))
								{
									foreach($arResult["DISPLAY_PROPERTIES"][$propCode]["DISPLAY_VALUE"] as $k => $v)
										$arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"][$k] = CMNTPropertiesFormat::RemoveLink($v);
								}
								else
								{
									$arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"] = CMNTPropertiesFormat::RemoveLink($arResult["DISPLAY_PROPERTIES"][$propCode]["DISPLAY_VALUE"]);
								}
							}
							
							if($arProp["PROPERTY_TYPE"] == "L" && !is_array($arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"]) && in_array($arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"], array("Есть", "есть", "Да", "да", "Y")))
								$arResult["DISPLAY_PROPERTIES"][$propCode]["VALUE"] = "Да";
						}
					}
					
					$arResult["DISPLAY_PROPERTIES_TREE"] = CMNTPropertiesGroups::GetElPropsDetailTree($arResult["DISPLAY_PROPERTIES"]);
					
					foreach($arResult["DISPLAY_PROPERTIES"] as $propCode => $arProp)
					{
						$arResult["DISPLAY_PROPERTIES"][$propCode] = array(
							"NAME" => $arProp["NAME"],
							"VALUE" => $arProp["VALUE"],
						);
						if(!empty($arProp["IS_GROUP"]))
							$arResult["DISPLAY_PROPERTIES"][$propCode]["IS_GROUP"] = true;
					}
					
					// documentation
					if(!empty($arFieldsEl["PROPERTIES"]["DOCUMENTATION"]["VALUE"]))
					{
						$arResult["DOCUMENTATION"] = array();
						foreach($arFieldsEl["PROPERTIES"]["DOCUMENTATION"]["VALUE"] as $key => $docId)
						{
							$arDoc = CFile::GetFileArray($docId);
							
							$arItem = array();
							$arItem["SRC"] = $arDoc["SRC"];
							$arItem["SIZE"] = CMNTGeneral::FileSizeFormat(filesize($_SERVER["DOCUMENT_ROOT"].$arDoc["SRC"]));
							if(preg_match("~^(.+)\.([^\.]+)$~", $arDoc["FILE_NAME"], $match))
							{
								$arItem["EXT"] = $match[2];
								$arItem["FILE_NAME"] = $match[1];
							}
							
							$arItem["NAME"] = $arItem["FILE_NAME"];
							$arItem["FULL_NAME"] = $arItem["NAME"];
							if(strlen($arItem["NAME"]) > 17)
								$arItem["NAME"] = substr($arItem["NAME"], 0, 17)."...".$arItem["EXT"];
							
							if(!empty($arFieldsEl["PROPERTIES"]["DOCUMENTATION"]["DESCRIPTION"][$key]))
							{
								$arItem["NAME"] = $arFieldsEl["PROPERTIES"]["DOCUMENTATION"]["DESCRIPTION"][$key];
								$arItem["FULL_NAME"] = $arItem["NAME"];
								if(strlen($arItem["NAME"]) > 100)
									$arItem["NAME"] = substr($arItem["FULL_NAME"], 0, 97)."...";
							}
							
							$arResult["DOCUMENTATION"][] = $arItem;
						}
					}
					
					// video
					if(!empty($arFieldsEl["PROPERTIES"]["VIDEO"]["VALUE"]))
					{
						$arResult["VIDEO"] = array();
						$arVideoIDs = is_array($arFieldsEl["PROPERTIES"]["VIDEO"]["VALUE"]) ? $arFieldsEl["PROPERTIES"]["VIDEO"]["VALUE"] : array(0 => $arFieldsEl["PROPERTIES"]["VIDEO"]["VALUE"]);
						
						$resVid = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["CONTENT"]["video"], "ACTIVE" => "Y", "ID" => $arVideoIDs), false, array("nTopCount" => count($arVideoIDs)), array("ID", "IBLOCK_ID", "NAME", "PROPERTY_CODE"));
						while($arFieldsVid = $resVid->GetNext())
						{
							$strVideoCode = $arFieldsVid["PROPERTY_CODE_VALUE"];
							
							if(preg_match("~watch\?v=([a-zA-Z0-9\-])+~is", $strVideoCode, $match))
								$strVideoCode = $match[1];
							
							$arResult["VIDEO"][] = array(
								"CODE" => $strVideoCode,
								"DESCRIPTION" => $arFieldsVid["NAME"],
							);
						}
					}
					
					// comment
					if(!empty($arFieldsEl["PROPERTIES"]["COMMENT"]["VALUE"]))
						$arResult["COMMENT"] = $arFieldsEl["PROPERTIES"]["COMMENT"]["VALUE"];
					
					// related and similar
					if(!empty($arFieldsEl["PROPERTIES"]["RELATED"]["VALUE"]))
						$arResult["RELATED"] = $arFieldsEl["PROPERTIES"]["RELATED"]["VALUE"];
					if(!empty($arFieldsEl["PROPERTIES"]["SIMILAR"]["VALUE"]))
						$arResult["RELATED"] = $arFieldsEl["PROPERTIES"]["SIMILAR"]["VALUE"];
					
					// analog
					if(!empty($arFieldsEl["PROPERTIES"]["ANALOG"]["VALUE"]))
					{
						if(!is_array($arFieldsEl["PROPERTIES"]["ANALOG"]["VALUE"]))
							$arFieldsEl["PROPERTIES"]["ANALOG"]["VALUE"] = array($arFieldsEl["PROPERTIES"]["ANALOG"]["VALUE"]);
						
						foreach($arFieldsEl["PROPERTIES"]["ANALOG"]["VALUE"] as $elementID)
						{
							$resElAnalog = CIBlockElement::GetList(array(), array("ID" => $elementID, "ACTIVE" => "Y"), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL"));
							if($arFieldsElAnalog = $resElAnalog->GetNext())
							{
								$arResult["ANALOG"][] = array(
									"ID" => $arFieldsElAnalog["ID"],
									"IBLOCK_ID" => $arFieldsElAnalog["IBLOCK_ID"],
									"NAME" => $arFieldsElAnalog["NAME"],
									"DETAIL_PAGE_URL" => $arFieldsElAnalog["DETAIL_PAGE_URL"],
								);
							}
						}
					}
				}
			break;
			case "buy":
				if(!CModule::IncludeModule("catalog"))
					return false;
				
				$arFilterEl = array(
					"ID" => $arParams["ID"],
					"ACTIVE" => "Y",
				);
				
				if($arParams["IBLOCK_ID"] > 0)
					$arFilterEl["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
				
				$arSelectEl = array(
					"ID",
					"IBLOCK_ID",
					"IBLOCK_SECTION_ID",
				);
				
				if(empty($GLOBALS["SITE_CONFIG"]["PRICES"]) || !is_array($GLOBALS["SITE_CONFIG"]["PRICES"]))
					return false;
				
				$arPrices = array();
				foreach($GLOBALS["SITE_CONFIG"]["PRICES"] as $priceID => $arPrice)
				{
					$arSelectEl[] = "CATALOG_GROUP_".$priceID;
					$arPrices[$priceID] = $arPrice["NAME"];
				}
				
				$resEl = CIBlockElement::GetList(array(), $arFilterEl, false, array("nTopCount" => 1), $arSelectEl);
				if($obEl = $resEl->GetNextElement())
				{
					$arFieldsEl = $obEl->GetFields();
					
					$arResult["PRODUCT_ID"] = $arFieldsEl["ID"];
					
					$arResult["CATALOG"] = array();
					foreach($arPrices as $priceID => $storeCode)
					{
						$arResult["CATALOG"][$priceID] = array(
							"VALID_STORE" => false,
							"VALID_PRICE" => false,
							"SOON" => false,
							"CAN_BUY" => false,
							"PRICE" => floatval($arFieldsEl["CATALOG_PRICE_".$priceID]),
						);
						
						$arFieldsEl["OLD_PRICE"] = $obEl->GetProperty("OLD_PRICE_".$storeCode);
						$arResult["CATALOG"][$priceID]["OLD_PRICE"] = floatval($arFieldsEl["OLD_PRICE"]["VALUE"]);
						
						$arFieldsEl["STORE"] = $obEl->GetProperty($storeCode);
						$arResult["CATALOG"][$priceID]["STORE"] = $arFieldsEl["STORE"]["VALUE"];
						
						if(in_array($arResult["CATALOG"][$priceID]["STORE"], array("В наличии", "Под заказ")))
							$arResult["CATALOG"][$priceID]["VALID_STORE"] = true;
						
						if($arResult["CATALOG"][$priceID]["PRICE"] > 0 && $arResult["CATALOG"][$priceID]["VALID_STORE"])
							$arResult["CATALOG"][$priceID]["VALID_PRICE"] = true;
						
						if($arResult["CATALOG"][$priceID]["VALID_STORE"] && $arResult["CATALOG"][$priceID]["VALID_PRICE"])
							$arResult["CATALOG"][$priceID]["CAN_BUY"] = true;
						
						$arResult["CATALOG"][$priceID]["CAN_BUY"] = false;
						
						if($arResult["CATALOG"][$priceID]["PRICE"] == 0 && $arResult["CATALOG"][$priceID]["STORE"] != "Снят с производства")
						{
							$arResult["CATALOG"][$priceID]["SOON"] = true;
							$arResult["CATALOG"][$priceID]["STORE"] = "Скоро в продаже";
						}
					}
					
					// labels
					$arLabels = CMNTLabels::GetLabelsForElementBuy(array("ID" => $arFieldsEl["ID"], "IBLOCK_ID" => $arFieldsEl["IBLOCK_ID"], "IBLOCK_SECTION_ID" => $arFieldsEl["IBLOCK_SECTION_ID"], "CATALOG" => $arResult["CATALOG"]));
					
					if(!empty($arLabels) && is_array($arLabels))
						$arResult["LABELS"] = $arLabels;
					
					// SKU
					$arResult["SKU"] = array();
					if(CMNTSku::IsProductIblock($arFieldsEl["IBLOCK_ID"]))
					{
						// при использовании более 1 типа цен будет дополнительная выборка из БД для каждого типа цен
						// переделать этот момент, если где то будет несколько цен
						
						foreach($arPrices as $priceID => $storeCode)
						{
							$arResult["SKU"][$priceID] = array();
							
							$resElSku = CIBlockElement::GetList(
								array(
									"propertysort_".$storeCode => "ASC",
									"catalog_PRICE_".$priceID => "ASC",
									"SORT" => "ASC",
									"ID" => "ASC"
								),
								array(
									"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS"][$arFieldsEl["IBLOCK_ID"]]["SKU_IBLOCK_ID"],
									"ACTIVE" => "Y",
									"PROPERTY_CML2_LINK" => $arFieldsEl["ID"],
								),
								false,
								false,
								array(
									"ID",
									"IBLOCK_ID",
									"CATALOG_GROUP_".$priceID,
								)
							);
							while($obElSku = $resElSku->GetNextElement())
							{
								$arFieldsElSku = $obElSku->GetFields();
								
								$arSku = array(
									"PRODUCT_ID" => $arFieldsElSku["ID"],
									"CATALOG" => array(
										"VALID_STORE" => false,
										"VALID_PRICE" => false,
										"CAN_BUY" => false,
										"PRICE" => floatval($arFieldsElSku["CATALOG_PRICE_".$priceID]),
									),
								);
								
								$arFieldsElSku["OLD_PRICE"] = $obElSku->GetProperty("OLD_PRICE_".$storeCode);
								$arSku["CATALOG"]["OLD_PRICE"] = floatval($arFieldsElSku["OLD_PRICE"]["VALUE"]);
								
								$arFieldsElSku["STORE"] = $obElSku->GetProperty($storeCode);
								$arSku["CATALOG"]["STORE"] = $arFieldsElSku["STORE"]["VALUE"];
								
								if(in_array($arSku["CATALOG"]["STORE"], array("В наличии", "Под заказ")))
									$arSku["CATALOG"]["VALID_STORE"] = true;
								
								if($arSku["CATALOG"]["PRICE"] > 0 && $arSku["CATALOG"]["VALID_STORE"])
									$arSku["CATALOG"]["VALID_PRICE"] = true;
								
								if($arSku["CATALOG"]["VALID_STORE"] && $arSku["CATALOG"]["VALID_PRICE"])
									$arSku["CATALOG"]["CAN_BUY"] = true;
								
								if($arSku["CATALOG"]["PRICE"] == 0 && $arSku["CATALOG"]["STORE"] != "Снят с производства")
								{
									$arSku["CATALOG"]["SOON"] = true;
									$arSku["CATALOG"]["STORE"] = "Скоро в продаже";
								}
								
								// labels
								$arLabels = CMNTLabels::GetLabelsForElementBuy(array(
									"ID" => $arFieldsElSku["ID"],
									"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS"][$arFieldsEl["IBLOCK_ID"]]["SKU_IBLOCK_ID"],
									"BASE_PRODUCT_ID" => $arFieldsEl["ID"],
									"BASE_PRODUCT_IBLOCK_ID" => $arFieldsEl["IBLOCK_ID"],
									"BASE_PRODUCT_IBLOCK_SECTION_ID" => $arFieldsEl["IBLOCK_SECTION_ID"],
									"CATALOG" => array($priceID => $arSku["CATALOG"]),
								));
								
								if(!empty($arLabels) && is_array($arLabels))
									$arSku["CATALOG"]["LABELS"] = $arLabels[$priceID];
								
								$arResult["SKU"][$priceID][] = $arSku;
							}
							
							if(empty($arResult["SKU"][$priceID]))
								unset($arResult["SKU"][$priceID]);
						}
					}
					
					if(CMNTSku::IsSkuIblock($arFieldsEl["IBLOCK_ID"]))
						$arResult["IS_SKU"] = "Y";
				}
			break;
		}
		
		return (is_array($arResult) && !empty($arResult)) ? $arResult : false;
	}
}
?>