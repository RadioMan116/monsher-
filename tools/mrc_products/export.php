<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::includeModule('iblock');

$IBLOCK_TYPE = "mn_catalog";
$IS_MRC_PROP = "IS_MRC";
$UNID_PROP = "_UNID";
$SHOP_UNID = "LHR";


//-----------------------------

$products = MnLibElementElement::getForIBlockType(
	$IBLOCK_TYPE,
	array("!PROPERTY_" . $UNID_PROP => false),
    array("ID", "IBLOCK_ID", "PROPERTY_" . $UNID_PROP, "PROPERTY_" . $IS_MRC_PROP, "CATALOG_GROUP_1", "CATALOG_GROUP_8"),
	array('NO_ADDITIONS' => 'Y', 'NO_PROPS' => 'Y', 'ACTIVE' => null)
);

$xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
$xml .= "<root shop_unid='" . $SHOP_UNID . "'>";
foreach($products as $item){
	$unid = (int) $item["PROPERTY_" . $UNID_PROP . "_VALUE"];
	if( $unid <= 0 )
		continue;
	$isMrc = ( $item["PROPERTY_" . $IS_MRC_PROP . "_VALUE"] == "Y" ? "1" : "0" );
	$price = (int) $item["CATALOG_PRICE_1"];
	$actionPrice = (int) $item["CATALOG_PRICE_8"];
	if( $actionPrice > 0 )
		$price = $actionPrice;

	$xml .= "<item>";
	$xml .= "<unid>" . $unid . "</unid>";
	$xml .= "<is_mrc>" . $isMrc . "</is_mrc>";
	$xml .= "<price>" . $price . "</price>";
	$xml .= "</item>";
}
$xml .= "</root>";

if( file_put_contents("export.xml", $xml) )
	echo "ok";

?>