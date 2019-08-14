<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Ознакомьтесь с условиями возврата и обмена товара в нашем интернет-магазине.");
$APPLICATION->SetPageProperty("title", "Возврат и обмен товара в интернет-магазине l-rus.ru");
$APPLICATION->SetTitle("Возврат товара");

$APPLICATION->AddHeadScript("/tpl/js/return-form.js");
?>

		<?$APPLICATION->IncludeFile('/local/include_areas/claim-text.php');?>
		<?$APPLICATION->IncludeFile('/local/include_areas/claim-form.php');?>
		
		
		
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>