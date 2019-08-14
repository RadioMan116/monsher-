<?php
use \Bitrix\Main;


class ArtFilterLeft extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {		
		return $arParams;		
    }

    public function executeComponent()
    { 		
	
		
		$this->IncludeComponentTemplate();
    }
}
