<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
//$APPLICATION->RestartBuffer();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
$el = new CIBlockElement;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

include_once($_SERVER["DOCUMENT_ROOT"]."/_ecommerce/class.php");


use Bitrix\Main;
$USER = new CUser;
$UID = $USER->GetID();

$arResult = array(
	"SUCCESS" => "N",	
	"CAPTCHA" => "Y",
	"MESSAGE" => "",
	"RELOAD" => "N",
	"DATA" => array(),
	"CLEARPOLS" => "Y",
);



switch ($request->get('action')) {

	case "GetDataList":
	
		if($request->offsetGet('items')) {
			$arItems = explode(',',$request->offsetGet('items'));		

			$prodids = $request->offsetGet('items');
			$totalvalue = 0;
			$Pagetype = '';
			
			foreach($arItems as $k=>$id) {
				
				if($arProduct = CE::GetByProduct($id)) {
					$arProductReady = CE::GetCommerceData($arProduct);	
					
					if($request->offsetGet('list')) $arProductReady["list"] = $request->offsetGet('list');
					$arProductReady["position"] = $k + 1;
					$arResult["DATA"][] = $arProductReady;
					
					if(!$Pagetype) $Pagetype = $arProductReady["list"];
					$totalvalue = $totalvalue + $arProductReady["price"];
				}
			}	
			
			$arResult["DATA_2"] = array(
				'prodids' => $prodids,
				'Pagetype' => $Pagetype,
				'totalvalue' => $totalvalue,
			);
			
			$arResult["SUCCESS"] = "Y";
			
		}
	
	
	break;
	case "GetDataBasket":
	
		$order_id = false;
		$prod_id = false;
		if($request->offsetGet('order_id')) $order_id = $request->offsetGet('order_id');
		if($request->offsetGet('prod_id')) $prod_id = $request->offsetGet('prod_id');
	
		if($prod_id) {
			
			if($arProduct = CE::GetByProduct($prod_id)) {
				
				$arProductReady = CE::GetCommerceData($arProduct);	
				$arProductReady["quantity"] = 1;
				
				//pre($arProductReady);
				
				$arResult["DATA"] = array($arProductReady);
			}
			
		}
		else {
			
			
			
			
			$arItems = CE::goodInBasket(false, $order_id);
			
			$prodids = array();
			$totalvalue = 0;
			$Pagetype = '';
		
			$arResult["SUM"] = 0;
			foreach($arItems as $k=>$item) {
				
				if($arProduct = CE::GetByProduct($item["PRODUCT_ID"])) {
					$arProductReady = CE::GetCommerceData($arProduct);	
					$arProductReady["quantity"] = (int)$item['QUANTITY'];
					//$arProductReady["position"] = $k+1;
					
					$arResult["DATA"][] = $arProductReady;
					
					$arResult["SUM"] = $arResult["SUM"] + (int)$item["QUANTITY"]*(int)$item["PRICE"];	

					if(!$Pagetype) $Pagetype = $arProductReady["list"];
					$totalvalue = $totalvalue + $arProductReady["price"];
					$prodids[] = $item["PRODUCT_ID"];
				}
			}

			$arResult["DATA_2"] = array(
				'prodids' => implode(',',$prodids),
				'Pagetype' => $Pagetype,
				'totalvalue' => $totalvalue,
			);
		}
	
		
		
		$arResult["SUCCESS"] = "Y";
	
	break;
	case "GetDataProduct":

		if($request->offsetGet('ID') || $request->offsetGet('ID2')) {			
			
			
			
			if($request->offsetGet('ID')) $product_id = $request->offsetGet('ID');
			else {				
				$res = current(CE::goodInBasket($request->offsetGet('ID2')));
				$product_id = $res['PRODUCT_ID'];
			}
			
			if($arProduct = CE::GetByProduct($product_id)) {
				
				
				
				
				$arProductReady = CE::GetCommerceData($arProduct);	
				if($res['QUANTITY']) $arProductReady["quantity"] = (int)$res['QUANTITY'];
				//pre($arProductReady);
				$arResult["DATA"] = $arProductReady;
				
				
				if($request->offsetGet('items')) {
					$arItems = explode(',',$request->offsetGet('items'));		

					$prodids = $request->offsetGet('items');
					$totalvalue = 0;
					$Pagetype = '';
					
					foreach($arItems as $k=>$id) {
						
						if($arProduct = CE::GetByProduct($id)) {
							$arProductReady = CE::GetCommerceData($arProduct);	
							
							if($request->offsetGet('list')) $arProductReady["list"] = $request->offsetGet('list');
							$arProductReady["position"] = $k + 1;
							$arResult["DATA2"][] = $arProductReady;
							
							if(!$Pagetype) $Pagetype = $arProductReady["list"];
							$totalvalue = $totalvalue + $arProductReady["price"];
						}
					}	
								
					
				}
				
				
				
				
				
				
				
				
				
				
				
				$arResult["SUCCESS"] = "Y";
			}





			
		}		
	
	break;
}









	if($arResult["RELOAD"]!='Y')
	{
		if($html == '' && $title!='') {
			$html = '<div class="response-popup">
						<div class="response-popup__inner">
							<div class="response-popup__title">'.$title.'</div>
							<div class="response-popup__text">'.$html_text.'</div>							
						</div>
					</div>';			
			
		}
		$arResult["HTML"] = $html;
	}

	echo json_encode($arResult);



	
	
	
	
include_once ($_SERVER['DOCUMENT_ROOT'] .'/bitrix/modules/main/include/epilog_after.php');
?>