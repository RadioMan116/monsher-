<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//@todo t50 integration - get all this and call from order_ready file out after testing
//beta version

AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", "sendOrderToController");
function sendOrderToController($id, $arFields = array())
{
    $orderData = array();
    $orderData['ORDER_ID'] = $id;

    $data = convertOrderDataToStandard($orderData);
    $data = serialize($data);

    /* if (!requestPost('http://t50.su/ext/remote/order/CreateOrder.php', array('data' => $data))) storeUnshippedOrder($data); */
	$t50 = new T50HTTP;
	if( !$t50->syncOrder($data) )
		storeUnshippedOrder($data);
}

function resendOrder($data)
{
    /* if (requestPost('http://t50.su/ext/remote/order/CreateOrder.php', array('data' => $data))) return true; */
	$t50 = new T50HTTP;
	return $t50->syncOrder($data);
}

function orderPhoneT50($params){
	$shopUnid = "LHR";

	$fio = iconv_(htmlspecialcharsbx($params["FIO"]));
	$phone = htmlspecialcharsbx($params["PHONE"]);
	$test = (bool) $params["TEST"];
	$fromUrl = htmlspecialcharsbx($params["FROM_URL"]);
	$city = htmlspecialcharsbx($params["CITY"]);
	$bicontent = htmlspecialcharsbx($params["BICONTENT"]);

	$post = compact("fio", "phone", "test", "shopUnid", "fromUrl", "city", "bicontent");

	$ch = curl_init("http://t50.su/ext/remote/order/OrderPhone.php");
	curl_setopt_array($ch, array(
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => http_build_query($post),
	));
	$data = curl_exec($ch);
	curl_close($ch);

	$result = ( trim($data) === "ok" );

	return $result;
}


