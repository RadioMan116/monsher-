<?
$menu_tip = 'docs';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Инструкции по эксплуатации товаров Liebherr в интернет-магазине l-rus.ru");
$APPLICATION->SetPageProperty("title", "Инструкции по товарам Liebherr");
$APPLICATION->SetTitle("Инструкции Liebherr");
?>

<?$APPLICATION->IncludeComponent("inter.olsc:tech.docs", "", array(
	"IBLOCK_ID" => $request->offsetGet("IBLOCK_ID")
), false);?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>