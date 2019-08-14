<div class="reviews__review">
					<div class="reviews__inner-button">
						<a class="js-reviews__review-link reviews__review-link" href="#">Оставить отзыв</a>
					</div>
					<div class="reviews-form  js-reviews-form">

						<form action="" method="post" class="reviews-form__form js-form">

							<div class="reviews-form__block">
								<div class="reviews-form__col">
									<div class="reviews-form__text">Оценка</div>
								</div>
								<div class="reviews-form__col  reviews-form__col_rate">
								
									<div class="reviews-form__rate">
										<input class="js-review_rating" type="hidden" value="5" name="F[RATING]" />
										<div class="reviews-rate reviews-rate_pointer">
											<div class="reviews-rate__section js-reviews-rate__section">
												<div class="reviews-rate__start"></div>
												<div class="reviews-rate__click" style="width: 100%;"></div>
												<div class="reviews-rate__disable"></div>
												<div class="reviews-rate__hover"></div>
											</div>
										</div>
										<!-- /.reviews-rate -->

									</div>								
									
								</div>
							</div>
							<div class="reviews-form__wrapper">
								<div class="reviews-form__block  reviews-form__block_half">
									<div class="reviews-form__col">
										<div class="reviews-form__text">Ваше имя:</div>
									</div>
									<div class="reviews-form__col">
										<input type="text" name="F[NAME]" class="reviews-form__input" value="" placeholder="Введите имя" />
									</div>
								</div>
								<div class="reviews-form__block  reviews-form__block_half">
									<div class="reviews-form__col">
										<div class="reviews-form__text reviews-form__text_phone">E-mail:</div>
									</div>
									<div class="reviews-form__col">
										<input type="email" name="F[EMAIL]" class="reviews-form__input typeEmail" value="" placeholder="Введите e-mail" />
									</div>
								</div>
							</div>
							<div class="reviews-form__block">
								<div class="reviews-form__col">
									<div class="reviews-form__text reviews-form__text_msg">Достоинства:</div>
								</div>
								<div class="reviews-form__col">
									<textarea name="F[PLUS]" class="reviews-form__msg" placeholder="Достоинства"></textarea>
								</div>
							</div>

							<div class="reviews-form__block">
								<div class="reviews-form__col">
									<div class="reviews-form__text reviews-form__text_msg">Недостатки:</div>
								</div>
								<div class="reviews-form__col">
									<textarea name="F[MINUS]" class="reviews-form__msg" placeholder="Недостатки"></textarea>
								</div>
							</div>

							<div class="reviews-form__block">
								<div class="reviews-form__col">
									<div class="reviews-form__text  reviews-form__text_msg">Комментарий:</div>
								</div>
								<div class="reviews-form__col">
								<textarea name="F[TXT]" class="reviews-form__msg reviews-form__msg_comment"  placeholder="Комментарии"></textarea>
								</div>
							</div>

							<div class="reviews-form__footer">
								
								<label class="reviews-form__label js-checkbox">
									<div class="js-check_error">
									<input type="checkbox" name="CONFIRM" value="Y" checked="checked" class="reviews-form__checkbox js-check" />
									<span></span>Отправляя персональные данные, потверждаю согласие на их <a href="<?=CStatic::$pathConf?>" target="_blank">обработку</a>
									</div>
								</label>
								
								<input type="submit" value="Оставить отзыв" class="reviews-form__submit js-go" />
								<input type="hidden" value="reviewsAdd" name="action" />
								<input type="hidden" value="<?=$arParams["TOV_ID"]?>" name="F[TOV_ID]" />
							</div>

						</form>
					</div>
				</div>