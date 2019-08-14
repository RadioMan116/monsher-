<?php

class MnLibIBlockPropertyProperty
{
    //@todo refactor name to "forIblock etc"
    public static function getAllCodesForSearchableByIBlockId($iBlockId)
    {
        CModule::IncludeModule('iblock');

        $dbProps = CIBlockProperty::GetList(array(),
            array('IBLOCK_ID' => $iBlockId, 'SEARCHABLE' => 'Y', 'ACTIVE' => 'Y')
        );

        $codes = array();
        while ($arProps = $dbProps->GetNext()) $codes[] = $arProps['CODE'];

        return $codes;
    }

    //@todo refactor name to "forIblock etc"
    public static function getAllCodesForSearchableByIBlockIdAndShopUnid($iBlockId, $shopUnid)
    {
        CModule::IncludeModule('iblock');

        //@todo delme when september ends
        if ($shopUnid == 'HDF')
        {
            $dbProps = CIBlockProperty::GetList(array(),
                array('IBLOCK_ID' => $iBlockId, 'ACTIVE' => 'Y')
            );

            $codes = array();
            while ($arProps = $dbProps->GetNext())
            {
                if (substr($arProps['CODE'], 0, 2) == '__') continue;

                $codes[] = $arProps['CODE'];
            }
        }
        else
        {
            $dbProps = CIBlockProperty::GetList(array(),
                array('IBLOCK_ID' => $iBlockId, 'ACTIVE' => 'Y', 'NAME' => '__' . $shopUnid . '%')
            );

            $codes = array();
            while ($arProps = $dbProps->GetNext()) $codes[] = $arProps['CODE'];
        }

        return $codes;
    }

    public static function getForIBlock($iBlockId)
    {
        $propObject = new CIBlockProperty();
        $dbProps = $propObject->GetList(array(), $arFilter = array("IBLOCK_ID" => $iBlockId, "ACTIVE" => "Y"));

        $props = array();
        while ($arProp = $dbProps->Fetch()) $props[] = $arProp;

        return $props;
    }

    public static function getById($id)
    {
        CModule::IncludeModule('iblock');

        $dbProperty = CIBlockProperty::GetByID($id);
        if($arProperty = $dbProperty->GetNext())
        {
            $property = $arProperty;

            return $property;
        }
    }

    //@todo refactor name to get off "Property"
    public static function getPropertyByCodeAndIBlockId($code, $iBlockId)
    {
        CModule::IncludeModule('iblock');

        $dbProperty = CIBlockProperty::GetList(array(),
            array('CODE' => $code, 'IBLOCK_ID' => $iBlockId, 'ACTIVE' => 'Y')
        );

        $property = $dbProperty->Fetch();

        return $property;
    }

    public static function getListTypeData($id)
    {
        $dbList = CIBlockProperty::GetPropertyEnum($id);

        $data = array();
        while($arEntry = $dbList->Fetch()) $data[] = $arEntry;

        return $data;
    }

    public static function getListTypeDataByCodeAndIBlockId($code, $iBlockId)
    {
        $dbList = CIBlockPropertyEnum::GetList(array(), array('CODE' => $code, 'IBLOCK_ID' => $iBlockId));

        $result = array();
        while($arList = $dbList->GetNext())
        {
            $result[] = $arList;
        }

        return $result;
    }

    public static function isExist($code, $iBlockId)
    {
        $property = self::getPropertyByCodeAndIBlockId($code, $iBlockId);

        if (!empty($property)) return true;
    }

    //@todo refactor last params to array style
    public static function createTextType($name, $code, $iBlockId, $required = 'N', $sort = 500, $descr = null, $multiple = null)
    {
        CModule::IncludeModule('iblock');

        if (!MnLibIBlockPropertyProperty::isExist($code, $iBlockId))
        {
            $fields = array(
                'NAME' => $name,
                "PROPERTY_TYPE" => "S",
                "ACTIVE" => "Y",
                "IS_REQUIRED" => $required,
                "SORT" => $sort,
                "CODE" => $code,
                "IBLOCK_ID" => $iBlockId,
            );

            if ($descr == 'Y') $fields["WITH_DESCRIPTION"] = 'Y';
            if ($multiple == 'Y') $fields["MULTIPLE"] = 'Y';

            $iBlockProperty = new CIBlockProperty;
            $iBlockPropertyId = $iBlockProperty->Add($fields);

            return $iBlockPropertyId;
        }
    }

