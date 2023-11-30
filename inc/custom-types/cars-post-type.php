<?php

if (!defined('ABSPATH')) {
	exit; // exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'init', 'register_cars_post_types' );
function register_cars_post_types() {
	register_post_type( 'cars', [
		'label'  => null,
		'labels' => [
			'name'               => 'Cars',
			'singular_name'      => 'Car',
			'add_new'            => 'Add new car',
			'add_new_item'       => 'Adding car',
			'edit_item'          => 'Edit car',
			'new_item'           => 'New car',
			'view_item'          => 'Viw car',
			'search_items'       => 'Search car',
			'not_found'          => 'Not found',
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Cars',
		],
		'description'            => '',
		'public'                 => true,
		'show_in_menu'           => null, // показывать ли в меню админки
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
//		'menu_icon'           => 'dashicons-cars',
		'hierarchical'        => false,
		'supports'            => [ 'title', 'thumbnail', 'editor' ],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}

add_action('carbon_fields_register_fields', 'register_cars_fields');
function register_cars_fields() {

	Container::make('post_meta', 'cars_info', 'О проекте')
		->where('post_type', '=', 'cars')
        ->add_tab('Common', array(
            Field::make('text', 'rentprog_id', "Car's rentprog id"),
            Field::make( 'media_gallery', 'photos', __( 'Photos' ) )
                ->set_help_text('First photo will be showed in catalog'),
        ))


        ->add_tab('API', array(
            Field::make( 'text', 'car_name', __( 'Car name' ) ),
            Field::make( 'textarea', 'description', __( 'Description' ) ),
            Field::make( 'text', 'fuel', __( 'Fuel' ) ),
            Field::make( 'text', 'number_seats', __( 'Number seats' ) ),
            Field::make( 'text', 'trunk_volume', __( 'Trunk volume' ) ),
            Field::make( 'text', 'transmission', __( 'Transmission' ) ),


            Field::make( 'text', 'year', __( 'Year' ) ),
            Field::make( 'text', 'color', __( 'Color' ) ),
            Field::make( 'text', 'is_air', __( 'Is air' ) ),
            Field::make( 'text', 'engine_capacity', __( 'Engine capacity' ) ),
            Field::make( 'text', 'is_electropackage', __( 'Is electropackage' ) ),
            Field::make( 'text', 'car_class', __( 'Car class' ) ),
            Field::make( 'text', 'car_type', __( 'Car type' ) ),
            Field::make( 'text', 'number_doors', __( 'Number doors' ) ),
            Field::make( 'text', 'tank_value', __( 'Tank value' ) ),
            Field::make( 'text', 'drive_unit', __( 'Drive unit' ) ),
            Field::make( 'text', 'engine_power', __( 'Engine power' ) ),
            Field::make( 'text', 'airbags', __( 'Airbags' ) ),
            Field::make( 'text', 'roof', __( 'Roof' ) ),

            Field::make( 'text', 'gas_mileage', __( 'Gas mileage' ) ),
            Field::make( 'text', 'steering_side', __( 'Steering side' ) ),
            Field::make( 'text', 'interior', __( 'Interior' ) ),
            Field::make( 'text', 'abs', __( 'ABS' ) ),
            Field::make( 'text', 'ebd', __( 'EBD' ) ),
            Field::make( 'text', 'esp', __( 'ESP' ) ),
            Field::make( 'text', 'window_lifters', __( 'Window lifters' ) ),
            Field::make( 'text', 'state', __( 'State' ) ),
            Field::make( 'text', 'tire_type', __( 'Tire type' ) ),
        Field::make( 'text', 'store_place', __( 'Store place' ) ),

            Field::make( 'text', 'heated_seats', __( 'Heated seats' ) ),
            Field::make( 'text', 'heated_seats_front', __( 'Heated seats front' ) ),
            Field::make( 'text', 'parktronic', __( 'Parktronic' ) ),
            Field::make( 'text', 'parktronic_back', __( 'Parktronic back' ) ),
            Field::make( 'text', 'parktronic_camera', __( 'Parktronic camera' ) ),
            Field::make( 'text', 'wheel_adjustment', __( 'Wheel adjustment' ) ),
            Field::make( 'text', 'wheel_adjustment_full', __( 'Wheel adjustment full' ) ),
            Field::make( 'text', 'audio_system', __( 'Audio system' ) ),
            Field::make( 'text', 'video_system', __( 'Video system' ) ),
            Field::make( 'text', 'tv_system', __( 'TV system' ) ),
            Field::make( 'text', 'cd_system', __( 'CD system' ) ),
            Field::make( 'text', 'usb_system', __( 'USB system' ) ),
            Field::make( 'text', 'climate_control', __( 'Climate control' ) ),
            Field::make( 'text', 'folding_seats', __( 'Folding seats' ) ),
            Field::make( 'text', 'heated_windshield', __( 'Heated windshield' ) ),
            Field::make( 'text', 'rain_sensor', __( 'Rain sensor' ) ),

        Field::make( 'text', 'prices', __( 'Prices' ) ),
        /*
            {
                "id": 75241,
                "values": [
                    100.0,
                    90.0,
                    80.0,
                    70.0,
                    60.0
                ],
                "car_id": 39297,
                "season_id": null,
                "created_at": "2023-11-24T11:05:00.111+03:00",
                "updated_at": "2023-11-24T11:05:00.111+03:00"
            }
         */
        ))

        ->add_tab('Первый экран', array(
            Field::make( 'textarea', 'title', __( 'Заголовок' ) ),
            Field::make( 'textarea', 'caption', __( 'Подпись под заголовоком' ) )
                ->set_help_text('Отображается на странице проекта в блоке «следующий проект» при выборе этого проекта'),
            Field::make( 'textarea', 'excerpt', __( 'Краткое описание' ) )
        ))
        ->add_tab('Экраны в конце', array(
            Field::make( 'radio', 'show_quiz', __( 'Отображение квиза?' ) )
                ->set_options( array(
                    'show' => 'Показывать',
                    'hide' => 'Скрывать',
                ) ),

            Field::make( 'image', 'floor_plan_img', __( 'Отображаемая картинка планировки' ) )
                ->set_help_text('Если не выбирать картинку, то блок не отобразится')
                ->set_width(50),

            Field::make( 'textarea', 'floor_plan_title', __( 'Заголовок блока с планировкой' ) )
                ->set_default_value('[hl]Планировка квартиры\n[/hl]'),
            Field::make( 'textarea', 'floor_plan_desc', __( 'Описание блока с планировкой' ) ),
            Field::make( 'file', 'floor_plan_file', __( 'Файл планировки' ) )
                ->set_value_type('url'),

            Field::make( 'association', 'review', __( 'Отзыв' ) )
                ->set_types( array(
                    array(
                        'type'      => 'post',
                        'post_type' => 'reviews',
                    )
                ) ),

            Field::make( 'association', 'cars_next', __( 'Следующий проект' ) )
                ->set_max(1)
                ->set_types( array(
                    array(
                        'type'      => 'post',
                        'post_type' => 'cars',
                    )
                ) )
        ))
		;


}

add_action('admin_enqueue_scripts', 'enqueue_custom_js_for_cars');
function enqueue_custom_js_for_cars() {
    if (is_admin() && isset($_GET['post']) && get_post_type($_GET['post']) === 'cars') {
        // Get the current post object
        $post = get_post($_GET['post']);

        // Check if the post object is not null
        if (!is_null($post)) {
            // Enqueue your JS file
            wp_enqueue_script('custom-js-for-cars', get_template_directory_uri() . '/js/cars-edit.js', array('jquery'), '1.0', true);

            // Output your API data
            $rentprog_api = carbon_get_theme_option('rentprog_api');
            echo "<div style=\"display: none;\" id=\"rentprog_api\">{$rentprog_api}</div>";
        }
    }
}

add_action('wp_ajax_filter_cars', 'filter_cars');
add_action('wp_ajax_nopriv_filter_cars', 'filter_cars');

function filter_cars() {
    $rentprog_api = carbon_get_theme_option('rentprog_api');
    // Шаг 1: Получение токена
    $token_url = 'https://rentprog.pro/api/v1/public/get_token?company_token='. $rentprog_api;
    $token_response = wp_remote_get($token_url);

    if (is_wp_error($token_response)) {
        // Обработка ошибки запроса токена
        $error_message = $token_response->get_error_message();
        echo "Ошибка запроса токена: " . $error_message;
    } else {
        $token_body = wp_remote_retrieve_body($token_response);
        $token_data = json_decode($token_body, true);

        // Получение значения токена
        $token = $token_data['token'];

        // Шаг 2: Запрос данных с использованием токена
        $data_url = 'https://rentprog.pro/api/v1/public/free_cars';
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];
        $data_response = wp_remote_get($data_url, [
            'headers' => $headers
        ]);

        if (is_wp_error($data_response)) {
            // Обработка ошибки запроса данных
            $error_message = $data_response->get_error_message();
            echo "Ошибка запроса данных: " . $error_message;
        } else {
            $data_body = wp_remote_retrieve_body($data_response);
            $data = json_decode($data_body, true);

            // Используйте переменную $data для обработки данных из ответа

            // Пример вывода данных
            echo "Данные из API: ";
            print_r($data);
        }
    }
}
