<?
$menu_tip = 'contacts';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Телефон, время работы и реквизиты нашего интернет-магазина l-rus.ru.");
$APPLICATION->SetPageProperty("title", "Контактная информация интернет-магазина l-rus.ru");
$APPLICATION->SetTitle("Контакты");

$arData = CStatic::getElement(CStatic::$DataIdByRegion[$_COOKIE["K_REGION"]], 31);
	
	$phone_1 = $arData["PROPERTIES"]["PHONE_1"]["VALUE"];
	$phone_1_f = preg_replace("([^0-9])", "", $phone_1);
?>

							<div class="contact">
								<div class="contact__item">
									<div class="contact__element">
										<p>
											<b>Телефон</b>
											<a href="tel:<?=$phone_1_f?>" class="header__phone"><?=$phone_1?></a>
										</p>
										<p>
											<b>Email</b>
											<a itemprop="email" saprocessedanchor="true" href="mailto:<?=$arData["PROPERTIES"]["EMAIL"]["VALUE"]?>" target="_blank"><?=$arData["PROPERTIES"]["EMAIL"]["VALUE"]?></a>
										</p>
										<p>
											<b>Время работы</b>
											<span><?=$arData["PROPERTIES"]["TIMEWORK"]["~VALUE"]["TEXT"]?></span>
										</p>
									</div>
									<div class="contact__element">									
										<?$APPLICATION->IncludeFile('/local/include_areas/contacts-req.php')?>
		<?$APPLICATION->IncludeFile(
			 '/local/include_areas/micro_contacts.php',
			 Array(),
			 Array("MODE"=>"php")
		 )?> 
									</div>
								</div>
								<div class="contact__item">	
									<?$APPLICATION->IncludeFile('/local/include_areas/contacts-form.php')?>
								</div>
								<div class="contact__item">								
									<p>
										<b>Правила приема рекламационных обращений</b>
										Информация по приему <a href="/kachestvo/">претензий и заявлений на рекламацию</a>
									</p>								
								</div>
							</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>