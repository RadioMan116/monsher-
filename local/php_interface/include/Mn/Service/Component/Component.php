<?php

class MnServiceComponentComponent
{
    private static $app;

    public static function init()
    {
        self::$app = $GLOBALS['APPLICATION'];
    }

    public static function getArea($src, $scope = 'file')
    {
        $area = self::$app->IncludeComponent("bitrix:main.include", "", array(
                "PATH"           => SITE_TEMPLATE_PATH . "/include_areas/" . $src,
                "AREA_FILE_SHOW" => $scope,
                "EDIT_TEMPLATE"  => ""
            ), false
        );

        return $area;
    }

    public static function getSearchForm($template, $url = '/search/')
    {
        $searchForm = self::$app->IncludeComponent("bitrix:search.form", $template, array(
                "PAGE" => $url
            ), false
        );

        return $searchForm;
    }

    public static function getAuthForm($template)
    {
        $authForm = self::$app->IncludeComponent("bitrix:system.auth.authorize", $template, array(
                "REGISTER_URL" => '/',
                "PROFILE_URL"  => '/',
                "SHOW_ERRORS"  => 'Y'
            ), false
        );
    }

    public static function getMenu($type, $maxLevel, $template, $childType = null)
    {
        if (!CUser::IsAuthorized()) return false;

        if (!$childType) $childType = $type;

        $menu = self::$app->IncludeComponent("bitrix:menu", $template, array(
                "ROOT_MENU_TYPE" => $type,
                "MAX_LEVEL" => $maxLevel,
                "CHILD_MENU_TYPE" => $childType,
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_TIME" => "3600",
            ), false
        );

        return $menu;
    }

    public static function getMenuSections($iBlockType, $iBlockId, $baseUrl)
    {
        $menuSections = self::$app->IncludeComponent("bitrix:menu.sections", "", array(
                "IS_SEF" => "Y",
                "SEF_BASE_URL" => $baseUrl,
                "SECTION_PAGE_URL" => "#IBLOCK_CODE#/#SECTION_CODE#/",
                "DETAIL_PAGE_URL" => "#IBLOCK_CODE#/#SECTION_CODE#/#ELEMENT_CODE#",
                "IBLOCK_TYPE" => $iBlockType,
                "IBLOCK_ID" => $iBlockId,
                "DEPTH_LEVEL" => "2",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            ), false
        );

        return $menuSections;
    }

    public static function getBreadcrumb($template)
    {
        $breadcrumb = self::$app->IncludeComponent('bitrix:breadcrumb', $template, array(
                "START_FROM" => "0",
                "PATH" => "",
                "SITE_ID" => ""
            ), false
        );

        return $breadcrumb;
    }

    public static function getCatalogTop($template, $iBlockId = null, $shopCode, $priceCode)
    {
        $catalogTop = self::$app->IncludeComponent("bitrix:catalog.top", $template, array(
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => $iBlockId,
                "ELEMENT_SORT_FIELD" => "SHOWS",
                "ELEMENT_SORT_ORDER" => "asc",
                "FILTER_NAME" => "arrFilter",
                "SECTION_URL" => "/catalog/" . $shopCode . "/#IBLOCK_CODE#/",
                "DETAIL_URL" => "/catalog/" . $shopCode . "/#IBLOCK_CODE#/#ELEMENT_CODE#.html",
                "BASKET_URL" => "/catalog/cart/",
                "ACTION_VARIABLE" => "action",
                "PRODUCT_ID_VARIABLE" => "id",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "DISPLAY_COMPARE" => "N",
                "ELEMENT_COUNT" => "15",
                "LINE_ELEMENT_COUNT" => "1",
                "PROPERTY_CODE" => array('MN_KIND', 'MODEL', 'PHOTOS'),
                "PRICE_CODE" => $priceCode,
                "USE_PRICE_COUNT" => "N",
                "SHOW_PRICE_COUNT" => "1",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            ), false
        );

        return $catalogTop;
    }

