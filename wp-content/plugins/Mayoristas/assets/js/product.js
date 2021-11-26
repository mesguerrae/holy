(function($) {
	$(function() {
		selected_price = 0;
		$(".wholesales-price-type").change(function(){
			selected_price = $(this).val();

			var number = $('input[name="quantity"]').val();
	  		var unitPrice = $('.product-page-price').find('.woocommerce-Price-amount').html().replace(/(<([^>]+)>)/ig,"").replace('.','').replace('$','');
			console.log('precio'+unitPrice);
			var result = unitPrice - selected_price;
			var saving =  number*result;
			$('#utility').html('$'+saving.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
		});
		//$('#utility').html('$0');
		$('input[name="quantity"]').change(function () {
	  		/*var number = $(this).val();
	  		var unitPrice = $('.product-page-price').find('.woocommerce-Price-amount').html().replace(/(<([^>]+)>)/ig,"").replace('.','').replace('$','');
	  		console.log('precio'+unitPrice);
	  		$('#wholesales-discounts > tbody > tr').each(function() {
	  			var range = $(this).find('.discount').html();
	  			var price = $(this).find('.discount-price').html();
	  			if (typeof range != 'undefined') {
	  				arrayRangrange = range.split(' - ');
	  				if (parseInt(number) >= parseInt(arrayRangrange[0]) && parseInt(number) <= parseInt(arrayRangrange[1])) {
	  					var realPrice = price.replace('x Unidad','').replace(',','').replace('$','');
	  					var diff = parseInt(unitPrice)-parseInt(realPrice);
	  					var result = parseInt(diff)*parseInt(number);
	  					$('#utility').html('$'+result.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
	  					return false;
	  				}else{
	  					$('#utility').html('$0');
	  					//return false;
	  				}
	  			}
			  });*/

			var number = $(this).val();
	  		var unitPrice = $('.product-page-price').find('.woocommerce-Price-amount').html().replace(/(<([^>]+)>)/ig,"").replace('.','').replace('$','');
			console.log('precio'+unitPrice);
			var result = unitPrice - selected_price;
			var saving =  number*result;
			$('#utility').html('$'+saving.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));

		});

	});

	$('.wholesales-price-type').change(function() {
	    $('#product_discount').val($(this).val());
	});
})(jQuery);