<?php

/**
 * Functions
 * 
 * @author WPPW
 * @link http://wppw.ru
 * **************************** */
defined( 'ABSPATH' ) or exit;

acf_register_form( array(
	'id'				 => 'wppw_cpt_rent',
	'post_id'			 => 'new_post',
	'new_post'			 => array(
		'post_type'		 => 'wppw_cpt_rent',
		'post_status'	 => 'pending',
	),
	'post_title'		 => true,
	'post_content'		 => true,
	'submit_value'		 => __( "Отправить" ),
	'updated_message'	 => __( "Пост отправлен" ),
) );

/*
 * Scripts & styles
 */
add_action( 'wp_enqueue_scripts', function () {

	$src = get_stylesheet_directory_uri();

	// Только на страницах фотогалереи
	if ( is_singular( 'wppw_cpt_rent' ) ) {
		// http://miromannino.github.io/Justified-Gallery/
		wp_enqueue_style( 'justifiedgallery', $src . '/lib/jg/css/justifiedGallery.min.css' );
		wp_enqueue_script( 'justifiedgallery', $src . '/lib/jg/js/jquery.justifiedGallery.min.js', [ 'jquery' ], NULL, TRUE );

		// https://github.com/sachinchoolur/lightGallery
		wp_enqueue_style( 'lightgallery', 'https://cdn.jsdelivr.net/npm/lightgallery@1.6.11/dist/css/lightgallery.min.css' );
		wp_enqueue_script( 'lightgallery', 'https://cdn.jsdelivr.net/combine/npm/lightgallery,npm/lg-autoplay,npm/lg-fullscreen,npm/lg-hash,npm/lg-pager,npm/lg-share,npm/lg-thumbnail,npm/lg-video,npm/lg-zoom', [ 'jquery' ], NULL, TRUE );
	}

	wp_enqueue_script( 'js', $src . '/js.min.js', [ 'jquery' ] );
} );