    public static function getCatalogShopProductsByString($template, $shopCode, $addFilter = array())
    {
        $shop = MnLibElementElement::getByCodeAndIBlockId($shopCode, 50);

        if (!MnModelShopShop::userHasAccess($shop['ID']))
        {
            include($_SERVER["DOCUMENT_ROOT"]."/404.php");
            exit();
        }

        $priceCode = MnLibSalePriceType::getCurrentForShop($shop['PROPS']['_UNID']['VALUE']);
        MnLibSalePriceType::leaveOneByCode($priceCode[0]);

        $GLOBALS['arrFilter']['PROPERTY__SHOPS'] = $shop['ID'];

        if (!empty($addFilter))
            foreach ($addFilter as $filterCode => $filterValue) $GLOBALS['arrFilter'][$filterCode] = $filterValue;

        self::getCatalogTop($template, null, $shopCode, $priceCode);
    }

    public static function getCatalogElement($elementCode, $iBlockId, $sectionCode, $template, $id = null, $shopCode = null, $brandCode = null, $priceCode = array('MSK'))
    {
        $props = MnLibIBlockPropertyProperty::getAllCodesForSearchableByIBlockId($iBlockId);

        $catalogElement = self::$app->IncludeComponent("mn:catalog.element", $template, array(
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => $iBlockId,
                "ELEMENT_ID" => $id,
                "ELEMENT_CODE" => $elementCode,
                "SECTION_ID" => "",
                "SECTION_CODE" => $sectionCode,
                "SECTION_URL" => "/catalog/" . $shopCode . "/#IBLOCK_CODE#/" . $brandCode . "/",
                "DETAIL_URL" => "/catalog/" . $shopCode . "/#IBLOCK_CODE#/" . $brandCode . "/#ELEMENT_CODE#.html",
                "BASKET_URL" => "/catalog/cart/",
                "ACTION_VARIABLE" => "action",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "META_KEYWORDS" => "-",
                "META_DESCRIPTION" => "-",
                "BROWSER_TITLE" => "-",
                "SET_TITLE" => "Y",
                "SET_STATUS_404" => "Y",
                "ADD_SECTIONS_CHAIN" => "Y",
                "PROPERTY_CODE" => $props,
                "OFFERS_FIELD_CODE" => array(),
                "OFFERS_PROPERTY_CODE" => array(),
                "OFFERS_SORT_FIELD" => "sort",
                "OFFERS_SORT_ORDER" => "asc",
                "PRICE_CODE" => $priceCode,
                "USE_PRICE_COUNT" => "N",
                "SHOW_PRICE_COUNT" => "1",
                "PRICE_VAT_INCLUDE" => "Y",
                "PRICE_VAT_SHOW_VALUE" => "N",
                "USE_PRODUCT_QUANTITY" => "N",
                "LINK_IBLOCK_TYPE" => "",
                "LINK_IBLOCK_ID" => "",
                "LINK_PROPERTY_SID" => "",
                "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "CACHE_GROUPS" => "Y",
                "OFFERS_CART_PROPERTIES" => array()
            ), false
        );

        return $catalogElement;
    }

    public static function getCatalogShopElement($elementCode, $iBlockId, $sectionCode, $template, $shopCode, $brandCode, $id = null)
    {
        $shop = MnLibElementElement::getByCodeAndIBlockId($shopCode, 50);

        if (!MnModelShopShop::userHasAccess($shop['ID']))
        {
            include($_SERVER["DOCUMENT_ROOT"]."/404.php");
            exit();
        }

        $priceCode = MnLibSalePriceType::getCurrentForShop($shop['PROPS']['_UNID']['VALUE']);
        MnLibSalePriceType::leaveOneByCode($priceCode[0]);

        self::getCatalogElement($elementCode, $iBlockId, $sectionCode, $template, $id = null, $shopCode, $brandCode, $priceCode);
    }