//2 person types now, 1 pay system supported
function convertOrderDataToStandard($orderData)
{
    $shopUnid = 'MNHR';

    $individualPersonTypeId = 1;
    $cashPaySystemId = 1;

    $namePropId = 1;
    $phonePropId = 2;
    $emailPropId = 3;
    $cityPropId = 0;
    $addressPropId = 5;
    $deliveryDatePropId = 4;
    $commentPropId = 0;
    $trackcodePropId = 0;

	$elevatorPropId = 6;
	$floorPropId = 7;

	$bicontentPropId = 10;
	$TRACK_SID = 8;
	$TRACK_UUID = 9;

    CModule::IncludeModule("iblock");
    CModule::IncludeModule("catalog");
    CModule::IncludeModule("sale");

    $data = array();

    if (!isset($orderData)) $orderData['ORDER_ID'] = $_REQUEST['ORDER_ID'];

    $order = CSaleOrder::GetByID($orderData['ORDER_ID']);

    if ($order['PERSON_TYPE_ID'] == $individualPersonTypeId) $data['PERSON_TYPE'] = 'individual';
    else
    {
        $data['PERSON_TYPE'] = 'another';

        $personType = CSalePersonType::GetByID($order['PERSON_TYPE_ID']);
        $data['PERSON_TYPE_ANOTHER'] = iconv_($personType['NAME']);
    }

    if ($order['PAY_SYSTEM_ID'] == $cashPaySystemId || $order['PAY_SYSTEM_ID'] == false) $data['PAY_SYSTEM'] = 'cash';
    else
    {
        $data['PAY_SYSTEM'] = 'another';

        $paySystem = CSalePaySystem::GetByID($order['PAY_SYSTEM_ID']);
        $data['PAY_SYSTEM_ANOTHER'] = $paySystem['NAME'];
    }

    //no delivery enabled in system now, so no id is checked
    if ($order['DELIVERY_ID'] == false) $data['DELIVERY'] = 'courier';
    else
    {
        $data['DELIVERY'] = 'another';

        $delivery = CSaleDelivery::GetByID($order['DELIVERY_ID']);
        $data['DELIVERY_ANOTHER'] = $delivery['NAME'];
    }

    $dbOrderProps = CSaleOrderPropsValue::GetOrderProps($orderData['ORDER_ID']);
    while ($arOrderProps = $dbOrderProps->GetNext())
    {
        $orderData['PROPS'][$arOrderProps['ORDER_PROPS_ID']] = $arOrderProps;
    }

    $data['PROPS']['NAME'] = iconv_($orderData['PROPS'][$namePropId]['VALUE']);
    $data['PROPS']['PHONE'] = $orderData['PROPS'][$phonePropId]['VALUE'];
    $data['PROPS']['EMAIL'] = $orderData['PROPS'][$emailPropId]['VALUE'];
    $data['PROPS']['CITY'] = iconv_($orderData['PROPS'][$cityPropId]['VALUE']);

    if (intval($data['PROPS']['CITY']))
    {
        $location = CSaleLocation::GetByID($data['PROPS']['CITY']);
        $data['PROPS']['CITY'] = iconv_($location['CITY_NAME_ORIG']);
    }

    $data['PROPS']['ADDRESS'] = iconv_($orderData['PROPS'][$addressPropId]['VALUE']);
    $data['PROPS']['DELIVERY_DATE'] = $orderData['PROPS'][$deliveryDatePropId]['VALUE'];
    $data['PROPS']['COMMENT'] = iconv_($orderData['PROPS'][$commentPropId]['VALUE']);
	if( !empty($order['COMMENTS']) )
		$data['PROPS']['COMMENT'] = iconv_($order['COMMENTS']);
	if( !empty($order['USER_DESCRIPTION']) )
		$data['PROPS']['COMMENT'] = iconv_($order['USER_DESCRIPTION']);
    $data['PROPS']['TRACKCODE'] = $orderData['PROPS'][$trackcodePropId]['VALUE'];
	$data['PROPS']['BICONTENT'] = $orderData['PROPS'][$bicontentPropId]['VALUE'];
	$data['PROPS']['TRACK_SID'] = $orderData['PROPS'][$TRACK_SID]['VALUE'];
	$data['PROPS']['TRACK_UUID'] = $orderData['PROPS'][$TRACK_UUID]['VALUE'];

	$data['PROPS']['FLOOR'] = $orderData['PROPS'][$floorPropId]['VALUE'];
	$data['PROPS']['ELEVATOR'] = $orderData['PROPS'][$elevatorPropId]['VALUE'];

    $data['ORDER_PRICE'] = $order['PRICE'];
    $data['TAX'] = $order['TAX_VALUE'];

    $data['ORDER_WEIGHT'] = 0;

    $basketProducts = array();
    $dbBasket = CSaleBasket::GetList(($b="NAME"), ($o="ASC"), array("ORDER_ID"=>$orderData['ORDER_ID']));
    while ($arBasket = $dbBasket->Fetch())
    {
        $basketProducts[] = $arBasket;

        $arBasket["WEIGHT"] = DoubleVal($arBasket["WEIGHT"]);
        $data['ORDER_WEIGHT']+= $arBasket["WEIGHT"] * $arBasket["QUANTITY"];
    }

    $products = array();

    foreach ($basketProducts as $basketProduct)
    {
        $dbProductPre = CIBlockElement::GetByID($basketProduct['PRODUCT_ID']);
        if($arProductPre = $dbProductPre->GetNext())
        {
            $dbProduct = CIBlockElement::GetList(array(), array('INCLUDE_SUBSECTIONS' => 'Y', 'ID' => $arProductPre['ID'], 'IBLOCK_ID' => $arProductPre['IBLOCK_ID']),
                false, false, array('ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'PROPERTY_MODEL', 'PROPERTY__UNID')
            );
            $arProduct = $dbProduct->GetNextElement();
            $product = $arProduct->GetFields();

            $dbPriceType = CCatalogGroup::GetList(array(), array('NAME_LANG' => $basketProduct['NOTES']));
            if ($arPriceType = $dbPriceType->Fetch()) $priceType = $arPriceType['NAME'];
            else $priceType = $basketProduct['NOTES'];

            $products[] = array(
                'unid' => $product['PROPERTY__UNID_VALUE'], 'quantity' => $basketProduct['QUANTITY'], 'price' => $basketProduct['PRICE'],
                'currency' => $basketProduct['CURRENCY'], 'id' => $product['ID'], 'name' => iconv_($product['NAME']), 'model' => iconv_($product['PROPERTY_MODEL_VALUE']),
                'iblock_id' => $product['IBLOCK_ID'], 'iblock_section_id' => $product['IBLOCK_SECTION_ID'], 'price_type' => iconv_($priceType), 'weight' => $basketProduct['WEIGHT']
            );
        }
    }
    $data['BASKET_PRODUCTS'] = $products;

    $data['SHOP_UNID'] = $shopUnid;
    $data['BASE_LANG_CURRENCY'] = $order['CURRENCY'];
    $data['DELIVERY_PRICE'] = $order['PRICE_DELIVERY'];
    $data['TAX_PRICE'] = $order['TAX_VALUE'];
    $data['ORDER_ID'] = $orderData['ORDER_ID'];

    return $data;
}

function storeUnshippedOrder($data)
{
    $shopUnid = 'LHR';
}

function requestPost($url, $postdata, $files = null)
{
    $data = "";
    $boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);

    //Collect Postdata
    foreach($postdata as $key => $val)
    {
        $data .= "--$boundary\n";
        $data .= "Content-Disposition: form-data; name=\"".$key."\"\n\n".$val."\n";
    }

    $data .= "--$boundary\n";

    //Collect Filedata
    if (isset($files))
    {
        foreach($files as $key => $file)
        {
            $fileContents = file_get_contents($file['tmp_name']);

            $data .= "Content-Disposition: form-data; name=\"{$key}\"; filename=\"{$file['name']}\"\n";
            $data .= "Content-Type: image/jpeg\n";
            $data .= "Content-Transfer-Encoding: binary\n\n";
            $data .= $fileContents."\n";
            $data .= "--$boundary--\n";
        }
    }

    $params = array('http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: multipart/form-data; boundary='.$boundary,
        'content' => $data
    ));

    $ctx = stream_context_create($params);
    $fp = fopen($url, 'rb', false, $ctx);

    if (!$fp) {
        return false;
    }

    $response = @stream_get_contents($fp);

    if ($response === false) return false;
    elseif ($response == 'ok') return true;
    else return false;
}

function iconv_($text){
	return iconv("utf-8", "cp1251", $text);
}

class T50HTTP
{
	function syncOrder($serialize){
		$data = array('data' => $serialize);
		return $this->syncOk('http://t50.su/ext/remote/order/CreateOrder.php', $data);
	}

	function syncOk($url, $data = array(), $options = array()){
		$result = $this->send($url, $data, $options);
		if( DBG === true ){
			echo "<pre>result "; var_dump($result); echo "</pre>";
		}
		return ( trim($result) == "ok" );
	}

	function send($url, $data = array(), $options = array()){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->getAccessString());
		if( !empty($data) ){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}

	private function getAccessString(){
		$data = json_decode(file_get_contents("http://market3.t50.su/p.php"));
		$accessString = "{$data->login}:{$data->password}";
		return $accessString;
	}
}
?>