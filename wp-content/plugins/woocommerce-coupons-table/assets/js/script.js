jQuery(document).ready( function($) {
    //jQuery selector to point to 
    $('.order-product-items').click(function(e){
        e.preventDefault()
        let text = '<h3> Items </h3>' + $(this).attr('items');
        $(this).pointer({
            content: text,
            position: 'top',
            close: function() {
                // This function is fired when you click the close button
            }
        }).pointer('open');

    })
    
    
});