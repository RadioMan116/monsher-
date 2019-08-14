<?
$menu_tip = 'about';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О компании");
?>
<div class="about-us">

<?$APPLICATION->IncludeFile('/local/include_areas/about-text.php')?>

</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>