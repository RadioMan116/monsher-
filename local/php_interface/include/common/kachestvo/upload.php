<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);

setlocale(LC_ALL, 'ru_RU.UTF-8');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!(isset($_REQUEST["_method"]) && $_REQUEST["_method"] == "DELETE"))
{
	if(!isset($_REQUEST["sessid"]) || $_REQUEST["sessid"] != bitrix_sessid())
		die();
}

//AddMessage2Log("_REQUEST: ".print_r($_REQUEST, true));
//AddMessage2Log("_POST: ".print_r($_POST, true));
//AddMessage2Log("_GET: ".print_r($_GET, true));
//AddMessage2Log("_FILES: ".print_r($_FILES, true));

//require("upload_handler.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/lib/upload/upload_handler.php");
$upload_handler = new UploadHandler(
	array(
		"upload_dir" => $_SERVER["DOCUMENT_ROOT"]."/upload/assets/kachestvo/tmp/",
		"upload_url" => "http://".$_SERVER["SERVER_NAME"]."/upload/assets/kachestvo/tmp/",
		//"user_dirs" => true,
		//"max_number_of_files" => 2,
		"print_file_name_length" => 70,
		//"convert_encoding" => true,
		"translit_file_name" => true,
		"datetime_in_filename" => true,
		"hide_file_url" => true,
	)
);
