<?php
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/csv_data.php");

if(!CModule::IncludeModule("iblock")){
	ShowError("Ошибка подключения модуля iblock");
	die();
}


$uploaddir = $_SERVER["DOCUMENT_ROOT"].'/tools/seo/tmp/';
$filename = basename($_FILES['userfile']['name']);
$uploadfile = $uploaddir . $filename;


if(isset($_FILES) && !empty($_FILES)){

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
		ShowMessage(Array("TYPE"=>"OK", "MESSAGE"=>"Файл корректен и был успешно загружен"));
		
		$csvFile = new CCSVData('R', true);
		$csvFile->LoadFile($uploadfile);
		$csvFile->SetDelimiter(';');
		$arLines = array();
		while ($arRes = $csvFile->Fetch()) {
			$id = $arRes[10];
			$ws = $arRes[9];
			$name = $arRes[5];
			$arLines[$id]['WORDSTAT'] = $ws;
			$arLines[$id]['NAME'] = conv_to_read($name);
		}
		
		//pre($arLines);
		
		foreach($arLines as $id=>$values){		
			if($values["WORDSTAT"] > 0){
				CIBlockElement::SetPropertyValuesEx($id, false, array('WORDSTAT' => $values["WORDSTAT"]));				
			}	
			
			$arProduct = CStatic::getElement($id, CStatic::$catalogIdBlock);
			CStatic::SortByWordstat($arProduct);
		}
		ShowMessage(Array("TYPE"=>"OK", "MESSAGE"=>"Данные WORDSTAT обновлены"));
		unlink('tmp/'.$filename);
	} else {
		ShowError("Проблема при загрузке файла");
	}

}

?>
<html>
	<style>
		.notetext{
			display: block;
			line-height: 30px;
			background: #dcffdc;
			padding: 0 20px;
		}
		.errortext{
			display: block;
			line-height: 30px;
			background: #ffd2d2;
			padding: 0 20px;
		}
		.button{
			border: 0;
			background: #f1f1f1;
			padding: 6px 20px;
			margin-top: 22px;
			cursor: pointer; 
		}
		.button:hover{
			background: #dedede;
			color: #fbfbfb; 
		}
	</style>
	<body>
		<h3>Обновление свойства WORDSTAT</h3><br>
		<form enctype="multipart/form-data" action="" method="POST">
			Загрузить csv: <input name="userfile" type="file" /><br>
			<input type="submit" value="Отправить файл" class="button"/>
		</form>
	</body>
</html>