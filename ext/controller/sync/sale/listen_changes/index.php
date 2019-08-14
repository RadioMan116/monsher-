<?
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

class ListenChanges
{
	private $catalogType = 'lb_catalog';


	private $availCodes = array(null, 'В наличии', 'Под заказ', 'Нет в наличии', 'Снят с производства');

	function run(){
		echo $this->saveData();
	}

	function saveData(){
		$fileName = "data.json";
		$jsonData = json_decode(file_get_contents($fileName), true);
		if( empty($jsonData) )
			$jsonData = $this->getDefaultJsonData();
		$dbData = $this->getData();

		$mergedData = $this->mergeVersions($jsonData, $dbData);
		$mergedData = json_encode($mergedData);
		file_put_contents($fileName, $mergedData);

		return $mergedData;
	}

	function mergeVersions($jsonData, $dbData){
		$versions = array(
			"old_date" => $jsonData["versions"]["new_date"],
			"new_date" => $dbData["dateVersion"],
		);
		$jsonData = $jsonData["data"];
		$dbData = $dbData["data"];
		$jsonDataUnids = array_keys($jsonData);
		$dbDataUnids = array_keys($dbData);

		$matches = array_intersect($jsonDataUnids, $dbDataUnids);
		$hidden = array_diff($jsonDataUnids, $dbDataUnids);
		$new = array_diff($dbDataUnids, $jsonDataUnids);

		$mergeData = array();
		foreach($matches as $k => $unid){
			$jsonItem = $jsonData[$unid]["n"];
			$dbItem = $dbData[$unid];
			$mergeData[$unid] = array(
				"o" => array("p" => $jsonItem["p"], "a" => $jsonItem["a"]),
				"n" => array("p" => $dbItem["p"], "a" => $dbItem["a"]),
			);

			/* emulation changes* /
			if( $k % 10 == 0 ){
				$koeff = ( rand(0, 1) == 1 ? 1 : -1 );
				$mergeData[$unid]["n"]["p"] +=
					$mergeData[$unid]["n"]["p"] * ( rand(1, 3) / 10 ) * $koeff;
			}
			if( $k % 13 == 0 ){
				$mergeData[$unid]["n"]["a"] = rand(1, 4);
			}
			/********************/
		}
		foreach($hidden as $unid){
			$jsonItem = $jsonData[$unid]["o"];
			$mergeData[$unid] = array(
				"o" => array("p" => $jsonItem["p"], "a" => $jsonItem["a"]),
				"n" => array("p" => 0, "a" => 0),
			);
		}
		foreach($new as $unid){
			$dbItem = $dbData[$unid];
			$mergeData[$unid] = array(
				"o" => array("p" => 0, "a" => 0),
				"n" => array("p" => $dbItem["p"], "a" => $dbItem["a"]),
			);
		}
		$data = $mergeData;
		return compact("data", "versions");
	}

	function getData(){
		$products = MnLibElementElement::getForIBlockType(
			$this->catalogType, array( "ACTIVE" => "Y", "!PROPERTY__UNID" => false ),
			array('ID', 'IBLOCK_ID', 'PROPERTY__UNID', 'PROPERTY_MSK', "CATALOG_GROUP_1"),
			array('NO_ADDITIONS' => 'Y', 'NO_PROPS' => 'Y', 'ACTIVE' => null, "INDEX" => array("PROPERTY__UNID_VALUE"))
		);
		$data = array();
		foreach($products as $unid => $item){
			$data[$unid] = array(
				"p" => (int) $item["CATALOG_PRICE_1"],
				"a" => $this->availCodes[$item["PROPERTY_MSK_VALUE"]],
			);
		}
		$dateVersion = date("d.m.Y H:i:s");
		return compact("dateVersion", "data");
	}

	function getDefaultJsonData(){
		$data = array();
		$dateVersion = date("d.m.Y H:i:s");
		$versions = array("new_date" => $dateVersion);
		return compact("versions", "data");
	}

	function __construct(){
		$this->availCodes = array_flip($this->availCodes);
	}

	static function main(){
		if( isset($_GET["test"]) )
			die();

		$obj = new ListenChanges();
		$obj->run();
	}
}
ListenChanges::main();
?>
