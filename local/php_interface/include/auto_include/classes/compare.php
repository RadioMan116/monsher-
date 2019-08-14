<?
class CMNTCompare extends CMNTCompareCommon
{
	function AddElement($elementID, $iblockID = false)
	{
		$elementID = intval($elementID);
		
		if($elementID <= 0)
			return false;
		
		if($iblockID > 0 && !empty($_SESSION["CATALOG_COMPARE_LIST"][$iblockID]["ITEMS"][$elementID]))
			return true;
		
		if(CModule::IncludeModule("iblock"))
		{
			$arInfoList = CMNTProductCache::GetByID(array("ID" => $elementID, "TYPE" => "list"));
			
			if(!empty($arInfoList["NAME"]))
			{
				$_SESSION["CATALOG_COMPARE_LIST"][$arInfoList["IBLOCK_ID"]]["ITEMS"][$elementID] = array(
					"NAME" => $arInfoList["NAME"],
					"BRAND" => $arInfoList["PRODUCT_PARAMS"]["BRAND"],
					"MODEL" => $arInfoList["PRODUCT_PARAMS"]["MODEL"],
					"CODE" => $arInfoList["CODE"],
					"IBLOCK_SECTION_ID" => $arInfoList["IBLOCK_SECTION_ID"],
					"DETAIL_PAGE_URL" => $arInfoList["DETAIL_PAGE_URL"],
				);
				return true;
			}
		}
		
		return false;
	}
}
?>