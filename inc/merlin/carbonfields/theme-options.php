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
        Field::make('text', 'rentprog_api', 'rentprog.ru api key')
            ->set_width(50),
        Field::make('text', 'currency', 'Currency')
            ->set_width(20),

		Field::make('header_scripts', 'crb_header_script', 'Header Script'),
		Field::make('footer_scripts', 'crb_footer_script', 'Footer Script'),
	))

	->add_tab('Contacts', array(
		Field::make('textarea', 'phones', 'Phones')
            ->set_help_text('Every phone must be on new line')
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
