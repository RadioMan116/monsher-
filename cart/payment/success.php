<?
$menu_tip = 'order';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");

$inv_id = htmlspecialchars($_REQUEST["InvId"]);
echo "Платеж совершен успешно. Заказ № ".$inv_id;



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?> 