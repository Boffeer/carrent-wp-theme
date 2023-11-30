<?php
if (!defined('ABSPATH')) {
	exit; // exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/*
	Page
*/
// Container::make('post_meta', 'page_info', 'Страница')
// 	->where('post_type', '!=', 'faq')
// 	->where('post_type', '!=', 'case')
// 	->add_fields(array(
// 		Field::make('text', 'page_title', 'Заголовок для соцсетей')
// 			->set_width(30),
// 		Field::make('textarea', 'page_description', 'Описание для соцсетей')
// 			->set_width(30),
// 		Field::make('image', 'og_image', 'Картинка для соцсетей — og:image')
// 			->set_width(30)
// 			->set_value_type('url'),
// 	));


/*
	Homepage
 */
$homepage_id  = get_option('page_on_front');
Container::make('post_meta', 'homepage_info', 'Настройки страницы')
//	->where('post_id', '=', $homepage_id)
->where('post_type', '=', 'page')
->add_fields(array(
//    Field::make('textarea', 'page_description', 'Описание для соцсетей'),

    Field::make( 'radio', 'header_overlap', __( 'Будет ли заезжать шапка на первый экран?' ) )
        ->set_width(50)
        ->set_options( array(
            'false' => 'Нет',
            'true' => 'Шапка заезжает на первый экран',
        ) ),
));