    public static function getCatalogElementByIdAndIBlockId($id, $iBlockId, $template)
    {
        $catalogElement = self::getCatalogElement(null, $iBlockId, null, $template, $id);

        return $catalogElement;
    }

    public static function getCatalogSectionList($iBlockId, $sectionCode, $template,
        $iBlockDescr = null, $iBlockPicture = null
    ) {
        $catalogSectionList = self::$app->IncludeComponent("bitrix:catalog.section.list", $template, array(
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => $iBlockId,
                "SECTION_ID" => "",
                "SECTION_CODE" => $sectionCode,
                "SECTION_URL" => "/catalog/#IBLOCK_CODE#/#SECTION_CODE#/",
                "COUNT_ELEMENTS" => "N",
                "TOP_DEPTH" => "1",
                "ADD_SECTIONS_CHAIN" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "CACHE_GROUPS" => "Y",
                "IBLOCK_DESCR" => $iBlockDescr,
                "IBLOCK_PICTURE" => $iBlockPicture
            ), false
        );

        return $catalogSectionList;
    }

    public static function getCatalogSection($sectionCode, $iBlockId, $template, $shopCode = null, $brandCode = null, $priceCode = array('MSK'))
    {
        $props = MnLibIBlockPropertyProperty::getAllCodesForSearchableByIBlockId($iBlockId);

        $catalogSection = self::$app->IncludeComponent("mn:catalog.section", $template, array(
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => $iBlockId,
                "SECTION_ID" => "",
                "SECTION_CODE" => $sectionCode,
                "SECTION_USER_FIELDS" => array(),
                "ELEMENT_SORT_FIELD" => "sort",
                "ELEMENT_SORT_ORDER" => "asc",
                "FILTER_NAME" => "arrFilter",
                "INCLUDE_SUBSECTIONS" => "Y",
                "SHOW_ALL_WO_SECTION" => "Y",
                "SECTION_URL" => "/catalog/" . $shopCode . "/#IBLOCK_CODE#/" . $brandCode . "/",
                "DETAIL_URL" => "/catalog/" . $shopCode . "/#IBLOCK_CODE#/" . $brandCode . "/#ELEMENT_CODE#.html",
                "BASKET_URL" => "/catalog/cart/",
                "ACTION_VARIABLE" => "action",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "META_KEYWORDS" => "-",
                "META_DESCRIPTION" => "-",
                "BROWSER_TITLE" => "-",
                "ADD_SECTIONS_CHAIN" => "Y",
                "DISPLAY_COMPARE" => "N",
                "SET_TITLE" => "Y",
                "SET_STATUS_404" => "Y",
                "PAGE_ELEMENT_COUNT" => "10",
                "LINE_ELEMENT_COUNT" => "1",
                "PROPERTY_CODE" => $props,
                "OFFERS_FIELD_CODE" => array("ID"),
                "OFFERS_PROPERTY_CODE" => array(""),
                "OFFERS_SORT_FIELD" => "timestamp_x",
                "OFFERS_SORT_ORDER" => "asc",
                "PRICE_CODE" => $priceCode,
                "USE_PRICE_COUNT" => "N",
                "SHOW_PRICE_COUNT" => "1",
                "PRICE_VAT_INCLUDE" => "Y",
                "USE_PRODUCT_QUANTITY" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "CACHE_NOTES" => "",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "Товары",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => "",
                "PAGER_DESC_NUMBERING" => "Y",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "OFFERS_CART_PROPERTIES" => array(),
                "AJAX_OPTION_SHADOW" => "Y",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => ""
            ), false
        );
    }

