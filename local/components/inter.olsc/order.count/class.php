<?php
use \Bitrix\Main;


class OrderCount extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {		
		return $arParams;		
    }

    public function executeComponent()
    { 		
	
		$this->arResult = CStatic::goodsInBasket();
		
			$count = 0;
			
			
			$this->arResult["TOV_COUNT_FAVORITE"] = 0;
				if($_COOKIE["FAVORITE_LIST"])
				{
					$this->arResult["TOV_COUNT_FAVORITE"] = 0;					
					$FAVORITE_LIST = explode('|',$_COOKIE["FAVORITE_LIST"]);
					$this->arResult["TOV_COUNT_FAVORITE"] = count(array_diff($FAVORITE_LIST, array('')));
				}
			
			
			//pre($_SESSION["CATALOG_COMPARE_LIST"]);
			
			if(count($_SESSION["CATALOG_COMPARE_LIST"])>0)
			{
				foreach($_SESSION["CATALOG_COMPARE_LIST"] as $iblock =>$items)
				{					
					if(in_array($iblock, CStatic::$catalogIdBlock)) {						
						$count = $count + count($items["ITEMS"]);	
					}
				}
			}
			
		$this->arResult["TOV_COUNT_COMPARE"] = $count;
		$this->IncludeComponentTemplate();
    }
}
