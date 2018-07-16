jQuery( document ).ready( function ( $ ) {

	// Добавление фото =========================================================================================================================
	( function () {
		$( '#add_photo' ).on( 'click', function () {
			var
					$this = $( this ),
					$target = $this.siblings( '.input-group:last' );

			// Дублируем
			$target.clone().removeAttr( 'style' ).insertBefore( $this ).find( ':input' ).val( '' );

			return false;
		} );
	} )();

	// Отменить  фото =========================================================================================================================
	( function () {

		$( '#rent_form_post' ).on( 'click', '.cross', function () {
			var $this = $( this );

			$this.closest( '.input-group' ).remove();

			return false;
		} );
	} )();


	// Валидатор формы добавления недвижимости =========================================================================================================================
	window.addEventListener( 'load', function () {
		var form = document.getElementById( 'rent_form_post' );
		form.addEventListener( 'submit', function ( event ) {
			if ( form.checkValidity() === false ) {
				event.preventDefault();
				event.stopPropagation();
			}
			form.classList.add( 'was-validated' );
		}, false );
	}, false );

	// Ajax rent_form_post =========================================================================================================================
	$( '#rent_form_post' ).on( 'submit', rent_form_post );
	function rent_form_post(  ) {

		// Detect invalid forms
		if ( false === this.checkValidity() )
			return false;

		var
				$form = $( '#rent_form_post' ),
				fd = new FormData(),
				$photos = $( 'input.gallery', $form );

		fd.append( "action", 'rent_form_post' );
		fd.append( "form", $form.serialize() );

		// Собираем файлы
		function files( name, target ) {
			target.each( function ( i, e ) {
				var
						$this = $( this ),
						file = $this[0].files[0];

				if ( 'undefined' != typeof file )
					fd.append( name + i, file );
			} );
		}

		// Фото
		files( 'photos', $photos );

		$.ajax( {
			type : 'POST',
			url : rent_form_post_vars.ajaxurl,
			data : fd,
			contentType : false,
			processData : false,
			success : function ( response ) {
				console.info( response );

				if ( response.success ) {
					$form.slideUp();

					swal( {
						title : "Отлично, Ваша заявка успешно добавлена!",
						text : "Мы проверим её и обязательно опубликуем позже!",
						type : "success",
					} );

					$form[0].reset();
				}
			},
		} );

		return false;
	}

	//  =========================================================================================================================
	( function () {


	} )();


} );