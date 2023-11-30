<?php

if (!defined('ABSPATH')) {
	exit; // exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'init', 'register_team_specs_taxonomy' );
function register_team_specs_taxonomy() {

    $labels = array(
        'name'              => __( 'Специализации' ),
        'singular_name'     => __( 'Специализация' ),
        'search_items'      => __( 'Искать специализацию' ),
        'all_items'         => __( 'Все специализации' ),
        'edit_item'         => __( 'Редактировать специализацию' ),
        'update_item'       => __( 'Обновить специализацию' ),
        'add_new_item'      => __( 'Добавить специализацию' ),
        'new_item_name'     => __( 'Новая специализация' ),
        'menu_name'         => __( 'Специализации' )
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'hierarchical'      => true,
        'show_tagcloud'     => false,
        'show_ui'           => true,
        'query_var'         => true
    );

    register_taxonomy( 'team-specs', 'team', $args );
}
add_action( 'carbon_fields_register_fields', 'register_team_specs_fields' );
function register_team_specs_fields() {
    Container::make( 'term_meta', __( 'Про специализацию', 'crb' ) )
        ->where( 'term_taxonomy', '=', 'team-specs' ) // only show our new field for categories
        ->add_fields( array(
            Field::make('text', 'order', 'Порядок сортировки')
        ) );
}


add_action( 'init', 'register_team_post_types' );
function register_team_post_types() {
	register_post_type( 'team', [
		'label'  => null,
		'labels' => [
			'name'               => 'Команда',
			'singular_name'      => 'Работник',
			'add_new'            => 'Добавить работника',
			'add_new_item'       => 'Добавление работника',
			'edit_item'          => 'Редактирование работника',
			'new_item'           => 'Новый работника',
			'view_item'          => 'Смотреть работника',
			'search_items'       => 'Искать работника',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'parent_item_colon'  => '',
			'menu_name'          => 'Команда',
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
		'taxonomies'          => ['team-specs'],
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

add_action('carbon_fields_register_fields', 'register_team_fields');
function register_team_fields() {
	Container::make('post_meta', 'team_info', 'О работнике')
		->where('post_type', '=', 'team')
		->add_fields(array(
            Field::make('text', 'occupy', 'Должность')
                ->set_width(33),
            Field::make('text', 'order', 'Порядковый номер')
                ->set_width(33),
        ))
		;
}

