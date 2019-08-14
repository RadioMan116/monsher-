<?php
// Get iblock array 111222
function getArIblock($ibType = "", $code = false, $id = false) {
    if(CModule::IncludeModule("iblock") && ($code || $id)) {
        global $USER;

        $obCache = new CPHPCache;
        $life_time = 60*60*24;
        $cache_id = $ibType.$code.$id.$USER->GetUserGroupString();
        if($obCache->InitCache($life_time, $cache_id, "/")) {
            $vars = $obCache->GetVars();
            $arIB = $vars["IBLOCK"];
        } else {
			$arFilter["TYPE"] = $ibType;
			if($code) $arFilter["CODE"] = $code;
			if($id) $arFilter["ID"] = $id;		
		
            $rsIB = CIBlock::GetList(
                        Array("SORT" => "ASC"),
                        $arFilter,
                        false
            );
            $arIB = $rsIB->GetNext();
        }
        if($obCache->StartDataCache()) {
            $obCache->EndDataCache(array(
                "IBLOCK"    => $arIB
            ));
        }
        return $arIB;
    }
}


function getArSection($ibBlockId = "", $code = false, $id = false) {
    if(CModule::IncludeModule("iblock") && ($code || $id)) {
        global $USER;

        $obCache = new CPHPCache;
        $life_time = 60*60*24;
        $cache_id = (int)$ibBlockId.'_'.$code.'_'.(int)$id.$USER->GetUserGroupString();
        if($obCache->InitCache($life_time, $cache_id, "/")) {
            $vars = $obCache->GetVars();
            $arSEC = $vars["SECTION"];
        } else {
			
			if($ibBlockId) $arFilter["IBLOCK_ID"] = $ibBlockId;
			if($code) $arFilter["CODE"] = $code;
			if($id) $arFilter["ID"] = $id;	
			
            $rsSEC = CIBlockSection::GetList(
                        Array("SORT" => "ASC"),
                        $arFilter,
                        false,
						array(
							"ID",
							"NAME",
							"CODE",
							"IBLOCK_ID",
							"IBLOCK_SECTION_ID",
							"SECTION_PAGE_URL",
							"PICTURE",
							"DETAIL_PICTURE",
							"DESCRIPTION",
							"UF_*",
						)
            );
            $arSEC = $rsSEC->GetNext();
        }
        if($obCache->StartDataCache()) {
            $obCache->EndDataCache(array(
                "SECTION"    => $arSEC
            ));
        }
        return $arSEC;
    }
}

function getArElement($ibBlockId = "", $code) {
    if(CModule::IncludeModule("iblock") && $code) {
        global $USER;

        $obCache = new CPHPCache;
        $life_time = 60*60*24;
        $cache_id = $ibBlockId.$code.$USER->GetUserGroupString();
        if($obCache->InitCache($life_time, $cache_id, "/")) {
            $vars = $obCache->GetVars();
            $arEL = $vars["ELEMENT"];
        } else {
            $rsEL = CIBlockElement::GetList(
                        Array("SORT" => "ASC"),
                        Array("IBLOCK_ID" => $ibBlockId, "CODE" => $code),
                        false
            );
            $arEL = $rsEL->GetNext();
        }
        if($obCache->StartDataCache()) {
            $obCache->EndDataCache(array(
                "ELEMENT"    => $arEL
            ));
        }
        return $arEL;
    }
}
?>
