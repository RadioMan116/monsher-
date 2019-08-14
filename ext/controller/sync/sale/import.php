<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
ini_set('post_max_size', '256M');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$HAS_PRICES = false;

$catalogType = 'lb_catalog';
$priceTypeIds = array('MSK' => 1, 'SPB' => 2);
$availableCodesLinkage = array(
    0 => 'null',
    1 => 'В наличии',
    2 => 'Под заказ',
    3 => 'Нет в наличии',
    4 => 'Снят с производства'
);
$territoryCodeToPropArray = array(
    'MSK' => 'MSK',
    'SPB' => 'SPB'
);


//preprocessing
$availPropsData = array();
$iBlocks = MnLibIBlockIBlock::getByType($catalogType, array('ACTIVE' => null));
foreach ($iBlocks as $iBlock)
{
    foreach ($territoryCodeToPropArray as $territoryCode => $territoryValue)
        $availPropsData[$iBlock['ID']][$territoryValue] = MnLibIBlockPropertyProperty::getListTypeDataByCodeAndIBlockId($territoryValue, $iBlock['ID']);
}

$products = MnLibElementElement::getForIBlockType($catalogType, array(),
    array('ID', 'IBLOCK_ID', 'PROPERTY__UNID', 'PROPERTY_MSK', 'PROPERTY_SPB', 'PROPERTY_FAST_DELIVERY', 'PROPERTY_COMMISSION', "PROPERTY_ARRIVAL_DATE"), array('NO_ADDITIONS' => 'Y', 'NO_PROPS' => 'Y', 'ACTIVE' => null)
);
$productsIndexedByUnid = MnLibToolsTools::reindexArrayBy(array('PROPERTY__UNID_VALUE'), $products);


$postData = unserialize($_POST['data']);
MnServiceSimpleXMLSimpleXML::export($postData, 'last_income');
$xml = simplexml_load_string($postData);
$xmlData = MnServiceSimpleXMLSimpleXML::convertNodeToStrings($xml);

foreach ($xmlData['offer'] as $xmlOffer)
{
    //get data from xml
    $productUnid = $xmlOffer['unid'];

	$bHasFastDelivery = false;
	if(array_key_exists('fast_delivery', $xmlOffer))
	{
		$bHasFastDelivery = true;
		$productFastDelivery = $xmlOffer['fast_delivery'] == 'Y' ? 'Y' : '';
	}

    $productPrices = array();
    foreach ($xmlOffer['prices'] as $xmlOfferPrice)
    {
        $productPrices[] = array('value' => $xmlOfferPrice['value'], 'type' => $xmlOfferPrice['territory_code']);
		//if( $xmlOfferPrice['territory_code'] == 'MSK' ){ // copy in SPB
		//	$xmlOfferPrice['territory_code'] = 'SPB';
		//	$productPrices[] = array('value' => $xmlOfferPrice['value'], 'type' => $xmlOfferPrice['territory_code']);
		//}
    }

    $productAvails = $xmlOffer['avails']['avail'];
    $productAvails = MnLibToolsTools::arraySingleToIndexed($productAvails);

    if (isset($productsIndexedByUnid[$productUnid]))
    {
        $product = $productsIndexedByUnid[$productUnid];

		//fast delivery
		if($bHasFastDelivery && $productFastDelivery != $product['PROPERTY_FAST_DELIVERY_VALUE'])
		{
			CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], array('FAST_DELIVERY' => $productFastDelivery));
		}

		// update comission
		//if( !empty($xmlOffer['comissions']) ){
		//	foreach ($xmlOffer['comissions'] as $xmlOfferComission){
		//		if( $product["PROPERTY_COMMISSION_VALUE"] != $xmlOfferComission['value'] )
		//			CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], array('COMMISSION' => $xmlOfferComission['value']));
		//	}
		//}

		// update comission
		if( !empty($xmlOffer['comissions']) ){
			$comissionPropMap = array("MSK" => "COMMISSION", "SPB" => "COMMISSION_SPB");
			foreach ($xmlOffer['comissions'] as $xmlOfferComission){
				$comProp = $comissionPropMap[$xmlOfferComission['territory_code']];
				if( !array_key_exists("PROPERTY_{$comProp}_VALUE", $product) )
					continue;

				if( $product["PROPERTY_{$comProp}_VALUE"] != $xmlOfferComission['value'] )
					CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], array($comProp => $xmlOfferComission['value']));
			}
		}

        //prices
        foreach ($productPrices as $productPrice)
        {
	     if (empty($productPrice['value'])) continue;

			$HAS_PRICES = true;

            if (empty($productPrice['type'])) $productPriceTypeId = $priceTypeIds[0];
            else $productPriceTypeId = $priceTypeIds[$productPrice['type']];

            $priceFields = Array(
                "PRODUCT_ID" => $product['ID'],
                "CATALOG_GROUP_ID" => $productPriceTypeId,
                "PRICE" => $productPrice['value'],
                "CURRENCY" => "RUB"
            );

            $dbPrice = CPrice::GetList(array(), array("PRODUCT_ID" => $product['ID'], "CATALOG_GROUP_ID" => $productPriceTypeId));
            if ($arPrice = $dbPrice->Fetch())
{
if ($priceFields['PRICE'] != $arPrice['PRICE']) CPrice::Update($arPrice["ID"], $priceFields);
}
else CPrice::Add($priceFields);
        }

        //avail
        foreach ($productAvails as $productAvail)
        {
            if ($availableCodesLinkage[$productAvail['value']] == 'null') $availPropValueId == null;
            else
            {
                $availPropData = $availPropsData[$product['IBLOCK_ID']][$territoryCodeToPropArray[$productAvail['territory_code']]];
                foreach ($availPropData as $availPropDataEntry)
                {
                    if ($availPropDataEntry['VALUE'] == $availableCodesLinkage[$productAvail['value']])
                    {
                        $availPropValueId = $availPropDataEntry['ID'];
                        break;
                    }
                }
            }

			// >>> update ARRIVAL_DATE
			if( array_key_exists("PROPERTY_ARRIVAL_DATE_VALUE", $product) ){
				if( empty($productAvail["deadline"]) )
					$productAvail["deadline"] = null;
				if( $product["PROPERTY_ARRIVAL_DATE_VALUE"] != $productAvail["deadline"] ){
					CIBlockElement::SetPropertyValuesEx(
						$product["ID"],
						$product["IBLOCK_ID"],
						array('ARRIVAL_DATE' => $productAvail["deadline"])
					);
				}
			}
			// <<< update ARRIVAL_DATE

            if ($availPropValueId != $product['PROPERTY_' . $territoryCodeToPropArray[$productAvail['territory_code']] . '_ENUM_ID']) CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], array($territoryCodeToPropArray[$productAvail['territory_code']] => array('VALUE' => $availPropValueId)));
        }
    }
}

if( $HAS_PRICES ){
	include dirname(__FILE__) . "/UpdPurchasePrices.php";
	$updPurchasePrices = new UpdPurchasePrices;
	$updPurchasePrices->updatePurchases();
}

echo 'ok';

BXClearCache(true);
?>