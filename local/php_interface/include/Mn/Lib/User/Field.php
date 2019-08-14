<?php

class MnLibUserField
{
    public static function getListValues($ids)
    {
        //@todo active param add
        $dbField = CUserFieldEnum::GetList(array(), array('ID' => $ids));

        $values = array();
        while ($arField = $dbField->GetNext())
            $values[] = $arField;

        return $values;
    }
}
