$(document).ready(function() {

	var text = '<div class="return-form__field js-return-form__field">\
                	<div class="return-form__item">\
                    	<input type="file" class="return-form__file js-return-form__file noCheck" name="files[]">\
                       	<div class="return-form__button js-return-form__button">Добавить файл</div>\
                        <div class="return-form__delete js-return-form__delete">&times;</div>\
                    </div>\
                </div>';

	var form = $('#js-return-form__form');
	var section = $('#js-return-form__section');
	var add = $('#js-return-form__add');

	
	
	$('body').on('click','.js-form_doc .js-go',function(){						
						
						var c_form = $(this).parents('.js-form_doc');	
						var form = $(this).parents('.js-form_doc')[0];
						
						c_form.find('.js-error').fadeOut();
						var error = CheckPols(c_form);
						if(c_form.find('.js-check').length) {

							var inp_check = c_form.find('.js-check');
							
							if(!inp_check.prop('checked')) {								
								inp_check.parents('.js-checkbox').find('.js-check_icon').addClass('BadPols');
								error = 1;
							}
							else {
								inp_check.parents('.js-checkbox').find('.js-check_icon').removeClass('BadPols');								
							}								
						}
						
						if(error!='1')
						{							
							var data = new FormData(form);
							//data.append("docs", $('.fileinput')[0].files[0]);
							
							$.ajax({
							  url: "/ajax/back.php",	
							  type: "POST",
						      contentType: false, // важно - убираем форматирование данных по умолчанию
							  processData: false, // важно - убираем преобразование строк по умолчанию
							  data: data,
							  dataType: "json",
							  success: function(returns) {													
																
								if(returns.CAPTCHA == 'N') {
									$('.js-captcha_inp').addClass('BadPols');
									$('.js-captcha_change').trigger('click');
								} 
								else {
									$('.js-captcha_inp').removeClass('BadPols');
								}							
																
								c_form.find('.js-error').fadeIn().html(returns.MESSAGE);	
								if(returns.RELOAD == 'Y')
								{									
									location.reload();								
								}
								if(returns.SUCCESS == 'Y')
								{								
									
									//console.log(returns.CLEARPOLS);
									if(returns.CLEARPOLS == 'Y')	{									
										ClearPols(c_form);									
									}	

									if(returns.HTML) {	
										globalPopup.html(returns.HTML).show();	
									}
														
								}	
							  }
							});					
						}
						
						return false;
	});
	
	
	
	/*
	$(form).submit(function() {

		

		var error = CheckPols($('#js-return-form__form'));
		
		if(error!='1')
		{
				var data = new FormData(this);
				$.ajax({
					url: "/ajax/back.php",
					type: "POST",
					contentType: false, // важно - убираем форматирование данных по умолчанию
					processData: false, // важно - убираем преобразование строк по умолчанию
					data: data,
					dataType: "json",
					success: function(returns) {

					}
				});
		}
		
		return false;

	});
*/
	$(add).click(function() {

		$(section).append(text);
		return false;

	});

	$(document).on('change', '.js-return-form__file', function(e) {
		$(this).parent().addClass('active');
		$(this).next().html(this.value);
	});

	$(document).on('click', '.js-return-form__delete', function(e) {

		if($('.js-return-form__field').length > 1) {
			$(this).parent().parent().fadeOut(function() {
				$(this).remove();
			});
			return false;
		}

		$(this).parent().removeClass('active');
		$(this).prev().html('Добавить файл');
		$(this).siblings('input').val('');

	});

});