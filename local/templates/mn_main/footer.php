

			<?if(!in_array($menu_tip, $arrNoDefaultPage)):?>	
				</div>
			<?endif;?>	


			<?if($menu_tip!='catalog'):?>	

				<?if(!in_array($menu_tip, $arrNoColumns)):?>
				
				
						</div>
						
						
						
						<?if(in_array($menu_tip, array('favorites'))):?>
<div class="favorite__footer">	
<?			
		global $arrFilterD;
		$arrFilterD["ID"] = $_SESSION["CATALOG_VIEWS_LIST"];
		//$arrFilterD["!ID"] = $arResult["ID"];

		//PRE($arrFilterD);
		
	$APPLICATION->IncludeComponent(
		"inter.olsc:catalog.section",
		"tovs.list.viewed",
		Array(		
			"BLOCK_CLASS" => 'js-recently-watched',
			"EC_TYPE" => 'ViewsList',
			//"CATALOG_COMPARE_LIST" => $arParams["CATALOG_COMPARE_LIST"],
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BASKET_URL" => "/order/",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "SORT",
			"ELEMENT_SORT_ORDER" => "ASC",
			"ELEMENT_SORT_FIELD2" => "",
			"ELEMENT_SORT_ORDER2" => "",
			"FILTER_NAME" => "arrFilterD",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => CStatic::$catalogIdBlock,
			"IBLOCK_TYPE" => "mn_catalog",
			"INCLUDE_SUBSECTIONS" => "Y",
			"LINE_ELEMENT_COUNT" => "3",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_COMPARE" => "Сравнить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_CART_PROPERTIES" => array(),
			"OFFERS_FIELD_CODE" => array(),
			"OFFERS_LIMIT" => "1",
			"OFFERS_PROPERTY_CODE" => array(),
			"OFFERS_SORT_FIELD" => "",
			"OFFERS_SORT_FIELD2" => "",
			"OFFERS_SORT_ORDER" => "",
			"OFFERS_SORT_ORDER2" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => 12,
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array("MSK"),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_DISPLAY_MODE" => "Y",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PROPERTY_CODE" => array("PHOTOS"),
			"SECTION_CODE" => "",
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => "",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_ALL_WO_SECTION" => "Y",
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"TEMPLATE_THEME" => "blue",
			"USE_PRICE_COUNT" => "N",
			"USE_PRODUCT_QUANTITY" => "N"
		),
		false
	);?>					
	</div>		
			<?endif;?>
						
						
						
						
						
					</div>	
						
				<?endif;?>











			<?if($menu_tip!='main'):?>			
				</div>
			<?endif;?>
			
			</div>
			<!-- /.content -->
			<?endif;?>		
			
			
		</div>
		<!-- /.middle -->
	</div>
	<!-- /.wrapper -->
	
	
	<div class="footer" id="js-footer">
		<div class="container">
			<div class="footer__inner">
				<?$APPLICATION->IncludeComponent("inter.olsc:catalogblock.list", "catalog.menu.bottom" , Array("PLACE" => "BOTTOM"), false);?>
				<!-- /.footer__col -->
				<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom.menu", array(
											"ROOT_MENU_TYPE" => "bottom",
											"MENU_CACHE_TYPE" => "N",
											"MENU_CACHE_TIME" => "3600",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => "",
											"MAX_LEVEL" => "2",
											"CHILD_MENU_TYPE" => "left",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
										),
										false
				);?>
				<!-- /.footer__col -->
				<div class="footer__col">
					<div class="footer__text footer__text_first">Интернет-магазин Monsher</div>
					<a href="tel:<?=$phone_1_f?>" class="footer__tel"><?=$phone_1?></a>
					<a href="tel:<?=$phone_2_f?>" class="footer__tel"><?=$phone_2?></a>
					
					<a href="" class="footer__call-phone-button js-viewForm" data-action="callback"><span>Заказать звонок</span></a>
					
					<a href="mailto:<?=$arData["PROPERTIES"]["EMAIL"]["VALUE"]?>" class="footer__email"><i>Email:</i><?=$arData["PROPERTIES"]["EMAIL"]["VALUE"]?></a>
					
					<div class="footer__text footer__text_two">Режим работы</div>
					<div class="footer__text footer__text_three"><?=$arData["PROPERTIES"]["TIMEWORK"]["~VALUE"]["TEXT"]?></div>
				</div>
				<!-- /.footer__col -->
			</div>
			
			
			
			<div class="footer__row">
				<div class="footer__copyright">
					© 2004 – <?=date("Y");?> Магазин Monsher.
					<br>«NTK LTD TM all rights reserved»
					<?if(!in_array($menu_tip, array('contacts'))):?>
										<?$APPLICATION->IncludeFile(
											 '/local/include_areas/micro_footer.php',
											 Array("MENU_TIP" => $menu_tip),
											 Array("MODE"=>"php")
										 )?>
					<?endif;?>
				</div>
				<? /*
				<div class="social-items">
					<a href="" class="social-link vk"></a>
					<a href="" class="social-link fb"></a>
					<a href="" class="social-link tw"></a>
					<a href="" class="social-link ok"></a>
				</div>
				*/?>
				<a class="privacy-policy" title="Политика конфиденциальности" href="/personal-data/">Политика конфиденциальности</a>
				<div class="footer__telephones">
					<div class="l-abuse">
						<a href="" class="footer__button abuse__button js-viewForm" data-action="guideback">Пожаловаться руководству</a>
					</div>
				</div>
			</div>
			
			<!-- /.footer__row -->
		</div>

		<!-- /.footer__inner -->

	</div>
	

				
	<!-- end footer -->
	<?$APPLICATION->IncludeFile('/local/include_areas/counters_footer.php')?>
</body>
</html>
