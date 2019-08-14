<?
$menu_tip = 'news_detail';
$bIndexPage = ($_SERVER["SCRIPT_NAME"] == "/index.php" ? true : false);
define("PATH_TO_404", "/404.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();


/*
echo 'ntcn';

 $arResult["PROP"] = array();
 global $APPLICATION;
 $id = 1266;
			$res = Add2BasketByProductID($id, 1, $arProp);		
$ex = $APPLICATION->GetException();
echo $ex->GetString();
pre($res);


echo '<br/>##################################################################';
echo '<br/>##################################################################';
echo '<br/>##################################################################';
echo '<br/>##################################################################';
*/
/*

$id =1319;
			$res = Add2BasketByProductID($id, 1, $arProp);		
$ex = $APPLICATION->GetException();
echo $ex->GetString();
pre($res);

//PRE($_SERVER);

//setcookie("CITY_CURRENT", "REGION77", time() + 15552000, "/");
//setcookie("CITY_CURRENT", "REGION77", time() + 15552000, "/", "spb.l-rus.ru");


/*
$arPrice = CStatic::GetPrice(1611);

pre($arPrice);*/
/*

pre($_COOKIE["K_REGION"]);
pre($GLOBALS["K_PRICE_CODE"]);
pre($GLOBALS["K_EXIST_CODE"]);
*/
/*
$code = '';




setcookie("CITY", $code, time()+15552000, "/"); 
$_COOKIE["CITY"] = $code;

setcookie("CITY_CURRENT", $code, time()+15552000, "/"); 
$_COOKIE["CITY_CURRENT"] = $code;
*/
?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>