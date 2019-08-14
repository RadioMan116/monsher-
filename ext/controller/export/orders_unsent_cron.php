<?
$_SERVER["DOCUMENT_ROOT"] = "/var/www/l-rus.ru/web";
$_GET = getopt("", array("mode:"));
$_REQUEST = getopt("", array("mode:"));

include $_SERVER["DOCUMENT_ROOT"]."/ext/controller/export/orders_unsent.php";
?>