    public static function getForElement($elementId, $elementIBlockId)
    {
        $props = array();

        $dbProps = CIBlockElement::GetProperty($elementIBlockId, $elementId, array(), array('ACTIVE' => 'Y'));
        while ($arProp = $dbProps->Fetch()) $props[] = $arProp;

        return $props;
    }

    //@todo u kidding me, wrong class
    public static function getForElementByCode($code, $elementId, $iBlockId)
    {
        $property = array();

        //@todo any activity check here
        $dbProperty = CIBlockElement::GetProperty($iBlockId, $elementId, 'sort', 'asc', array('CODE' => $code));

        while ($arProperty = $dbProperty->GetNext()) $property[] = $arProperty;

        return $property;
    }

    //only one value processing now
    public static function updateMultiple($code, $elementId, $iBlockId, $newValues, $type = null, $options = array())
    {
        if ($type == 'text')
        {
            $element = MnLibElementElement::getById($elementId, array('PROPERTIES' => $code));
            $property = $element['PROPS'][$code];

            $valuesFormatted = array();
            $oldValues = array();

            if (!isset($options['TEXT_DESCR'])) $options['TEXT_DESCR'] = '';

            $updateDenied = false;
            foreach ($property['VALUE'] as $ind => $propValue)
            {
                $oldValues[] = $propValue;

                if ($propValue == $newValues && $property['DESCRIPTION'][$ind] == $options['TEXT_DESCR'])
                {
                    $updateDenied = true;
                }
                elseif ($options['DESCR_UNIQUE'] == 'Y' && $property['DESCRIPTION'][$ind] == $options['TEXT_DESCR'])
                {
                    $valuesFormatted[] = array('VALUE' => $newValues, 'DESCRIPTION' => $property['DESCRIPTION'][$ind]);
                    $updateDenied = true;
                }
                else
                {
                    $valuesFormatted[] = array('VALUE' => $propValue, 'DESCRIPTION' => $property['DESCRIPTION'][$ind]);
                }
            }

            if (!$updateDenied) $valuesFormatted[] = array('VALUE' => $newValues, 'DESCRIPTION' => $options['TEXT_DESCR']);
        }
        else
        {
            $property = MnLibIBlockPropertyProperty::getForElementByCode($code, $elementId, $iBlockId);

            $valuesFormatted = array();
            $oldValues = array();

            foreach ($property as $propEntry)
            {
                $oldValues[] = $propEntry['VALUE'];
                $valuesFormatted[] = array('VALUE' => $propEntry['VALUE']);
            }

            $valuesFormatted[] = array('VALUE' => $newValues);
        }

        if (!in_array($newValues, $oldValues) || ($options['DUPLICATES_ALLOW'] == 'Y' && isset($options['TEXT_DESCR'])))
        {
            CIBlockElement::SetPropertyValuesEx($elementId, $iBlockId, array($code => $valuesFormatted));

            return true;
        }
    }

