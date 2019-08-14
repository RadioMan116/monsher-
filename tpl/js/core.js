


function CompareChange(_this) 
{	
	var ID = _this.data('id');
	
	
		if ( !_this.hasClass('active')) {
			$.ajax({type: 'POST', dataType: "json", url: "/ajax/back.php", data: { id: ID, action: "ADD_TO_COMPARE_LIST", }, success:function(result){ 
						
						//console.log(result);						
						//console.log('Стало '+result.COUNT);						
						$('.js-GoodsInCompare').data('count', result.COUNT).html(result.COUNT);	
						//$('.js-GoodsInCompare_mtext').html(result.COUNT);	
						//$('.js-GoodsInCompare_text').html(result.COUNT_TEXT);	
						
						_this.addClass('active');
						//if(_this.children().length) _this.children().html('В сравнении');
						//else _this.html('В сравнении');
						CompareInit2(ID);
						//CompareInit();
						
						
			}}); //добавить товар к сравнению
		} 
		else {
			$.ajax({type: 'POST', dataType: "json", url: "/ajax/back.php", data: { id: ID, action: "DELETE_FROM_COMPARE_LIST", }, success:function(result){				
				//console.log(result);		
				//console.log('Стало '+result.COUNT);
				$('.js-GoodsInCompare').data('count', result.COUNT).html(result.COUNT);
				//$('.js-GoodsInCompare_mtext').html(result.COUNT);	
				//$('.js-GoodsInCompare_text').html(result.COUNT_TEXT);	
				//$('.js-GoodsInCompare').html(result.COUNT_TEXT);


				//CompareInit();
				_this.removeClass('active');
				
				if(!$('.js-compare_page').length) {	
					//if(_this.children().length) _this.children().html('Сравнить');
					//else _this.html('Сравнить');
				}
				
				if($('.js-compare_page').length) {	
					location.reload();	
				}
				
			}}); //удаление товара из сравнения			
		}
		
}



	
function declension(num, expressions) {

    var result;
    count = num % 100;

    if (count >= 5 && count <= 20) {
        result = expressions['2'];
    } else {
        count = count % 10;
        if (count == 1) {
            result = expressions['0'];
        } else if (count >= 2 && count <= 4) {
            result = expressions['1'];
        } else {
            result = expressions['2'];
        }
    }
    return result;
}





