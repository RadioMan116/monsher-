<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

?>

<?if($arParams["ACTIVE"]["MENU"]):?>

<?$APPLICATION->IncludeComponent("bitrix:menu", "left.menu", array(
											"ROOT_MENU_TYPE" => "left",
											"MENU_CACHE_TYPE" => "N",
											"MENU_CACHE_TIME" => "3600",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => "",
											"MAX_LEVEL" => "1",
											"CHILD_MENU_TYPE" => "left",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
										),
										false
								);?>		

<?endif;?>



<?$APPLICATION->IncludeFile('/local/include_areas/block-left_ym-rate.php');?>
<?$APPLICATION->IncludeFile('/local/include_areas/block-left_quality.php');?>