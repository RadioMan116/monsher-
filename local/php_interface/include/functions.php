<?php



/**
 * Вывод переменной. 3333
 *
 * @param mixed $mixed Переменная.
 * @param string $color Цвет текста в формате #0000ff или blue. По-умолчанию blue.
 * @param string $bgColor Цвет фона в формате #ffffff или white. По-умолчанию white.
 *
 * @return void
 */
 function mb_lcfirst($text) {
    return mb_strtolower(mb_substr($text, 0, 1)) . mb_substr($text, 1);
}
 
function dump ($mixed, $color = "blue", $bgColor = "white") {
	
	echo "<pre style=\"background: white; color: {$color}; background-color: {$bgColor}\">";
	var_dump($mixed);
	echo '</pre>';
	
}
function pre ($mixed, $color = "blue", $bgColor = "white") {
	
	echo "<pre style=\"background: white; color: {$color}; background-color: {$bgColor}\">";
	print_r($mixed);
	echo '</pre>';
	
}
function declOfNum($number, $titles){
    $cases = array (2, 0, 1, 1, 1, 2);
    return $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
}


function translitB($name, $length = 200) { 


	$name = preg_replace("/[0-9]{1}/", "", $name); 

	$arParams = array("change_case"=>"U","replace_space"=>"_","replace_other"=>"_","delete_repeat_replace"=>false,"max_len"=>$length);
	return CUtil::translit($name,"ru",$arParams);
	
} 

function translit($name) { 

	$arParams = array("replace_space"=>"-","replace_other"=>"-","delete_repeat_replace"=>false,"max_len"=>"200");
	return CUtil::translit($name,"ru",$arParams);
	
} 


	//pre($arResult["OFFERS"]);
	function FBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		$bytes = max($bytes, 0);
		$pow = floor(($bytes?log($bytes):0)/log(1024));
		$pow = min($pow, count($units)-1);
		$bytes /= pow(1024, $pow);
		return round($bytes, $precision).' '.$units[$pow];
	}

function conv_to_read($n) { 
	return iconv("CP1251","UTF-8", $n); 
} 
function conv_to_write($n) { 
	return iconv("UTF-8","CP1251", $n); 
} 
function get_filesize($filesize)
{
    // Если размер больше 1 Кб
    if($filesize > 1024)
    {
        $filesize = ($filesize/1024);
        // Если размер файла больше Килобайта
        // то лучше отобразить его в Мегабайтах. Пересчитываем в Мб
        if($filesize > 1024)
        {
            $filesize = ($filesize/1024);
            // А уж если файл больше 1 Мегабайта, то проверяем
            // Не больше ли он 1 Гигабайта
            if($filesize > 1024)
            {
                $filesize = ($filesize/1024);
                $filesize = round($filesize, 1);
                return $filesize." ГБ";
            }
            else
            {
                $filesize = round($filesize, 1);
                return $filesize." MБ";
            }
        }
        else
        {
            $filesize = round($filesize, 1);
            return $filesize." Кб";
        }
    }
    else
    {
        $filesize = round($filesize, 1);
        return $filesize." байт";
    }
}