// _____________________________________________________________________________
// Преобразует число в строку формата 1_separator000_separator000._decimal
function sdf_FTS(_number,_decimal,_separator)
// сокращение переводится как Float To String
// sd_ - понятно и так почему :)
// _number - число любое, целое или дробное не важно
// _decimal - число знаков после запятой
// _separator - разделитель разрядов
{
// определяем, количество знаков после точки, по умолчанию выставляется 2 знака
var decimal=(typeof(_decimal)!='undefined')?_decimal:2;

// определяем, какой будет сепаратор [он же разделитель] между разрядами
var separator=(typeof(_separator)!='undefined')?_separator:'';

// преобразовываем входящий параметр к дробному числу, на всяк случай, если вдруг
// входящий параметр будет не корректным
var r=parseFloat(_number)

// так как в JavaScript нет функции для фиксации дробной части после точки
// то выполняем своеобразный fix
var exp10=Math.pow(10,decimal);// приводим к правильному множителю
r=Math.round(r*exp10)/exp10;// округляем до необходимого числа знаков после запятой

// преобразуем к строгому, фиксированному формату, так как в случае вывода целого числа
// нули отбрасываются не корректно, то есть целое число должно
// отображаться 1.00, а не 1
rr=Number(r).toFixed(decimal).toString().split('.');

// разделяем разряды в больших числах, если это необходимо
// то есть, 1000 превращаем 1 000
b=rr[0].replace(/(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g,"\$1"+separator);

// можно использовать r=b+'.'+rr[1], но при 0 после запятой выходит ошибка undefined,
// поэтому применяем костыль
r=(rr[1]?b+'.'+rr[1]:b);

return r;// возвращаем результат
}

function isValidEmailAddress(emailAddress){
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
}
function isInt(n) {
   return n % 1 === 0;
}

function ClearPols(nForm) {
	nForm.find('input[type="text"],input[type="tel"],input[type="email"],input[type="password"], textarea').each(function () {
		$(this).val('');	
	});
}



function CheckPols(nForm) {

	var $error = 0;
	nForm.find('input, select, textarea').each(function () {
	
       var _this = $(this);
	   var _val = $.trim($(this).val());
	   
	   
			
			if(_val!='' ||  _this.hasClass('noCheck')) {
				 
				if(_this.hasClass('js-select_combox')) {
					_this.parents('.js-combox').find('.combox__title').removeClass('BadPols');
				}
				else {
					_this.removeClass('BadPols');					
				}				
			}
			else {
				
				if(_this.hasClass('js-select_combox')) {
					_this.parents('.js-combox').find('.combox__title').addClass('BadPols');
				}
				else {
					_this.addClass('BadPols');
				}
				
				//console.log(_this.attr('name'));
				
				$error = '1';
				
				if(_this.data('other') && _this.data('other')!='')
				{
					if(_this.parents('form').find('input[name="'+_this.data('other')+'"]').val()!='') 
					{
						_this.removeClass('BadPols');						
						$error = 0;
					}
				}	
			}
			
			
			
			if(_this.hasClass('typeEmail') && _val!='')
			{
				if(isValidEmailAddress(_val)) {_this.removeClass('BadPols');}
				else {$error = '1'; _this.addClass('BadPols'); }
			}	

			if(_this.data('check') && _this.data('check')!='')
			{										
					if(_this.parents('form').find('input.'+_this.data('check')).val()!=_this.val()) 
					{
						_this.addClass('BadPols');
						_this.parents('form').find('input.'+_this.data('check')).addClass('BadPols');
						$error = 1;
					}					
			}	
    });
	
	return $error;
}



function CompareInit2(_id) {
	var str2 = 'action=ADD2COMPARE_AFTER&TOV_ID='+_id;
											$.post('/ajax/front.php', str2, function (returns2) {												
												
												globalPopup.options({
													closeButtons: '.js-close'
												}).html(returns2).show();
												
											});	
}
function CompareInit() {
	
	

	var _count = parseInt($('.js-GoodsInCompare').data('count'));
	
	 
	if(_count > 0) {
		
		console.log('больше 0');
		
		if($('.js-GoodsInCompare').css('display')=='none') {
			console.log('показываем');
			$('.js-GoodsInCompare').fadeIn();
		}		
			
	}
	else {
		console.log('меньше 0');		
		
		if($('.js-GoodsInCompare').css('display')!='none') {
			
			
			console.log('скрываем');
			$('.js-GoodsInCompare').fadeOut();
		}		
	}	
	
}

	
	
	
 function setHeight_My(parent, children, children__name) {

        var parent = $(parent);
        var fn = function() {

            $(children, children__name).css({
                height: ''
            })

            $(parent).each(function () {

                var attr = (this.getAttribute('data-count')).split(',');
                var count = window.innerWidth < 1260 ? parseInt(attr[0]) : parseInt(attr[1]);

                $(this).setEqualHeight({itemsSel: children__name, itemsInLineCount: count});
                $(this).setEqualHeight({itemsSel: children, itemsInLineCount: count});

            });
        };

        fn();

        window.addEventListener('resize', fn);

    }


// detail-video
	(function() {

		var texts = $('.js-detail-video__text');
		var links = $('.js-detail-video__link');
		var buttons = $('#js-detail-video__buttons a');
		var count = 0;

		$(buttons).click(function(e) {

			e.preventDefault();

			var direction = this.getAttribute('data-direction');

			if(direction == 'prev') {

				count = count - 1;
				if(count < 0) {
					count = 0;
				}
			}
			else {

				count = count + 1;
				if(count >= texts.length - 1) {
					count = texts.length - 1;
				}
			}

			$(texts).hide();
			$(links).hide();
			$(texts[count]).show();
			$(links[count]).show();

		});

	})();
