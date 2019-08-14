<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "На всю продукцию Liebherr, которая приобретена в нашем интернет-магазине, действует официальная гарантия производителя.");
$APPLICATION->SetPageProperty("title", "Условия предоставления гарантии на холодильную технику в нашем интернет-магазине l-rus.ru");
$APPLICATION->SetTitle("Гарантия");
?>


<?$APPLICATION->IncludeFile('/local/include_areas/page_warranty_'.$_COOKIE["K_REGION"].'.php');?>




<?$APPLICATION->IncludeFile('/local/include_areas/page_warranty_bottom_'.$_COOKIE["K_REGION"].'.php');?>




								
						

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>