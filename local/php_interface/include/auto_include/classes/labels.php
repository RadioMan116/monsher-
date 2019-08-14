<?
class CMNTLabels
{
	public static function GetLabelsForElementList($arParamsCurrent = array())
	{
		$arParamsDefault = array(
			"ELEMENT" => array(),
		);
		$arParams = array();
		foreach($arParamsDefault as $optKey => $optVal)
			$arParams[$optKey] = is_array($arParamsCurrent) && array_key_exists($optKey, $arParamsCurrent) ? $arParamsCurrent[$optKey] : $arParamsDefault[$optKey];
		
		if(!is_array($arParams["ELEMENT"]))
			$arParams["ELEMENT"] = array();
		
		if(empty($arParams["ELEMENT"]["PROPERTIES"]) || !is_array($arParams["ELEMENT"]["PROPERTIES"]))
			return false;
		
		$arResult = array();
		$arResultLabelIDs = array();
		
		if(!empty($arParams["ELEMENT"]["PROPERTIES"]["LABELS"]["VALUE"]))
		{
			foreach($arParams["ELEMENT"]["PROPERTIES"]["LABELS"]["VALUE"] as $labelID)
			{
				$arResultLabelIDs[] = $labelID;
			}
		}
		
		$arResultLabelCodes = array();
		
		if($arParams["ELEMENT"]["PROPERTIES"]["RECOMMENDED"]["VALUE"] == "Y")
			$arResultLabelCodes[] = "recommended";
		
		if($arParams["ELEMENT"]["PROPERTIES"]["HIT"]["VALUE"] == "Y")
			$arResultLabelCodes[] = "hit";
		
		if($arParams["ELEMENT"]["PROPERTIES"]["NEW"]["VALUE"] == "Y")
			$arResultLabelCodes[] = "new";
		
		if(!empty($arResultLabelIDs) || !empty($arResultLabelCodes))
		{
			$arLabelsData = CMNTCached::GetLabels();
			$arLabelExceptions = CMNTCached::GetLabelExceptions();
			
			foreach($arLabelsData as $labelID => $arLabel)
			{
				if(in_array($labelID, $arResultLabelIDs) || in_array($arLabel["CODE"], $arResultLabelCodes))
				{
					if(self::CheckLabelExceptions(array(
						"EXCEPTIONS" => $arLabelExceptions,
						"LABEL_CODE" => $arLabel["CODE"],
						"LABEL_TYPE" => "list",
						"ID" => $arParams["ELEMENT"]["ID"],
						"IBLOCK_ID" => $arParams["ELEMENT"]["IBLOCK_ID"],
						"IBLOCK_SECTION_ID" => $arParams["ELEMENT"]["IBLOCK_SECTION_ID"],
						"BASE_PRODUCT_ID" => $arParams["ELEMENT"]["BASE_PRODUCT_ID"],
						"BASE_PRODUCT_IBLOCK_ID" => $arParams["ELEMENT"]["BASE_PRODUCT_IBLOCK_ID"],
						"BASE_PRODUCT_IBLOCK_SECTION_ID" => $arParams["ELEMENT"]["BASE_PRODUCT_IBLOCK_SECTION_ID"],
					)))
						$arResult[] = $labelID;
				}
			}
			
			$arResult = array_unique($arResult);
		}
		
		return $arResult;
	}
	
	public static function GetLabelsForElementBuy($arParamsCurrent = array())
	{
		$arParamsDefault = array(
			"ID" => "",
			"IBLOCK_ID" => "",
			"IBLOCK_SECTION_ID" => "",
			"BASE_PRODUCT_ID" => "",
			"BASE_PRODUCT_IBLOCK_ID" => "",
			"BASE_PRODUCT_IBLOCK_SECTION_ID" => "",
			"CATALOG" => array(),
		);
		$arParams = array();
		foreach($arParamsDefault as $optKey => $optVal)
			$arParams[$optKey] = is_array($arParamsCurrent) && array_key_exists($optKey, $arParamsCurrent) ? $arParamsCurrent[$optKey] : $arParamsDefault[$optKey];
		
		$arParams["ID"] = intval($arParams["ID"]);
		$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
		
		if($arParams["ID"] <= 0 || $arParams["IBLOCK_ID"] <= 0)
			return false;
		
		if(empty($arParams["CATALOG"]) || !is_array($arParams["CATALOG"]))
			return false;
		
		$arResult = array();
		$arResultLabelCodesAll = array();
		
		foreach($arParams["CATALOG"] as $priceID => $arBuyParams)
		{
			if($arBuyParams["OLD_PRICE"] > $arBuyParams["PRICE"])
				$arResultLabelCodesAll[$priceID][] = "discount";
			
			$arResultLabelCodesAll[$priceID][] = "free-delivery";
			$arResultLabelCodesAll[$priceID][] = "free-install";
			$arResultLabelCodesAll[$priceID][] = "warranty";
		}
		
		if(!empty($arResultLabelCodesAll))
		{
			$arLabelsData = CMNTCached::GetLabels();
			$arLabelExceptions = CMNTCached::GetLabelExceptions();
			
			foreach($arResultLabelCodesAll as $priceID => $arResultLabelCodes)
			{
				$arResultLabelCodes = array_unique($arResultLabelCodes);
				foreach($arLabelsData as $labelID => $arLabel)
				{
					if(in_array($arLabel["CODE"], $arResultLabelCodes))
					{
						if(self::CheckLabelExceptions(array(
							"EXCEPTIONS" => $arLabelExceptions,
							"LABEL_CODE" => $arLabel["CODE"],
							"LABEL_TYPE" => "buy",
							"ID" => $arParams["ID"],
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
							"IBLOCK_SECTION_ID" => $arParams["IBLOCK_SECTION_ID"],
							"BASE_PRODUCT_ID" => $arParams["BASE_PRODUCT_ID"],
							"BASE_PRODUCT_IBLOCK_ID" => $arParams["BASE_PRODUCT_IBLOCK_ID"],
							"BASE_PRODUCT_IBLOCK_SECTION_ID" => $arParams["BASE_PRODUCT_IBLOCK_SECTION_ID"],
							"PRICE" => $arParams["CATALOG"][$priceID]["PRICE"]
						)))
							$arResult[$priceID][] = $labelID;
					}
				}
			}
		}
		
		return $arResult;
	}
	
