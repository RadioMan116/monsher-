/* ############################## list ################################## */
//var dataLayer = [];
var _page_s = false;

function ECOM_ProductList() {	
	
	var _arr = [];
	var k = 1;
	if(!_page_s) _page_s = '1';
	
	$('.js-ecom_product-item').each(function() {		
		
		$(this).find('a:not(.js-viewForm, .js-add2basket, .js-compare, .js-add2Compare, .js-add2favorite)').addClass('js-ecom_product-link');	
		_arr.push($(this).data('id'));
	});
	
	console.log('собираем');
	console.log(_arr);
	
		if($('.js-ecom_product-list').data('list')) {
			var _typelist =  $('.js-ecom_product-list').data('list');
			$.cookie('ecommerce_page_list', _typelist, { expires: 7, path: '/' });	
		}	
			
			
			var data = 'action=GetDataList&list='+_typelist+'&items='+_arr;	

			
			console.log('готовимся');
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "/_ecommerce/ecommerce.php",
				data: data,
				success: function(returns) {	
										
					if(returns.SUCCESS == 'Y') {						
					/*	
					console.log('1##################################');	
					console.log('2##################################');	
					console.log(returns.DATA);	
					*/
					
						window.dataLayer = window.dataLayer || [];
						dataLayer.push({'ecommerce': {
							'currencyCode': 'RUB',
							'impressions': returns.DATA
							},
							'event': 'gtm-ee-event',
							'gtm-ee-event-category': 'Enhanced Ecommerce',
							'gtm-ee-event-action': 'Product Impressions',
							'gtm-ee-event-non-interaction': true,
						});
						
						// доп фича отедльное
						
						
						if(_page_s == '1') {
							console.log('Data go list');
							ECOM_GTM(returns.DATA_2.Pagetype, returns.DATA_2.prodids, returns.DATA_2.totalvalue);
						}
						
					
						/*
						var _data = returns.DATA;
						//_data.each(function(product){
						_data.forEach(function(product, index) {
							
							//var product = $(this);
							//console.log(product);	
							
							ga('ec:addImpression', {
								'id': product.id,
								'name': product.name,
								'price': product.price,
								'category': product.category,
								//'variant': product.variant,
								'brand': product.brand,
								'list': product.list,
								'position': product.position							
							});				
							
						});						
						ga("send", "pageview");
						*/
						console.log('отправили данные');
					}										
				}			
			});	
	
	
}
/* ############################## list end ################################## */
/* ############################## detail ################################## */

function ECOM_ProductDetail() {	
	
			
			if(!_page_s) _page_s = '2';
			var _LIST = '';
			
			if($.cookie('ecommerce_page_list')) {
				_LIST = $.cookie('ecommerce_page_list');				
				$.removeCookie('ecommerce_page_list', { path: '/' });
			}
			
			
			var _id = $('.js-ecom_product-detail').data('id');
			var data = 'action=GetDataProduct&ID='+_id;			

			var _arr = [];
			$('.js-ecom_product-item').each(function() {		
				
				$(this).find('a:not(.js-viewForm, .js-add2basket, .js-compare, .js-add2Compare, .js-add2favorite)').addClass('js-ecom_product-link');	
				_arr.push($(this).data('id'));
			});

			var data = data+'&items='+_arr;			
			
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "/_ecommerce/ecommerce.php",
				data: data,
				success: function(returns) {	
										
					if(returns.SUCCESS == 'Y') {	
						
						var product = returns.DATA;		

						window.dataLayer = window.dataLayer || [];
						dataLayer.push({												
							'ecommerce': {
								'detail': {
									'actionField': {'list': _LIST},
									'products': [{
										'id': product.id,
										'name': product.name,
										'price': product.price,
										'category': product.category,
										'variant': product.variant,
										'brand': product.brand,
										'position': 0
									}]
								},
								'impressions': returns.DATA2,		
							},	
							'event': 'gtm-ee-event',
							'gtm-ee-event-category': 'Enhanced Ecommerce',
							'gtm-ee-event-action': 'Product Details',
							'gtm-ee-event-non-interaction': true,
						});	
						
						
						if(_page_s == '2') {
							console.log('Data go detail');
							ECOM_GTM('product', product.id, product.price);
						}
						
						
						/*
						ga('ec:addProduct', {
							'id': product.id,
							'name': product.name,
							'price': product.price,
							'category': product.category,
						    'brand': product.brand,						  
						});

						ga('ec:setAction', 'detail');
						ga('send', 'pageview');
*/
						
										
					}										
				}			
			});	
	
	
}

/* ############################## detail end ################################## */
/* ############################## list click ################################## */



