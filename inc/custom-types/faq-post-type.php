<?php

if (!defined('ABSPATH')) {
	exit; // exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'init', 'register_faq_post_types' );
function register_faq_post_types() {
	register_post_type( 'faq', [
		'label'  => null,
		'labels' => [
			'name'               => 'FAQ',
			'singular_name'      => 'FAQ',
			'add_new'            => 'Добавить FAQ',
			'add_new_item'       => 'Добавление FAQ',
			'edit_item'          => 'Редактирование FAQ',
			'new_item'           => 'Новый FAQ',
			'view_item'          => 'Смотреть FAQ',
			'search_items'       => 'Искать FAQ',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'parent_item_colon'  => '',
			'menu_name'          => 'FAQ',
		],
		'description'            => '',
		'public'                 => true,
		'show_in_menu'           => null, // показывать ли в меню админки
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-editor-table',
		'hierarchical'        => false,
		'supports'            => [ 'title',],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}

function is_field_value($field, $value) {
	return array(
		'relation' => 'AND', // Optional, defaults to "AND"
      array(
        'field' => $field,
        'value' => $value, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
	      'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
    )
	);
}

add_action('carbon_fields_register_fields', 'register_faq_fields');
function register_faq_fields() {
	Container::make('post_meta', 'faq_info', ' ')
		->where('post_type', '=', 'faq')
		->add_fields(array(
            Field::make('textarea', 'question', 'Вопрос')
                ->set_width(50),
            Field::make('textarea', 'answer', 'Ответ')
                ->set_width(50),
        ))
		;
}

