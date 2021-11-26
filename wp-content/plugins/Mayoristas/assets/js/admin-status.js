(function($) {
	$( document ).ready(function() {
	    $( "#order_status option[value='wc-processing']" ).remove();
	    $( "#order_status option[value='wc-completed']" ).remove();
	});
	
})(jQuery);