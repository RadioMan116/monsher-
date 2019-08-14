<?
$_SERVER["BX_PERSONAL_ROOT"] = "/bitrix";
//$_SERVER["HTTP_HOST"] = "l-rus.ru";
$_SERVER["HTTP_HOST"] = "l-rus.ru";
$_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__) . "/../../";

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

$_GET = getopt("", array("mode:", "start::", "postfix::"));
$_REQUEST = getopt("", array("mode:", "start::", "postfix::"));

// cron check
if($fp = @fopen(dirname(__file__) . "/tmp/log/cron_check.txt", "a+"))
{
	@fwrite($fp, date("d.m.Y H:i:s")." cron\n");
	@fclose($fp);
}
$_GET = array();
$bCron = true;
include dirname(__file__) . "/index.php";
?>
