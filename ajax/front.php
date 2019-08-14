<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//$APPLICATION->RestartBuffer();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
$el = new CIBlockElement;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();


use Bitrix\Main;


switch ($request->get('action')) {

		case "GET_MESSAGE":
?>		
			<div class="response-popup">
				<div class="response-popup__inner">
					<?if($request->offsetGet('title')):?><div class="response-popup__title"> <?=$request->get('title')?> </div><?endif;?>
					<?if($request->offsetGet('text')):?><div class="response-popup__text"> <?=$request->get('text')?> </div><?endif;?>
				</div>
			</div>			
<?		
		break;
		case "GoodPrice":
		?>			
		
		
		
		<div class="response-popup">
			<div class="response-popup__inner">
				<div class="response-popup__title">
					Гарантия лучшей цены
				</div>
				<div class="response-popup__text b-left">
					<p>Мы гарантируем лучшую цену, заявленную от производителя.</p>

					<p>Нашли дешевле? Снизим стоимость и вернем разницу + ценный подарок!</p>

					<p>Отправьте на <a href="mailto:<?$APPLICATION->IncludeFile('/local/include_areas/email.php')?>" title="Написать письмо"><?$APPLICATION->IncludeFile('/local/include_areas/email.php')?></a> любой факт, подтверждающий различие цены. Менеджер свяжется с вами в течение часа и сообщит о принятом решении.*</p>
					<br/>
					<div class="s11">* Магазин, на который вы ссылаетесь, должен находиться в том же городе, что и покупатель.<br/>
					** В отношении магазина будет проведена экспресс-проверка заявленной цены и наличия товара, а также принадлежности товара к официальной поставке и сертификации.</div>
				</div>
			</div>
		</div>		
		
		
		<?
		break;		
		case "servicesList":
		
		
		$arFilter = array(
			"IBLOCK_ID" => 52,
			"ACTIVE" => "Y",
		);
		
		
		$arServices = CStatic::GetElementList($arFilter, false, array("SORT" => "ASC"), true);
		
		
		
		?>
		
		<div class="popup_container warranty_popup">
			<span class="title-strike warranty__title">Сервисные центры Liebherr по обслуживанию бытовой техники в регионах РФ</span>
			<div class="warranty__items ">
			
			<?foreach($arServices as $arService):?>
				<p>
					<b><?=$arService["NAME"]?></b>
					<?=$arService["PREVIEW_TEXT"]?>
				</p>		
			<?endforeach;?>
				
			</div>
		</div>		
		
		<?
		break;		
		case "feedbackAdd":
		
		$arTov = CStatic::getElement($request->get("ID"));	
		?>
		
		<div class="opinion">
				<div class="opinion__title">Задать вопрос</div>
				<div class="opinion__name"><?=$arTov["NAME"]?></div>
				
					
				<form action="" method="post" class="opinion__form js-form">
					<div class="opinion__block">
						<div class="opinion__field-wrapper opinion__field-wrapper_req">
							<input type="text" name="F[NAME]" class="opinion__field js-phone_mask" placeholder="Имя" />
						</div>
						<div class="opinion__field-wrapper opinion__field-wrapper_req">
							<input type="text" name="F[EMAIL]" class="opinion__field typeEmail" placeholder="Контактный e-mail" />
						</div>
					</div>					
					<div class="opinion__block">
						<textarea name="F[TXT]" class="opinion__textarea" placeholder="Комментарии"></textarea>
					</div>
					

					<div class="contact-form__field">
						<label class="checkbox checkbox_oferta js-checkbox">
							<input class="checkbox__input js-check" type="checkbox" checked="checked" />
							<span class="checkbox__icon js-check_icon"></span>
							<span class="checkbox__text">Согласен с <a target="_blank" href="<?=CStatic::$pathConf?>">использованием персональных данных</a> для обработки обращения</span>
						</label>
					</div>
					
						<input type="hidden" value="feedbackAdd" name="action" />
						<input type="hidden" value="<?=$request->get('ID')?>" name="F[TOV_ID]" />
					
						<button type="submit" class="opinion__submit js-go">отправить</button>
				</form>

		</div>
		
		
				
		<?
		break;		
		case "reviewsAdd":
		
		
		?>
		<div class="opinion">
				<div class="opinion__title">Оставить отзыв</div>				
				
				<?if($request->offsetGet("ID")):?>
				<?
				$arTov = CStatic::getElement($request->offsetGet("ID"));
				?>
					<div class="opinion__name"><?=$arTov["NAME"]?></div>
				<?else:?>
					<div class="opinion__name">ОТЗЫВЫ О МАГАЗИНЕ L-RUS.RU</div>
				<?endif;?>
				
				
					<div class="ratings__section js-ratings__section">
						<div class="ratings__start"></div>
						<div class="ratings__click" style="width: 120px;"></div>
						<div class="ratings__disable"></div>
						<div class="ratings__hover"></div>						
					</div>
					
					<form action="" method="post" class="js-form opinion__form">
						<div class="opinion__block">
							<div class="opinion__field-wrapper opinion__field-wrapper_req">
								<input type="text" name="F[NAME]" class="opinion__field" placeholder="Имя" />
							</div>
							<div class="opinion__field-wrapper opinion__field-wrapper_req">
								<input type="text" name="F[EMAIL]" class="opinion__field typeEmail" placeholder="Контактный e-mail" />
							</div>
						</div>
						<?if($request->offsetGet("ID")):?>
						<div class="opinion__block">
							<textarea name="F[PLUS]" class="opinion__textarea noCheck" placeholder="Достоинства"></textarea>
						</div>
						<div class="opinion__block">
							<textarea name="F[MINUS]" class="opinion__textarea noCheck" placeholder="Недостатки"></textarea>
						</div>
						<?endif;?>
						<div class="opinion__block">
							<textarea name="F[TXT]" class="opinion__textarea" placeholder="Комментарии"></textarea>
						</div>
						

						<div class="contact-form__field">
							<label class="checkbox checkbox_oferta js-checkbox">
								<input class="checkbox__input js-check" type="checkbox" checked="checked" />
								<span class="checkbox__icon js-check_icon"></span>
								<span class="checkbox__text">Согласен с <a target="_blank" href="<?=CStatic::$pathConf?>">использованием персональных данных</a> для обработки обращения</span>
							</label>
						</div>
						
						<?if($request->offsetGet("ID")):?>				
							<input type="hidden" value="reviewsAdd" name="action" />
							<input type="hidden" value="<?=$request->offsetGet('ID')?>" name="F[TOV_ID]" />
						<?else:?>
							<input type="hidden" value="reviewsStoreAdd" name="action" />
						<?endif;?>
						
							<input type="hidden" value="5" name="F[RATING]" class="js-review_rating" />
							
							
						
							<button type="submit" class="opinion__submit js-go">отправить</button>
					</form>

		</div>
		
		<?
		break;		
		case "guideback":
		?>
		
<div class="popup_container abuse-popup-container">
	<span class="abuse__title">
		Пожаловаться руководству
	</span>
	<div class="abuse-popup">
		<form action="" method="post" class="abuse-popup__form js-form" style="position:relative;">

			<p class="abuse-popup__text">
				Руководство интернет-магазина <a href="/" class="abuse-popup__link"><?=$_SERVER["SERVER_NAME"]?></a> уделяет пристальное внимание качеству обслуживания своих клиентов.
			</p>
			<p class="abuse-popup__text">
				Если вы столкнулись с какими-либо проблемами на любом этапе заказа - мы будем благодарны за подробную информацию.
			</p>
			<p class="abuse-popup__text abuse-popup__text-last">
				Все сообщения рассматриваются лично руководством магазина.
			</p>
			<div class="abuse-popup__item">
				<strong class="abuse-popup__name">Ваше имя: <span class="abuse-popup__req">*</span></strong>
				<input type="text" name="F[NAME]" value="" class="abuse-popup__input" title="Ваше имя" />
			</div>
			<div class="abuse-popup__item">
				<strong class="abuse-popup__name">Номер заказа:</strong>
				<input type="text" name="F[ORDER_ID]" value="" class="abuse-popup__input noCheck" title="Номер заказа" />
			</div>
			<div class="abuse-popup__item">
				<strong class="abuse-popup__name">Телефон: <span class="abuse-popup__req">*</span></strong>
				<input type="text" name="F[PHONE]" value="" class="abuse-popup__input js-phone_mask" title="Телефон" />
			</div>
			<div class="abuse-popup__item">
				<strong class="abuse-popup__name">E-mail: <span class="abuse-popup__req">*</span></strong>
				<input type="text" name="F[EMAIL]" value="" class="typeEmail abuse-popup__input" title="E-mail" />
			</div>
			<div class="abuse-popup__item">
				<strong class="abuse-popup__name">Что произошло?: <span class="abuse-popup__req">*</span></strong>
				<textarea name="F[TXT]" class="abuse-popup__textarea" title="Что произошло?"></textarea>
			</div>

			<div class="abuse-popup__footer">
				<span class="abuse-popup__required">
					<span class="abuse-popup__req">*</span>- поля, обязательные для заполнения
				</span>
			</div>
			<br>
			<div class="abuse-popup__item">
				<label class="checkbox checkbox_oferta js-checkbox">
							<input class="checkbox__input js-check" type="checkbox" checked="checked" />
							<span class="checkbox__icon js-check_icon"></span>
							<span class="checkbox__text">Согласен с <a target="_blank" href="<?=CStatic::$pathConf?>">использованием персональных данных</a> для обработки обращения</span>
						</label>
				
				<input type="hidden" name="action" value="guideAdd" />
				<input type="submit" value="Отправить" class="btn-sub3 abuse-popup__submit js-go" />
			</div>
		</form>
	</div>
</div>			
		
		<?
		break;		
		case "BuyOneClick":
		
		$arTov = CStatic::getElement($request->get("ID"));
		?>
		
		
		
		
		
		
		<div class="popup_container callback_popup">
			<span class="title-strike abuse__title">Купить в 1 клик</span>			
			<div class="call-popup-phone-inner relative">
				<p><?=$arTov["NAME"]?></p>
				<form class="callback__form js-form" action="" method="post">
					<strong class="abuse-popup__name callback__name">Ваше имя:
						<span class="abuse-popup__req callback__req">*</span>
					</strong>
					<input type="text" name="F[NAME]" value="" placeholder="Ваше имя" />
					
					<strong class="abuse-popup__name callback__name">Ваше телефон:
						<span class="abuse-popup__req callback__req">*</span>
					</strong>
					<input type="tel" name="F[PHONE]"  class="js-phone_mask" value="" placeholder="Номер телефона" />
					
					<div class="abuse-popup__item">
						<label class="checkbox checkbox_oferta js-checkbox">
							<input class="checkbox__input js-check" type="checkbox" checked="checked" />
							<span class="checkbox__icon js-check_icon"></span>
							<span class="checkbox__text">Согласен с <a target="_blank" href="<?=CStatic::$pathConf?>">использованием персональных данных</a> для обработки обращения</span>
						</label>
						
						<input type="hidden" name="F[PAGE]" class="js-form_page noCheck" value="" />
						<input type="hidden" name="TOV_ID" value="<?=$request->offsetGet('ID')?>" />
						<input type="hidden" name="action" value="BuyOneClick" />
					
						<input type="submit" value="Отправить" class="btn-sub3 abuse-popup__submit js-go" />
					</div>
				</form>
			</div>
		</div>		
		
		
		
		
		<?		
		break;	
		case "callback":
		?>
		
		<div class="popup_container callback_popup">
			<span class="title-strike abuse__title">Заказать звонок</span>			
			<div class="call-popup-phone-inner relative">
				<p>Наши менеджеры перезвонят вам в течение 30 мин</p>
				<form class="callback__form js-form" action="" method="post">
					<strong class="abuse-popup__name callback__name">Ваше имя:
						<span class="abuse-popup__req callback__req">*</span>
					</strong>
					<input type="text" name="F[NAME]" value="" placeholder="Ваше имя" />
					
					<strong class="abuse-popup__name callback__name">Ваше телефон:
						<span class="abuse-popup__req callback__req">*</span>
					</strong>
					<input type="tel" name="F[PHONE]" class="js-phone_mask" value="" placeholder="Номер телефона" />
					
					<div class="abuse-popup__item">
						<label class="checkbox checkbox_oferta js-checkbox">
							<input class="checkbox__input js-check" type="checkbox" checked="checked" />
							<span class="checkbox__icon js-check_icon"></span>
							<span class="checkbox__text">Согласен с <a target="_blank" href="<?=CStatic::$pathConf?>">использованием персональных данных</a> для обработки обращения</span>
						</label>
						
						<input type="hidden" name="F[PAGE]" class="js-form_page noCheck" value="" />
						<input type="hidden" name="action" value="callbackAdd" />
						<input type="submit" value="Отправить" class="btn-sub3 abuse-popup__submit js-go" />
					</div>
				</form>
			</div>
		</div>				
		
		
		
		<?
		break;
		case "ADD2COMPARE_AFTER":
		case "ADD2BASKET_AFTER":

			$arTov = CStatic::getElement($request->get("TOV_ID"));			

			//pre($arTov);
			
			
			switch($request->get('action')) {
				case "ADD2BASKET_AFTER":
					$title = "Товар добавлен в корзину";
					$title_go = "Перейти к оформлению";
					$url_go = "/cart/";
				break;	
				case "ADD2COMPARE_AFTER":
					$title = "Товар добавлен к сравнению";
					$title_go = "Перейти к сравнению";
					$url_go = "/compare/";
					
					$count = 0;
					if(!empty($_SESSION["CATALOG_COMPARE_LIST"][$arTov["IBLOCK_ID"]]["ITEMS"])) {
						$count = count($_SESSION["CATALOG_COMPARE_LIST"][$arTov["IBLOCK_ID"]]["ITEMS"]);
					}						
						
					//if($count < 2) {die("");}
					
					
					
				break;
							
			}
			
			
			
					$picture = '';
					if($arTov["PREVIEW_PICTURE"]) {						
						$picture = $arTov["PREVIEW_PICTURE"];
					}
					else if($arTov["DETAIL_PICTURE"]) {	
						$picture = $arTov["DETAIL_PICTURE"];
					}
					else if($arTov["PROPERTIES"]["PHOTOS"]["VALUE"]) {								
						$picture = $arTov["PROPERTIES"]["PHOTOS"]["VALUE"][0];
					}
					
					//pre($picture);
					if($picture) {
						$arTov["IMG_1"] = CFile::ResizeImageGet($picture, array('width'=>205, 'height'=>205), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
						//$arTov["IMG_2"] = CFile::ResizeImageGet($picture, array('width'=>410, 'height'=>410), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];	
						//$arTov["IMG_3"] = CFile::ResizeImageGet($picture, array('width'=>615, 'height'=>615), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];
					}
			
			$arPrices = CStatic::GetPrice($arTov["ID"]);	
			$arPrice = reset($arPrices);
			//pre($arPrices);
			
			
			$discountPercent = round(($arPrice["VALUE"]-$arPrice["DISCOUNT_VALUE"])*100/$arPrice["VALUE"]);
?>



<div class="popup_container added-cart_popup">
	<span class="title-strike abuse__title"><?=$title?></span>
	<div class="added-cart__items">
		<div class="added-cart__item">
			<a href="<?=$arTov["DETAIL_PAGE_URL"]?>" title="<?=$arTov["NAME"]?>">
				<img src="<?=$arTov["IMG_1"]?>" class="added-cart__img" />
			</a>
		
		</div>
		<div class="added-cart__item">
			<a class="added-cart__title" href="<?=$arTov["DETAIL_PAGE_URL"]?>" title="<?=$arTov["NAME"]?>"><?=$arTov["NAME"]?></a>
			<?if($arTov["PROPERTIES"]["ART"]["VALUE"]):?><span class="added-cart__key">Код товара: <?=$arTov["PROPERTIES"]["ART"]["VALUE"]?></span><?endif;?>
			
			<span class="added-cart__price"><?=number_format($arPrice["DISCOUNT_VALUE"], 0, '.', ' ')?><b>руб.</b></span>
			<div class="added-cart__button">
				<a href="#" id="added-cart__back" class="added-cart__back js-close">Продолжить покупки</a>
				<a class="added-cart__order" href="<?=$url_go?>"><?=$title_go?></a>
				<br>
			</div>
		</div>


	</div>
	
	
	
	<?if($arTov["PROPERTIES"]["ACC"]["VALUE"]):?>
	
	
<?

		global $arrFilter2;
		$arrFilter2 = array(
			"ID" => $arTov["PROPERTIES"]["ACC"]["VALUE"],
		);

		//Pre($arrFilter2);
?>
<?$APPLICATION->IncludeComponent(
		"inter.olsc:catalog.section",
		"tovs.list.acc.popup",
		Array(
			"K_PRICE_CODE" => $GLOBALS["K_PRICE_CODE"],
			"K_EXIST_CODE" => $GLOBALS["K_EXIST_CODE"],
			"CATALOG_COMPARE_LIST" => $_SESSION["CATALOG_COMPARE_LIST"],			
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_SECTIONS_CHAIN" => "N",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BASKET_URL" => "/order/",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "RAND",
			"ELEMENT_SORT_ORDER" => "",
			"ELEMENT_SORT_FIELD2" => "",
			"ELEMENT_SORT_ORDER2" => "",
			"FILTER_NAME" => "arrFilter2",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => CStatic::$accIdBlock,
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
			"OFFERS_LIMIT" => "10",
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
			"PAGE_ELEMENT_COUNT" => 3,
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array($GLOBALS["K_PRICE_CODE"],$GLOBALS["K_PRICE_CODE_SALE"]),
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
		)
	);?>
	<?endif;?>
</div>

<?
		break;

		
}



include_once ($_SERVER['DOCUMENT_ROOT'] .'/bitrix/modules/main/include/epilog_after.php');
?>