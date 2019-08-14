<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

// general
$GLOBALS["SITE_CONFIG"]["GENERAL"] = array(
	"SITE_ID" => "s1",
	"SITE_NAME" => "Neff",
	"GA_TRACKER_ID" => "",
);

// seo
$GLOBALS["SITE_CONFIG"]["SEO"] = array(
	"NAV_CHAIN_MAIN" => "Neff",
	"SITE_NAME" => "nf-store.ru",
	"MAIN_BRAND" => "Neff",
	"MAIN_BRAND_RU" => "Нефф",
);

// cache
$GLOBALS["SITE_CONFIG"]["CACHE"] = array(
	"SITE_CONFIG_CACHE_TIME" => "86400", // 86400
	"PRODUCT_CACHE_TYPE" => "A",
	"PRODUCT_CACHE_TIME" => "36000000",
	"CACHED_DATA_CACHE_TYPE" => "A",
	"CACHED_DATA_CACHE_TIME" => "36000000",
);

// standard product params
$GLOBALS["SITE_CONFIG"]["PRODUCT_PARAMS"] = array(
	"MODEL" => "MODEL",
	"BRAND" => "_BRAND",
	"TYPEPREFIX" => "TYPEPREFIX_NOMINATIVE",
	"UNID" => "_UNID",
);

// product flags
$GLOBALS["SITE_CONFIG"]["PRODUCT_FLAGS"] = array(
	//"BEST" => "BEST",
	//"POPULAR" => "POPULAR",
	//"NEW" => "NEW",
);

// iblock types
$GLOBALS["SITE_CONFIG"]["IBLOCK_TYPES"] = array(
	"CATALOG" => "s1_catalog",
	//"OFFERS" => "s1_offers",
	"CONTENT" => "s1_content",
	"DIRECTORY" => "s1_directory",
);

// catalog
$GLOBALS["SITE_CONFIG"]["CATALOG"] = array(
	"HAS_SKU" => "N",
	"SEF_FOLDER" => "/catalog/",
	"STORE_SORT" => array(
		"В наличии" => "1",
		"Под заказ" => "2",
		"Нет в наличии" => "3",
		"Снят с производства" => "4",
	),
	"STORE_CLASS" => array(
		"В наличии" => "",
		"Под заказ" => "store-on-order",
		"Нет в наличии" => "store-unavailable",
		"Снят с производства" => "store-discontinued",
		"Скоро в продаже" => "store-coming-soon",
	),
	"SKU_LIMIT" => "1000",
);
?>