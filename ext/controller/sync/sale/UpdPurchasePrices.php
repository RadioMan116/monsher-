<?
class UpdPurchasePrices
{
	private $catalogType = "lb_catalog";
	private $catalogPriceId = 1;

	function getComissions(){
		$arResult = array();
		$products = MnLibElementElement::getForIBlockType(
			$this->catalogType,
			array( ),
			array('ID', 'IBLOCK_ID', 'PROPERTY_COMMISSION'),
			array('NO_ADDITIONS' => 'Y', 'NO_PROPS' => 'Y', 'ACTIVE' => null)
		);
		foreach($products as $item){
			$commission = (int) $item["PROPERTY_COMMISSION_VALUE"];
			if( $commission > 0 )
				$arResult[$item["ID"]] = $commission;
		}
		return $arResult;
	}

	function getPrices($productsId){
		if( empty($productsId) )
			return array();

		$arResult = array();
		$res = CPrice::GetList(
			array(),
			array(
                "PRODUCT_ID" => $productsId,
                "CATALOG_GROUP_ID" => $this->catalogPriceId,
                ">PRICE" => 0
            ),
			false, false,
			array("ID", "PRODUCT_ID", "PRICE")
		);

		while( $result = $res->getNext() )
			$arResult[$result["PRODUCT_ID"]] = $result["PRICE"];

		return $arResult;
	}

	function getCurrentPurchases($productsId){
		if( empty($productsId) )
			return array();

		$res = CCatalogProduct::GetList(
			array(), array("ID" => $productsId),
			false, false, array("ID", "PURCHASING_PRICE" )
		);
		$arResult = array();
		while( $result = $res->getNext() )
			$arResult[$result["ID"]] = $result["PURCHASING_PRICE"];

		return $arResult;
	}

	function updatePurchases(){
		$commissions = $this->getComissions();
		$productsId = array_keys($commissions);
		$prices = $this->getPrices($productsId);
		$currentPurchases = $this->getCurrentPurchases($productsId);
		foreach($prices as $productId => $price){
			$purchase = $price - $commissions[$productId];
			if( $purchase != $currentPurchases[$productId] ){
				$updOk = CCatalogProduct::Update($productId, array("PURCHASING_PRICE" => $purchase));
				//echo "id {$productId} - " . ( $updOk ? "ok" : "FAIL!!!" ) . "<br/>";die();
			}
		}
	}
}

?>