    public static function exportXmlFormatForProduct($elementId, $iBlockId, $catalogType)
    {
        $xml = '';

        $arPropOrder = array(
            "sort" => "asc",
            "id" => "asc",
            "enum_sort" => "asc",
            "value_id" => "asc",
        );

        $rsProps = CIBlockElement::GetProperty($iBlockId, $elementId, $arPropOrder, array("ACTIVE"=>"Y", "CODE" => "PHOTOS"));
        $arProps = array();
        while($arProp = $rsProps->Fetch())
        {
            $pid = $arProp["ID"];
            if(!array_key_exists($pid, $arProps))
                $arProps[$pid] = array(
                    "ID" => $arProp["ID"],
                    "CODE" => $arProp["CODE"],
                    "PROPERTY_TYPE" => $arProp["PROPERTY_TYPE"],
                    "LINK_IBLOCK_ID" => $arProp["LINK_IBLOCK_ID"],
                    "VALUES" => array(),
                );

            if($arProp["PROPERTY_TYPE"] == "L")
                $arProps[$pid]["VALUES"][] = array(
                    "ID" => $arProp["ID"],
                    "CODE" => $arProp["CODE"],
                    "VALUE" => $arProp["VALUE_ENUM"],
                    "DESCRIPTION" => $arProp["DESCRIPTION"],
                    "VALUE_ENUM_ID" => $arProp["VALUE"],
                );
            else
                $arProps[$pid]["VALUES"][] = array(
                    "ID" => $arProp["ID"],
                    "CODE" => $arProp["CODE"],
                    "VALUE" => $arProp["VALUE"],
                    "DESCRIPTION" => $arProp["DESCRIPTION"],
                    "VALUE_ENUM_ID" => $arProp["VALUE_ENUM_ID"],
                );
        }

        $xml.= '<props>' . PHP_EOL;

        foreach($arProps as $pid => $arProp)
        {
            $xml.= '<prop>' . PHP_EOL;
            $xml.= '<id>' . $arProp['ID'] . '</id>' . PHP_EOL;
            $xml.= '<code>' . $arProp['CODE'] . '</code>' . PHP_EOL;
            $xml.= '<values>' . PHP_EOL;

            foreach($arProp["VALUES"] as $arValue)
            {
                $value = $arValue["VALUE"];

                if(is_array($value) || strlen($value))
                {
                    if($arProp["PROPERTY_TYPE"]=="L")
                    {
                        $value = CIBlockPropertyEnum::GetByID($arValue["VALUE_ENUM_ID"]);
                        $value = $value["XML_ID"];
                    }
                    elseif($arProp["PROPERTY_TYPE"]=="F")
                    {
                        $value = CFile::GetFileArray($value);
                        $value = 'http://' . $_SERVER['HTTP_HOST'] . htmlspecialchars($value['SRC']);

                    }
                    elseif($arProp["PROPERTY_TYPE"]=="G")
                    {
                        $value = $value;
                    }
                    elseif($arProp["PROPERTY_TYPE"]=="E")
                    {
                        $linkedElement = MnLibElementElement::getById($value);

                        if ($linkedElement['IBLOCK_TYPE_ID'] != $catalogType)
                        {
                            $value = $linkedElement['NAME'];
                            $arValue["DESCRIPTION"] = 'REMOTE_LINKED_ID_' . $linkedElement['ID'];
                        }
                        else $value = $linkedElement['PROPS']['_UNID']['VALUE'];
                    }
                    if(is_array($value))
                    {
                        $bSerialized = true;
                        $value = serialize($value);
                    }
                    else $bSerialized = false;
                }

                $xml.= '<value_data>' . PHP_EOL;
                if(isset($bSerialized) && $bSerialized === true) $xml.= '<serialized>true</serialized>' . PHP_EOL;
                $xml.= '<value>' . htmlspecialchars($value) . '</value>' . PHP_EOL;
                $xml.= '<description>' . htmlspecialchars($arValue["DESCRIPTION"]) . '</description>' . PHP_EOL;
                $xml.= '</value_data>' . PHP_EOL;
            }

            $xml.= '</values>' . PHP_EOL;
            $xml.= '</prop>' . PHP_EOL;
        }

        $xml.= '</props>' . PHP_EOL;

        return $xml;
    }