    public static function getCatalogShopSection($sectionCode, $iBlockId, $template, $shopCode, $brandId, $brandCode)
    {
        //@todo id to const
        $shop = MnLibElementElement::getByCodeAndIBlockId($shopCode, 50);

        if (!MnModelShopShop::userHasAccess($shop['ID']))
        {
            include($_SERVER["DOCUMENT_ROOT"]."/404.php");
            exit();
        }

        $priceCode = MnLibSalePriceType::getCurrentForShop($shop['PROPS']['_UNID']['VALUE']);
        MnLibSalePriceType::leaveOneByCode($priceCode[0]);

        $GLOBALS['arrFilter']['PROPERTY__BRAND'] = $brandId;
        $GLOBALS['arrFilter']['PROPERTY__SHOPS'] = $shop['ID'];

        self::getCatalogSection($sectionCode, $iBlockId, $template, $shopCode, $brandCode, $priceCode);
    }

    public static function getCatalogCart($template)
    {
        $catalogCart = self::$app->IncludeComponent("bitrix:sale.basket.basket", $template, array(
                "PATH_TO_ORDER" => "/catalog/order/",
                "HIDE_COUPON" => "Y",
                "QUANTITY_FLOAT" => "N",
                "PRICE_VAT_SHOW_VALUE" => "N",
                "SET_TITLE" => "N",
                "COLUMNS_LIST" => array(),
                "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N"
            ), false
        );

        return $catalogCart;
    }

    public static function getCatalogShopCart($template)
    {
        self::getCatalogCart($template);
    }

    public static function getCatalogCartWidget($template)
    {
        $catalogCartWidget = self::$app->IncludeComponent("bitrix:sale.basket.basket.small", $template, array(
                "PATH_TO_BASKET" => "/catalog/cart/",
                "PATH_TO_ORDER" => "/catalog/order/"
            )
        );

        return $catalogCartWidget;
    }

    public static function getCatalogShopCartWidget($template)
    {
        self::getCatalogCartWidget($template);
    }

    public static function getCatalogOrder($template, $options)
    {
        $arParams = array(
            "PATH_TO_BASKET" => "/catalog/cart/",
            "SET_TITLE" => "Y",
            "PAY_FROM_ACCOUNT" => "N",
            "COUNT_DELIVERY_TAX" => "N",
            "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
            "ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
            "ALLOW_AUTO_REGISTER" => "Y",
            "SEND_NEW_USER_NOTIFY" => "N"
        );

        if (isset($options['PROCESS_ONLY']) && $options['PROCESS_ONLY'] == 'Y') $arParams['PROCESS_ONLY'] = 'Y';

        $catalogOrder = self::$app->IncludeComponent("mn:sale.order.ajax", $template, $arParams);

        return $catalogOrder;
    }

    public static function getCatalogShopOrder($template, $options = array())
    {
        self::getCatalogOrder($template, $options);
    }

    public static function getIBlockElements($id, $template)
    {
        self::$app->IncludeComponent("mn:iblock.elements", $template, array(
                "IBLOCK_ID" => $id,
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            )
        );
    }

    //without section now
    public static function getCatalogShopCategories($code, $iBlockId)
    {
        self::$app->IncludeComponent("mn:iblock.linked.list", 'catalog_shop_categories', array(
                "CODE" => $code,
                "IBLOCK_ID" => $iBlockId,
                "IBLOCK_LINKED_TYPE" => 'catalog',
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            )
        );
    }

    //@todo doesn't linked to category now
    public static function getCatalogShopCategoryBrands($code, $iBlockId)
    {
        self::$app->IncludeComponent("mn:iblock.element", 'catalog_shop_category_brands', array(
                "CODE" => $code,
                "IBLOCK_ID" => $iBlockId,
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            )
        );
    }

