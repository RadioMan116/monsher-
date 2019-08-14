<h3>Задать вопрос</h3>
<form action="" method="post" class="contact__form js-form">
										
										
										<div class="contact__detail">
											<span class="return-exchange__name">Ваше имя <span class="return-exchange__required">*</span></span>
											<input type="text" name="F[NAME]" value="" class="return-exchange__input" placeholder="Ваше имя *" title="ФИО" />
										</div>
										<div class="contact__detail">
											<span class="return-exchange__name">E-mail <span class="return-exchange__required">*</span></span>
											<input type="text" name="F[EMAIL]" value="" class="return-exchange__input typeEmail" placeholder="Email *" title="E-mail" />
										</div>
										<div class="contact__detail">
											<span class="return-exchange__name">Телефон</span>
											<input type="tel" name="F[PHONE]" value="" class="return-exchange__input noCheck" placeholder="Телефон *" title="Телефон" />
										</div>
										<div class="contact__detail contact__detail-textarea">
											<span class="return-exchange__name">Ваш вопрос <span class="return-exchange__required">*</span></span>
											<textarea name="F[TXT]" class="return-exchange__textarea" placeholder="Ваш вопрос *" title="Причина обращения"></textarea>
										</div>
										
										
										<label class="checkbox checkbox_oferta js-checkbox"> 
											<input type="checkbox" name="" class="checkbox__input js-check" checked="">
											<span class="checkbox__icon js-check_icon"></span>
											<span class="checkbox__text">Согласен с	<a target="_blank" href="<?=CStatic::$pathConf?>">использованием персональных данных</a> для обработки обращения</span>
										</label>
										
										
										
										<input type="hidden" name="action" value="feedbackAdd" /> 
										<input type="submit" value="Отправить" class="btn-sub3 abuse-popup__submit js-go" />
</form>