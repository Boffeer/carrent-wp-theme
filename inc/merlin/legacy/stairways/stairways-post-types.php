<?php
add_action( 'init', 'register_post_types' );

function register_post_types(){
	register_post_type( 'stairs', [
			'label'  => null,
			'labels' => [
			'name'               => 'Лестницы', // основное название для типа записи
			'singular_name'      => 'Лестница', // название для одной записи этого типа
			'add_new'            => 'Добавить лестницу', // для добавления новой записи
			'add_new_item'       => 'Добавление лестницы', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование лестницы', // для редактирования типа записи
			'new_item'           => 'Новая лестница', // текст новой записи
			'view_item'          => 'Смотреть лестницу', // для просмотра записи этого типа.
			'search_items'       => 'Искать лестницу', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Лестницы', // название меню
			],
			'items_list_navigation'    => 'true',
			'description'         => '',
			'public'              => true,
			// 'publicly_queryable'  => null, // зависит от public
			// 'exclude_from_search' => null, // зависит от public
			// 'show_ui'             => null, // зависит от public
			// 'show_in_nav_menus'   => null, // зависит от public
			'show_in_menu'        => true, // показывать ли в меню адмнки
			// 'show_in_admin_bar'   => null, // зависит от show_in_menu
			'show_in_rest'        => null, // добавить в REST API. C WP 4.7
			'rest_base'           => null, // $post_type. C WP 4.7
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-editor-aligncenter',
			//'capability_type'   => 'post',
			//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
			//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
			'hierarchical'        => true,
			'supports'            => [ 'title' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
			'taxonomies'          => ['categories'],
			'has_archive'         => true,
			'rewrite'             => true,
			'query_var'           => true,
		] );

	register_taxonomy( 'categories', ['stairs'], array(
			'label'                 => '',
			'labels'                => array(
					'name'              => 'Категории',
					'singular_name'     => 'Категория',
					'search_items'      => 'Поиск категории',
					'all_items'         => 'Все категории',
					'view_item '        => 'Просмотр категории',
					'parent_item'       => 'Родительская категория',
					'parent_item_colon' => 'Родительская категория:',
					'edit_item'         => 'Изменить категория',
					'update_item'       => 'Обновить категорию',
					'add_new_item'      => 'Добавить новую категорию',
					'new_item_name'     => 'Название новой категории',
					'menu_name'         => 'Категории',
			),
			'description'           => '',
			'public'                => true,
			'has_archive'           => true,
			'hierarchical'          => true,
			'rewrite'               => true,
			'query_var'             => 'categories',
			'capabilities'          => array(),
			'meta_box_cb'           => 'post_categories_meta_box',
			'show_in_nav_menus'     => true,
			'show_admin_column'     => true,
			'show_in_rest'          => true,
			'rest_base'             => 'categories',
	) );


	register_post_type( 'cases', [
			'label'  => null,
			'labels' => [
			'name'               => 'Кейсы', // основное название для типа записи
			'singular_name'      => 'Кейс', // название для одной записи этого типа
			'add_new'            => 'Добавить кейс', // для добавления новой записи
			'add_new_item'       => 'Добавление кейса', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование кейса', // для редактирования типа записи
			'new_item'           => 'Новый кейс', // текст новой записи
			'view_item'          => 'Смотреть кейс', // для просмотра записи этого типа.
			'search_items'       => 'Искать кейс', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Кейсы', // название меню
			],
			'items_list_navigation'    => 'true',
			'description'         => '',
			'public'              => true,
			// 'publicly_queryable'  => null, // зависит от public
			// 'exclude_from_search' => null, // зависит от public
			// 'show_ui'             => null, // зависит от public
			// 'show_in_nav_menus'   => null, // зависит от public
			'show_in_menu'        => true, // показывать ли в меню адмнки
			// 'show_in_admin_bar'   => null, // зависит от show_in_menu
			'show_in_rest'        => null, // добавить в REST API. C WP 4.7
			'rest_base'           => null, // $post_type. C WP 4.7
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-images-alt2',
			//'capability_type'   => 'post',
			//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
			//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
			'hierarchical'        => true,
			'supports'            => [ 'title' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
			'taxonomies'          => ['categories'],
			'has_archive'         => true,
			'rewrite'             => true,
			'query_var'           => true,
		] );

	register_post_type( 'reviews', [
			'label'  => null,
			'labels' => [
			'name'               => 'Отзывы', // основное название для типа записи
			'singular_name'      => 'Отзыв', // название для одной записи этого типа
			'add_new'            => 'Добавить отзыв', // для добавления новой записи
			'add_new_item'       => 'Добавление отзыва', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование отзыва', // для редактирования типа записи
			'new_item'           => 'Отзыв кейс', // текст новой записи
			'view_item'          => 'Смотреть отзыв', // для просмотра записи этого типа.
			'search_items'       => 'Искать отзыв', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Отзывы', // название меню
			],
			'items_list_navigation'    => 'true',
			'description'         => '',
			'public'              => true,
			// 'publicly_queryable'  => null, // зависит от public
			// 'exclude_from_search' => null, // зависит от public
			// 'show_ui'             => null, // зависит от public
			// 'show_in_nav_menus'   => null, // зависит от public
			'show_in_menu'        => true, // показывать ли в меню адмнки
			// 'show_in_admin_bar'   => null, // зависит от show_in_menu
			'show_in_rest'        => null, // добавить в REST API. C WP 4.7
			'rest_base'           => null, // $post_type. C WP 4.7
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-format-status',
			//'capability_type'   => 'post',
			//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
			//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
			'hierarchical'        => true,
			'supports'            => [ 'title' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
			'has_archive'         => true,
			'rewrite'             => true,
			'query_var'           => true,
		] );

	register_post_type( 'news', [
			'label'  => null,
			'labels' => [
			'name'               => 'Новости', // основное название для типа записи
			'singular_name'      => 'Новость', // название для одной записи этого типа
			'add_new'            => 'Добавить новость', // для добавления новой записи
			'add_new_item'       => 'Добавление новости', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование новости', // для редактирования типа записи
			'new_item'           => 'Новая новость', // текст новой записи
			'view_item'          => 'Смотреть новость', // для просмотра записи этого типа.
			'search_items'       => 'Искать новость', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Новости', // название меню
			],
			'items_list_navigation'    => 'true',
			'description'         => '',
			'public'              => true,
			// 'publicly_queryable'  => null, // зависит от public
			// 'exclude_from_search' => null, // зависит от public
			// 'show_ui'             => null, // зависит от public
			// 'show_in_nav_menus'   => null, // зависит от public
			'show_in_menu'        => true, // показывать ли в меню адмнки
			// 'show_in_admin_bar'   => null, // зависит от show_in_menu
			// 'show_in_rest'        => null, // добавить в REST API. C WP 4.7
			'show_in_rest'       => true, // To use Gutenberg editor.
			'rest_base'           => null, // $post_type. C WP 4.7
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-admin-site',
			//'capability_type'   => 'post',
			//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
			//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
			'hierarchical'        => true,
			'supports'            => [ 'title', 'editor' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
			'has_archive'         => false,
			'rewrite'             => true,
			'query_var'           => true,
		] );

	register_post_type( 'videos', [
			'label'  => null,
			'labels' => [
			'name'               => 'Видео', // основное название для типа записи
			'singular_name'      => 'Видео', // название для одной записи этого типа
			'add_new'            => 'Добавить видео', // для добавления новой записи
			'add_new_item'       => 'Добавление видео', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование видео', // для редактирования типа записи
			'new_item'           => 'Новое видео', // текст новой записи
			'view_item'          => 'Смотреть видео', // для просмотра записи этого типа.
			'search_items'       => 'Искать видео', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Видео', // название меню
			],
			'items_list_navigation'    => 'true',
			'description'         => '',
			'public'              => true,
			// 'publicly_queryable'  => null, // зависит от public
			// 'exclude_from_search' => null, // зависит от public
			// 'show_ui'             => null, // зависит от public
			// 'show_in_nav_menus'   => null, // зависит от public
			'show_in_menu'        => true, // показывать ли в меню адмнки
			// 'show_in_admin_bar'   => null, // зависит от show_in_menu
			'show_in_rest'        => null, // добавить в REST API. C WP 4.7
			'rest_base'           => null, // $post_type. C WP 4.7
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-video-alt3',
			//'capability_type'   => 'post',
			//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
			//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
			'hierarchical'        => true,
			'supports'            => [ 'title' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
			'has_archive'         => false,
			'rewrite'             => true,
			'query_var'           => true,
		] );
		register_taxonomy( 'videos-categories', ['videos'], array(
				'label'                 => '',
				'labels'                => array(
						'name'              => 'Категории видео',
						'singular_name'     => 'Категория видео',
						'search_items'      => 'Поиск категории видео',
						'all_items'         => 'Все категории видео',
						'view_item '        => 'Просмотр категории видео',
						'parent_item'       => 'Родительская категория видео',
						'parent_item_colon' => 'Родительская категория видео:',
						'edit_item'         => 'Изменить категорию видео',
						'update_item'       => 'Обновить категорию видео',
						'add_new_item'      => 'Добавить новую категорию видео',
						'new_item_name'     => 'Название новой категории видео',
						'menu_name'         => 'Категории видео',
				),
				'description'           => '',
				'public'                => true,
				'has_archive'           => true,
				'hierarchical'          => true,
				'rewrite'               => true,
				'query_var'             => 'categories',
				'capabilities'          => array(),
				'meta_box_cb'           => 'post_categories_meta_box',
				'taxonomies'          => ['videos-categories'],
				'show_in_nav_menus'     => true,
				'show_admin_column'     => true,
				'show_in_rest'          => true,
				'rest_base'             => 'categories',
		) );

	register_post_type( 'faq', [
			'label'  => null,
			'labels' => [
			'name'               => 'ЧаВо', // основное название для типа записи
			'singular_name'      => 'ЧаВо', // название для одной записи этого типа
			'add_new'            => 'Добавить ЧаВо', // для добавления новой записи
			'add_new_item'       => 'Добавление ЧаВо', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование ЧаВо', // для редактирования типа записи
			'new_item'           => 'Новое ЧаВо', // текст новой записи
			'view_item'          => 'Смотреть ЧаВо', // для просмотра записи этого типа.
			'search_items'       => 'Искать ЧаВо', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'ЧаВо', // название меню
			],
			'items_list_navigation'    => 'true',
			'description'         => '',
			'public'              => true,
			// 'publicly_queryable'  => null, // зависит от public
			// 'exclude_from_search' => null, // зависит от public
			// 'show_ui'             => null, // зависит от public
			// 'show_in_nav_menus'   => null, // зависит от public
			'show_in_menu'        => true, // показывать ли в меню адмнки
			// 'show_in_admin_bar'   => null, // зависит от show_in_menu
			'show_in_rest'        => null, // добавить в REST API. C WP 4.7
			'rest_base'           => null, // $post_type. C WP 4.7
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-sos',
			//'capability_type'   => 'post',
			//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
			//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
			'hierarchical'        => true,
			'supports'            => [ 'title' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
			'has_archive'         => true,
			'rewrite'             => true,
			'query_var'           => true,
		] );
}

