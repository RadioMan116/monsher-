<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
//$APPLICATION->RestartBuffer();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
$el = new CIBlockElement;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");


use Bitrix\Main;
$USER = new CUser;
$UID = $USER->GetID();

$arResult = array(
	"SUCCESS" => "N",	
	"CAPTCHA" => "Y",
	"MESSAGE" => "",
	"RELOAD" => "N",	
	"CLEARPOLS" => "Y",
);

if(!$_COOKIE["Bicontent"])  $_COOKIE["Bicontent"] = 'None';
switch ($request->get('action')) {
	
		case "autocomplete_METRO":		
		
			$str = $request->offsetGet('str');		
			$arAutoList = array();
			if($str!='') {
			
				$arFilter = array(					
					"IBLOCK_ID" => 373,					
					"?NAME" => $str					
				);			
				
				$res = CIBlockElement::GetList(
					Array("SORT" => "ASC", "NAME" => "ASC"), 
					$arFilter, 
					false, 
					array("nTopCount" => 5),
					array(
						"ID",
						"NAME",
					)
				);
				while($ob = $res->fetch()) {
					$arAutoList[] = $ob["NAME"];
				}
				$arAutoList = array_unique($arAutoList);
			}
			$arResult["AUTO_LIST"] = $arAutoList;
		
		
		break;
		case "autocomplete_CITY":
		
			$str = $request->offsetGet('str');
			$arAutoList = array();
			if($str!='') {
			
				$arFilter = array(					
					"CITY_LID" => LANGUAGE_ID,
					"%CITY_NAME" => $str
				);
			
			
				$db_vars = CSaleLocation::GetList(
					array(
							"SORT" => "ASC",
							"COUNTRY_NAME_LANG" => "ASC",
							"CITY_NAME_LANG" => "ASC"
					),
					$arFilter,
					false,
					array("nTopCount" => 10),
					array()
				);
				while ($location = $db_vars->Fetch()) {		
					$arAutoList[] = $location["CITY_NAME"];
				}
				
				$arAutoList = array_unique($arAutoList);
					
			}
			$arResult["AUTO_LIST"] = $arAutoList;
		
		break;
		case "changeSectionsFavorites":
		
			$_SESSION["F_SECTIONS"] = $request->get('F_SECTIONS');
			/*
			if(!is_array($request->get('val'))
			$val = 
			setcookie("ART_TYPE", $request->get('PAGE_KOL'), time() + 15552000, "/");
			*/
			//$arResult["data"] = $request->get('ART_TYPE');
			$arResult["SUCCESS"] = 'Y';
		
		break;
		case "changeCity":
		
		
			setcookie("REGION", $request->offsetGet('REGION'), time() + 15552000, "/");
			
			$arResult["SUCCESS"] = 'Y';
		
		break;
		case "changeTypeArt":
		
		
			
			
			$_SESSION["ART_TYPE"] = $request->get('ART_TYPE');
			/*
			if(!is_array($request->get('val'))
			$val = 
			setcookie("ART_TYPE", $request->get('PAGE_KOL'), time() + 15552000, "/");
			*/
			//$arResult["data"] = $request->get('ART_TYPE');
			$arResult["SUCCESS"] = 'Y';
		break;
		case "changeView":
		
			$_SESSION["CATALOG_VIEW"] = $request->get('val');
			
			$arResult["SUCCESS"] = 'Y';
		break;
		case "changePage":
		
			setcookie("PAGE_KOL", $request->get('PAGE_KOL'), time() + 15552000, "/");
			
				//BXClearCache(true, "/s1/bitrix/catalog.section/");
				//BXClearCache(true, "/s1/inter.olsc/catalog.section/");		
			
			$arResult["SUCCESS"] = 'Y';
			$arResult["RELOAD"] = 'Y';
		break;	
		case "changeSort":

			//$_SESSION["SORT"] = $request->get('SORT');
			setcookie("K_SORT", $request->get('K_SORT'), time() + 15552000, "/");
			setcookie("K_ORDER", $request->get('K_ORDER'), time() + 15552000, "/");
			
			$arResult["SUCCESS"] = 'Y';
			$arResult["RELOAD"] = 'Y';
		break;
		case "commentsAdd":
		
			$title = 'Спасибо!';
			$html_text = 'Ваш комментарий отправлен.';			
							
				$arEventFields = $PROP = $F = $request->get('F');
				UNSET($PROP["TXT"]);
			
				$arFields = Array(
					"ACTIVE_FROM" => date("d.m.Y"),
					"IBLOCK_ID" => CStatic::$commentIdBlock,
					"IBLOCK_SECTION_ID" => CStatic::$commentIdSec,
					"NAME" => $_SERVER["SERVER_NAME"]." | Новый комментарий от ".date("d.m.Y H:i"),
					"ACTIVE" => "N",
					"PREVIEW_TEXT_TYPE" => 'text',
					"PREVIEW_TEXT" => $F["TXT"],
					"PROPERTY_VALUES" => $PROP,
				);
				
				$ID = $el->Add($arFields);
				
				//$arResult["PARAMS"] = $arFields;
				
				
				
				
				$arEventFields["ADMIN_URL"] = 'https://'.$_SERVER["SERVER_NAME"].'/iblock_element_edit.php?IBLOCK_ID='.CStatic::$commentIdBlock.'&type=backform&ID='.$ID.'&lang=ru&find_section_section='.CStatic::$commentIdSec.'&WF=Y';
				

				CEvent::Send("BACK_FORM",SITE_ID, $arEventFields, false, 83);
				
				$arEventFields = false;
				
			
				$arResult["SUCCESS"] = 'Y';		

		break;
		case "reviewsAdd":
		
			$title = 'Спасибо!';
			$html_text = 'Ваш отзыв отправлен.';			
							
				$arEventFields = $PROP = $F = $request->get('F');
				UNSET($PROP["TXT"]);
			
				$arFields = Array(
					"ACTIVE_FROM" => date("d.m.Y"),
					"IBLOCK_ID" => CStatic::$ReviewsIdBlock,
					"IBLOCK_SECTION_ID" => CStatic::$ReviewsIdSec,
					"NAME" => $_SERVER["SERVER_NAME"]." | Новое отзыв от ".date("d.m.Y H:i"),
					"ACTIVE" => "N",
					"PREVIEW_TEXT_TYPE" => 'text',
					"PREVIEW_TEXT" => $F["TXT"],
					"PROPERTY_VALUES" => $PROP,
				);
				
				$ID = $el->Add($arFields);
				
				//$arResult["PARAMS"] = $arFields;
				
				
				
				
				$arEventFields["ADMIN_URL"] = 'https://'.$_SERVER["SERVER_NAME"].'/iblock_element_edit.php?IBLOCK_ID='.CStatic::$ReviewsIdBlock.'&type=backform&ID='.$ID.'&lang=ru&find_section_section='.CStatic::$ReviewsIdSec.'&WF=Y';
				

				CEvent::Send("BACK_FORM",SITE_ID, $arEventFields, false, 84);
				
				$arEventFields = false;
				
			
				$arResult["SUCCESS"] = 'Y';		

		break;
		case "reviewsStoreAdd":
		
			$title = 'Спасибо!';
			$html_text = 'Ваш отзыв отправлен.';			
							
				$arEventFields = $PROP = $F = $request->get('F');
				UNSET($PROP["TXT"]);
			
				$arFields = Array(
					"ACTIVE_FROM" => date("d.m.Y"),
					"IBLOCK_ID" => CStatic::$ReviewsStoreIdBlock,
					"IBLOCK_SECTION_ID" => CStatic::$ReviewsStoreIdSec,
					"NAME" => $_SERVER["SERVER_NAME"]." | Новое отзыв от ".date("d.m.Y H:i"),
					"ACTIVE" => "N",
					"PREVIEW_TEXT_TYPE" => 'text',
					"PREVIEW_TEXT" => $F["TXT"],
					"PROPERTY_VALUES" => $PROP,
				);
				
				$ID = $el->Add($arFields);
				
				//$arResult["PARAMS"] = $arFields;
				
				
				
				
				$arEventFields["ADMIN_URL"] = 'https://'.$_SERVER["SERVER_NAME"].'/iblock_element_edit.php?IBLOCK_ID='.CStatic::$ReviewsStoreIdBlock.'&type=backform&ID='.$ID.'&lang=ru&find_section_section='.CStatic::$ReviewsStoreIdSec.'&WF=Y';
				

				CEvent::Send("BACK_FORM",SITE_ID, $arEventFields, false, 84);
				
				$arEventFields = false;
				
			
				$arResult["SUCCESS"] = 'Y';		

		break;
		case "callbackAdd":
		
			$title = 'Заказать звонок';
			$html_text = 'Спасибо! Ваш запрос отправлен.<br/> Менеджер свяжется с вами в ближайшее время.';					
							
				
				$arEventFields = $request->get('F');
				CEvent::Send("BACK_FORM",SITE_ID, $arEventFields, false, 90);	
				
						if(!$_COOKIE["Bicontent"])  $_COOKIE["Bicontent"] = 'None';
						/*
						$sent = orderPhoneT50(array(
						   "FIO" => $arEventFields["NAME"],
						   "PHONE" => $arEventFields["PHONE"],
						   "FROM_URL" => $arEventFields["PAGE"],
						   "BICONTENT" => $_COOKIE["Bicontent"],
						));
				*/
				$arResult["SUCCESS"] = 'Y';

		break;
		case "claimAdd":
		
			$title = 'Возврат товара';
			$html_text = 'Спасибо! Ваш запрос отправлен.<br/> Мы ответим Вам в ближайшее время.';
					
				$arEventFields = $PROP = $F = $request->get('F');
				unset($PROP["TXT"]);
				
				if($_FILES["files"]) {					
					//переделываем массив , т.к. множественная отправка файлов
					$arFiles = array();
					foreach($_FILES["files"] as $key=>$arFs) {
						if($key == 'error') continue;
						
						foreach($arFs as $k=>$arFs2) {	
							if($arFs2) $arFiles[$k][$key] = $arFs2;
						}
					}
					
					$arResult["arFiles"] = $arFiles;
					
					foreach($arFiles as $key=>$arFile) {	
						//$arFile = $_FILES["files"];
						$arFile['del'] = 'N';
						$arFile['MODULE_ID'] = 'iblock';

						$arResult["FILES"][] = $arFile;	
						
						$fid = CFile::SaveFile($arFile, "docs");
						$arResult["FILES_R"][] = $fid;
						
						$PROP["ATTACHMENT"][] = $fid;					
					}
					
					
				}
				
				$PROP["SITE"] = $_SERVER["SERVER_NAME"];
				$arFields = Array(
					"IBLOCK_ID" => CStatic::$claimIdBlock,
					"IBLOCK_SECTION_ID" => CStatic::$claimIdSec,
					"NAME" => $_SERVER["SERVER_NAME"]." [".$PROP["NAME"]."] [".$PROP["EMAIL"]."]",
					"DATE_ACTIVE_FROM" => date("d.m.Y H:i"),
					"ACTIVE" => "N",
					"PREVIEW_TEXT_TYPE" => 'text',
					"PREVIEW_TEXT" => $F["TXT"],
					"PROPERTY_VALUES" => $PROP,
				);		
				
				$ID = $el->Add($arFields);				
				
				$arEventFields["ITEM_ID"] = $ID;
				//$arEventFields["EMAIL_TO"] = 'sergebormatov@gmail.com';
				$arEventFields["EMAIL_TO"] = 'kachestvo@'.$_SERVER["SERVER_NAME"];
				
				if($PROP["ATTACHMENT"]) {
					foreach($PROP["ATTACHMENT"] as $fileKey => $fileID) {
						$arFile = CFile::GetFileArray($fileID);						
						$arEventFields["ATTACHMENT"] .= ($fileKey > 0 ? " \n" : "") . "https://".$_SERVER["SERVER_NAME"].$arFile["SRC"];
					}
				}
				$arEventFields["ITEM_ID"] = $ID;	

				$arEventFields["ADMIN_URL"] = 'https://'.$_SERVER["SERVER_NAME"].'/iblock_element_edit.php?IBLOCK_ID='.CStatic::$claimIdBlock.'&type=backform&ID='.$ID.'&lang=ru&find_section_section='.CStatic::$claimIdSec.'&WF=Y';
				
				
				CEvent::Send("BACK_FORM",SITE_ID, $arEventFields, false, 87);	
				
				$arResult["SUCCESS"] = 'Y';
				$arResult["CLEARPOLS"] = 'N';

		break;
		case "guideAdd":
		
			$title = 'Связь с руководством';
			$html_text = 'Спасибо! Ваш запрос отправлен.<br/> Мы ответим Вам в ближайшее время.';
					
				$arEventFields = $PROP = $F = $request->get('F');
				unset($PROP["TXT"]);
			
				$arFields = Array(
					"IBLOCK_ID" => CStatic::$guideIdBlock,
					"IBLOCK_SECTION_ID" => CStatic::$guideIdSec,
					"NAME" => $_SERVER["SERVER_NAME"]." | Новое обращение от ".date("d.m.Y H:i"),
					"ACTIVE" => "N",
					"PREVIEW_TEXT_TYPE" => 'text',
					"PREVIEW_TEXT" => $F['TXT'],
					"PROPERTY_VALUES" => $PROP,
				);			

				$ID = $el->Add($arFields);				
				
				
				$arEventFields["ADMIN_URL"] = 'https://'.$_SERVER["SERVER_NAME"].'/iblock_element_edit.php?IBLOCK_ID='.CStatic::$guideIdBlock.'&type=backform&ID='.$ID.'&lang=ru&find_section_section='.CStatic::$guideIdSec.'&WF=Y';
				
				
				CEvent::Send("BACK_FORM",SITE_ID, $arEventFields, false, 86);	
				
				$arResult["SUCCESS"] = 'Y';

		break;
		case "feedbackAdd":
		
			$title = 'Обратная связь';
			$html_text = 'Спасибо! Ваш запрос отправлен.<br/> Мы ответим Вам в ближайшее время.';
					
				$arEventFields = $PROP = $F = $request->get('F');
				unset($PROP["TXT"]);
			
				$arFields = Array(
					"IBLOCK_ID" => CStatic::$feedbackIdBlock,
					"IBLOCK_SECTION_ID" => CStatic::$feedbackIdSec,
					"NAME" => $_SERVER["SERVER_NAME"]." | Новое обращение от ".date("d.m.Y H:i"),
					"ACTIVE" => "N",
					"PREVIEW_TEXT_TYPE" => 'text',
					"PREVIEW_TEXT" => $F['TXT'],
					"PROPERTY_VALUES" => $PROP,
				);			

				$ID = $el->Add($arFields);		
				
				$arEventFields["ADMIN_URL"] = 'https://'.$_SERVER["SERVER_NAME"].'/iblock_element_edit.php?IBLOCK_ID='.CStatic::$feedbackIdBlock.'&type=backform&ID='.$ID.'&lang=ru&find_section_section='.CStatic::$feedbackIdSec.'&WF=Y';
				
				
				CEvent::Send("BACK_FORM",SITE_ID, $arEventFields, false, 88);	
				
				$arResult["SUCCESS"] = 'Y';

		break;
		case "change_captcha":
			include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
			$cpt = new CCaptcha();
			$captchaPass = COption::GetOptionString("main", "captcha_password", "");
			if(strlen($captchaPass) <= 0)
			{
				$captchaPass = randString(10);
				COption::SetOptionString("main", "captcha_password", $captchaPass);
			}
			$cpt->SetCodeCrypt($captchaPass);
			
			$arResult["CAPTCHA_CODE"] = htmlspecialchars($cpt->GetCodeCrypt());
			$arResult["CAPTCHA_IMG"] = '/bitrix/tools/captcha.php?captcha_code='.htmlspecialchars($cpt->GetCodeCrypt());
		
		break;
		case "VIEWED_REMOVE":			
		
			if($request->offsetGet("ID")) {
				$key = array_search($request->offsetGet("ID"),$_SESSION["CATALOG_VIEWS_LIST"]);
				unset($_SESSION["CATALOG_VIEWS_LIST"][$key]);
			}
			
		break;	
		case "VIEWED_REMOVE_ALL":			
		
			unset($_SESSION["CATALOG_VIEWS_LIST"]); 
			
			$arResult["SUCCESS"] = 'Y';
			
		break;	
		case "CLEAR_COMPARE_BLOCK":	
		
		
			if($request->offsetGet("BLOCK_ID")) {
				unset($_SESSION["CATALOG_COMPARE_LIST"][$request->get("BLOCK_ID")]);
			}
			$arResult["SUCCESS"] = 'Y';
			
		break;	
		case "CHANGE_COMPARE_BLOCK":	
		
			foreach($_SESSION["CATALOG_COMPARE_LIST"] as $iblock => &$mTov){
				if($mTov["ACTIVE"]) unset($mTov["ACTIVE"]);
			}	
		
			$_SESSION["CATALOG_COMPARE_LIST"][$request->get("BLOCK_ID")]["ACTIVE"] = 'Y';
			
			$arResult["SUCCESS"] = 'Y';
				
		break;
		case "CHANGE_COMPARE_SECTION":	
		
		
			if($request->offsetGet("SECTION_ID")) {
				
				$SECTION_ID = $request->offsetGet("SECTION_ID");				
				$arSection = getArSection(false , false, $SECTION_ID);
				$arResult["SEC"] = $arSection;
				$BLOCK_ID = $arSection["IBLOCK_ID"];				
		
				foreach($_SESSION["CATALOG_COMPARE_LIST"] as $iblock => &$mTov){
					if($mTov["ACTIVE"]) unset($mTov["ACTIVE"]);
				}	
				$_SESSION["CATALOG_COMPARE_LIST"][$BLOCK_ID]["ACTIVE"] = 'Y';
				
				unset($_SESSION["CATALOG_COMPARE_SECTION_ACTIVE"]);				
				$arResult["SEC_ACTIVE"] = $_SESSION["CATALOG_COMPARE_SECTION_ACTIVE"] = $SECTION_ID;
				
				
				$arResult["SUCCESS"] = 'Y';
			}
				
		break;
		case "ADD_COMPARE_ALL":
		
		$arIds = explode('_', $request->offsetGet("IDS"));		
		
		foreach($arIds as $id) {
			
			if($arTov = CStatic::getElement($id)) {			
			
				$_SESSION["CATALOG_COMPARE_LIST"][$arTov["IBLOCK_ID"]]["ITEMS"][$id] = ARRAY(
					"ID" => $arTov["ID"],
					"~ID" => $arTov["ID"],
					"IBLOCK_ID" => $arTov["IBLOCK_ID"],
					"~IBLOCK_ID" => $arTov["IBLOCK_ID"],
					"IBLOCK_SECTION_ID" => $arTov["IBLOCK_SECTION_ID"],
					"~IBLOCK_SECTION_ID" => $arTov["IBLOCK_SECTION_ID"],
					"NAME" => $arTov["NAME"],
					"~NAME" => $arTov["NAME"],
					"DETAIL_PAGE_URL" => $arTov["DETAIL_PAGE_URL"],
					"~DETAIL_PAGE_URL" => $arTov["DETAIL_PAGE_URL"],
					"SECTIONS_LIST" => Array($arTov["IBLOCK_SECTION_ID"] => $arTov["IBLOCK_SECTION_ID"]),
					"PARENT_ID" => $arTov["ID"],
					"DELETE_URL" => "/ajax/back.php?action=DELETE_FROM_COMPARE_LIST&id=".$arTov["ID"]		
				);	
			}
			
		}
		
		
		
			$count = 0;
			if(count($_SESSION["CATALOG_COMPARE_LIST"])>0)
			{
				foreach($_SESSION["CATALOG_COMPARE_LIST"] as $iblock =>$items)
				{					
					if(in_array($iblock, CStatic::$catalogIdBlock)) {						
						$count = $count + count($items["ITEMS"]);	
					}
				}
			}
			
			$arResult["COUNT_TEXT"] = $count.' '.declOfNum($count, array('товар','товара','товаров'));
			$arResult["COUNT"] = $count;
			
			
			$title = 'Сравнение';
			$html_text = 'Все товары из списка добавлены в сравнение';
			
			$arResult["SUCCESS"] = 'Y';
		
		break;
		case "DELETE_FROM_COMPARE_LIST":	
		case "ADD_TO_COMPARE_LIST":	
		
			$arTov = CStatic::getElement($request->get("id"));
		
			$APPLICATION->IncludeComponent("bitrix:catalog.compare.list", "compare_popup", Array(
					"COMPONENT_TEMPLATE" => ".default",
					"IBLOCK_TYPE" => "mn_catalog",	// Тип инфоблока
					"IBLOCK_ID" => $arTov["IBLOCK_ID"],	// Инфоблок
					"POSITION_FIXED" => "Y",	// Отображать список сравнения поверх страницы
					"POSITION" => "top left",	// Положение на странице
					"AJAX_MODE" => "N",	// Включить режим AJAX
					"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
					"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
					"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
					"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
					"DETAIL_URL" => "/#IBLOCK_CODE#/#SECTION_CODE#/#ELEMENT_CODE#.html",	// URL, ведущий на страницу с содержимым элемента раздела
					"COMPARE_URL" => "/compare/",	// URL страницы с таблицей сравнения
					"NAME" => "CATALOG_COMPARE_LIST",	// Уникальное имя для списка сравнения
					"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
					"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
				),
				false
			);
			
			$count = 0;
			if(count($_SESSION["CATALOG_COMPARE_LIST"])>0)
			{
				foreach($_SESSION["CATALOG_COMPARE_LIST"] as $iblock =>$items)
				{					
					if(in_array($iblock, CStatic::$catalogIdBlock)) {						
						$count = $count + count($items["ITEMS"]);	
					}
				}
			}
			
			$arResult["COUNT_TEXT"] = $count.' '.declOfNum($count, array('товар','товара','товаров'));
			$arResult["COUNT"] = $count;
			
			$arResult["SUCCESS"] = 'Y';

		break;	
		case "ADD2BASKET":
		
			
			 
			 $arResult["PROP"] = array();
			 //$arResult["POST"] = $_POST;
			 // записываем 3й параметр, т.к. в 4й (как написано в документации, не работает)
			$arResult["BASKET_ID"] = Add2BasketByProductID($request->get("TOV_ID"), 1, $arProp);		
			 
			 
			 $res = CStatic::goodsInBasket();			 
			 $arResult["COUNT"] = $res["TOV_COUNT_BASKET"];
			 $arResult["COUNT_TEXT"] = $res["TOV_COUNT_BASKET"].' '.declOfNum($res["TOV_COUNT_BASKET"], array('товар','товара','товаров'));
			 $arResult["SUM"] = number_format($res["TOV_SUM_BASKET"], 0, '.', ' ');	

			 
			 
			 $arResult["SUCCESS"] = 'Y';
			
        break;	
		case "CHANGE2BASKET":
			$kol = (int)$request->get('KOL');
			$arFields = array(
			   "QUANTITY" => $kol,
			);
			
			CSaleBasket::Update($request->get('TOV_ID'), $arFields);
			
			$res = CStatic::goodsInBasket();	
			$arResult["COUNT"] = $res["TOV_COUNT_BASKET"];
			$arResult["COUNT_TEXTR"] = $res["TOV_COUNT_BASKET"].' '.declOfNum($res["TOV_COUNT_BASKET"], array('товар', 'товара', 'товаров'));
			
			$arResult["SUM"] = number_format($res["TOV_SUM_BASKET"], 0, '.', ' ');			
			
			$arResult["SUCCESS"] = 'Y';
		break;
		case "DELETE2BASKET":
			CSaleBasket::Delete($request->get('ID'));
			
			$res = CStatic::goodsInBasket();			 
			$arResult["COUNT"] = $res["TOV_COUNT_BASKET"];
			$arResult["SUM"] = number_format($res["TOV_SUM_BASKET"], 0, '.', ' ');	
			
			$arResult["SUCCESS"] = 'Y';
			
		break;	
		case "BuyOneClick":
		
			 // удаляем все товары из корзины, чтобы создать заказ толкьо с тем по которому был оформлен быстрый заказ
			 CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
			 
			 
			$arProp = array();
			 // записываем 3й параметр, т.к. в 4й (как написано в документации, не работает)
			Add2BasketByProductID($request->get("TOV_ID"), 1, $arProp);
		
				$arErrors = array();
				$arWarnings = array();				
				$payerPhone = $request->get('PHONE');
				
				$arResult["PHONE"] = $payerPhone;
				$arResult["PHONE_CHECK"] = preg_replace("([^0-9])", "", $payerPhone);
				
				
				$payerEMail = 'oneclick'.preg_replace("([^0-9])", "", $payerPhone).'@'.$_SERVER["SERVER_NAME"];		
				$arResult["email_CHECK"] = $payerEMail;
				
				$payerName = $request->get('NAME');
				
				$userNew = false;
				if($UID>0) {
					//$arResult["STEP_1"] = 'auth';
				}
				else {
					
						//$arResult["STEP_1"] = 'noauth';
						$pos = strpos($payerEMail, "@");
						$payerEMailNew = substr($payerEMail, 0, $pos);
						$dbUserLogin = CUser::GetByLogin($payerEMailNew);

						if ($arUser = $dbUserLogin->Fetch())
						{
							//$arResult["STEP_2"] = 'find';
							$UID = $arUser['ID'];
						}
						else
						{
							//$arResult["STEP_2"] = 'nofind reg';
							$arFields = array(
								$payerEMail,
								$payerName,
								SITE_ID,
								$arErrors,
								array()
							);
							
							$arResult["arFields_check"] = $arFields;

							$UID = CSaleUser::DoAutoRegisterUser($payerEMail, $payerName, SITE_ID, $arErrors, array());
							$userNew = true;
						}				
				}

				//$arResult["USER_ID"] = $UID;
				
				if ($UID > 0)
				{
						$USER->Authorize($UID);
						$PERSON_TYPE = '1'; // физ лицо

						if($userNew) {						
							$arPropValues = array(				
								"1" => $payerName,				
								"2" => $payerPhone,				
							);						
						}
						else {
						
							$arUsers = CStatic::GetUserInfo($UID);
						
							$arPropValues = array(				
								"1" => $arUsers['LAST_NAME'].' '.$arUsers['NAME'],				
								"3" => $arUsers['EMAIL'],				
								"2" => $payerPhone,				
							);
						}	
				
						$arPropValues["10"] = $_COOKIE["Bicontent"];


					$arShoppingCart = CSaleBasket::DoGetUserShoppingCart(SITE_ID, $UID, intval(CSaleBasket::GetBasketUserID()), $arErrors);

					$DELIVERY_ID = 2;	// бесплатная доставка			
					$PAYSYSTEM_ID = 2; // оплата наличными при получении

					$arfields = array(
						SITE_ID,
						$UID,
						$arShoppingCart,
						$PERSON_TYPE,
						$arPropValues,
						$DELIVERY_ID,
						$PAYSYSTEM_ID,
						array(),
						$arErrors,
						$arWarnings
					);

					//print_r($arfields);

					$arBasketItems = CSaleOrder::DoCalculateOrder(
						SITE_ID,
						$UID,
						$arShoppingCart,
						$PERSON_TYPE,
						$arPropValues,
						$DELIVERY_ID,
						$PAYSYSTEM_ID,
						array(),
						$arErrors,
						$arWarnings
					);

					$arAdditionalFields = array(
						"LID" => SITE_ID,
						"STATUS_ID" => "N",
						"PAYED" => "N",
						"CANCELED" => "N",
						"USER_DESCRIPTION" => '',
					);

					$arResult["ORDER_ID"] = CSaleOrder::DoSaveOrder($arBasketItems, $arAdditionalFields, 0, $arErrors);
					
					if($arResult["ORDER_ID"]) {
						
											
						
						$strOrderList = "";
						$arBasketList = array();						
						
						$dbBasketItems = CSaleBasket::GetList(
							array("ID" => "ASC"),
							array("ORDER_ID" => $arResult["ORDER_ID"]),
							false,
							false,
							array("ID", "PRODUCT_ID", "NAME", "QUANTITY", "PRICE", "CURRENCY", "TYPE", "SET_PARENT_ID","DETAIL_PAGE_URL")
						);
						while ($arItem = $dbBasketItems->Fetch())
						{
							if (CSaleBasketHelper::isSetItem($arItem))
								continue;

							$arBasketList[] = $arItem;
						}
						
						
						
						$arBasketList = getMeasures($arBasketList);

						if (!empty($arBasketList) && is_array($arBasketList))
						{
							
							$orderTotalSum = 0;
							foreach ($arBasketList as $arItem)
							{
								$measureText = (isset($arItem["MEASURE_TEXT"]) && strlen($arItem["MEASURE_TEXT"])) ? $arItem["MEASURE_TEXT"] : GetMessage("SOA_SHT");

								$orderTotalSum = $orderTotalSum + IntVal($arItem["PRICE"]);
								
								
								$strOrderList .= "<a href='".$arItem["DETAIL_PAGE_URL"]."' title='Перейти к товару' target='_blank'>".$arItem["NAME"]."</a> - ".$arItem["QUANTITY"]." шт. ".$measureText.": ".SaleFormatCurrency($arItem["PRICE"], $arItem["CURRENCY"]);
								$strOrderList .= "<br/>";
							}
						}
						
						
						$html_mail = '<h2>Быстрый заказ на сайте</h2>';						
						$html_mail.= '<b>Контактный данные:</b><br/>';
						if(!empty($arPropValues[1])) $html_mail.= 'ФИО: '.$arPropValues[1].'<br/>';
						if(!empty($arPropValues[3])) $html_mail.= 'E-mail: '.$arPropValues[3].'<br/>';
						if(!empty($arPropValues[2])) $html_mail.= 'Телефон: '.$arPropValues[2].'<br/>';							
						$html_mail.= '<br/>'.$strOrderList;		
						
						
						$arFieldsM = array(
							"HTML" => $html_mail,
							"ORDER_ID" => $arResult["ORDER_ID"]												
						);				
						
						
						$event = new CEvent;
						$event->Send('BACK_FORM', SITE_ID, $arFieldsM, "N", 85);
						
						foreach (GetModuleEvents("sale", "OnSaleComponentOrderOneStepComplete", true) as $arEvent)
							ExecuteModuleEventEx($arEvent, array($arResult["ORDER_ID"], array(), array()));
						
						
						$arResult["SUCCESS"] = 'Y';
						
						$title = 'Купить в 1 клик';
						$html_text = 'Спасибо! Ваш заказ оформлен.<br/> Менеджер свяжется с вами в ближайшее время.';
					}
				
			}
		
		
		
		break;	
		
		
		
}











	if($arResult["RELOAD"]!='Y')
	{
		if($html == '' && $title!='') {
			$html = '<div class="response-popup">
						<div class="response-popup__inner">
							<div class="response-popup__title">'.$title.'</div>
							<div class="response-popup__text">'.$html_text.'</div>
						</div>
					</div>';
			
		}
		$arResult["HTML"] = $html;			
		
	}

	echo json_encode($arResult);



include_once ($_SERVER['DOCUMENT_ROOT'] .'/bitrix/modules/main/include/epilog_after.php');
?>