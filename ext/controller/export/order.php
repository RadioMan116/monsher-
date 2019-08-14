<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');


if (isset($_POST['data'])) $postData = unserialize($_POST['data']);

if (isset($postData['ORDER_ID']))
{
    sendOrderToController($postData['ORDER_ID']);
}

echo 'done';

?>