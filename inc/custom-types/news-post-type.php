<?php

if (!defined('ABSPATH')) {
	exit; // exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'init', 'register_news_post_types' );
function register_news_post_types() {
	register_post_type( 'news', [
		'label'  => null,
		'labels' => [
			'name'               => 'News',
			'singular_name'      => 'Article',
			'add_new'            => 'Add article',
			'add_new_item'       => 'Adding article',
			'edit_item'          => 'Edit article',
			'new_item'           => 'New article',
			'view_item'          => 'View article',
			'search_items'       => 'Search article',
			'not_found'          => 'Not found',
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'News',
		],
		'description'            => '',
		'public'                 => true,
		'show_in_menu'           => null, // показывать ли в меню админки
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-editor-table',
		'hierarchical'        => false,
        'supports'            => [ 'title', 'thumbnail', 'editor', 'excerpt' ],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}

add_action('carbon_fields_register_fields', 'register_news_fields');
function register_news_fields() {
	Container::make('post_meta', 'news_info', ' ')
		->where('post_type', '=', 'news')
		->add_fields(array(
            Field::make('association', 'news_recommended', 'Recommended')
                ->set_types( array(
                    array(
                        'type' => 'post',
                        'post_type' => 'news',
                    ),
                ) )
        ))
		;
}

