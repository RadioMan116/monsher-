<?
//$_SERVER["DOCUMENT_ROOT"] = "/var/www/u0065058/data/www/kps.asko-shop.ru/";

set_time_limit(360);
ini_set('max_execution_time', '360');


require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//$APPLICATION->RestartBuffer();


CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
$el = new CIBlockElement;
$bs = new CIBlockSection;
$ib = new CIBlock;
$ibp = new CIBlockProperty;







die();



				$arFilter = Array(
						'TYPE'=>'mn_catalog', 						
						'ACTIVE'=>'Y', 		
						//'IBLOCK_ID'=>315, 		
				);
		
				pre($arFilter);
		
				$b_res = CIBlock::GetList(
					Array("SORT" => "ASC"), 
					$arFilter, 
					false
				);
				while($arBlock = $b_res->Fetch())
				{	
			
			//pre($arBlock["ID"]);
			
			
						$arFilter2 = array(
						  'IBLOCK_ID' => $arBlock["ID"],  
						  'CODE' => 'VIDEO'
						);

						$rsProperty = CIBlockProperty::GetList(
						 array(),
						 $arFilter2
						);

						if($element = $rsProperty->Fetch())
						{
							
							//pre($element);
							//CIBlockProperty::Delete($element["ID"]);
							
							
							
							
							$arFields = Array(
							  //"LINK_IBLOCK_ID" => 47,	
							  //"NAME" => "Sale | Акционная цена только для Москвы",
							  //"ACTIVE" => "Y",
							  //"SORT" => 14,
							  //"CODE" => "S_SALE_ONLY_MSK",							  									
							);	
							
							
							
							//pre($arFields);
							//$PropID = $ibp->Update($arFields);
			
							
							
						}
			
			
						//continue;
						
					
							$arFields = Array(
							  "NAME" => "Видео",
							  "ACTIVE" => "Y",
							  "SORT" => 103,
							  "CODE" => "VIDEOS",
							  "PROPERTY_TYPE" => "E",					  
							  "IBLOCK_ID" => $arBlock["ID"],							  									
							  "LINK_IBLOCK_ID" => 80,							  									
							  "MULTIPLE" => "Y",							  									
							  "MULTIPLE_CNT" => 1,							  									
							);	
						
						
							
						/*
							$arFields = Array(
							  "NAME" => "Размер комиссии | Санкт-Петербург",
							  "ACTIVE" => "Y",
							  "SORT" => 8,
							  "CODE" => "COMMISSION_SPB",
							  "PROPERTY_TYPE" => "N",					  
							  "SEARCHABLE" => "N",					  
							  "IBLOCK_ID" => $arBlock["ID"],					
							  "MULTIPLE" => "N",	  									
							);	
						*/	
						
							/*
							$arFields = Array(
							  "NAME" => "Sale | Акционная цена только для Москвы",
							  "ACTIVE" => "Y",
							  "SORT" => 14,
							  "CODE" => "S_SALE_ONLY_MSK",
							  "PROPERTY_TYPE" => "S",
								"USER_TYPE" => "SASDCheckbox",
								"USER_TYPE_SETTINGS" => array(
									"VIEW" => array(
										"N" => "N",
										"Y" => "Y",
									)
								),
							  "IBLOCK_ID" => $arBlock["ID"],							  									
							);	
						*/
						
						/*
						$arFields = Array(
						  "NAME" => "Склад СПб",
						  "ACTIVE" => "Y",
						  "SORT" => "61",
						  "CODE" => "SPB",
						  "PROPERTY_TYPE" => "L",
						  "IBLOCK_ID" => $arBlock["ID"]
						);

						$arFields["VALUES"][0] = Array(
						  "VALUE" => "В наличии",
						  "XML_ID" => "t1",
						  "DEF" => "Y",
						  "SORT" => "100"
						);

						$arFields["VALUES"][1] = Array(
						  "VALUE" => "Под заказ",
						  "XML_ID" => "t2",
						  "DEF" => "N",
						  "SORT" => "200"
						);

						$arFields["VALUES"][2] = Array(
						  "VALUE" => "Нет в наличии",
						  "XML_ID" => "t3",
						  "DEF" => "N",
						  "SORT" => "300"
						);

						$arFields["VALUES"][3] = Array(
						  "VALUE" => "Снят с производства",
						  "XML_ID" => "t4",
						  "DEF" => "N",
						  "SORT" => "400"
						);

					*/
						
						
						
							pre($arFields);
							//$PropID = $ibp->Add($arFields);
			
							continue;
			
			
						$arFilter3 = array(							
							"IBLOCK_ID" => $arBlock["ID"],						
							"!PROPERTY_COUNTRY" => false						
						);	
						
						//PRE($arFilter3);

						$db_res = CIBlockElement::GetList(
							array(),
							$arFilter3,
							false,
							false,
							array(
								"ID",
								"NAME",
								"IBLOCK_ID",							
								"CODE",																	
								"PROPERTY_COUNTRY",																	
							)
						);
						//while ($arElement = $db_res->Fetch())
						{
							
							//PRE($arElement["ID"]);
							//PRE($arElement["PROPERTY_COUNTRY_VALUE"]);
							
							//IF($arElement["PROPERTY_COUNTRY_VALUE"]) 
							{
								
								
								
								//$SEARCH_INDEX = $arElement["PROPERTY_SEARCH_INDEX_VALUE"].' '.str_replace(' ','', $arElement["PROPERTY_MODEL_VALUE"]).' '.str_replace(' ','', preg_replace("|[^\d\w ]+|i","",$arElement["PROPERTY_MODEL_VALUE"]));
								
								//ECHO '<BR/>'.$SEARCH_INDEX;
								
								
								
									//IF($SEARCH_INDEX) 
									{
										/*CIBlockElement::SetPropertyValuesEx(
															$arElement["ID"],
															$arElement["IBLOCK_ID"],
															array(
																//'SEARCH_INDEX' => $SEARCH_INDEX,
																'COUNTRY' => "",
															)	
										);*/
									}
								
							}
							
								
						}
			
						//CONTINUE;
						
						$arFilter2 = array(
						  'IBLOCK_ID' => $arBlock["ID"],  
						  'CODE' => 'ANALOG',
						);
						
						//pre($arFilter2);

						$rsProperty = CIBlockProperty::GetList(
						 array(),
						 $arFilter2
						);
						//если не находим свойство то добавляем его
						if(!$element = $rsProperty->Fetch())
						{
							
							$arFields = Array(
							  "NAME" => "Похожие товары",
							  "ACTIVE" => "Y",
							  "SORT" => '100',
							  "CODE" => "ANALOG",
							  "PROPERTY_TYPE" => "E",					  
							  //"PROPERTY_TYPE" => "N",					  
							  "IBLOCK_ID" => $arBlock["ID"],					
							  "MULTIPLE" => "Y",
							  "MULTIPLE_CNT" => 1,
							  "LINK_IBLOCK_ID" => $arBlock["ID"],
							);	
							
							pre($arFields);
							$PropID = $ibp->Add($arFields);
						}
						else
						{
							
							//pre($element);
							
							$arFields = array(
								//"PROPERTY_TYPE" => "E",
								//"USER_TYPE" => "EList",
								/*"USER_TYPE" => "SASDCheckbox",
								"USER_TYPE_SETTINGS" => array(
									"VIEW" => array(
										"N" => "N",
										"Y" => "Y",
									)
								),*/
								//"COL_COUNT" => 10,
								//"SEARCHABLE" => "Y",
								//"MULTIPLE" => "Y",
								//"IS_REQUIRED" => "Y",
								//"LIST_TYPE" => "L",
								//"SORT" => 39,
								"NAME" => "Похожие товары",
								//"LINK_IBLOCK_ID" => 3,
								//"CODE" => 'SEARCH_WORD'
							);
							
							//PRE($arFields);
							
							//$PropID = $ibp->Update($element["ID"], $arFields);
							
							
							//CIBlockProperty::Delete($element["ID"]);
							//pre($element);
							
						}	
						
						continue;
						
						/*
						$arFilter2 = array(
						  'IBLOCK_ID' => $arBlock["ID"],  
						  'CODE' => 'S_HIT'
						);

						$rsProperty = CIBlockProperty::GetList(
						 array(),
						 $arFilter2
						);

						WHILE($element = $rsProperty->Fetch())
						{
							//CIBlockProperty::Delete($element["ID"]);
							//pre($element);
							
						}	
						
							*/
			
			/*
			

							$arFields = Array(
								  "NAME" => "SEO тексты",
								  "ACTIVE" => "Y",
								  "SORT" => 34,
								  "CODE" => "SEO_TEXT_ID",
								  "PROPERTY_TYPE" => "E",
								  "MULTIPLE" => "Y",
								  "LINK_IBLOCK_ID" => 242,
								  "MULTIPLE_CNT" => 2,
								  "IBLOCK_ID" => $arBlock["ID"],	
								 
								 
							);	
							*/
							//pre($arFields);
							//$PropID = $ibp->Add($arFields);
							/*
							$arFields = Array(
								  "NAME" => "Хит продаж",
								  "ACTIVE" => "Y",
								  "SORT" => 2,
								  "CODE" => "S_HIT",
								  "PROPERTY_TYPE" => "S",
								  "MULTIPLE" => "N",
								  "IBLOCK_ID" => $arBlock["ID"],	
								  "USER_TYPE" => "SASDCheckbox",
								  "USER_TYPE_SETTINGS" => Array(
										"VIEW" => Array(
											"N" => "N",
											"Y" => "Y"
										)
								  )
							);	
							//pre($arFields);
							//$PropID = $ibp->Add($arFields);
							*/
							
				}
	
	
	

include_once ($_SERVER['DOCUMENT_ROOT'] .'/bitrix/modules/main/include/epilog_after.php');
?>