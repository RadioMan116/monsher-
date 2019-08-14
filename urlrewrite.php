<?php
$arUrlRewrite=array (
  3 => 
  array (
    'CONDITION' => '#^/catalog/([a-zA-Z_0-9\\-_]+)/([a-zA-Z_0-9\\-_]+)/type-([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1&SECTION_CODE=$2&TYPE_CODE=$3',
    'ID' => '',
    'PATH' => '/catalog/types.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/catalog/([a-zA-Z_0-9\\-_]+)/([a-zA-Z_0-9\\-_]+)/([a-zA-Z_0-9\\-_]+).html(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1&SECTION_CODE=$2&ELEMENT_CODE=$3',
    'ID' => '',
    'PATH' => '/catalog/detail.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/reviews/category-([a-zA-Z_0-9\\-_]+)/razdel-([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1&SECTION_CODE=$2',
    'ID' => '',
    'PATH' => '/reviews/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/catalog/([a-zA-Z_0-9\\-_]+)/type-([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1&TYPE_CODE=$2',
    'ID' => '',
    'PATH' => '/catalog/types.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/catalog/([a-zA-Z_0-9\\-_]+)/([a-zA-Z_0-9\\-_]+).html(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1&ELEMENT_CODE=$2',
    'ID' => '',
    'PATH' => '/catalog/detail.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/catalog/([a-zA-Z_0-9\\-_]+)/([a-zA-Z_0-9\\-_]+).php(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1&ELEMENT_CODE=$2',
    'ID' => '',
    'PATH' => '/catalog/detail.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/catalog/([a-zA-Z_0-9\\-_]+)/([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1&SECTION_CODE=$2',
    'ID' => '',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/tags/([a-zA-Z_0-9\\-_]+)/([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1&SECTION_CODE=$2',
    'ID' => '',
    'PATH' => '/catalog/tags.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/reviews/category-([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1',
    'ID' => '',
    'PATH' => '/reviews/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/reviews/product-([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/reviews/index.php',
    'SORT' => 100,
  ),
  12 => 
  array (
    'CONDITION' => '#^/catalog/type-([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'TYPE_CODE=$1',
    'ID' => '',
    'PATH' => '/catalog/types_all.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/news/([a-zA-Z_0-9\\-_]+).html(\\?.*)?$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/news/detail.php',
    'SORT' => 100,
  ),
  14 => 
  array (
    'CONDITION' => '#^/catalog/([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1',
    'ID' => '',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  15 => 
  array (
    'CONDITION' => '#^/news/([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^/tags/([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1',
    'ID' => '',
    'PATH' => '/catalog/tags.php',
    'SORT' => 100,
  ),
  17 => 
  array (
    'CONDITION' => '#^/work/([a-zA-Z_0-9\\-_]+)/(\\?.*)?$#',
    'RULE' => 'IBLOCK_CODE=$1',
    'ID' => '',
    'PATH' => '/work/catalog.php',
    'SORT' => 100,
  ),
  18 => 
  array (
    'CONDITION' => '#^/doc-view/(\\d+)/(\\d+)/$#',
    'RULE' => 'PRODUCT_ID=$1&FILE_ID=$2',
    'ID' => '',
    'PATH' => '/doc-view/index.php',
    'SORT' => 100,
  ),
);