	public static function CheckLabelExceptions($arParamsCurrent = array())
	{
		$arParamsDefault = array(
			"EXCEPTIONS" => "",
			"LABEL_CODE" => "",
			"LABEL_TYPE" => "",
			"ID" => "",
			"IBLOCK_ID" => "",
			"IBLOCK_SECTION_ID" => "",
			"BASE_PRODUCT_ID" => "",
			"BASE_PRODUCT_IBLOCK_ID" => "",
			"BASE_PRODUCT_IBLOCK_SECTION_ID" => "",
			"PRICE" => "",
		);
		$arParams = array();
		foreach($arParamsDefault as $optKey => $optVal)
			$arParams[$optKey] = is_array($arParamsCurrent) && array_key_exists($optKey, $arParamsCurrent) ? $arParamsCurrent[$optKey] : $arParamsDefault[$optKey];
		
		$arParams["PRICE"] = floatval($arParams["PRICE"]);
		
		if(strlen($arParams["LABEL_CODE"]) <= 0)
			return false;
		
		if(!is_array($arParams["EXCEPTIONS"]))
			$arParams["EXCEPTIONS"] = CMNTCached::GetLabelExceptions();
		
		if(!empty($arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["IBLOCK_IDS"]) && is_array($arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["IBLOCK_IDS"]))
		{
			if(!empty($arParams["IBLOCK_ID"]) && in_array($arParams["IBLOCK_ID"], $arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["IBLOCK_IDS"]))
				return false;
			
			if(!empty($arParams["BASE_PRODUCT_IBLOCK_ID"]) && in_array($arParams["BASE_PRODUCT_IBLOCK_ID"], $arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["IBLOCK_IDS"]))
				return false;
		}
		
		if(!empty($arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["SECTION_IDS"]) && is_array($arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["SECTION_IDS"]))
		{
			if(!empty($arParams["IBLOCK_SECTION_ID"]) && in_array($arParams["IBLOCK_SECTION_ID"], $arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["SECTION_IDS"]))
				return false;
			
			if(!empty($arParams["BASE_PRODUCT_IBLOCK_SECTION_ID"]) && in_array($arParams["BASE_PRODUCT_IBLOCK_SECTION_ID"], $arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["SECTION_IDS"]))
				return false;
		}
		
		if(!empty($arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["ELEMENT_IDS"]) && is_array($arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["ELEMENT_IDS"]))
		{
			if(!empty($arParams["ID"]) && in_array($arParams["ID"], $arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["ELEMENT_IDS"]))
				return false;
			
			if(!empty($arParams["BASE_PRODUCT_ID"]) && in_array($arParams["BASE_PRODUCT_ID"], $arParams["EXCEPTIONS"][$arParams["LABEL_CODE"]]["ELEMENT_IDS"]))
				return false;
		}
		
		// тип buy - лейблы, привязываемые к товару автоматически, требуется проверка по цене
		// лейблы с кодами free-delivery и free-install, привязанные вручную, не должны проверяться по цене
		if($arParams["LABEL_TYPE"] == "buy")
		{
			if($arParams["LABEL_CODE"] == "free-delivery")
			{
				if(strlen($GLOBALS["SITE_CONFIG"]["SESSION_PARAMS"]["REGION_INFO"]["PRICE_FREE_DELIVERY"]) <= 0)
					return false;
				
				if($arParams["PRICE"] < $GLOBALS["SITE_CONFIG"]["SESSION_PARAMS"]["REGION_INFO"]["PRICE_FREE_DELIVERY"])
					return false;
			}
			
			if($arParams["LABEL_CODE"] == "free-install")
			{
				if(strlen($GLOBALS["SITE_CONFIG"]["SESSION_PARAMS"]["REGION_INFO"]["PRICE_FREE_INSTALL"]) <= 0)
					return false;
				
				if($arParams["PRICE"] < $GLOBALS["SITE_CONFIG"]["SESSION_PARAMS"]["REGION_INFO"]["PRICE_FREE_INSTALL"])
					return false;
			}
		}
		
		return true;
	}
}
?>