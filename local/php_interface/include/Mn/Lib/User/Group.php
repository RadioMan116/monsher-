<?php

class MnLibUserGroup
{
    public static function getAll()
    {
        $dbGroups = CGroup::GetList($by = "c_sort", $order = "asc", array());

        $groups = array();
        while ($arGroup = $dbGroups->GetNext()) $groups[] = $arGroup;

        return $groups;
    }
}
