jQuery( document ).ready( function ( $ ) {

	( function () {

		var $gallery = $( '#lightgallery' );
		if ( $gallery.length ) {
			$gallery.justifiedGallery( {
				rowHeight : 150,
				lastRow : 'justify',
			} ).on( 'jg.complete', function () {
				$gallery.lightGallery( {
					thumbnail : true,
				} );
				$gallery.on( 'onSlideClick.lg', function ( event, index, fromTouch, fromThumb ) {
					$gallery.data( 'lightGallery' ).goToNextSlide();
				} );
			} );
		}

	} )();
} );