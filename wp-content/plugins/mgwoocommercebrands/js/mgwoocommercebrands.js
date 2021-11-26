(function($){
$(document).ready(function() {

	$(".mgt-woocommerce-brands-dropdown").change(function() {
		if ($(this).val()) {
			location.href = $(this).val();
		}
	});

});
})(jQuery);

