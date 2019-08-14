$(document).ready(function(){

	$(".js-tech_select").on('change', function() {

		console.log('++++++++++++++++++++++++');
		console.log($(this).val());
		
		$(this).parents('.js-form').submit();	
	});
});