<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');


$shopUnid = 'LHR';


global $DB;

$dbOrders = $DB->Query("SELECT * FROM t50_orders_unsent WHERE shop_unid = '".$shopUnid."'");

$orders = array();
while ($arOrder = $dbOrders->Fetch()) $orders[] = $arOrder;

foreach ($orders as $order)
{
    $data = unserialize($order['data']);

    if (sendOrderToController($data['ORDER_ID'])) $dbOrders = $DB->Query("DELETE FROM t50_orders_unsent WHERE id = '".$order['id']."'");
    else continue;
}

echo 'done';

?>