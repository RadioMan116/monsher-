<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

$GLOBALS["SITE_CONFIG"]["RESIZE"] = array(
	"PATH_TO_BLANK" => "/images/blank.gif",
	"PHPTHUMB_CLASS_PATH" => $_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/lib/phpthumb/phpthumb.class.php",
	"PHPTHUMB_CACHE_PATH" => $_SERVER["DOCUMENT_ROOT"]."/upload/thumbnails/neff/",
	"PHPTHUMB_CACHE_LIFETIME" => "31536000",
);

// config item keys:
// array PARAMS - phpthumb params
// array FILTER - phpthumb param "fltr"
// int LIFETIME - img cache lifetime, default = PHPTHUMB_CACHE_LIFETIME
// str PATH - img cache path, default = PHPTHUMB_CACHE_PATH
// str SUBDIR - cache path subdir

// * full list of params in phpthumb.readme.txt (look in PHPTHUMB_CLASS_PATH directory)

$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_SECTION_TOP"] = array(
	"PARAMS" => array(
		"w" => "48",
		"h" => "102",
		//"far" => "C",
	),
	"SUBDIR" => "48_102",
);

$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CART_RELATED"] = array(
	"PARAMS" => array(
		"w" => "85",
		"h" => "166",
		//"far" => "C",
	),
	"SUBDIR" => "85_166",
);

$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_MENU_POPULAR"] = array(
	"PARAMS" => array(
		"w" => "112",
		"h" => "112",
		"far" => "C",
	),
	"SUBDIR" => "112_112_fc",
);

$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_CATEGORY_SECTIONS"] = array(
	"PARAMS" => array(
		"w" => "200",
		"h" => "200",
		//"far" => "C",
	),
	"SUBDIR" => "200_200",
);

$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_SECTION"] = array(
	"PARAMS" => array(
		"w" => "180",
		"h" => "180",
		//"far" => "C",
	),
	"SUBDIR" => "180_180",
);
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_INDEX_SPECIAL"] = $GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_SECTION"];
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_DETAIL_SIMILAR"] = $GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_SECTION"];
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_DETAIL_RELATED"] = $GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_SECTION"];
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["ORDER_RELATED"] = $GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_SECTION"];
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["SEARCH_PAGE"] = $GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_SECTION"];
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_COMPARE"] = $GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_SECTION"];
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_CART_POPUP"] = $GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_SECTION"];

$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_DETAIL_BIG"] = array(
	"PARAMS" => array(
		"w" => "800",
		"h" => "600",
		//"far" => "C",
	),
	"SUBDIR" => "800_600",
);
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_DETAIL_MEDIUM"] = array(
	"PARAMS" => array(
		"w" => "420",
		"h" => "375",
		//"far" => "C",
	),
	"SUBDIR" => "420_375",
);
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_DETAIL_SMALL"] = array(
	"PARAMS" => array(
		"w" => "88",
		"h" => "49",
		"far" => "C",
	),
	"SUBDIR" => "88_49_fc",
);
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["SKU_LIST_ON_DETAIL"] = array(
	"PARAMS" => array(
		"w" => "50",
		"h" => "50",
		"far" => "C",
	),
	"SUBDIR" => "50_50_fc",
);

$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_LIVE_SEARCH"] = array(
	"PARAMS" => array(
		"w" => "50",
		"h" => "50",
		"far" => "C",
	),
	"SUBDIR" => "50_50_fc",
);
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CART"] = array(
	"PARAMS" => array(
		"w" => "120",
		"h" => "120",
		//"far" => "C",
	),
	"SUBDIR" => "120_120",
);

$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_LABELS_SMALL"] = array(
	"PARAMS" => array(
		"h" => "16",
	),
	"SUBDIR" => "16__",
);
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["CATALOG_LABELS_OVERLAY"] = array(
	"PARAMS" => array(
		"w" => "46",
		"h" => "46",
	),
	"SUBDIR" => "46_46",
);
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["NEWS_LIST"] = array(
	"PARAMS" => array(
		"w" => "270",
		"h" => "90",
		//"far" => "C",
	),
	"SUBDIR" => "270_90",
);
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["NEWS_LIST_SIDEBAR"] = array(
	"PARAMS" => array(
		"w" => "182",
		"h" => "140",
		"far" => "C",
	),
	"SUBDIR" => "182_140_fc",
);
$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["PRODUCTS_PROMO_LIST"] = array(
	"PARAMS" => array(
		"w" => "162",
		"h" => "178",
		//"far" => "C",
	),
	"SUBDIR" => "162_178",
);

$GLOBALS["SITE_CONFIG"]["RESIZE"]["TYPES"]["INDEX_VIDEO"] = array(
	"PARAMS" => array(
		"w" => "620",
		"h" => "320",
		//"far" => "C",
	),
	"SUBDIR" => "620_320",
);
?>