<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>
<!DOCTYPE html>
<html>
<head>
	<link href="style.css" type="text/css" rel="stylesheet" title="style" />
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<title>Обновление карты сайта</title>
</head>
<body>
	<div id="container">
		Обновление карты сайта<br />
		<br />
<?
	// read last state
	$arLastState = CMNTStateFile::Read($STATE_FILE);
	$arUpdateStatus = CMNTStateFile::Read($UPDATE_FILE);
	
	$bUpdateTimeout = !empty($arUpdateStatus["MICROTIME"]) && (getmicrotime() - $arUpdateStatus["MICROTIME"]) > $UPDATE_TIMEOUT ? true : false;
	
	echo "Текущее состояние:<br />";
	echo $arLastState["TIME"]." ".$arLastState["NAME"].", запуск: ".$arLastState["MODE"]."<br /><br />";
	
	echo "Статус обновления:<br />";
	if(empty($arUpdateStatus))
	{
		echo "не найдено<br /><br />";
	}
	else
	{
		if($arUpdateStatus["UPDATE"])
			echo $arUpdateStatus["TIME"]." требуется обновление по событию '".$arUpdateStatus["EVENT"]."'<br /><br />";
		elseif($bUpdateTimeout)
			echo $arUpdateStatus["TIME"]." требуется обновление по таймауту (".$UPDATE_TIMEOUT." сек)<br /><br />";
		else
			echo $arUpdateStatus["TIME"]." обновление не требуется<br /><br />";
	}
	
	if(is_array($arLastState["ERRORS"]) && !empty($arLastState["ERRORS"]))
	{
		echo "Ошибки:<br />";
		
		foreach($arLastState["ERRORS"] as $error)
			echo $error."<br />";
		
		echo "<br />";
	}
?>
		<form action="" method="get">
			<input type="hidden" name="start" value="1" />
			<div><input type="submit" value="Старт"></div>
			<br />
		</form>
<?
	if(!empty($arMessages)) {
?>
		<p>Обработка:</p>
		<ul>
<?
		foreach($arMessages as $item) {
?>
			<li><?=$item?></li>
<?
		}
?>
		</ul>
<?
	}
?>
<?
	if(!empty($arErrors)) {
?>
		<br />
		<p>Ошибки:</p>
		<ul>
<?
		foreach($arErrors as $item) {
?>
			<li><?=$item?></li>
<?
		}
?>
		</ul>
<?
	}
?>
	</div>
</body>
</html>