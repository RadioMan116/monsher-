<?
AddEventHandler("search", "BeforeIndex", Array("SearchIndexClass", "BeforeIndex"));

class SearchIndexClass
{    
    function BeforeIndex($arFields){
		//$arFields["TITLE"] = iconv("utf-8", "cp1251", $arFields["TITLE"]);
		//$arFields["BODY"] = iconv("utf-8", "cp1251", $arFields["BODY"]);
		self::dump($arFields);
		return $arFields;
    }
	
	private function dump($var){
		$dumpFile = dirname(__FILE__) . "/dumpvar.log";
		$var = var_export($var, true);		
		file_put_contents($dumpFile, $var);
	}
}
?>