<?php
if (!defined('ABSPATH')) {
	exit; // exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Default options page
Container::make('theme_options', 'theme_settings',  'Theme settings')
    ->add_tab('Main', array(
        Field::make('text', 'logo_text', 'Logo text')
            ->set_width(30),
//        Field::make('image', 'loader', 'Ладер для ленивой загрузки картинок')
//            ->set_width(30),
        Field::make('image', 'default_og_img', 'Default socials image')
            ->set_width(20),
        Field::make('text', 'currency', 'Currency'),
//        Field::make('text', 'min_hour_booking_gap', 'Minimum Hour Gap Between Bookings'),
        Field::make('text', 'time_min', 'Earliest booking time')
            ->set_width(33),
        Field::make('text', 'time_max', 'Latest booking time')
            ->set_width(33),
        Field::make('text', 'time_step', 'Timepicker minutes step')
            ->set_width(33),

		Field::make('header_scripts', 'crb_header_script', 'Header Script'),
		Field::make('footer_scripts', 'crb_footer_script', 'Footer Script'),
	))
    ->add_tab('Integrations', array(
        Field::make('text', 'rentprog_api', 'rentprog.ru api key')
            ->set_width(50),
        Field::make( 'radio', 'stripe_key_type', __( 'Which Stripe keys to use?' ) )
            ->set_options( array(
                'prod' => 'Real cards',
                'test' => 'Test',
            ) ),
        Field::make('text', 'stripe_public_key', 'Stripe Public Key')
            ->set_width(50),
        Field::make('text', 'stripe_secret_key', 'Stripe Secret Key')
            ->set_width(50),
        Field::make('text', 'test_stripe_public_key', 'Test Stripe Public Key')
            ->set_width(50),
        Field::make('text', 'test_stripe_secret_key', 'Test Stripe Secret Key')
            ->set_width(50),

        Field::make('text', 'coupon_sheets_form_id', 'Google Form: Form ID (form url)')
            ->set_width(33),
        Field::make('text', 'coupon_sheets_partial_response', 'Google Form: Partial Response (form source code)')
            ->set_width(33),
        Field::make('text', 'coupon_sheets_fbzx', 'Google Form: FBZX (form source code)')
            ->set_width(33),
))

	->add_tab('Contacts', array(
		Field::make('textarea', 'phones', 'Phones')
            ->set_help_text('Every phone must be on new line')
			->set_width(50),
        Field::make('text', 'phone_country_code', 'Phone country code')
            ->set_width(50),
        Field::make('textarea', 'emails', 'Email')
            ->set_width(50),
        Field::make('textarea', 'socials', 'Socials')
            ->set_width(50),
        Field::make('textarea', 'address', 'Address')
            ->set_width(50),
        Field::make('textarea', 'work_time', 'Work time')
            ->set_width(50),
//        Field::make('textarea', 'leads_emails', 'Имейлы для заявок')
//            ->set_width(50),
	))
	;
