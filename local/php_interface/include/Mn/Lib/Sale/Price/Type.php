<?php

class MnLibSalePriceType
{
    public static function getAll()
    {
        CModule::IncludeModule('catalog');

        $types = array();

        $dbTypes = CCatalogGroup::GetList(array(), array(), false, false, array());
        while ($arType = $dbTypes->GetNext()) $types[] = $arType;

        return $types;
    }

    public static function getById($id)
    {
        CModule::IncludeModule('catalog');

        $dbType = CCatalogGroup::GetList(array(), array('ID' => $id), false, false, array());

        if ($arType = $dbType->GetNext()) return $arType;
    }

    public static function leaveOneByCode($code)
    {
        $types = self::getAll();

        foreach ($types as $type)
        {
            $userGroups = MnLibUserGroup::getAll();

            $userGroupsIdsWoutAdmins = array();
            foreach ($userGroups as $userGroup)
            {
                //@todo move id to const, with saving ability to have more than 1 admin group
                if ($userGroup['ID'] == 1) continue;
                $userGroupsIdsWoutAdmins[] = $userGroup['ID'];
            }

            if ($type['NAME'] == $code)
            {
                $curTypeFields = array(
                   "NAME"  => $type['NAME'],
                   "SORT"  => 0,
                   "USER_GROUP"     => $userGroupsIdsWoutAdmins,
                   "USER_GROUP_BUY" => $userGroupsIdsWoutAdmins,
                   "USER_LANG" => array(
                        "ru" => $code,
                        "en" => $code
                    )
                );

                CCatalogGroup::Update($type['ID'], $curTypeFields);

                $leftTypeId = $type['ID'];
            }
            else
            {
                $otherTypeFields = array(
                    "NAME"  => $type['NAME'],
                    "SORT"  => 0,
                    "USER_GROUP"     => array(1),
                    "USER_GROUP_BUY" => array(1),
                    "USER_LANG" => array(
                        "ru" => $type['NAME_LANG'],
                        "en" => $type['NAME_LANG']
                    )
                );

                CCatalogGroup::Update($type['ID'], $otherTypeFields);
            }
        }

        return $leftTypeId;
    }

    public static function getByName($name)
    {
        $dbPriceType = CCatalogGroup::GetList(array(), array('NAME' => $name));

        if ($priceType = $dbPriceType->Fetch()) return $priceType;
    }

    public static function getCurrentForShop($shopUnid, $typePostfix = null)
    {
        $shopPriceTypes = self::getForShop($shopUnid);

        if (isset($typePostfix))
        {
            $type = array('_' . $shopUnid . '_' . $typePostfix);
        }
        else
        {
            //@todo warning depends on id
            if (!isset($_SESSION['shops'][$shopUnid]['price_type']))
            {
                $defaultExists = false;
                foreach ($shopPriceTypes as $shopPriceType)
                {
                    if (substr($shopPriceType['NAME'], -4) == '_MSK') $defaultExists = true;
                    break;
                }

                if ($defaultExists) $type = array($shopPriceType['NAME']);
                else $type = array($shopPriceTypes[0]['NAME']);
            }
            else $type = array($_SESSION['shops'][$shopUnid]['price_type']);
        }

        return $type;
    }

    public static function getForShop($shopUnid)
    {
        $shopPriceTypes = array();

        $types = self::getAll();
        foreach ($types as $type)
            if (substr($type['NAME'], 1, 3) == $shopUnid) $shopPriceTypes[] = $type;

        return $shopPriceTypes;
    }
}
