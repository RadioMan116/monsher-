<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Ознакомьтесь с условиями доставки и оплаты товаров в интернет-магазине L-rus.ru");
$APPLICATION->SetPageProperty("title", "Условия доставки и оплаты в интернет-магазине L-rus.ru");
$APPLICATION->SetTitle("Доставка и оплата");
?>


<?$APPLICATION->IncludeFile('/local/include_areas/page_delivery_payment_'.$_COOKIE["K_REGION"].'.php');?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>