<?php

if (!defined('ABSPATH')) {
	exit; // exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;


add_action( 'init', 'register_car_booking_post_types' );
function register_car_booking_post_types() {
	register_post_type( 'car_booking', [
		'label'  => null,
		'labels' => [
			'name'               => 'Bookings',
			'singular_name'      => 'Bookings',
			'add_new'            => 'Add booking',
			'add_new_item'       => 'Adding booking',
			'edit_item'          => 'Edit booking',
			'new_item'           => 'New booking',
			'view_item'          => 'View booking',
			'search_items'       => 'Search bookings',
			'not_found'          => 'Not found',
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Bookings',
		],
		'description'            => '',
		'public'                 => true,
		'show_in_menu'           => null, // показывать ли в меню админки
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-editor-table',
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor', 'thumbnail' ],
		'taxonomies'          => [],
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}

add_action('carbon_fields_register_fields', 'register_car_booking_fields');
function register_car_booking_fields() {
	Container::make('post_meta', 'car_booking_info', __('Booking'))
		->where('post_type', '=', 'car_booking')
        ->add_tab(__('Order'), array(
            Field::make('text', 'name', 'name'),
            Field::make('text', 'phone', 'phone'),
            Field::make('text', 'email', 'email'),
            Field::make('text', 'date_start', 'date_start')
                ->set_width(50),
            Field::make('text', 'date_end', 'date_end')
                ->set_width(50),
            Field::make('text', 'time_start', 'time_start')
                ->set_width(50),
            Field::make('text', 'time_end', 'time_end')
                ->set_width(50),
            Field::make('text', 'location_start', 'location_start')
                ->set_width(50),
            Field::make('text', 'location_end', 'location_end')
                ->set_width(50),
            Field::make('text', 'flight_number', 'flight_number'),
            Field::make('text', 'amount', 'amount (cents)'),
            Field::make('text', 'created', 'created'),
            Field::make('text', 'crm_booking_id', 'crm_booking_id'),
            Field::make('text', 'product_id', 'product_id'),
            Field::make('text', 'booking_id', 'booking_id'),
            Field::make('text', 'date_of_birth', 'date_of_birth'),
            Field::make('text', 'agree', 'agree'),
            Field::make('text', 'options', 'options'),
            Field::make('text', 'receipt_url', 'receipt_url'),
            Field::make('text', 'id', 'id'),
        ))
        ->add_tab(__('Stripe'), array(
            Field::make('text', 'payment_intent', 'payment_intent'),
            Field::make('text', 'payment_session_id', 'payment_session_id'),
            Field::make('textarea', 'json', 'json'),
        ))
		;
}


//// Ограничение доступа к записям типа "car_bookings" для всех кроме администраторов и редакторов
function restrict_car_bookings_access() {
    if (is_singular('car_booking')) {
        wp_redirect(home_url()); // Перенаправить на главную страницу
        exit;
    }
}
add_action('template_redirect', 'restrict_car_bookings_access');


function getBookingInfo($booking_id) {
    $booking = array(
        'booking_id' => carbon_get_post_meta($booking_id, 'booking_id'),
        'crm_booking_id' => carbon_get_post_meta($booking_id, 'crm_booking_id'),
        'name' => carbon_get_post_meta($booking_id, 'name'),
        'email' => carbon_get_post_meta($booking_id, 'email'),
        'phone' => carbon_get_post_meta($booking_id, 'phone'),
        'amount' => carbon_get_post_meta($booking_id, 'amount'),
        'product_id' => carbon_get_post_meta($booking_id, 'product_id'),
        'date_start' => carbon_get_post_meta($booking_id, 'date_start'),
        'date_end' => carbon_get_post_meta($booking_id, 'date_end'),
        'location_start' => carbon_get_post_meta($booking_id, 'location_start'),
        'location_end' => carbon_get_post_meta($booking_id, 'location_end'),
    );

    return $booking;
}
// Добавьте следующий код в файл functions.php вашей темы или в свой собственный плагин

//function custom_change_title_for_car_booking($title, $post_id) {
//    // Проверка, является ли текущая запись типом "car_booking"
//    if (get_post_type($post_id) === 'car_booking') {
//        // Ваш код для динамического определения нового заголовка
//        $new_title = 'Новый Заголовок';
//
//        // Возвращаем новый заголовок
//        return $new_title;
//    }
//
//    // Возвращаем оригинальный заголовок для других типов записей
//    return $title;
//}

// Регистрация фильтра
//add_filter('the_title', 'custom_change_title_for_car_booking', 10, 2);