function generate_filter_k($FILTER, $idblock){
    $arrFilter=array();
    CModule::IncludeModule("iblock");
	CModule::IncludeModule("currency");
	
	
	//pre($FILTER);
	
	
	if($FILTER["price_s"]!="") $FILTER["price_s"] = str_replace(" ", "", $FILTER["price_s"]);
	if($FILTER["price_do"]!="") $FILTER["price_do"] = str_replace(" ", "", $FILTER["price_do"]);
	
	
	//$MIN_PRICE=get_min_prop($_REQUEST["RAZDEL"], "catalog_PRICE_6");
	//$MAX_PRICE=get_max_prop($_REQUEST["RAZDEL"], "catalog_PRICE_6");
	
	
	if($FILTER["price_s"]>0){		
		//$arrFilter[">=catalog_PRICE_1"]= $_REQUEST["price_s"];
		$arrFilter[">=catalog_PRICE_1"]= $FILTER["price_s"];
	} 
	
	if($FILTER["price_do"]!="Неважно" && $FILTER["price_do"]>0){
		//$arrFilter["<=catalog_PRICE_1"]= $_REQUEST["price_do"];
		$arrFilter["<=catalog_PRICE_1"]= $FILTER["price_do"];
	}

	

    if($FILTER["price"]!=""){
        $tar = explode("-", $FILTER["price"]);
        
        //$arrFilter[">=catalog_PRICE_1"]=$tar[0];
		//$arrFilter["<=catalog_PRICE_1"]=$tar[1];
        $arrFilter[">=catalog_PRICE_1"]=$tar[0];        
        $arrFilter["<=catalog_PRICE_1"]=$tar[1];
    }	
	
	

	
	
	
	

    if($FILTER) {
    //Перебираем характеристики	
	//pre($FILTER);	
    foreach($FILTER as $code => $val){
		
        if(!in_array($code, array("clear_cache","RAZDEL", "price", "PAGEN_1", "act","BITRIX_SM_SOUND_LOGIN_PLAYED","PHPSESSID","BITRIX_SM_SALE_UID","BITRIX_SM_LOGIN","BITRIX_SM_DSC","BITRIX_SM_LAST_SETTINGS","RAZDEL2","TAG","CUR_CITY","BX_USER_ID","price_do","price_s"))){
            if(substr_count($code, "-")>0){
                $tar = explode("-", $code);

				
				$filt_p2 = Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$idblock, "CODE"=>$tar[0]);
				
				//pre($filt_p2);
				
                $properties = CIBlockProperty::GetList(Array("sort"=>"asc"), $filt_p2);
                if($prop_fields = $properties->GetNext()){
                    //var_dump($prop_fields);

                    //выбираем подходящие элементы
                    $FIDS = array();
                   // $val = explode("|", $val);
                    $arFilter_i = Array("IBLOCK_ID" => $prop_fields["LINK_IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_".$tar[1]=>$val);
                   // var_dump($arFilter_i);
                    $res_i = CIBlockElement::GetList(Array("SORT"=>"ASC", "NAME"=>"ASC"), $arFilter_i, false, false, array("ID"));
                    while($ob_i = $res_i->GetNext()){
                        $FIDS[]=$ob_i["ID"];
                    }
                    if(count($FIDS)>0) $arrFilter["PROPERTY_".$tar[0]]=$FIDS;
                }
            }else{
                if(substr_count($code, "_s")==0 && substr_count($code, "_do")==0){
                    $arrFilter["PROPERTY_".$code]=$val;
                }else{
                    if(substr_count($code, "_s")>0){
                        $code = str_replace("_s", "", $code);
                        $arrFilter[">=PROPERTY_".$code]=$val;
                    }

                    if(substr_count($code, "_do")>0){
                        $code = str_replace("_do", "", $code);
                        $arrFilter["<=PROPERTY_".$code]=$val;
                    }

                }
            }
        }

    }
	
	}

    return $arrFilter;
}