    //spec inside, by //!*
    public static function importFromXmlForShopProduct($xmlProps, $element, $shopId, $shopProducts, $elementPropsTypes)
    {
        $shop = MnLibElementElement::getById($shopId);
        $shopPropsLinkageSrc = $shop['PROPS']['PROPS_LINKAGE'];
        $shopPropsLinkage = array();
        foreach ($shopPropsLinkageSrc['VALUE'] as $ind => $originId) $shopPropsLinkage[$shopPropsLinkageSrc['DESCRIPTION'][$ind]] = $originId;

        $propSrcs = MnServiceSimpleXMLSimpleXML::convertNodeToStrings($xmlProps);
        $propSrcs = MnLibEncodingEncoding::convertArray($propSrcs, 'utf-8', 'windows-1251');

        $elementImportingProps = array();

        foreach ($propSrcs['prop'] as $propSrc)
        {
            $elementImportingProp = array();

            if (array_key_exists($propSrc['id'], $shopPropsLinkage))
            {
                $originPropId = $shopPropsLinkage[$propSrc['id']];
                $originPropType = $elementPropsTypes[$originPropId];

                //!* switcher for second path for transformed props
                if ($originPropType == 'F')
                {
                    if ($propSrc['code'] != 'PHOTOS') continue;
                }

//                if (isset($_SESSION['origin_changed_linkage']) && array_key_exists($originPropId, $_SESSION['origin_changed_linkage']) && $_SESSION['origin_changed_linkage'][$originPropId]['src_id'] == $propSrc['id'])
//                {
//                    $originPropId = $_SESSION['origin_changed_linkage'][$originPropId]['id'];
//                    $originPropType = 'S';
//                }

                if (array_key_exists('value', $propSrc['values']['value_data']))
                {
                    $propValuesSrc[0] = $propSrc['values']['value_data'];
                }
                else
                {
                    $propValuesSrc = $propSrc['values']['value_data'];
                }

                $i = 1;
                foreach ($propValuesSrc as $propValueSrc)
                {
                    if (empty($propValueSrc['value'])) $propValue = null;
                    else
                    {
                        if (isset($propValueSrc['serialized']) && $propValueSrc['serialized'] == 'true') $propValue = unserialize($propValueSrc['value']);

                        if ($originPropType == 'F')
                        {
                            $propValue = CFile::MakeFileArray($propValueSrc['value']);
                        }
                        elseif ($originPropType == 'G') $propValue = null;
                        elseif ($originPropType == 'E')
                        {
                            if (!empty($propValueSrc['description']))
                            {
                                $propValue = $propValueSrc['value'];

                                $eTypePropertyWrong = self::getById($originPropId);
                                CIBlockProperty::Delete($originPropId);

                                $sTypePropertyRightName = $eTypePropertyWrong['NAME'];
                                $sTypePropertyRightCode = $eTypePropertyWrong['CODE'];
                                $sTypePropertyRightId = self::createTextType($sTypePropertyRightName, $sTypePropertyRightCode, $element['IBLOCK_ID'], 'N', 500, 'Y');

                                if ($sTypePropertyRightId)
                                {
                                    $n = 0;
                                    $shopPropsLinkageValuesFormatted = array();
                                    foreach ($shopPropsLinkage as $linkageRemoteId => &$linkageOriginId)
                                    {
                                        if ($linkageOriginId == $originPropId)
                                        {
                                            $linkageOriginId = $sTypePropertyRightId;
                                        }

                                        $shopPropsLinkageValuesFormatted[$n] = array('VALUE' => $linkageOriginId, 'DESCRIPTION' => $linkageRemoteId);

                                        $n++;
                                    }

                                    CIBlockElement::SetPropertyValuesEx($shop['ID'], $shop['IBLOCK_ID'], array('PROPS_LINKAGE' => $shopPropsLinkageValuesFormatted));
                                }
                                else
                                {
                                    foreach ($shopPropsLinkage as $linkageRemoteId => $linkageOriginId)
                                    {
                                        if ($linkageRemoteId == $propSrc['id'])
                                        {
                                            $sTypePropertyRightId = $linkageOriginId;
                                        }
                                    }
                                }

                                $originPropId = $sTypePropertyRightId;
                            }
                            else
                            {
                                foreach ($shopProducts as $shopProduct)
                                {
                                    if ($shopProduct['PROPERTY__UNID_VALUE'] == $propValueSrc['value'])
                                    {
                                        $propValue = $shopProduct['ID'];
                                    }
                                }
                            }
                        }
                        elseif ($originPropType == 'L')
                        {
                            $rsEnum = CIBlockPropertyEnum::GetList(
                                Array(),
                                Array("EXTERNAL_ID" => $propValueSrc['value'], "PROPERTY_ID" => $originPropId)
                            );
                            if($arEnum = $rsEnum->Fetch())
                            {
                                $propValue = $arEnum["ID"];
                            }
                            else $propValue = false;
                        }
                        else $propValue = $propValueSrc['value'];
                    }

                    $elementImportingProp['n' . $i] = array(
                        'VALUE' => $propValue,
                        'DESCRIPTION' => $propValueSrc['description']
                    );

                    $i++;

                    //only first file
                    if ($originPropType == 'F') break;
                }

                $elementImportingProps[$originPropId] = $elementImportingProp;
            }

            unset($propValuesSrc);
        }

        CIBlockElement::SetPropertyValuesEx($element["ID"], $element['IBLOCK_ID'], $elementImportingProps);

        return true;
    }
}
