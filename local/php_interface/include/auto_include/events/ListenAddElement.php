<?
CModule::includeModule("iblock");
CModule::includeModule("search");
class ListenAddElement
{
	public static $searchPropCode = "SEARCH_INDEX";
	public static $SITE_ID = "s2";
	
	function main(&$arFields){
		if( CModule::includeModule("search") && CModule::includeModule("iblock") ){
			
			if(in_array($arFields["IBLOCK_ID"], CStatic::$catalogIdBlock)) {		
			
			
				$exist =  self::checkSearchProp($arFields);
				if( $exist === false ){
					self::setSearchProp($arFields);			
				}
			
			}
		}
	}
	
	function setSearchProp($arFields){
		
		//$SearchPropValue = preg_replace("#[\s_]#", "", $arFields["NAME"]);
		// так лучше, чтобы манагеры все смогли находить, и не выносили нам мозг.
		$SearchPropValue = str_replace(' ','', $arFields["NAME"]).' '.str_replace(' ','', preg_replace("|[^\d\w ]+|i","",$arFields["NAME"]));
		$SearchPropValue = mb_strtolower($SearchPropValue);		
		
		
		//pre($SearchPropValue);
		
		//die();
		
		
		CIBlockElement::SetPropertyValuesEx(
			$arFields["ID"], 
			$arFields["IBLOCK_ID"], 
			array( self::$searchPropCode => $SearchPropValue)
		);
		self::addToSearchIndex($arFields, $SearchPropValue);
	}
	
	function addToSearchIndex($arFields, $SearchPropValue){		
	
	
		$resInt = CSearch::Index(
			"iblock",
			$arFields["ID"],
			Array(
				//"DATE_CHANGE" => $arFields["DATE_CHANGE"],
				"TITLE" => $arFields["NAME"],
				"SITE_ID" => self::$SITE_ID,
				"PARAM1" => "lb_catalog",
				"PARAM2" => $arFields["IBLOCK_ID"],
				"BODY" => $arFields["DETAIL_TEXT"] . $SearchPropValue,				
			),
			false
		);		
		/*
		pre(Array(
				//"DATE_CHANGE" => $arFields["DATE_CHANGE"],
				"TITLE" => $arFields["NAME"],
				"SITE_ID" => self::$SITE_ID,
				"PARAM1" =>"jetair_catalog",
				"PARAM2" => $arFields["IBLOCK_ID"],
				"BODY" => $arFields["DETAIL_TEXT"] . $SearchPropValue,				
			));
			
			pre($resInt);
		
		die();
		*/
		
	}
	
	function checkSearchProp($arFields){
		$res = CIBlockElement::GetProperty(
			$arFields["IBLOCK_ID"], 
			$arFields["ID"], 
			array(), 
			array( "CODE" => self::$searchPropCode )
		);		
		$val = "";
		/*
		if( $result = $res->Fetch() ){
			$val = $result["VALUE"];
		} else {
			return null;
		}	*/			
		return ( !empty($val) );
	}
}
?>