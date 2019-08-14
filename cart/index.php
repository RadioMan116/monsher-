<?
$menu_tip = 'cart';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");

CStatic::CityBasketUpdate();


?>
		
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax", 
	"basket", 
	array(
		"ALLOW_AUTO_REGISTER" => "Y",
		"ALLOW_NEW_PROFILE" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"COUNT_DELIVERY_TAX" => "N",
		"DELIVERY_NO_AJAX" => "Y",
		"DELIVERY_NO_SESSION" => "Y",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"DISABLE_BASKET_REDIRECT" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"PATH_TO_AUTH" => "/auth/",
		"PATH_TO_BASKET" => "/cart/empty/",
		"PATH_TO_PAYMENT" => "/cart/payment/",
		"PATH_TO_PERSONAL" => "/personal/orders/",
		"PAY_FROM_ACCOUNT" => "N",
		"PRODUCT_COLUMNS" => array(
		),
		"PROP_1" => array(
		),
		"SEND_NEW_USER_NOTIFY" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_PAYMENT_SERVICES_NAMES" => "Y",
		"SHOW_STORES_IMAGES" => "N",
		"TEMPLATE_LOCATION" => "popup",
		"USE_PREPAYMENT" => "N"
	),
	false
);?>
			



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>