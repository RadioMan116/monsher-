<div class="return-exchange__item">
									<h3>Форма для рекламационных обращений</h3>
									<form name="" action="" method="post" enctype="multipart/form-data" class="return-exchange__form js-form_doc">
										<div class="return-exchange__field">
											<span class="return-exchange__name">ФИО
												<span class="return-exchange__required">*</span>
											</span>
											<input type="text" name="F[NAME]" value="" class="return-exchange__input" title="ФИО" />
											<span class="return-exchange__error-text"></span>
										</div>
										<div class="return-exchange__field">
											<span class="return-exchange__name">E-mail
												<span class="return-exchange__required">*</span>
											</span>
											<input type="text" name="F[EMAIL]" value="" class="return-exchange__input typeEmail" title="E-mail" />
											<span class="return-exchange__error-text"></span>
										</div>

										<div class="return-exchange__field">
											<span class="return-exchange__name">Телефон
												<span class="return-exchange__required">*</span>
											</span>
											<input type="text" name="F[PHONE]" value="" class="return-exchange__input" title="Телефон" />
											<span class="return-exchange__error-text"></span>
										</div>

										<div class="return-exchange__field">
											<span class="return-exchange__name">Номер заказа
												<span class="return-exchange__required">*</span>
											</span>
											<input type="text" name="F[ORDER_ID]" value="" class="return-exchange__input" title="Номер заказа" />
											<span class="return-exchange__error-text"></span>
										</div>

										<div class="return-exchange__field">
											<span class="return-exchange__name">Причина обращения
												<span class="return-exchange__required">*</span>
											</span>
											<textarea name="F[TXT]" class="return-exchange__textarea" title="Причина обращения"></textarea>
											<span class="return-exchange__error-text"></span>
										</div>
										
										
										<div class="return-exchange__field return-exchange__attachment">
											<span class="return-exchange__copyright">Приложения
												<i>(Внимание!</i>Рекламация будет рассмотрена после получения
												<a href="#claim-docs">полного пакета документов</a>)
											</span>
											<div class="return-exchange__file">
												<div class="button">ВЫБЕРИТЕ ФАЙЛ</div>
												<input name="files[]" type="file" multiple="multiple" class="multi noCheck" />
											</div>
										</div>
										
										
										<? /*
										<div class="return-exchange__field return-exchange__attachment">
											<span class="return-exchange__copyright">Приложения
												<i>(Внимание!</i>Рекламация будет рассмотрена после получения
												<a href="#claim-docs">полного пакета документов</a>)</span>
												
												<label class="return-exchange__file" for="test">
													<div>ВЫБЕРИТЕ ФАЙЛ</div>
													<input type="file" name="files[]" class="noCheck" id="test" />
												</label>
												<p id="filename"></p>															
												
										</div>
										<br>
										*/ ?>
										
										<div class="return-exchange__field return-exchange__attachment">
											<label class="checkbox checkbox_oferta js-checkbox">
												<input class="checkbox__input js-check" type="checkbox" name="CONFIRM" value="Y" checked="checked" />
												<span class="checkbox__icon js-check_icon"></span>
												<span class="checkbox__text">Согласен с <a target="_blank" href="<?=CStatic::$pathConf?>">использованием персональных данных</a> для обработки обращения</span>
											</label>
											<div class="return-exchange__error-text js-field-error-text"></div>
										</div>
										
										

										<div class="return-exchange__submit return-exchange__attachment">
											<input type="hidden" name="action" value="claimAdd" />
											<input type="submit" value="Отправить" class="btn-sub3 abuse-popup__submit js-go" />
										</div>
									</form>
								</div>