<?php

/**
 * Functions
 * 
 * @author WPPW
 * @link http://wppw.ru
 * **************************** */
defined( 'ABSPATH' ) or exit;

define( 'WPPW_NONCE', 'nonce_dsjfpmn3in4rfoivp243j5232p3nfg' );

/*
 * Scripts & styles
 */
add_action( 'wp_enqueue_scripts', function () {

	$src = get_stylesheet_directory_uri();

	// CPT Rent
	if ( is_singular( 'wppw_cpt_rent' ) ) {

		// http://miromannino.github.io/Justified-Gallery/
		wp_enqueue_style( 'justifiedgallery', $src . '/lib/jg/css/justifiedGallery.min.css' );
		wp_enqueue_script( 'justifiedgallery', $src . '/lib/jg/js/jquery.justifiedGallery.min.js', [ 'jquery' ], NULL, TRUE );

		// https://github.com/sachinchoolur/lightGallery
		wp_enqueue_style( 'lightgallery', 'https://cdn.jsdelivr.net/npm/lightgallery@1.6.11/dist/css/lightgallery.min.css' );
		wp_enqueue_script( 'lightgallery', 'https://cdn.jsdelivr.net/combine/npm/lightgallery,npm/lg-autoplay,npm/lg-fullscreen,npm/lg-hash,npm/lg-pager,npm/lg-share,npm/lg-thumbnail,npm/lg-video,npm/lg-zoom', [ 'jquery' ], NULL, TRUE );

		wp_enqueue_script( 'gallery', $src . '/js/gallery.min.js', [ 'jquery' ] );
	}

	// Frontpage
	if ( is_front_page() ) {

		// https://sweetalert2.github.io/
		wp_enqueue_script( 'sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@7.25.3/dist/sweetalert2.all.min.js' );
		
		wp_enqueue_script( 'rent_form_post', $src . '/js/rent_form_post.js', [ 'jquery' ] );
		wp_localize_script( 'rent_form_post', 'rent_form_post_vars', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		] );
	}
} );

// Ajax backend for rent_form_post
add_action( 'wp_ajax_rent_form_post', 'rent_form_post__callback' );
add_action( 'wp_ajax_nopriv_rent_form_post', 'rent_form_post__callback' );

function rent_form_post__callback() {

	// Получаем данные формы
	$form = $_POST['form'] or wp_send_json_error( [ 'msg' => 'Нет данных в форме', ] );

	// Парсим данные
	$form = wp_parse_args( $form );

	// Nonce
	isset( $form['nonce'] ) && wp_verify_nonce( $form['nonce'], WPPW_NONCE ) or wp_send_json_error( [
				'msg' => 'Не прошли верификацию nonce',
			] );

	// Метаданные
	$meta = [];
	foreach ( $form['meta'] as $meta_key => $meta_value ) {
		$meta[$meta_key] = $meta_value;
	} unset( $meta_value );

	// Вставляем/редактируем пост
	$post_id = wp_insert_post( [
		'post_title'	 => wp_strip_all_tags( $form['post_title'] ),
		'post_content'	 => $form['post_content'],
		'post_type'		 => 'wppw_cpt_rent',
		'post_status'	 => 'pending',
		'tax_input'		 => [
			'wppw_cpt_rent_type' => [ $form['wppw_cpt_rent_type'] ],
		],
		'meta_input'	 => $meta,
			], $wp_error = true );


	// Если пост создался
	if ( !is_wp_error( $post_id ) and is_numeric( $post_id ) ) {

		/**
		 * Прикрепляем миниатюру
		 */
		$mimes = [
			'image/png',
			'image/jpeg',
			'image/jpg',
		];

		/**
		 * Прикрепляем галерею фото
		 */
		// Скриншоты
		$r = []; // Массив

		if ( !empty( $_FILES['photos0'] ) ) {

			foreach ( $_FILES as $name => $file ) {

				// Аттач изображения, если пройден mime
				if ( in_array( $file['type'], $mimes ) ) {

					// Аттач изображения
					$attachment_id = media_handle_upload( $name, $post_id );

					// Создание миниатюры записи, если ещё нет
					if ( !has_post_thumbnail( $post_id ) and ! is_wp_error( $attachment_id ) )
						set_post_thumbnail( $post_id, $attachment_id );

					// Пишем в массив
					if ( !is_wp_error( $attachment_id ) )
						$r[] = $attachment_id;
				}
			} unset( $file );
		}

		// Если массив со скриншотами не пуст, создаём галерею
		if ( !empty( $r ) )
			update_field( 'gallery', $r, $post_id );


		// Отправляем всё во фронтенд
		wp_send_json_success( [
			'msg'	 => 'Всё хорошо, пост отправлен',
			'result' => $post_id,
		] );
	} else {

		wp_send_json_error( [
			'msg'	 => 'Пост не создался',
			'result' => $post_id -> get_error_message(),
		] );
	}

	exit;
}