    public static function getCatalogShopSectionFilter($iBlockId, $template, $brandId, $shopCode)
    {
        $shop = MnLibElementElement::getByCodeAndIBlockId($shopCode, 50);

        $props = MnLibIBlockPropertyProperty::getAllCodesForSearchableByIBlockIdAndShopUnid($iBlockId, $shop['PROPS']['_UNID']['VALUE']);
        $priceCode = MnLibSalePriceType::getCurrentForShop($shop['PROPS']['_UNID']['VALUE']);

        $GLOBALS['arrFilter']['PROPERTY__BRAND'] = $brandId;
        $GLOBALS['arrFilter']['PROPERTY__SHOPS'] = $shop['ID'];

        self::$app->IncludeComponent("bitrix:store.catalog.filter", $template, array(
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => $iBlockId,
                "FILTER_NAME" => "arrFilter",
                "FIELD_CODE" => array(),
                "PROPERTY_CODE" => $props,
                "PRICE_CODE" => $priceCode,
                "CACHE_TYPE" => "A",
                "CACHE_NOTES" => "",
                "CACHE_TIME" => "3600",
                "CACHE_GROUPS" => "Y",
                "LIST_HEIGHT" => "5",
                "TEXT_WIDTH" => "20",
                "NUMBER_WIDTH" => "5",
                "SAVE_IN_SESSION" => "N"
            )
        );
    }

    public static function getCatalogShopFilter($template, $shopCode)
    {
        $shop = MnLibElementElement::getByCodeAndIBlockId($shopCode, 50);

        $priceCode = MnLibSalePriceType::getCurrentForShop($shop['PROPS']['_UNID']['VALUE']);

        $GLOBALS['arrFilter']['PROPERTY__SHOPS'] = $shop['ID'];

        self::$app->IncludeComponent("bitrix:store.catalog.filter", $template, array(
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => null,
                "FILTER_NAME" => "arrFilter",
                "FIELD_CODE" => array(),
                "PROPERTY_CODE" => array(),
                "PRICE_CODE" => $priceCode,
                "CACHE_TYPE" => "A",
                "CACHE_NOTES" => "",
                "CACHE_TIME" => "3600",
                "CACHE_GROUPS" => "Y",
                "LIST_HEIGHT" => "5",
                "TEXT_WIDTH" => "20",
                "NUMBER_WIDTH" => "5",
                "SAVE_IN_SESSION" => "N"
            )
        );
    }

    public static function getOrderList($template)
    {
        self::$app->IncludeComponent("mn:order.list", $template, array(
                "ORDERS_PER_PAGE" => "100",
                "PATH_TO_DETAIL" => "/orders/order/?ID=#ID#",
                "SAVE_IN_SESSION" => "Y",
                "SET_TITLE" => "N"
            )
        );
    }

    public static function getOrder($id, $template)
    {
        self::$app->IncludeComponent("mn:order.detail", $template, array(
                "PATH_TO_LIST" => "/orders/list/",
                "ID" => $id,
                "SET_TITLE" => "N"
            )
        );
    }

    public static function getCallAddForm($template)
    {
        self::$app->IncludeComponent("mn:calls.call.add", $template, array());
    }

    public static function getCustomerRequestsList($template)
    {
        self::$app->IncludeComponent("mn:customer_requests.list", $template, array(
                'IBLOCK_ID' => 66,
                "REQUESTS_PER_PAGE" => "30"
            )
        );
    }

    public static function getCustomerRequest($template)
    {
        self::$app->IncludeComponent("mn:customer_requests.detail", $template, array(
            )
        );
    }

    public static function getCustomerRequestsWidget($template)
    {
        if (isset($_SESSION['customer_request']['id'])) self::$app->IncludeComponent("mn:customer_requests.widget", $template, array());
    }

    public static function getCallsList($template)
    {
        self::$app->IncludeComponent("mn:calls.list", $template, array(
                'IBLOCK_ID' => 67,
                "REQUESTS_PER_PAGE" => "100"
            )
        );
    }

    public static function getCustomersList($template)
    {
        self::$app->IncludeComponent("mn:customers.list", $template, array(
                'IBLOCK_ID' => 68,
                "REQUESTS_PER_PAGE" => "100"
            )
        );
    }
}

MnServiceComponentComponent::init();
