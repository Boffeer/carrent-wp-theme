<?php
if ( ! defined( 'ABSPATH') ) {
	 exit; // exit if accessed directly 
}

if (!function_exists('getPostsToDuplicate')) :
function getPostsToDuplicate() {
    return array(
        'faq',
        'cars',
        'news',
        'reviews',
    );
}
endif;

/*
	@source https://developer.yoast.com/duplicate-post/filters-actions/
 */
add_filter('duplicate_post_enabled_post_types', 'my_custom_enabled_post_types');
function my_custom_enabled_post_types( $enabled_post_types ) {
  return getPostsToDuplicate();
}

add_filter( 'add_meta_boxes', 'disable_yoast_seo_for_custom_posts' );
function disable_yoast_seo_for_custom_posts() {
    global $post_type;

    $types_to_disable_yoast = array(
      'faq',
      'reviews',
    );

    if ( in_array($post_type, $types_to_disable_yoast) ) {
      remove_meta_box( 'wpseo_meta', $post_type, 'normal' );
    }
}
