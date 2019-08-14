<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
$menu_tip = 'nofind';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Ошибка 404. Страница не найдена");

?>

<div class="alert-404">
						<h1>404</h1>
						<h2>Страница не найдена</h2>
						<p>Страница, которую вы запрашиваете, была удалена или не существует</p>
						<a class="alert-404__button" href="/">на главную</a>
</div>

<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>