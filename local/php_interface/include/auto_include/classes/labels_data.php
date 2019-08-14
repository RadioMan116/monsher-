<?
class CMNTLabelsData
{
	
	const TYPE_LEFT = "left";
	const TYPE_RIGHT = "right";
	const TYPE_TITLE = "title";
	
	public function getLabelInfo($code = ""){
		if( CMNTCache::hasCache("CMNTLabelsDataWhirlPool" . __FUNCTION__) )	
			return CMNTCache::getCache();	
			
		$res = CIBlockElement::getList(
			array(),
			array("IBLOCK_ID" => 78, "ACTIVE" => "Y"),
			false, false,
			array("ID", "IBLOCK_ID", "NAME", "DETAIL_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "CODE", "PROPERTY_PLACE" )
		);
		$arResult = array();
		while( $result = $res->getNext() ){
			$arItem = array();
			$arItem["TITLE"] = $result["NAME"];
			$arItem["CODE"] = $result["CODE"];
			$arItem["PLACE"] = $result["PROPERTY_PLACE_VALUE"];
			if( !empty($result["DETAIL_TEXT"]) ){
				$arItem["TITLE"] = $result["DETAIL_TEXT"];
			}
			$arItem["IMG_ID"] = $result["PREVIEW_PICTURE"];
			$arItem["SRC"] = CFile::getPath($result["PREVIEW_PICTURE"]);			
			$arItem["SRC_BIG"] = CFile::getPath($result["DETAIL_PICTURE"]);			
			$arResult[$result["ID"]] = $arItem;
		}
		
		
		
		
		$result = CMNTCache::setCache($arResult);
		if( !empty($code) ){
			foreach($result as $item){
				if( $code == $item["CODE"] )
					return $item;
			}
		}
		return $result;
	}	
	
	public function get($element, $labelInfo, $type = self::TYPE_LEFT){
		if( $element["PROPERTIES"][$_COOKIE["CITY"]]["VALUE"] == "Снят с производства" )
			return array();
		
		$labels = $element["PROPERTIES"]["LABELS"]["VALUE"];
		
		// all labels array( code => id )
		$arCodeIdAll = array();
		foreach($labelInfo as $id => $info){			
			$arCodeIdAll[$info["CODE"]] = $id;
			if( $info["CODE"] == "star" )
				$labelInfo[$id]["TITLE"] = "";
		}
		
		// add from card
		$arCodeId = array();
		foreach($labels as $id)
			$arCodeId[$labelInfo[$id]["CODE"]] = $id;		
		
		// add auto labels
		$code = "star";
		unset($arCodeId[$code]);
		if( $element["PROPERTIES"]["IS_MRC"]["VALUE"] == "Y" ){
			$arCodeId[$code] = $arCodeIdAll[$code];
		}
		
		$result = array();
		foreach($arCodeId as $code => $id){
			if( $labelInfo[$id]["PLACE"] == $type )
				$result[$code] = $labelInfo[$id];
		}
	
		return $result;
	}
}
?>