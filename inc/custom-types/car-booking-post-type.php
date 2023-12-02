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
	Container::make('post_meta', 'car_booking_info', 'Контакт')
		->where('post_type', '=', 'car_booking')
		->add_fields(array(
            Field::make('text', 'name', 'name'),
            Field::make('text', 'phone', 'phone'),
            Field::make('text', 'email', 'email'),
            Field::make('text', 'id', 'id'),
            Field::make('text', 'amount_total', 'amount_total (cents)'),
            Field::make('text', 'created', 'created'),
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