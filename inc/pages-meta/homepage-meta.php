<?php
if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'register_homepage_fields');
function register_homepage_fields() {

	$homepage_id  = get_option('page_on_front');

	Container::make('post_meta', 'homepage_info', 'Homepage')
		->where('post_id', '=', $homepage_id)
		->add_tab('Hero section', array(
            Field::make('textarea', 'hero_title', 'Title'),
            Field::make('complex', 'banners', 'Carousel banners')
                ->add_fields(array(
                    Field::make('textarea', 'text', 'Banner text'),
                    Field::make('text', 'link_text', 'Banner link text')
                        ->set_width(50),
                    Field::make('text', 'link_url', 'Banner link url')
                        ->set_width(50),
                    Field::make( 'image', 'img', __( 'Banner image' ) )
                ) ),
            Field::make('textarea', 'location_start_names', 'Locations start')
                ->set_help_text('One line - one location')
                ->set_width(50),
            Field::make('textarea', 'location_end_names', 'Locations end')
                ->set_help_text('One line - one location')
                ->set_width(50),
            Field::make('complex', 'bullets', 'Bullets')
                ->add_fields(array(
                    Field::make( 'image', 'icon', __( 'Bullet icon' ) ),
                    Field::make('textarea', 'title', 'Bullet title'),
                    Field::make('textarea', 'text', 'Bullet text'),
                ) ),
        ))
        ->add_tab('Cars', array(
            Field::make('association', 'home_cars', 'Default cars')
                ->set_types( array(
                    array(
                        'type' => 'post',
                        'post_type' => 'cars',
                    ),
                ) )
        ))
        ->add_tab('Reviews', array(
            Field::make( 'association', 'home_reviews', __( 'Reviews' ) )
                ->set_types( array(
                    array(
                        'type'      => 'post',
                        'post_type' => 'reviews',
                    )
                ) ),
            ))
        ->add_tab('FAQ', array(
            Field::make( 'association', 'home_faq', __( 'FAQ cards' ) )
                ->set_types( array(
                    array(
                        'type'      => 'post',
                        'post_type' => 'faq',
                    )
                ) ),
            Field::make( 'image', 'faq_img', __( 'FAQ image' ) ),
            Field::make('textarea', 'faq_title', 'FAQ title'),
            Field::make('textarea', 'faq_text', 'FAQ text'),
        ))
        ->add_tab('Meta', array(
            Field::make('textarea', 'price_tariffs', 'Tariff names'),
        ))
        ->add_tab('Ссылки', array(
            Field::make( 'association', 'link_privacy', __( 'Privacy policy' ) )
                ->set_types( array(
                    array(
                        'type'      => 'post',
                        'post_type' => 'page',
                    )
                ) )
                ->set_max(1),
            Field::make( 'association', 'link_offer', __( 'Offer' ) )
                ->set_types( array(
                    array(
                        'type'      => 'post',
                        'post_type' => 'page',
                    )
                ) )
                ->set_max(1),
        ))
    ;
}

