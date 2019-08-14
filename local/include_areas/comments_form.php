
<form class="add-comment__form js-form" action="" method="post" id="comment_form">
								<span class="form__title add-comment__title">Комментировать</span>
								<div class="add-comment__row">
									<strong class="add-comment__name">Ваш комментарий:
										<span class="abuse-popup__req callback__req">*</span>
									</strong>
									<textarea rows="4" cols="40" name="F[TXT]" placeholder="Ваш комментарий *"></textarea>
									<span class="add-comment__text">Комментарии публикуются после проверки модераторами. Перед отправкой вашего комментария убедитесь, что он не нарушает правил модерации.</span>
								</div>
								<div class="add-comment__row">
									<strong class="add-comment__name">Ваше имя:
										<span class="abuse-popup__req callback__req">*</span>
									</strong>
									<input type="text" name="F[NAME]" value="" placeholder="Ваше имя *" />
								</div>
								<div class="add-comment__row">
									<strong class="add-comment__name">Email:
										<span class="abuse-popup__req callback__req">*</span>
									</strong>
									<input type="email" name="F[EMAIL]" value="" placeholder="Email *" class="typeEmail" />
									<span class="add-comment__text">Нажимая кнопку отправить вы соглашаетесь с использованием <a href="<?=CStatic::$pathConf?>" target="_blank">персональных данных</a></span>
								</div>
								<div class="add-comment__item">
									<input type="submit" value="Отправить" class="btn-sub3 abuse-popup__submit js-go" />
									<input type="hidden" value="commentsAdd" name="action" />
									<input type="hidden" value="<?=$arParams["ART_ID"]?>" name="F[ART_ID]" />
								</div>
</form>

