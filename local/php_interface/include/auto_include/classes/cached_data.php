<?
class CMNTCached extends CMNTCachedCommon
{
	public static function GetDataNoCache($arParams = array())
	{
		switch($arParams["TYPE"])
		{
			case "default":
				$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
				
				if($arParams["IBLOCK_ID"] <= 0 || !CModule::IncludeModule("iblock"))
					return false;
				
				$arResult = array();
				$resEl = CIBlockElement::GetList(array("id" => "asc"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME"));
				while($arFieldsEl = $resEl->GetNext())
					$arResult[$arFieldsEl["ID"]] = $arFieldsEl["NAME"];
			break;
			case "category_seo":
				$arParams["CUSTOM_CONFIG"]["CATEGORIES_IBLOCK_ID"] = intval($arParams["CUSTOM_CONFIG"]["CATEGORIES_IBLOCK_ID"]);
				$arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"] = intval($arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"]);
				$arParams["CUSTOM_CONFIG"]["CATALOG_SECTION_CODE"] = trim($arParams["CUSTOM_CONFIG"]["CATALOG_SECTION_CODE"]);
				
				if(
					$arParams["CUSTOM_CONFIG"]["CATEGORIES_IBLOCK_ID"] <= 0 
					|| $arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"] <= 0 
					|| !CModule::IncludeModule("iblock")
				)
					return false;
				
				$arResult["IBLOCK"] = array();
				$resIb = CIBlock::GetByID($arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"]);
				if($arFieldsIb = $resIb->GetNext())
				{
					$listPageUrl = str_replace(array("#SITE_DIR#", "#IBLOCK_CODE#"), array(SITE_DIR, $arFieldsIb["CODE"]), $arFieldsIb["LIST_PAGE_URL"]);
					$listPageUrl = str_replace("//", "/", $listPageUrl);
					
					$arResult["IBLOCK"] = array(
						"NAME" => $arFieldsIb["NAME"],
						"CODE" => $arFieldsIb["CODE"],
						"LIST_PAGE_URL" => $listPageUrl,
						"DESCRIPTION" => $arFieldsIb["DESCRIPTION"],
					);
					
					$arResult["IBLOCK"]["SEO"] = array();
					$rsEl = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams["CUSTOM_CONFIG"]["CATEGORIES_IBLOCK_ID"], "ACTIVE" => "Y", "PROPERTY_IBLOCKID" => $arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"]), false, array("nTopCount" => 1), array("IBLOCK_ID", "ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PREVIEW_TEXT", "DETAIL_TEXT", "PREVIEW_TEXT_TYPE", "DETAIL_TEXT_TYPE"));
					if($obEl = $rsEl->GetNextElement())
					{
						$arFieldsEl = $obEl->GetFields();
						$arFieldsEl["PROPERTIES"] = $obEl->GetProperties();
						
						$arResult["IBLOCK"]["SEO"]["H1"] = $arFieldsEl["PROPERTIES"]["SEOH1"]["VALUE"];
						$arResult["IBLOCK"]["SEO"]["TITLE"] = $arFieldsEl["PROPERTIES"]["SEOTITLE"]["VALUE"];
						$arResult["IBLOCK"]["SEO"]["DESCRIPTION"] = $arFieldsEl["PROPERTIES"]["SEODESCRIPTION"]["VALUE"];
						$arResult["IBLOCK"]["SEO"]["KEYWORDS"] = $arFieldsEl["PROPERTIES"]["SEOKEYWORDS"]["VALUE"];
						
						$arResult["IBLOCK"]["SEO"]["TEXT_TOP"] = $arFieldsEl["PROPERTIES"]["SEOTEXT_TOP"]["~VALUE"];
						$arResult["IBLOCK"]["SEO"]["TEXT_BOT"] = $arFieldsEl["PROPERTIES"]["SEOTEXT_BOT"]["~VALUE"];
						
						$arResult["IBLOCK"]["SEO"]["PHOTO_TOP"] = CFile::GetFileArray($arFieldsEl["PROPERTIES"]["SEOPHOTO_TOP"]["VALUE"]);
						$arResult["IBLOCK"]["SEO"]["PHOTO_BOT"] = CFile::GetFileArray($arFieldsEl["PROPERTIES"]["SEOPHOTO_BOT"]["VALUE"]);
						
						$arResult["IBLOCK"]["SEO"]["CASES"] = array();
						
						$arResult["IBLOCK"]["SEO"]["CASES"]["MULTIPLE"]["NOMINATIVE"] = !empty($arFieldsEl["PROPERTIES"]["NOMINATIVE"]["VALUE"]) ? $arFieldsEl["PROPERTIES"]["NOMINATIVE"]["VALUE"] : $arFieldsEl["NAME"];
						$arResult["IBLOCK"]["SEO"]["CASES"]["MULTIPLE"]["GENITIVE"] = !empty($arFieldsEl["PROPERTIES"]["GENITIVE"]["VALUE"]) ? $arFieldsEl["PROPERTIES"]["GENITIVE"]["VALUE"] : $arFieldsEl["NAME"];
						$arResult["IBLOCK"]["SEO"]["CASES"]["MULTIPLE"]["ACCUSATIF"] = !empty($arFieldsEl["PROPERTIES"]["ACCUSATIF"]["VALUE"]) ? $arFieldsEl["PROPERTIES"]["ACCUSATIF"]["VALUE"] : $arFieldsEl["NAME"];
						
						$arResult["IBLOCK"]["SEO"]["CASES"]["SINGLE"]["NOMINATIVE"] = !empty($arFieldsEl["PROPERTIES"]["NOMINATIVEONE"]["VALUE"]) ? $arFieldsEl["PROPERTIES"]["NOMINATIVEONE"]["VALUE"] : $arFieldsEl["NAME"];
						$arResult["IBLOCK"]["SEO"]["CASES"]["SINGLE"]["GENITIVE"] = !empty($arFieldsEl["PROPERTIES"]["GENITIVEONE"]["VALUE"]) ? $arFieldsEl["PROPERTIES"]["GENITIVEONE"]["VALUE"] : $arFieldsEl["NAME"];
						$arResult["IBLOCK"]["SEO"]["CASES"]["SINGLE"]["ACCUSATIF"] = !empty($arFieldsEl["PROPERTIES"]["ACCUSATIFONE"]["VALUE"]) ? $arFieldsEl["PROPERTIES"]["ACCUSATIFONE"]["VALUE"] : $arFieldsEl["NAME"];
						
						$arResult["IBLOCK"]["SEO"]["ARTICLES"] = $arFieldsEl["PROPERTIES"]["ARTICLES"]["VALUE"];
					}
					
					if(!empty($arParams["CUSTOM_CONFIG"]["CATALOG_SECTION_CODE"]))
					{
						$arResult["SECTION"] = array();
						$resSec = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "CODE" => $arParams["CUSTOM_CONFIG"]["CATALOG_SECTION_CODE"]), false, array("UF_*"));
						if($arFieldsSec = $resSec->GetNext())
						{
							$arResult["SECTION"] = array(
								"ID" => $arFieldsSec["ID"],
								"NAME" => $arFieldsSec["NAME"],
								"CODE" => $arFieldsSec["CODE"],
								"ACTIVE" => $arFieldsSec["ACTIVE"],
								"GLOBAL_ACTIVE" => $arFieldsSec["GLOBAL_ACTIVE"],
								"DEPTH_LEVEL" => $arFieldsSec["DEPTH_LEVEL"],
								"SECTION_PAGE_URL" => $arFieldsSec["SECTION_PAGE_URL"],
								"DESCRIPTION" => $arFieldsSec["DESCRIPTION"],
								
								"SEO" => array(
									"H1" => $arFieldsSec["UF_SEOH1"],
									"TITLE" => $arFieldsSec["UF_SEOTITLE"],
									"DESCRIPTION" => $arFieldsSec["UF_SEODESCRIPTION"],
									"KEYWORDS" => $arFieldsSec["UF_SEOKEYWORDS"],
									"TEXT_TOP" => $arFieldsSec["~UF_SEOTEXT_TOP"],
									"TEXT_BOT" => $arFieldsSec["~UF_SEOTEXT_BOT"],
									"PHOTO_TOP" => CFile::GetFileArray($arFieldsSec["UF_SEOPHOTO_TOP"]),
									"PHOTO_BOT" => CFile::GetFileArray($arFieldsSec["UF_SEOPHOTO_BOT"]),
									"TYPE" => $arFieldsSec["UF_TYPE"],
									"SNIPPET" => $arFieldsSec["UF_SEOSNIPPET"],
									"CASES" => array(
										"MULTIPLE" => array(
											"NOMINATIVE" => !empty($arFieldsSec["UF_NOMINATIVE"]) ? $arFieldsSec["UF_NOMINATIVE"] : $arFieldsSec["NAME"],
											"GENITIVE" => !empty($arFieldsSec["UF_GENITIVE"]) ? $arFieldsSec["UF_GENITIVE"] : $arFieldsSec["NAME"],
											"ACCUSATIF" => !empty($arFieldsSec["UF_ACCUSATIF"]) ? $arFieldsSec["UF_ACCUSATIF"] : $arFieldsSec["NAME"],
										),
										"SINGLE" => array(
											"NOMINATIVE" => !empty($arFieldsSec["UF_NOMINATIVEONE"]) ? $arFieldsSec["UF_NOMINATIVEONE"] : $arFieldsSec["NAME"],
											"GENITIVE" => !empty($arFieldsSec["UF_GENITIVEONE"]) ? $arFieldsSec["UF_GENITIVEONE"] : $arFieldsSec["NAME"],
											"ACCUSATIF" => !empty($arFieldsSec["UF_ACCUSATIFONE"]) ? $arFieldsSec["UF_ACCUSATIFONE"] : $arFieldsSec["NAME"],
										),
									),
									"ARTICLES" => $arFieldsSec["UF_ARTICLES"],
								),
							);
							
							$arResult["SECTION"]["PATH"] = array();
							$resPath = CIBlockSection::GetNavChain($arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"], $arFieldsSec["ID"]);
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
				}
			break;
			case "region_info":
				$arParams["IBLOCK_ID"] = intval($arParams["CUSTOM_CONFIG"]["IBLOCK_ID"]);
				$arParams["COMMON_IBLOCK_ID"] = intval($arParams["CUSTOM_CONFIG"]["COMMON_IBLOCK_ID"]);
				$arParams["REGION"] = trim($arParams["CUSTOM_CONFIG"]["REGION"]);
				
				if(
					$arParams["IBLOCK_ID"] <= 0 
					|| $arParams["COMMON_IBLOCK_ID"] <= 0 
					|| empty($arParams["REGION"]) 
					|| !CModule::IncludeModule("iblock")
				)
					return false;
				
				$arCommonParams = array();
				$resEl = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams["COMMON_IBLOCK_ID"], "ACTIVE" => "Y"), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID"));
				if($obEl = $resEl->GetNextElement())
					$arCommonParams = $obEl->GetProperties();
				
				$arRegionParams = array();
				$resEl = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "PROPERTY_CITYCODE" => $arParams["REGION"]), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID", "NAME"));
				if($obEl = $resEl->GetNextElement())
					$arRegionParams = $obEl->GetProperties();
				
				$arResult = array(
					"PRICE" => $arRegionParams["PRICE"]["VALUE"],
					"NAME" => $arRegionParams["CITY"]["VALUE"],
					"CODE" => $arRegionParams["CITYCODE"]["VALUE"],
					"PHONE" => $arRegionParams["PHONE"]["VALUE"],
					"PHONE_FREE" => !empty($arRegionParams["PHONE_FREE"]["VALUE"]) ? $arRegionParams["PHONE_FREE"]["VALUE"] : $arCommonParams["PHONE_FREE"]["VALUE"],
					"WORKTIME" => !empty($arRegionParams["WORKTIME"]["VALUE"]) ? $arRegionParams["WORKTIME"]["VALUE"] : $arCommonParams["WORKTIME"]["VALUE"],
					"EMAIL" => !empty($arRegionParams["EMAIL"]["VALUE"]) ? $arRegionParams["EMAIL"]["VALUE"] : $arCommonParams["EMAIL"]["VALUE"],
					"EMAIL_SK" => !empty($arRegionParams["EMAIL_SK"]["VALUE"]) ? $arRegionParams["EMAIL_SK"]["VALUE"] : $arCommonParams["EMAIL_SK"]["VALUE"],
					"ADDRESS" => $arRegionParams["ADDRESS"]["VALUE"],
					"PRICE_FREE_DELIVERY" => "",
					"PRICE_FREE_INSTALL" => "",
				);
				
				if(strlen($arRegionParams["PRICE_FREE_DELIVERY"]["VALUE"]) > 0)
					$arResult["PRICE_FREE_DELIVERY"] = floatval($arRegionParams["PRICE_FREE_DELIVERY"]["VALUE"]);
				elseif(strlen($arCommonParams["PRICE_FREE_DELIVERY"]["VALUE"]) > 0)
					$arResult["PRICE_FREE_DELIVERY"] = floatval($arCommonParams["PRICE_FREE_DELIVERY"]["VALUE"]);
				
				if(strlen($arRegionParams["PRICE_FREE_INSTALL"]["VALUE"]) > 0)
					$arResult["PRICE_FREE_INSTALL"] = floatval($arRegionParams["PRICE_FREE_INSTALL"]["VALUE"]);
				elseif(strlen($arCommonParams["PRICE_FREE_INSTALL"]["VALUE"]) > 0)
					$arResult["PRICE_FREE_INSTALL"] = floatval($arCommonParams["PRICE_FREE_INSTALL"]["VALUE"]);
				
			break;
			case "labels":
				$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
				
				if($arParams["IBLOCK_ID"] <= 0 || !CModule::IncludeModule("iblock"))
					return false;
				
				$arResult = array();
				$resEl = CIBlockElement::GetList(array("sort" => "asc"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "CODE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "SORT"));
				while($obEl = $resEl->GetNextElement())
				{
					$arFieldsEl = $obEl->GetFields();
					$arFieldsEl["TYPE"] = $obEl->GetProperty("TYPE");
					$arFieldsEl["CLASS"] = $obEl->GetProperty("CLASS");
					
					$arLabel = array(
						"NAME" => $arFieldsEl["NAME"],
						"CODE" => $arFieldsEl["CODE"],
						"IMG" => CFile::GetFileArray($arFieldsEl["PREVIEW_PICTURE"]),
						"IMG_DETAIL" => CFile::GetFileArray($arFieldsEl["DETAIL_PICTURE"]),
						"TYPE" => $arFieldsEl["TYPE"]["VALUE_XML_ID"],
						"CLASS" => $arFieldsEl["CLASS"]["VALUE"],
						"SORT" => $arFieldsEl["SORT"],
						"DESCRIPTION" => !empty($arFieldsEl["~PREVIEW_TEXT"]) ? $arFieldsEl["~PREVIEW_TEXT"] : "",
					);
					
					/*
					if($arLabel["TYPE"] == "overlay" && is_array($arLabel["IMG"]))
					{
						$bResize = false;
						if(!empty($GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_LABELS_OVERLAY"]["PARAMS"]["w"]) && $arLabel["IMG"]["WIDTH"] > $GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_LABELS_OVERLAY"]["PARAMS"]["w"])
							$bResize = true;
						if(!empty($GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_LABELS_OVERLAY"]["PARAMS"]["h"]) && $arLabel["IMG"]["HEIGHT"] > $GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_LABELS_OVERLAY"]["PARAMS"]["h"])
							$bResize = true;
						
						if($bResize)
							$arLabel["IMG"] = CMNTImg::GetImgArray($arLabel["IMG"]["SRC"], "CATALOG_LABELS_OVERLAY");
					}
					
					if(!empty($arLabel["IMG"]["SRC"]))
						$arLabel["IMG_SMALL"] = CMNTImg::GetImgArray($arLabel["IMG"]["SRC"], "CATALOG_LABELS_SMALL");
					*/
					
					$arResult[$arFieldsEl["ID"]] = $arLabel;
				}
			break;
			case "label_exceptions":
				$arParams["CATALOG_IBLOCK_TYPE"] = trim($arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_TYPE"]);
				$arParams["CATEGORIES_IBLOCK_ID"] = intval($arParams["CUSTOM_CONFIG"]["CATEGORIES_IBLOCK_ID"]);
				$arParams["LABELS_IBLOCK_ID"] = intval($arParams["CUSTOM_CONFIG"]["LABELS_IBLOCK_ID"]);
				
				if(
					$arParams["CATEGORIES_IBLOCK_ID"] <= 0 
					|| $arParams["LABELS_IBLOCK_ID"] <= 0 
					|| empty($arParams["CATALOG_IBLOCK_TYPE"]) 
					|| !CModule::IncludeModule("iblock")
				)
					return false;
				
				$arResult = array(
					"free-delivery" => array(),
					"free-install" => array(),
				);
				
				$arLabelsData = CMNTCached::GetLabels();
				
				// iblocks
				$resEl = CIBlockElement::GetList(
					array(),
					array(
						"IBLOCK_ID" => $arParams["CATEGORIES_IBLOCK_ID"],
						"ACTIVE" => "Y",
						"!PROPERTY_LABEL_EXCEPTIONS" => false,
					),
					false,
					false,
					array(
						"ID",
						"IBLOCK_ID",
						"PROPERTY_IBLOCKID",
					)
				);
				while($obEl = $resEl->GetNextElement())
				{
					$arFieldsEl = $obEl->GetFields();
					$arFieldsEl["LABEL_EXCEPTIONS"] = $obEl->GetProperty("LABEL_EXCEPTIONS");
					
					if(is_array($arFieldsEl["LABEL_EXCEPTIONS"]["VALUE"]))
					{
						foreach($arFieldsEl["LABEL_EXCEPTIONS"]["VALUE"] as $labelID)
							$arResult[$arLabelsData[$labelID]["CODE"]]["IBLOCK_IDS"][] = $arFieldsEl["PROPERTY_IBLOCKID_VALUE"];
					}
				}
				
				$resIb = CIBlock::GetList(array(), array("TYPE" => $arParams["CATALOG_IBLOCK_TYPE"], "ACTIVE" => "Y"), false);
				while($arFieldsIb = $resIb->Fetch())
				{
					// sections
					$resSec = CIBlockSection::GetList(
						array(),
						array("IBLOCK_ID" => $arFieldsIb["ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"),
						false,
						array(
							"UF_LABEL_EXCEPTIONS",
						)
					);
					while($arFieldsSec = $resSec->Fetch())
					{
						if(is_array($arFieldsSec["UF_LABEL_EXCEPTIONS"]))
						{
							foreach($arFieldsSec["UF_LABEL_EXCEPTIONS"] as $labelID)
								$arResult[$arLabelsData[$labelID]["CODE"]]["SECTION_IDS"][] = $arFieldsSec["ID"];
						}
					}
					
					// elements
					$resEl = CIBlockElement::GetList(
						array(),
						array(
							"IBLOCK_ID" => $arFieldsIb["ID"],
							"ACTIVE" => "Y",
							"!PROPERTY_LABEL_EXCEPTIONS" => false,
						),
						false,
						false,
						array(
							"ID",
							"IBLOCK_ID",
						)
					);
					while($obEl = $resEl->GetNextElement())
					{
						$arFieldsEl = $obEl->GetFields();
						$arFieldsEl["LABEL_EXCEPTIONS"] = $obEl->GetProperty("LABEL_EXCEPTIONS");
						
						if(is_array($arFieldsEl["LABEL_EXCEPTIONS"]["VALUE"]))
						{
							foreach($arFieldsEl["LABEL_EXCEPTIONS"]["VALUE"] as $labelID)
								$arResult[$arLabelsData[$labelID]["CODE"]]["ELEMENT_IDS"][] = $arFieldsEl["ID"];
						}
					}
				}
			break;
			case "features":
				$arParams["CUSTOM_CONFIG"]["IBLOCK_ID"] = intval($arParams["CUSTOM_CONFIG"]["IBLOCK_ID"]);
				$arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"] = intval($arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"]);
				
				if($arParams["CUSTOM_CONFIG"]["IBLOCK_ID"] <= 0 || !CModule::IncludeModule("iblock"))
					return false;
				
				$arFilter = array(
					"IBLOCK_ID" => $arParams["CUSTOM_CONFIG"]["IBLOCK_ID"],
					"ACTIVE" => "Y",
				);
				
				if($arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"] > 0)
					$arFilter["PROPERTY_CATEGORY_ID"] = $arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"];
				
				$arResult["ITEMS"] = array();
				$arResult["PROP_IDS"] = array();
				$arResult["PROP_VALUES"] = array();
				
				$resEl = CIBlockElement::GetList(array("sort"=>"asc"), $arFilter, false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "PREVIEW_PICTURE"));
				while($obEl = $resEl->GetNextElement())
				{
					$arFieldsEl = $obEl->GetFields();
					$arFieldsEl["PROPERTIES"] = $obEl->GetProperties();
					
					if(!empty($arFieldsEl["PROPERTIES"]["CATEGORY_ID"]["VALUE"]))
					{
						$arResult["ITEMS"][$arFieldsEl["ID"]] = array(
							"NAME" => $arFieldsEl["NAME"],
							"PREVIEW_TEXT" => $arFieldsEl["~PREVIEW_TEXT"],
							"IMG" => CFile::GetFileArray($arFieldsEl["PREVIEW_PICTURE"]),
							"CATALOG_IBLOCK_ID" => $arFieldsEl["PROPERTIES"]["CATEGORY_ID"]["VALUE"],
						);
						
						if(!empty($arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["VALUE"]))
						{
							if(!is_array($arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["VALUE"]))
							{
								$arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["VALUE"] = array($arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["VALUE"]);
								$arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["DESCRIPTION"] = array($arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["DESCRIPTION"]);
							}
							
							foreach($arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["VALUE"] as $k => $v)
							{
								//$description = $arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["DESCRIPTION"][$k];
								
								//if(strlen($description) <= 0)
								//	$arResult["PROP_IDS"][$v][] = $arFieldsEl["ID"];
								//else
								//	$arResult["PROP_VALUES"][$v][$description] = $arFieldsEl["ID"];
								
								if(strlen($arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["DESCRIPTION"][$k]) <= 0)
								{
									$arResult["PROP_IDS"][$v][] = $arFieldsEl["ID"];
								}
								else
								{
									$arDescription = explode("|", $arFieldsEl["PROPERTIES"]["PROP_ID_VALUE"]["~DESCRIPTION"][$k]);
									
									foreach($arDescription as $description)
										$arResult["PROP_VALUES"][$v][$description] = $arFieldsEl["ID"];
								}
							}
						}
					}
				}
			break;
			case "navigation_info":
				$arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_TYPE"] = trim($arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_TYPE"]);
				$arParams["CUSTOM_CONFIG"]["CATALOG_MENU_IBLOCK_ID"] = intval($arParams["CUSTOM_CONFIG"]["CATALOG_MENU_IBLOCK_ID"]);
				
				if(
					$arParams["CUSTOM_CONFIG"]["CATALOG_MENU_IBLOCK_ID"] <= 0 
					|| strlen($arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_TYPE"]) <= 0 
					|| !CModule::IncludeModule("iblock")
				) {
					return false;
				}
				
				$arFilter = array(
					"IBLOCK_ID" => $arParams["CUSTOM_CONFIG"]["IBLOCK_ID"],
					"ACTIVE" => "Y",
				);
				
				if($arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"] > 0)
					$arFilter["PROPERTY_CATEGORY_ID"] = $arParams["CUSTOM_CONFIG"]["CATALOG_IBLOCK_ID"];
				
				$arResult = array();
				
				$arIblocks = array();
				$arSections = array();
				$resIB = CIBlock::GetList(array("sort" => "asc"), array("TYPE" => $arParams["CATALOG_IBLOCK_TYPE"], "SITE_ID" => SITE_ID, "ACTIVE" => "Y", "CNT_ACTIVE" => "Y"), true);
				while($arFieldsIB = $resIB->GetNext())
				{
					if($arFieldsIB["ELEMENT_CNT"] > 0)
					{
						$arIblocks[$arFieldsIB["ID"]] = array(
							"NAME" => $arFieldsIB["NAME"],
							"CODE" => $arFieldsIB["CODE"],
						);
						
						$resSec = CIBlockSection::GetList(array("sort" => "asc"), array("IBLOCK_ID" => $arFieldsIB["ID"], "ACTIVE" => "Y", "DEPTH_LEVEL" => 1, "CNT_ACTIVE" => "Y"), true);
						while($arFieldsSec = $resSec->GetNext())
						{
							if($arFieldsSec["ELEMENT_CNT"] > 0)
							{
								$arSections[$arFieldsSec["ID"]] = array(
									"IBLOCK_ID" => $arFieldsIB["ID"],
									"NAME" => $arFieldsSec["NAME"],
									"CODE" => $arFieldsSec["CODE"],
								);
							}
						}
					}
				}
				
				$arMenuItems = array();
				$resSec = CIBlockSection::GetList(array("sort" => "asc"), array("IBLOCK_ID" => $arParams["CATALOG_MENU_IBLOCK_ID"], "ACTIVE" => "Y", "DEPTH_LEVEL" => 1), false);
				while($arFieldsSec = $resSec->GetNext())
				{
					$arMenuItems[$arFieldsSec["ID"]] = $arFieldsSec;
					$arMenuItems[$arFieldsSec["ID"]]["TYPE"] = "SECTION";
				}
				
				$resEl = CIBlockElement::GetList(array("sort" => "asc"), array("IBLOCK_ID" => $arParams["CATALOG_MENU_IBLOCK_ID"], "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "IBLOCK_SECTION_ID", "CODE"));
				while($obEl = $resEl->GetNextElement())
				{
					$arFieldsEl = $obEl->GetFields();
					$arFieldsEl["PROPERTIES"] = $obEl->GetProperties();
					$arFieldsEl["TYPE"] = "ELEMENT";
					
					if(!empty($arFieldsEl["IBLOCK_SECTION_ID"]))
						$arMenuItems[$arFieldsEl["IBLOCK_SECTION_ID"]]["ITEMS"][] = $arFieldsEl;
					else
						$arMenuItems["EL".$arFieldsEl["ID"]] = $arFieldsEl;
				}
				
				usort($arMenuItems, array("CMNTGeneral", "SortArray"));
				
				//echo "<pre>".print_r($arMenuItems, true)."</pre>";
				
				$arResult["ITEMS"] = array();
				$arResult["BY_SECTION_ID"] = array();
				$arResult["BY_IBLOCK_ID"] = array();
				$arResult["BY_URI"] = array();
				
				foreach($arMenuItems as $arMenuItem)
				{
					if($arMenuItem["TYPE"] == "ELEMENT")
					{
						$iblockSectionID = $arMenuItem["PROPERTIES"]["IBLOCK_SECTION_ID"]["VALUE"];
						$iblockID = $arMenuItem["PROPERTIES"]["IBLOCK_ID"]["VALUE"];
						
						if(!empty($iblockSectionID))
						{
							if(!empty($arSections[$iblockSectionID]))
							{
								$arResult["ITEMS"][] = array(
									"NAME" => $arMenuItem["NAME"],
									"URL" => $GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"].$arMenuItem["CODE"]."/",
									"CODE" => $arSections[$iblockSectionID]["CODE"],
									"TYPE" => "SECTION",
								);
								$arResult["BY_SECTION_ID"][$iblockSectionID] = array(
									"URL" => $GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"].$arMenuItem["CODE"]."/",
									"PATH" => array(
										0 => array(
											"URL" => "",
											"NAME" => "",
										),
									),
								);
							}
						}
						elseif(!empty($iblockID))
						{
							if(!empty($arIblocks[$iblockID]))
							{
								$arResult["ITEMS"][] = array(
									"NAME" => $arMenuItem["NAME"],
									"URL" => $arIblocks[$iblockID]["URL"],
									"CODE" => $arIblocks[$iblockID]["CODE"],
									"TYPE" => "IBLOCK",
								);
							}
						}
					}
					
					if($arMenuItem["TYPE"] == "SECTION")
					{
						$arResultItem = array(
							"NAME" => $arMenuItem["NAME"],
							"URL" => $GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"]."group/".$arMenuItem["CODE"]."/",
							"CODE" => $arMenuItem["CODE"],
							"TYPE" => "GROUP",
							"ITEMS" => array(),
						);
						
						foreach($arMenuItem["ITEMS"] as $arSubItem)
						{
							$iblockSectionID = $arSubItem["PROPERTIES"]["IBLOCK_SECTION_ID"]["VALUE"];
							$iblockID = $arSubItem["PROPERTIES"]["IBLOCK_ID"]["VALUE"];
							
							if(!empty($iblockSectionID))
							{
								if(!empty($arSections[$iblockSectionID]))
								{
									$arResultItem["ITEMS"][] = array(
										"NAME" => $arSubItem["NAME"],
										"URL" => $arSections[$iblockSectionID]["URL"],
										"CODE" => $arSections[$iblockSectionID]["CODE"],
										"TYPE" => "SECTION",
									);
								}
							}
							elseif(!empty($iblockID))
							{
								if(!empty($arIblocks[$iblockID]))
								{
									$arResultItem["ITEMS"][] = array(
										"NAME" => $arSubItem["NAME"],
										"URL" => $arIblocks[$iblockID]["URL"],
										"CODE" => $arIblocks[$iblockID]["CODE"],
										"TYPE" => "IBLOCK",
									);
								}
							}
						}
						
						$arResult["ITEMS"][] = $arResultItem;
					}
				}
				
			break;
			case "catalog_groups":
				$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
				
				if($arParams["IBLOCK_ID"] <= 0 || !CModule::IncludeModule("iblock"))
					return false;
				
				$arResult = array();
				$resEl = CIBlockElement::GetList(array("sort" => "asc"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "CODE"));
				while($arFieldsEl = $resEl->GetNext())
				{
					$arResult[$arFieldsEl["ID"]] = array(
						"NAME" => $arFieldsEl["NAME"],
						"CODE" => $arFieldsEl["CODE"],
						"URL" => $GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"]."group/".$arFieldsEl["CODE"]."/",
					);
				}
			break;
			case "catalog_collections":
				$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
				
				if($arParams["IBLOCK_ID"] <= 0 || !CModule::IncludeModule("iblock"))
					return false;
				
				$arResult = array();
				$resEl = CIBlockElement::GetList(array("sort" => "asc"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "CODE"));
				while($arFieldsEl = $resEl->GetNext())
				{
					$arResult[$arFieldsEl["ID"]] = array(
						"NAME" => $arFieldsEl["NAME"],
						"CODE" => $arFieldsEl["CODE"],
						"URL" => $GLOBALS["SITE_CONFIG"]["CATALOG"]["SEF_FOLDER"]."collection/".$arFieldsEl["CODE"]."/",
					);
				}
			break;
			case "sku":
				if(!CModule::IncludeModule("iblock"))
					return false;
				
				$arResult = array();
				$resEl = CIBlockElement::GetList(
					array(),
					array("IBLOCK_ID" => $arParams["CUSTOM_CONFIG"]["IBLOCK_ID"], "ACTIVE" => "Y", "CODE" => $arParams["CUSTOM_CONFIG"]["ELEMENT_CODE"]),
					false,
					array("nTopCount" => 1),
					array("ID", "IBLOCK_ID")
				);
				if($arFieldsEl = $resEl->Fetch())
				{
					$resElSku = CIBlockElement::GetList(
						array(
							"propertysort_".$arParams["CUSTOM_CONFIG"]["CATALOG_STORE_CODE"] => "ASC",
							"catalog_PRICE_".$arParams["CUSTOM_CONFIG"]["CATALOG_GROUP_ID"] => "ASC",
							"SORT" => "ASC",
							"ID" => "ASC"
						),
						array(
							"IBLOCK_ID" => $arParams["CUSTOM_CONFIG"]["SKU_IBLOCK_ID"],
							"ACTIVE" => "Y",
							"PROPERTY_CML2_LINK" => $arFieldsEl["ID"],
						),
						false,
						array("nTopCount" => $arParams["CUSTOM_CONFIG"]["SKU_LIMIT"]),
						array(
							"ID",
							"IBLOCK_ID",
							"PROPERTY_COLOR",
							"PROPERTY_".$arParams["CUSTOM_CONFIG"]["CATALOG_STORE_CODE"],
							"PROPERTY_ARTIKUL",
						)
					);
					while($arFieldsElSku = $resElSku->Fetch())
					{
						$arResult[$arFieldsElSku["ID"]] = array(
							"COLOR" => $arFieldsElSku["PROPERTY_COLOR_VALUE"],
							"STORE" => $arFieldsElSku["PROPERTY_".$arParams["CUSTOM_CONFIG"]["CATALOG_STORE_CODE"]."_VALUE"],
							"ARTIKUL" => $arFieldsElSku["PROPERTY_ARTIKUL_VALUE"],
						);
					}
				}
			break;
			case "colors":
				$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
				
				if($arParams["IBLOCK_ID"] <= 0 || !CModule::IncludeModule("iblock"))
					return false;
				
				$arResult = array();
				$resEl = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_RGB"));
				while($arFieldsEl = $resEl->GetNext())
				{
					if(!empty($arFieldsEl["PREVIEW_PICTURE"]))
						$arFieldsEl["PREVIEW_PICTURE"] = CFile::GetFileArray($arFieldsEl["PREVIEW_PICTURE"]);
					
					$arPicture = "";
					if(is_array($arFieldsEl["PREVIEW_PICTURE"]))
					{
						$arPicture = array(
							"ID" => $arFieldsEl["PREVIEW_PICTURE"]["ID"],
							"SRC" => $arFieldsEl["PREVIEW_PICTURE"]["SRC"],
							"WIDTH" => $arFieldsEl["PREVIEW_PICTURE"]["WIDTH"],
							"HEIGHT" => $arFieldsEl["PREVIEW_PICTURE"]["HEIGHT"],
						);
					}
					
					$arResult[$arFieldsEl["ID"]] = array(
						"NAME" => $arFieldsEl["NAME"],
						"IMG" => $arPicture,
						"RGB" => $arFieldsEl["PROPERTY_RGB_VALUE"],
					);
				}
			break;
		}
		
		return (is_array($arResult) && !empty($arResult)) ? $arResult : false;
	}
	
	public static function GetFeatures($catalogIblockID)
	{
		return CMNTCached::GetData(array(
			"TYPE" => "features",
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
			"CUSTOM_CONFIG" => array(
				"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["CONTENT"]["features"],
				"CATALOG_IBLOCK_ID" => $catalogIblockID,
			),
		));
	}
	
	public static function GetLabels()
	{
		return CMNTCached::GetData(array(
			"TYPE" => "labels",
			"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["DIRECTORY"]["labels"],
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
		));
	}
	
	public static function GetLabelExceptions()
	{
		return CMNTCached::GetData(array(
			"TYPE" => "label_exceptions",
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
			"CUSTOM_CONFIG" => array(
				"CATALOG_IBLOCK_TYPE" => $GLOBALS["SITE_CONFIG"]["IBLOCK_TYPES"]["CATALOG"],
				"CATEGORIES_IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["CONTENT"]["categories"],
				"LABELS_IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["CONTENT"]["labels"],
			),
		));
	}
	
	public static function GetBrands()
	{
		return CMNTCached::GetData(array(
			"TYPE" => "default",
			"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["DIRECTORY"]["brands"],
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
		));
	}
	
	public static function GetCountries()
	{
		return CMNTCached::GetData(array(
			"TYPE" => "default",
			"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["DIRECTORY"]["countries"],
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
		));
	}
	
	public static function GetRegionInfo()
	{
		return CMNTCached::GetData(array(
			"TYPE" => "region_info",
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
			"CUSTOM_CONFIG" => array(
				"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["CONTENT"]["contacts"],
				"COMMON_IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["CONTENT"]["common"],
				"REGION" => $GLOBALS["SITE_CONFIG"]["SESSION_PARAMS"]["REGION"],
			),
		));
	}
	
	public static function GetNavigationInfo()
	{
		return CMNTCached::GetData(array(
			"TYPE" => "navigation_info",
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
			"CUSTOM_CONFIG" => array(
				"CATALOG_IBLOCK_TYPE" => $GLOBALS["SITE_CONFIG"]["IBLOCK_TYPES"]["CATALOG"],
				"CATALOG_MENU_IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["CONTENT"]["catalogmenu"],
			),
		));
	}
	
	public static function GetCatalogGroups()
	{
		return CMNTCached::GetData(array(
			"TYPE" => "catalog_groups",
			"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["DIRECTORY"]["groups"],
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
		));
	}
	
	public static function GetCatalogCollections()
	{
		return CMNTCached::GetData(array(
			"TYPE" => "catalog_collections",
			"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["DIRECTORY"]["collections"],
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
		));
	}
	
	public static function GetColors()
	{
		return CMNTCached::GetData(array(
			"TYPE" => "colors",
			"IBLOCK_ID" => $GLOBALS["SITE_CONFIG"]["IBLOCKS_BY_CODE"]["DIRECTORY"]["colors"],
			"CACHE_TYPE" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TYPE"] : "A",
			"CACHE_TIME" => !empty($GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"]) ? $GLOBALS["SITE_CONFIG"]["CACHE"]["CACHED_DATA_CACHE_TIME"] : "36000000",
		));
	}
}
?>