function ECOM_ProductClick() {
	
	
	$('body').on('click','.js-ecom_product-link', function() {
		
			var _this = $(this);							
			var _url = $(this).attr('href');							
			var _item = _this.parents('.js-ecom_product-item');							
			var _list = _item.parents('.js-ecom_product-list');							
			var _typelist = _item.parents('.js-ecom_product-list').data('list');							
			var _id = _item.data('id');		
			
								console.log('готовимся');	
								var data = 'action=GetDataProduct&list='+_typelist+'&ID='+_id;
								$.ajax({
									type: "POST",
									dataType: "json",
									url: "/_ecommerce/ecommerce.php",
									data: data,
									success: function(returns) {	
									
										if(returns.SUCCESS == 'Y') {
											
											
											
											var product = returns.DATA;		
											
											window.dataLayer = window.dataLayer || [];	
											dataLayer.push({
												'event': 'productClick',
												'ecommerce': {
													'click': {
														'actionField': {'list': _typelist},
														'products': [{
															'id': product.id,
															'name': product.name,
															'price': product.price,
															'category': product.category,
															'variant': product.variant,
															'brand': product.brand,
															'position': _item.index()
														}]
													}
												},
												 'event': 'gtm-ee-event',
												 'gtm-ee-event-category': 'Enhanced Ecommerce',
												 'gtm-ee-event-action': 'Product Clicks',
												 'gtm-ee-event-non-interaction': false,
											});		

											
											/*
											 ga('ec:addProduct', {
												'id': product.id,
												'name': product.name,
												'category': product.category,
												'brand': product.brand,												
												'position': _item.index()
											  });
											  ga('ec:setAction', 'click', {list: product.list});
											
											  // Send click with an event, then send user to product page.
											  ga('send', 'event', 'UX', 'click', 'Results', {
												hitCallback: function() {
												  document.location = _this.attr('href');
												}
											  });	
											*/									
											console.log('отправили данные');
											
											location.href = _url;
											
										}										
									}			
								});	
								
		
		return false;
	});						
								
								
	
}

/* ############################### list click end ################################# */
/* ############################### add2basket ################################# */


function ECOM_AddToBasket(_id, _quantity) {
	
								var data = 'action=GetDataProduct&ID='+_id;	
								//console.log(data);								
								$.ajax({
									type: "POST",
									dataType: "json",
									url: "/_ecommerce/ecommerce.php",
									data: data,
									success: function(returns) {
										
										if(returns.SUCCESS == 'Y') {
											
											var product = returns.DATA;												
										
												window.dataLayer = window.dataLayer || [];
												dataLayer.push({
													'event': 'addToCart',
													'ecommerce': {
														'currencyCode': 'RUB',
														'add': {
															'products': [{
																'id': product.id,
																'name': product.name,
																'price': product.price,
																'category': product.category,
																'variant': product.variant,
																'brand': product.brand,
																'quantity': _quantity
															}]
														}
													},
													'event': 'gtm-ee-event',
													'gtm-ee-event-category': 'Enhanced Ecommerce',
													'gtm-ee-event-action': 'Add-2-Cart',
													'gtm-ee-event-non-interaction': false,
												});

										
											/*
												  ga('ec:addProduct', {
													'id': product.id,
													'name': product.name,
													'category': product.category,
													'brand': product.brand,
													//'variant': product.variant,
													'price': product.price,
													'quantity': _quantity
												  });
												  ga('ec:setAction', 'add');
												  ga('send', 'event', 'UX', 'click', 'add to cart');
											*/
											
												
										}										
									}			
								});	
	
}


/* ############################### add2basket end ################################# */
/* ############################### delete2basket ################################# */


function ECOM_DeleteToBasket(_id) {
	
								var data = 'action=GetDataProduct&ID='+_id;	
								//console.log(data);								
								$.ajax({
									type: "POST",
									dataType: "json",
									url: "/_ecommerce/ecommerce.php",
									data: data,
									success: function(returns) {
										
										if(returns.SUCCESS == 'Y') {
											
											var product = returns.DATA;												
											
												window.dataLayer = window.dataLayer || [];
												dataLayer.push({
													'event': 'removeFromCart',
													'ecommerce': {
														'remove': {
															'products': [{
																'id': product.id,
																'name': product.name,
																'price': product.price,
																'category': product.category,
																'variant': product.variant,
																'brand': product.brand,
																'quantity': product.quantity
															}]
														}
													},
													'event': 'gtm-ee-event',
													'gtm-ee-event-category': 'Enhanced Ecommerce',
													'gtm-ee-event-action': 'Removing a Product from a Shopping Cart',
													'gtm-ee-event-non-interaction': false,
												});	
											
											/*
												ga('ec:addProduct', {
													'id': product.id,
													'name': product.name,
													'category': product.category,
													'brand': product.brand,
													//'variant': product.variant,
													'price': product.price,													
												  });
												  ga('ec:setAction', 'remove');
												  ga('send', 'event', 'UX', 'click', 'remove to cart');
											*/
											
												
										}										
									}			
								});	
	
}


/* ############################### delete2basket end ################################# */
/* ############################### InitBasket ################################# */