function afterSave($arg1, $arg2 = false) {

	CModule::IncludeModule('sale');
	CModule::IncludeModule('catalog');

    $element_id = false;
    $iblock_id = 4;
    $offers_iblock_id = 26;
    $offers_property_id = 'CML2_LINK';
    $current_iblock_id = false;
    if (CModule::IncludeModule('currency')) {
        $strDefaultCurrency = CCurrency::GetBaseCurrency();
    }
    //check for catalog event
    if(is_array($arg2) && $arg2['PRODUCT_ID'] > 0) {
	
	
        //get iblock element
        $rsPriceElement = CIBlockElement::GetList(
            array(),
            array(
             'ID' => $arg2['PRODUCT_ID'],
            ),
            false,
            false,
            array('ID', 'IBLOCK_ID')
        );
        if($arPriceElement = $rsPriceElement->Fetch()) {
            $arCatalog = CCatalog::GetByID($arPriceElement['IBLOCK_ID']);
            if(is_array($arCatalog)) {
                //check if it is offers iblock
                if($arCatalog['OFFERS'] == 'Y') {
                    //find product element
                    $rsElement = CIBlockElement::GetProperty(
                        $arPriceElement['IBLOCK_ID'],
                        $arPriceElement['ID'],
                        'sort',
                        'asc',
                        array('ID' => $arCatalog['SKU_PROPERTY_ID'])
                    );
                    $arElement = $rsElement->Fetch();
                    if($arElement && $arElement['VALUE'] > 0) {
                        $element_id = $arElement['VALUE'];     
                        
                        $current_iblock_id = $arPriceElement['IBLOCK_ID'];
                    }
                }
                //or iblock which has offers
                elseif($arCatalog['OFFERS_IBLOCK_ID'] > 0) {
                    $element_id = $arPriceElement['ID'];                   
                    $current_iblock_id = $arPriceElement['IBLOCK_ID'];
                }
                //or it's regular catalog
                else {
                    $element_id = $arPriceElement['ID'];                   
                    $current_iblock_id = $arPriceElement['IBLOCK_ID'];
                }
            }
        }
    }
    //check for iblock event
    elseif(is_array($arg1) && $arg1['ID'] > 0 && $arg1['IBLOCK_ID'] > 0) {
	
		
		$arOffers = CIBlockPriceTools::GetOffersIBlock($arg1['IBLOCK_ID']);
        if(is_array($arOffers)) {
            $element_id = $arg1['ID'];
            $iblock_id = $arg1['IBLOCK_ID'];
            $offers_iblock_id = $arOffers['OFFERS_IBLOCK_ID'];
            $offers_property_id = $arOffers['OFFERS_PROPERTY_ID'];
            $current_iblock_id =  $arg1['IBLOCK_ID'];
        }
		else {
			 $arOffers = CAllCatalogSKU::GetInfoByOfferIBlock($arg1['IBLOCK_ID']);	
			if(is_array($arOffers)) {
		
				if($arOffers["SKU_PROPERTY_ID"]) {
				
					$arOffer = CStatic::getElement($arg1['ID'], $arg1['IBLOCK_ID']);
					$element_id = $arOffer['PROPERTIES']['CML2_LINK']['VALUE'];	
				}
				else {		
			
					$element_id = $arg1['ID'];				
					$current_iblock_id =  $arg1['IBLOCK_ID'];			
				}
			}	
		}	
		
    }
	
	
	
    if($element_id) {
        static $arPropCache = array();
        if(!array_key_exists($iblock_id, $arPropCache)) {
            //check for MIN_PRICE property		
			
            $rsProperty = CIBlockProperty::GetByID('MIN_PRICE', $iblock_id);
            $arProperty = $rsProperty->Fetch();
            if($arProperty) {			
                $arPropCache[$iblock_id] = $arProperty['ID'];
            } else {			
                $arPropCache[$iblock_id] = false;
            }		
			
        }
        if($arPropCache[$iblock_id]) {
		
		
            //compose elements filter
            if($offers_iblock_id) {
                $rsOffers = CIBlockElement::GetList(
                    array(),
                    array(
                        'IBLOCK_ID' => $offers_iblock_id,
                        'PROPERTY_'.$offers_property_id => $element_id,
                    ),
                    false,
                    false,
                    array('ID')
                );
                while($arOffer = $rsOffers->Fetch())
                    $arProductID[] = $arOffer['ID'];
                if (!is_array($arProductID))
                    $arProductID = array($element_id);
            } else {
                $arProductID = array($element_id);
            }
            $minPrice = false;
            $maxPrice = false;
            //get price
            $rsPrices = CPrice::GetList(
                array(),
                array(
                    'PRODUCT_ID' => $arProductID,
                )
            );
            while($arPrice = $rsPrices->Fetch()) {
                if(
                    CModule::IncludeModule('currency') && 
                    $strDefaultCurrency != $arPrice['CURRENCY']
                )
                    $arPrice['PRICE'] = CCurrencyRates::ConvertCurrency(
                        $arPrice['PRICE'], 
                        $arPrice['CURRENCY'], 
                        $strDefaultCurrency
                    );
                $price = $arPrice['PRICE'];

                if($minPrice === false || $minPrice > $price)
                    $minPrice = $price;

                if($maxPrice === false || $maxPrice < $price)
                    $maxPrice = $price;
            }
			
			
            //save found min, max price into property
            if($minPrice !== false) {
			
			/*
			echo '###############<br/>';
			pre($element_id);
			pre($iblock_id);
			pre((int)$minPrice);
			pre((int)$maxPrice);
			echo '###############<br/>';
			*/
                $RES_1 = CIBlockElement::SetPropertyValuesEx(
                    $element_id,
                    $iblock_id,
                    array(
                        'MIN_PRICE' => (int)$minPrice,   
						'MAX_PRICE' => (int)$maxPrice
                    )
                );
				
				
				//PRE($RES_1);
				//PRE($RES_2);
				
				
            }
			
			
        }
    }
	
	//die();
}
