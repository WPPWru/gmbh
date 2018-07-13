<?php

/**
 * Произвольный тип записи Недвижимость
 * 
 * @author WPPW
 * @link http://wppw.ru
 * **************************** */
defined( 'ABSPATH' ) or exit;

final class WPPW_CPT_Rent {

	// Ярлык произвольного типа записи по умолчанию
	private $post_type = 'Недвижимость';

	function __construct( $post_type = "" ) {

		// Переопределяем значение ярлыка по умолчанию
		if ( $post_type )
			$this -> post_type = $post_type;

		/*
		 * Регистрируем Custom Post Type
		 */
		add_action( 'init', array( $this, 'wppw_cpt_rent' ) );
	}

	function wppw_cpt_rent() {

		/*
		 * Регистрируем произвольную таксономию Города
		 */
		register_taxonomy(
				'wppw_cpt_rent_city', 'wppw_cpt_rent', array(
			'label'			 => 'Города',
			'hierarchical'	 => true,
			'rewrite'		 => array( 'slug' => 'city' ), // Тут определяется ярлык таксономии
				)
		);

		/*
		 * Регистрируем произвольную таксономию Типы недвижимости
		 */
		register_taxonomy
				( 'wppw_cpt_rent_type', 'wppw_cpt_rent', array(
			'label'			 => 'Тип недвижимости',
			'hierarchical'	 => true,
			'rewrite'		 => array( 'slug' => 'type' ), // Тут определяется ярлык таксономии
				)
		);

		/*
		 * Регистрируем новый тип записи
		 */
		register_post_type( 'wppw_cpt_rent', array(
			'labels'		 => array(
				'name'			 => 'Недвижимость', // Основное название
				'singular_name'	 => 'Недвижимость', // Добавить
				'add_new'		 => 'Добавить новую', // Имя ссылки на новую запись в сайдбаре 
				'add_new_item'	 => 'Добавить новую недвижимость', // Заголовок в редакторе при добавлении новой записи
			),
			'menu_position'	 => 5,
			'public'		 => true,
			'rewrite'		 => array( 'slug' => 'rent' ), // Тут определяется ярлык Custom Post Type
			'supports'		 => array( 'title', 'editor', 'thumbnail', ),
			'has_archive'	 => $this -> post_type,
		) );


		if ( current_user_can( 'manage_options' ) )
		// Вот с этой функцией осторожней. Она сбрасывает все правила определения URL. Лучше её закомментировать после завершения всех работ
			flush_rewrite_rules();
	}

}

/*
 * Запускаем класс
 * В скобках можно определить название ярлыка типа записи
 */
new WPPW_CPT_Rent();
