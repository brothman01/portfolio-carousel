jQuery(function() {

	var defaultCss = {
		width: 240,
		height: 150,
		marginTop: 50,
		marginRight: 25,
		marginLeft: 25,
		opacity: 0.5,
		marginLeft: -50
	};
	var selectedCss = {
		width: 400,
		height: 260,
		marginTop: 0,
		marginRight: -25,
		marginLeft: -25,
		opacity: 1
	};
	var aniOpts = {
		queue: false,
		duration: 1000,
		easing: 'elastic'
	};
	var $car = jQuery('#carousel');
	for ( var a = 0; a < 3; a++ ) {
		$car.prepend( '<div />' );
	}
	for ( var a = 0; a < 3; a++ ) {
		$car.append( '<div />' );
	}
	$car.find('img').css('zIndex', 1).css( defaultCss );
	$car.find('img').eq(0).css('zIndex', 2).css( selectedCss );
	$car.carouFredSel({
		circular: false,
		infinite: false,
		width: '100%',
		height: 250,
		items: 7,
		prev: '#prev',
		next: '#next',
		auto: false,
		scroll: {
			items: 1,
			duration: 500,
			easing: 'elastic',
			onBefore: function( data ) {
				data.items.old.eq(3).find('img').css('zIndex', 1).animate( defaultCss, aniOpts );
				data.items.visible.eq(3).find('img').css('zIndex', 2).animate( selectedCss, aniOpts );
			}
		}
	});
});
