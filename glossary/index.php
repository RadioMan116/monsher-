<?
$menu_tip = 'glossary';
$bIndexPage = ($_SERVER["SCRIPT_NAME"] == "/index.php" ? true : false);
define("PATH_TO_404", "/404.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Примечания по функциям, технологиям и материалам, используемых в холодильном оборудовании Liebherr. Подготовлено интернет-магазином l-rus.ru");
$APPLICATION->SetPageProperty("title", "Функции, технологии и материалы холодильного оборудования Liebherr. ");
$APPLICATION->SetTitle("Глоссарий");
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();



?>




						
<?$APPLICATION->IncludeComponent(
							"bitrix:catalog.section.list",
							"glossary.list",
							Array(
								"ADD_SECTIONS_CHAIN" => "N",
								"CACHE_GROUPS" => "Y",
								"CACHE_TIME" => "36000000",
								"CACHE_TYPE" => "A",
								"COMPONENT_TEMPLATE" => "catalog.category.list",
								"COUNT_ELEMENTS" => "Y",
								"IBLOCK_ID" => 57,
								"IBLOCK_TYPE" => "mn_content",
								"SECTION_CODE" => "",
								"SECTION_FIELDS" => array("CODE","NAME"),
								"SECTION_ID" => "",
								"SECTION_URL" => "",
								"SECTION_USER_FIELDS" => array("",""),
								"SHOW_PARENT_NAME" => "Y",
								"TOP_DEPTH" => "1",
								"VIEW_MODE" => "LINE"
							)
						);?>












<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>