jQuery( document ).ready( function() {

	jQuery( 'body' ).on( 'mouseover', '.bp_portfolio_item_cell', function() {
		jQuery( this ).find( '.bp_portfolio_item_overlay' ).css( 'width', '100%' );
	});

	jQuery( 'body' ).on( 'mouseout', '.bp_portfolio_item_cell', function() {
		jQuery( this ).find( '.bp_portfolio_item_overlay' ).css( 'width', '0%' );
	});

	jQuery( 'body' ).on( 'click', '.bp_portfolio_item_selector_image', function() {

		jQuery( 'body' ).find( '.bp_portfolio_item_selected_image' ).removeClass( 'bp_portfolio_item_selected_image' );

		jQuery( this ).addClass( 'bp_portfolio_item_selected_image' );

		var new_image = jQuery( '.bp_portfolio_item_selected_image' ).attr('src');

		jQuery( '.bp_singlepage_portfolio_item_huge_image' ).css( 'background-image', 'url(' + new_image + ')');


	});


});