function ECOM_InitBasket(_step, _prodid) {	

			console.log('готовимся cart view');	
			
			if(!_page_s) _page_s = '3';
			var data = 'action=GetDataBasket';	
			if(_prodid) data = data+'&prod_id='+_prodid;
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "/_ecommerce/ecommerce.php",
				data: data,
				success: function(returns) {	
										
					if(returns.SUCCESS == 'Y') {
					
						if(_step == '1') _opinion = 'Cart';
						if(_step == '2') _opinion = '1_click_purchase';
					
					
						window.dataLayer = window.dataLayer || [];
						dataLayer.push({
							'event': 'checkout',
							'ecommerce': {
							'checkout': {
									'actionField': {'step': _step,'opinion': _opinion},
									'products': returns.DATA
								}
							},
							'event': 'gtm-ee-event',
							'gtm-ee-event-category': 'Enhanced Ecommerce',
							'gtm-ee-event-action': 'Checkout Step 1',
							'gtm-ee-event-non-interaction': false,
						});		
						
						if(_page_s == '3') {
							console.log('Data go basket init');
							ECOM_GTM('cart',returns.DATA_2.prodids, returns.DATA_2.totalvalue );
						}
						
					
					/*
						var _data = returns.DATA;
						console.log('отправляем');
						_data.forEach(function(product, index) {
							
							//var product = $(this);
							console.log(product);	
							
							ga('ec:addProduct', {
								'id': product.id,
								'name': product.name,
								'category': product.category,
								'brand': product.brand,
								//'variant': product.variant,
								'price': product.price,					
								'quantity': product.quantity,					
							});							
							
						});
						
						ga('ec:setAction','checkout', {
							'step': _step,
							'option': _opinion
						});
						 ga('send', 'viewCart');
					*/
					
					
						
					
						
							
					}										
				}			
			});					
	
}


/* ############################### InitBasket end ################################# */
/* ############################### OrderReady ################################# */


function ECOM_OrderReady(order_id) {	
			//console.log('готовимся cart complete');	
			if(!_page_s) _page_s = '4';
			var data = 'action=GetDataBasket&order_id='+order_id;	
			
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "/_ecommerce/ecommerce.php",
				data: data,
				success: function(returns) {	
										
					if(returns.SUCCESS == 'Y') {
					
					
						var _data = returns.DATA;
						window.dataLayer = window.dataLayer || [];
						
						/*
						dataLayer.push({
						   'transactionId': order_id, 						
						   'transactionAffiliation': 'Online Store',		
						   'transactionTotal': returns.SUM,						
						   'transactionTax': 0,							
						   'transactionShipping': 0,						
						   'transactionProducts': returns.DATA
						});
						*/
						
						dataLayer.push({
							'event': 'purchase',
							'ecommerce': {
							'purchase': {
									'actionField': {'id': order_id, 'affiliation': 'Online Store', 'revenue': returns.SUM, 'shipping': 0},
									'products': returns.DATA
								}
							},
							'event': 'gtm-ee-event',
							'gtm-ee-event-category': 'Enhanced Ecommerce',
							'gtm-ee-event-action': 'Purchase',
							'gtm-ee-event-non-interaction': false,
						});	
						
						
						
						if(_page_s == '4') {
							console.log('Data go basket complete');
							ECOM_GTM('purchase',returns.DATA_2.prodids, returns.DATA_2.totalvalue );
						}
					
					/*
						_data.forEach(function(product, index) {
							
							//var product = $(this);
							//console.log(product);	
							
							ga('ec:addProduct', {
								'id': product.id,
								'name': product.name,
								'category': product.category,
								'brand': product.brand,
								//'variant': product.variant,
								'price': product.price,					
								'quantity': product.quantity,					
							});							
							
						});
					
					
					ga('ec:setAction', 'purchase', {
					  'id': order_id,
					  'affiliation': 'Online Store',
					  'revenue': returns.SUM,
					  'tax': 0,
					  'shipping': 0,
					  //'coupon': 'SUMMER2013'    // User added a coupon at checkout.
					});

					ga('send', 'pageview');
					*/
					
					





						
							
					}										
				}			
			});					
	
}


/* ############################### OrderReady end ################################# */
/* ################################################################ */

function ECOM_GTM(Pagetype, prodids, totalvalue) {	

		
						window.dataLayer = window.dataLayer || [];
						console.log('Pagetype '+Pagetype);
						console.log('prodids '+prodids);	
						console.log('totalvalue '+totalvalue);	
						
						dataLayer.push({'prodids': prodids});
						dataLayer.push({'Pagetype': Pagetype});
						dataLayer.push({'totalvalue': totalvalue});
}




/* ################################################################ */



$(document).ready(function(){
	
	//ga('require', 'ec');
	
	
	if($('.js-ecom_basket-stepone').length) {	
		
		ECOM_InitBasket('1', 0);
	}
	
	if($('.js-ecom_order-ready').length) {		
		ECOM_OrderReady($('.js-ecom_order-ready').data('id'));	
	}
	
	
	if($('.js-ecom_product-detail').length) {
		ECOM_ProductDetail();
	}
	
	if($('.js-ecom_product-list').length) {			
		
		if(!$('.js-ecom_product-detail').length) {
			ECOM_ProductList();	
		}		
		ECOM_ProductClick();
	}
	
	
	
	
	if(!_page_s) {
		ECOM_GTM('other', '' ,0);
		// ga('send', 'pageview');
	}
	

});