<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
ini_set('max_execution_time', 60000);
CModule::IncludeModule("iblock");


$postData = unserialize($_POST['data']);
$xmlCatalog = simplexml_load_string($postData);

foreach ($xmlCatalog as $xmlOffer)
{
    $xmlUnid = (string) $xmlOffer->unid;
    $xmlId = (string) $xmlOffer->shop_element_id;

    $unid = intval($xmlUnid);
    $id = intval($xmlId);

    CIBlockElement::SetPropertyValuesEx($id, false, array('_UNID' => array('VALUE' => $unid)));
	CIBlockElement::UpdateSearch($id, true);
}

echo 'ok';

?>