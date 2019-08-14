var globalPopup;
$(document).ready(function(){
	
		 globalPopup = new Popup();
	
	
	
			function logElementEvent(eventName, element) {
				console.log(
					Date.now(),
					eventName,
					element.getAttribute("data-bg")
				);
			}
			var callback_enter = function(element) {
				logElementEvent("🔑 ENTERED", element);
			};
			var callback_exit = function(element) {
				logElementEvent("🚪 EXITED", element);
			};
			var callback_reveal = function(element) {
				logElementEvent("👁️ REVEALED", element);
			};
			var callback_loaded = function(element) {
				logElementEvent("👍 LOADED", element);
			};
			var callback_error = function(element) {
				logElementEvent("💀 ERROR", element);
				element.src =
					"https://via.placeholder.com/440x560/?text=Error+Placeholder";
			};
			var callback_finish = function() {
				logElementEvent("✔️ FINISHED", document.documentElement);
			};
			LL = new LazyLoad({
				elements_selector: ".lazyload",
				// Assign the callbacks defined above
				callback_enter: callback_enter,
				callback_exit: callback_exit,
				callback_reveal: callback_reveal,
				callback_loaded: callback_loaded,
				callback_error: callback_error,
				callback_finish: callback_finish
			});
	
	
	
	
	
	
		//setHeight_My('.js-catalog', '.js-catalog_item', '.js-catalog_item-name');
	
	
		$('.js-input_number').on('input', function (event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});
	
	
	
		$('.js-change_no').on('keypress', function (e) {
			e.preventDefault();
		});	
		$('.js-href_false').on('click', function() {	
			return false;
		});
	
		//$('.js-fancybox').fancybox();
	
	
		$('.js-phone_mask').inputmask("+7 (999) 999-9999");

	
		$('body').on('click', '.js-filter_go', function() {
			
			$('.js-filter_form').submit();		
			return false;
		});
		
		
		$('body').on('click', '.js-compare', function() {
					
					CompareChange($(this));	

					
					return false;	
		});
		
		//$('body').on('change', '.js-compare_block-change', function() {
		/*
		$('.js-compare_block-change').on('change',  function() {
			
			var _block = $(this).val();	
			var data = 'action=CHANGE_COMPARE_BLOCK&BLOCK_ID='+_block;
			$.ajax({
				 type: "POST",
				 dataType: "json",
				 url: "/ajax/back.php",
				 data: data,
				 success: function(returns) {
					 
					location.reload();	
				 }
			});				
		});*/
		
		
		
		
		
		
	$('body').on('click','.js-compare_all', function () {
		
		var _this = $(this);	
		var _ids = _this.data('products');		
		
			var data = 'action=ADD_COMPARE_ALL&IDS='+_ids;			
			$.ajax({
				 type: "POST",
				 dataType: "json",
				 url: "/ajax/back.php",
				 data: data,
				 success: function(returns) {

						$('.js-GoodsInCompare').data('count', returns.COUNT).html(returns.COUNT);	

						location.href = '/compare/';

/*
						globalPopup.options({
							closeButtons: '.js-close'
						}).html(returns.HTML).show();
					*/ 
				 }
			});	
			
			

			return false;			
		
	});
		
	$('body').on('click','.js-viewed_remove', function () {
		
		var _this = $(this);
		var _block = _this.parents('.js-catalog_item');		
		var _id = _block.data('id');
		
		
			var data = 'action=VIEWED_REMOVE&ID='+_id;
			
			$.ajax({
				 type: "POST",
				 dataType: "json",
				 url: "/ajax/back.php",
				 data: data,
				 success: function(returns) {					 
					_block.remove();
				 }
			});	

			return false;			
		
	});
		
	$('body').on('click','.js-viewed_remove-all', function () {
		
		var _this = $(this);
		var _block = _this.parents('.js-catalog_viewed');	
		
			var data = 'action=VIEWED_REMOVE_ALL';
			
			$.ajax({
				 type: "POST",
				 dataType: "json",
				 url: "/ajax/back.php",
				 data: data,
				 success: function(returns) {					 
					_block.html('');
				 }
			});	

			return false;			
		
	});
		
	$('body').on('click','.js-favorite_remove-all', function () {
		
		var _this = $(this);
		
		$.cookie('FAVORITE_LIST', '', { expires: 7, path: '/' });	
		
		$('.js-favorite_list').html('<div class="text-default">Список избранных товаров пуст.</div>');
		$('.js-favorite_filter').html('');
		
		$('.js-GoodsInFavorites').data('count', 0);	
		$('.js-GoodsInFavorites').html('0');
		
		return false;	
	});
		
	$('body').on('click','.js-add2favorite', function () {
		
		var _this = $(this);
		
		var _id = $(this).data('id');	
		
		
		if(_id) {
			
			if($.cookie('FAVORITE_LIST')) var _arr = $.cookie('FAVORITE_LIST');
			else var _arr = '';		
			_arr = _arr.split('|');			
			var _find = false;
			
			if(_arr.length>0)
			{
				$.each(_arr, function( key, value )
				{					
						if(!value) {	
							_arr.splice(key, 1);
						}
						else {				
							if(value == _id) 
							{
								_find = true;
								_arr.splice(key, 1);								
								
								console.log('Удаляем 3');
								_this.removeClass('active');	

								if(_this.children().length) _this.children().html('В избранное');
								
							}	
						}									
				});			
				
				if(!_find){
					console.log('Добавляем 1');
					_arr[_arr.length] = _id;
					_this.addClass('active');	
					
					if(_this.children().length) _this.children().html('Из избранное');
				}			
			}
			else
			{
				console.log('Добавляем 2');
				
				_arr.splice( 0,0,_id);
				_this.addClass('active');


				if(_this.children().length) _this.children().html('Из избранное');
			}
			
			var _count = _arr.length;		
			//$('.js-GoodsInLiked').html(_count);
			
			var _arr_text = ['товар','товара','товаров']; 
			
			
			console.log(_count);
			
			$('.js-GoodsInFavorites').data('count', _count);	
			$('.js-GoodsInFavorites').html(_count);	
			
			
			
			
			
			
			
			
			
			$.cookie('FAVORITE_LIST', _arr.join('|'), { expires: 7, path: '/' });			
			
			if($('.js-favorite_list').length>0 && _find) 
			{
				_this.parents('.js-catalog_item').remove();				
				
				if(!$('.js-catalog_item').length) {					
					$('.js-catalog').html('<div class="text-default">Список избранных товаров пуст.</div>');
					$('.js-favorite_filter').html('');
				}
				
			}
			

		}


		return false;
    });
		
		
		
		
		
		$('body').on('click', '.js-compare_block-change', function() {
			
			var _section = $(this).data('id');	
			var data = 'action=CHANGE_COMPARE_SECTION&SECTION_ID='+_section;
			$.ajax({
				 type: "POST",
				 dataType: "json",
				 url: "/ajax/back.php",
				 data: data,
				 success: function(returns) {
					 
					location.reload();	
				 }
			});	
			
			return false;
		});
		
		/*
		$('body').on('click', '.js-compare_block-change', function() {
			
			var _block = $(this).data('id');	
			var data = 'action=CHANGE_COMPARE_BLOCK&BLOCK_ID='+_block;
			$.ajax({
				 type: "POST",
				 dataType: "json",
				 url: "/ajax/back.php",
				 data: data,
				 success: function(returns) {
					 
					location.reload();	
				 }
			});	
			
			return false;
		});*/
		
		$('body').on('click', '.js-compare_block-clear', function() {
			
			var _block = $(this).data('id');	
			var data = 'action=CLEAR_COMPARE_BLOCK&BLOCK_ID='+_block;
			$.ajax({
				 type: "POST",
				 dataType: "json",
				 url: "/ajax/back.php",
				 data: data,
				 success: function(returns) {					 
					location.reload();	
				 }
			});	
			
			return false;
		});
		
		$('body').on('click', '.js-compare_remove', function() {	
					//winaclose();
					
					CompareChange($(this));
							
					return false;	
		});

		$("body").on('click','.js-art_type-change_all', function() {
			$('.js-art_type-change').attr('checked', false);
			
			var c_form = $(this).parents('.js-form');
			var data = c_form.serialize();	
			//console.log(data);
							
							$.ajax({
							  type: "POST",
							  dataType: "json",
							  url: "/ajax/back.php",
							  data: data,
							  success: function(returns) {
								  
									location.reload();	
							  }
							});		
		});

		
		$("body").on('click','.js-art_type-change', function() {
		
			var c_form = $(this).parents('.js-form');
			var data = c_form.serialize();	
			//console.log(data);
							
							$.ajax({
							  type: "POST",
							  dataType: "json",
							  url: "/ajax/back.php",
							  data: data,
							  success: function(returns) {
								  
									location.reload();	
							  }
							});								
							
			//return false;
		});

		$('body').on('click','.js-sort_change', function() {
		
							var data = 'action=changeSort&K_SORT='+$(this).data("sort")+'&K_ORDER='+$(this).data("order");
							$.ajax({
							  type: "POST",
							  dataType: "json",
							  url: "/ajax/back.php",
							  data: data,
							  success: function(returns) {
								location.reload();	
							  }
							});								
							
			return false;
		});
		
		
		
		
		$(".js-page_kol").on('change', function() {
		
			console.log('change');
			
							var data = 'action=changePage&PAGE_KOL='+$(this).val();
							$.ajax({
							  type: "POST",
							  dataType: "json",
							  url: "/ajax/back.php",
							  data: data,
							  success: function(returns) {

									location.reload();	
							  }
							});								
							
			return false;
		});
	
	
		$('body').on('click','.js-view_change', function() {
			
			var data = 'action=changeView&val='+$(this).data('val');
							$.ajax({
							  type: "POST",
							  dataType: "json",
							  url: "/ajax/back.php",
							  data: data,
							  success: function(returns) {	
									location.reload();	
							  }
							});								
							
			return false;
			
		});	
	
	$('body').on('click','.js-add2basket', function() {
		
							var _this = $(this);						
							var TOV_ID = _this.data('id');												
								var data = 'action=ADD2BASKET&TOV_ID='+TOV_ID;	
								//console.log(data);
								
								ECOM_AddToBasket(TOV_ID, 1);
								
								$.ajax({
									type: "POST",
									dataType: "json",
									url: "/ajax/back.php",
									data: data,
									success: function(returns) {
									
											$('.js-GoodsInBasket').html(returns.COUNT);												
											
											// Выводим плашку
											var str2 = 'action=ADD2BASKET_AFTER&TOV_ID='+TOV_ID+'&BASKET_ID='+returns.BASKET_ID;
											$.post('/ajax/front.php', str2, function (returns2) {												
												
												globalPopup.options({
													closeButtons: '.js-close'
												}).html(returns2).show();
												
											});	
											
											
									
									}			
								});								
							
			
							return false;
	});
		
	
		
		
		
	$('body').on('click','.js-captcha_change', function() {
		var _this = $(this);
		var _input = $('.js-captcha_code');
	
		var data = 'action=change_captcha';
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "/ajax/back.php",
			data: data,
			success: function(returns) {			
				_this.attr('src',returns.CAPTCHA_IMG);							
				_input.val(returns.CAPTCHA_CODE);			
			}			
		});	
	});
	
	
	$('body').on('click','.js-viewForm_block', function() {							
							
							if($(this).data('size')) {
								var _size = $(this).data('size');							
							}
							else {
								var _size = 594;		
							}	
							
							//console.log($(this).data('block'));
							
							
							if($($(this).data('block')).length) {
								
								var _html = $($(this).data('block')).html();
								
								globalPopup.html(_html).show();
								
							}
									
											
							return false;	
	});
	
	
	$('body').on('click','.js-viewForm', function() {							
							
							//console.log('33333');
							
							if($(this).data('size')) {
								var _size = $(this).data('size');							
							}
							else {
								var _size = 441;		
							}	
							
							var _action = $(this).data('action');
							var str = 'action='+_action;
							if($(this).data('type')) {
								str = str+'&TYPE='+$(this).data('type');							
							}
							if($(this).data('id')) {
								var _id = $(this).data('id');
								str = str+'&ID='+_id;							
							}

							$.post(
								'/ajax/front.php',
								str,
								function (_html) {									
									
									globalPopup.html(_html,function() {
										if($('.js-form_page').length) {$('.js-form_page').val(document.location.href);}
										
										
										if(_action == 'reviewsAdd') {										
										
											new Ratings({
												element: document.querySelector('.js-ratings__section'), // передаем элемент
												countRate: 5, // кол-во оценок
												clickFn: function (index) {
													console.log('val = '+index);
													$(".js-review_rating").val(index);
												}
											});
										}
										
										
										
										
										$('.js-phone_mask').inputmask("+7 (999) 999-9999");										
										
										if(_action == 'BuyOneClick') {											
											ECOM_InitBasket('2', _id);
										}										
										
									}).show();									
									
								}
							);
											
							return false;	
	});

	
				
					//console.log('form go init');
				$('body').on('click','.js-form .js-go',function(){	
						 
						 
						//console.log('form go');
						var _button = $(this);

						_button.fadeToggle(100);

						
						var c_form = $(this).parents('.js-form');	
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
							var data = c_form.serialize();
							$.ajax({
							  type: "POST",
							  dataType: "json",
							  url: "/ajax/back.php",
							  data: data,
							  success: function(returns) {

								_button.fadeToggle(100);
								
								
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
									//location.reload();								
									window.location = returns.LOCATION;
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
						else {
							_button.fadeToggle(100);
						}
						
						return false;
				});
				
				
				
				
				
				
				
				
				
				
				/* ################################### Корзина #################################### */	
				
				if($('.js-basket_city').length) {
					$(".js-basket_city").autocomplete({
						source: function( request, response ) {
							$.ajax({
								url: "/ajax/back.php",
								dataType: "json",
								data: {
									action: 'autocomplete_CITY',
									str: request.term
								},
								success: function( data ) {	
									response( $.map( data.AUTO_LIST, function(item) {
										return {
											label: item,
											value: item
										}
								}));
									
									
								}
							});
						},							
						minLength: 2,
					});
				}
				
				
				if($('.js-basket_metro').length) {
					$(".js-basket_metro").autocomplete({
						source: function( request, response ) {
							$.ajax({
								url: "/ajax/back.php",
								dataType: "json",
								data: {
									action: 'autocomplete_METRO',
									str: request.term
								},
								success: function( data ) {	
									response( $.map( data.AUTO_LIST, function(item) {
										return {
											label: item,
											value: item
										}
								}));
									
									
								}
							});
						},							
						minLength: 2,
					});
				}
				
				
				$('body').on('change','.js-basket_change-lift', function() {					
					
					console.log($(this).val());
					if($(this).val() == 'NO') {
						$('.js-basket_floor').addClass('hide');											
					}
					else {						
						$('.js-basket_floor').removeClass('hide');			
					}						
				});
				
				$('body').on('change','.js-basket_change-city', function() {					
					
					//console.log($(this).val());
					if($(this).val() == 'MSK') {
						$('.js-basket_metro').removeClass('hide');						
						$('.js-basket_city').addClass('hide');						
					}
					else {						
						$('.js-basket_metro').addClass('hide');
						$('.js-basket_city').removeClass('hide');	
					}						
				});
				
				
				$('body').on('click', '.js-basket__go', function() {
		
					var c_form = $(this).parents('.js-form');	
					//c_form.find('.js-error').fadeOut();
					var error = CheckPols(c_form);
					
					
					//console.log('goooooo 111');
					
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
					if(error!='1')	{		
						

/*
						try{		
							trackerParams =  k50Tracker.getResultData();
								
							$('.js-track_sid').val(trackerParams.sid);
							$('.js-track_uuid').val(trackerParams.uuid);	
								
						} catch(er){ console.error(er); }
*/
						
						submitForm('Y');							
					}
					else {		
						//console.log('error 111');
						$('html, body').animate({ scrollTop: $('.BadPols').offset().top-100}, 1000);
					}
					
					return false;
				});	
				
			
			$('body').on('click','.js-product__del', function(e) {
				if(e) e.preventDefault();	  	
				var _this = $(this)
				
					ECOM_DeleteToBasket($(this).data('prodid'));
					var str = 'action=DELETE2BASKET&ID='+$(this).data('id');
					$.ajax({
							type: "POST",
							dataType: "json",
							url: "/ajax/back.php",
							data: str,
							success: function(returns) {
							
							$('.js-GoodsInBasket').html(returns.COUNT);	
							
								_this.closest('.js-product__row').remove();
								if($('.js-product__row').length > 0) {
									ChangeItog();
								}
								else {
									location.reload();
								}
							
								
							}							  
					});				
				
			// удаление
				return false;  	
		  });

	
	  
		$('.js-change input').on('change', function() {

			var _item = $(this).parents('.js-change').find('input');
			var _kol = parseInt(_item.val());
			var _baskID = parseInt(_item.data('basketid'));			
				
				if(isInt(_kol))
				{
					if(_kol<1) _kol = 1; 	
				}
				else
				{
					var _kol = 1;
				}						
									
				$(this).parents('.js-change').find('input').val(_kol);					
				ChangeItog();	

				var str = 'action=CHANGE2BASKET&TOV_ID='+_baskID+'&KOL='+_kol;		
					$.ajax({
						type: "POST",
						dataType: "json",
						url: "/ajax/back.php",
						data: str,
						success: function(returns) {
							$('.js-GoodsInBasket').html(returns.COUNT);		
						}							  
				});		
			
		});
	  
		$('body').on('click','.js-change a', function() {					
				
				var _item = $(this).parents('.js-change').find('input');
				var _kol = parseInt(_item.val());		
				var _baskID = parseInt(_item.data('basketid'));
				
				if(isInt(_kol))
				{
					if($(this).hasClass("js-plus"))
					{
						var _NewKol = _kol + 1;
					}				
					if($(this).hasClass("js-minus"))
					{						
						var _NewKol = _kol - 1;	
					}
				}
				else
				{
					var _NewKol = 1;
				}		
				
					if(_NewKol<1) _NewKol = 1; 					
					$(this).parents('.js-change').find('input').val(_NewKol);
					ChangeItog();					
					
					var str = 'action=CHANGE2BASKET&TOV_ID='+_baskID+'&KOL='+_NewKol;		
					$.ajax({
						type: "POST",
						dataType: "json",
						url: "/ajax/back.php",
						data: str,
						success: function(returns) {
							$('.js-GoodsInBasket').html(returns.COUNT);	
						}							  
					});
					
			return false;		
					
		});
			
			
			
		 function ChangeItog()
		  {
				var _sum_all = 0;		
				$('.js-product__row').each(function() {			
					var _tov = $(this);
					var _kol = _tov.find('.js-change input').val();
					
					var _price = _tov.find('.js-change input').data('price');
					var _sum = parseInt(_kol)*parseInt(_price);
					
					_sum_all = parseInt(_sum_all) + parseInt(_sum);	
					_tov.find('.js-sum').html(sdf_FTS(_sum,0,' ')+' руб.');				
				});			
				
				$('.js-sum_all').html(sdf_FTS(_sum_all,0,' ')+' руб.');	  
		  }	  	
			
			
			/* #################################### Корзина End ################################### */








				
				
				
	
});