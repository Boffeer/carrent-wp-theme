<?php

if (!defined('ABSPATH')) {
	exit; // exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'init', 'register_reviews_post_types' );
function register_reviews_post_types() {
	register_post_type( 'reviews', [
		'label'  => null,
		'labels' => [
			'name'               => 'Reviews',
			'singular_name'      => 'Review',
			'add_new'            => 'Add review',
			'add_new_item'       => 'Adding review',
			'edit_item'          => 'Edit review',
			'new_item'           => 'New review',
			'view_item'          => 'View review',
			'search_items'       => 'Search review',
			'not_found'          => 'Not found',
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Reviews',
		],
		'description'            => '',
		'public'                 => true,
		'show_in_menu'           => null, // показывать ли в меню админки
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-star-filled',
		'hierarchical'        => false,
		'supports'            => [ 'title' ],
		'taxonomies'          => [],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}

add_action('carbon_fields_register_fields', 'register_reviews_fields');
function register_reviews_fields() {

	Container::make('post_meta', 'review_info', 'Отзыв')
		->where('post_type', '=', 'reviews')
		->add_fields(array(
		    /*
            Field::make( 'radio', 'stars', __( 'Звездность отзыва' ) )
                ->set_width(50)
                ->set_options( array(
                    '10' => '5',
                    '9' => '4.5',
                    '8' => '4',
                    '7' => '3.5',
                    '6' => '3',
                    '5' => '2.5',
                    '4' => '2',
                    '3' => '1.5',
                    '2' => '1',
                    '1' => '0.5',
                ) ),
		    */
            Field::make('image', 'user_pic', 'User photo')
                ->set_width(20),
            Field::make('textarea', 'user_name', 'Review user name')
                ->set_width(40),
            Field::make('image', 'feedback_pic', 'Review photo'),
            Field::make('textarea', 'feedback', 'Review text'),
            Field::make('text', 'video', 'Видео отзыва')
                ->set_help_text('Paste youtube video url')
                ->set_width(50),
        ))
		;
}

/*
function custom_reviews_archive_posts_per_page( $query ) {
    if (! is_admin() && $query->is_post_type_archive( 'reviews' ) && $query->is_main_query() ) {
        $query->set( 'posts_per_page', 4 );
    }
}
add_action( 'pre_get_posts', 'custom_reviews_archive_posts_per_page' );
*/

//add_action( 'wp_ajax_get_review', 'get_review' );
//add_action( 'wp_ajax_nopriv_get_review', 'get_review' );
/*
function get_review() {
    $review_id = $_POST['id'];

    $review_feedback = carbon_get_post_meta($review_id, 'feedback');

    echo json_encode(array(
        'feedback' => $review_feedback,
    ));

    wp_die();
}
*/