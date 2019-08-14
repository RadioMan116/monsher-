<?
// используется в генераторе yml для маркета, карте сайта
class CMNTStateFile extends CMNTStateFileCommon
{
	function Event($eventID)
	{
		if(in_array($eventID, array("T50 batch update", "T50 store update", "T50 price update", "OnAfterIBlockElementUpdate", "OnAfterIBlockElementAdd", "OnIBlockElementDelete")))
		{
			self::Save($_SERVER["DOCUMENT_ROOT"]."/tools/ymarket/tmp/update.txt", array("UPDATE" => 1, "EVENT" => $eventID));
			self::Save($_SERVER["DOCUMENT_ROOT"]."/tools/ymarket_all/tmp/update.txt", array("UPDATE" => 1, "EVENT" => $eventID));
		}
		
		if($eventID == "T50 batch update")
		{
			self::Save($_SERVER["DOCUMENT_ROOT"]."/tools/sku_update/tmp/update.txt", array("UPDATE" => 1, "EVENT" => $eventID));
		}
	}
}
?>