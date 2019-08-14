<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//AddMessage2Log("CMNTClearProductCacheEvents");

AddEventHandler("catalog", "OnPriceUpdate", Array("CMNTClearProductCacheEvents", "OnPriceUpdate"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("CMNTClearProductCacheEvents", "OnAfterIBlockElementUpdate"));

class CMNTClearProductCacheEvents
{
	function OnAfterIBlockElementUpdate($arFields)
	{
		CMNTProductCache::ClearByID(array("ID" => $arFields["ID"]));
		//AddMessage2Log("OnAfterIBlockElementUpdate: ".print_r($arFields, true));
		
		if(CMNTSku::HasSku())
		{
			if(!empty($arFields["IBLOCK_ID"]) && CMNTSku::IsSkuIblock($arFields["IBLOCK_ID"]))
			{
				$resElSku = CIBlockElement::GetList(array(), array("ID" => $arFields["ID"], "IBLOCK_ID" => $arFields["IBLOCK_ID"], "ACTIVE" => ""), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID", "PROPERTY_CML2_LINK"));
				if($arFieldsElSku = $resElSku->Fetch())
				{
					$productID = intval($arFieldsElSku["PROPERTY_CML2_LINK_VALUE"]);
					
					if($productID > 0)
					{
						CMNTProductCache::ClearByID(array("ID" => $productID));
						
						if(defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]))
						{
							$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$productID);
							
							$productIblockID = intval(CMNTSku::GetProductIblockID($arFieldsElSku["IBLOCK_ID"]));
							
							if($productIblockID > 0)
								$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$productIblockID);
						}
					}
				}
			}
		}
	}
	
	function OnPriceUpdate($priceID, $arFields)
	{
		//AddMessage2Log("OnPriceUpdate: ".print_r($arFields, true));
		CMNTProductCache::ClearByID(array("ID" => $arFields["PRODUCT_ID"], "PRODUCT_CACHE_TYPES" => array("buy")));
		
		if(CMNTSku::HasSku())
		{
			$resElSku = CIBlockElement::GetList(array(), array("ID" => $arFields["PRODUCT_ID"], "ACTIVE" => ""), false, array("nTopCount" => 1), array("ID", "IBLOCK_ID"));
			if($arFieldsElSku = $resElSku->Fetch())
			{
				if(CMNTSku::IsSkuIblock($arFieldsElSku["IBLOCK_ID"]))
				{
					$resProp = CIBlockElement::GetProperty($arFieldsElSku["IBLOCK_ID"], $arFields["PRODUCT_ID"], array(), array("CODE" => "CML2_LINK"));
					if($arFieldsProp = $resProp->Fetch())
					{
						$productID = intval($arFieldsProp["VALUE"]);
						
						if($productID > 0)
						{
							CMNTProductCache::ClearByID(array("ID" => $productID, "PRODUCT_CACHE_TYPES" => array("buy")));
							
							if(defined("BX_COMP_MANAGED_CACHE") && is_object($GLOBALS["CACHE_MANAGER"]))
							{
								$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_id_".$productID);
								
								$productIblockID = intval(CMNTSku::GetProductIblockID($arFieldsElSku["IBLOCK_ID"]));
								
								if($productIblockID > 0)
									$GLOBALS["CACHE_MANAGER"]->ClearByTag("mntproduct_iblock_id_".$productIblockID);
							}
						}
					}
				}
			}
		}
	}
}
?>