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
		;


}

add_action('admin_enqueue_scripts', 'enqueue_custom_js_for_cars');
function enqueue_custom_js_for_cars() {
    if (is_admin() && isset($_GET['post']) && get_post_type($_GET['post']) === 'cars' && (isset($_GET['action']) && in_array($_GET['action'], array('edit', 'post')))) {
//    if (is_admin() && isset($_GET['post']) && get_post_type($_GET['post']) === 'cars') {
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

function fetch_token($api_token) {
    $token_url = 'https://rentprog.pro/api/v1/public/get_token?company_token='. $api_token;
    $token_response = wp_remote_get($token_url);

    if (is_wp_error($token_response)) {
        // Обработка ошибки запроса токена
        $error_message = $token_response->get_error_message();
//        echo "Ошибка запроса токена: " . $error_message;
        return false;
    } else {
        $token_body = wp_remote_retrieve_body($token_response);
        $token_data = json_decode($token_body, true);

        // Получение значения токена
        $token = $token_data['token'];
        return $token;
    }
}

function fetch_free_cars($api_token, $start_date, $end_date) {
    $token = fetch_token($api_token);


    $data_url = 'https://rentprog.pro/api/v1/public/free_cars';
    $headers = [
        'Authorization' => $token
    ];
    $body = array(
        "start_date" => $start_date,
        "end_date" => $end_date,
    );
    $data_response = wp_remote_get($data_url, [
        'headers' => $headers,
        'body' => $body,
    ]);

    if (is_wp_error($data_response)) {
        // Обработка ошибки запроса данных
        $error_message = $data_response->get_error_message();
//        echo "Ошибка запроса данных: " . $error_message;
    } else {
        $data_body = wp_remote_retrieve_body($data_response);
        $data = json_decode($data_body, true);

        // Используйте переменную $data для обработки данных из ответа

        // Пример вывода данных
//        echo "Данные из API: ";
        return $data;
    }

}

function get_car_content($id) {

    $gallery = carbon_get_post_meta($id, 'photos');

    $thumb = '';
    if (!empty($gallery)) {
        $thumb = get_image_url_by_id($gallery[0]);
    }

    $prices_list = explode(',', carbon_get_post_meta($id, 'prices'));
    $last_index = count($prices_list) - 1;
    $price = $prices_list[$last_index];
    $tariff_names = explode_textarea(carbon_get_post_meta(HOMEPAGE_ID, 'price_tariffs'));
    $tariff_cheap  = $tariff_names[$last_index];

    $car = array(
        'id' => $id,
        'thumb' => $thumb,
        'title' => carbon_get_post_meta($id, 'car_name'),
        'url' => get_the_permalink($id),
        'price_cheap' => $price,
        'fuel' => carbon_get_post_meta($id,'fuel'),
        'number_seats' => carbon_get_post_meta($id, 'number_seats'),
        'trunk_volume' => carbon_get_post_meta($id, 'trunk_volume'),
        'transmission' => carbon_get_post_meta($id, 'transmission'),
        'currency' => carbon_get_theme_option('currency'),
        'text_price_hint' => $tariff_cheap,
//        'text_price_hint' => pll__('Rent Caption', 'crrt'),
        'text_book' => pll__('Select a Car', 'crrt'),
    );
    return $car;
}

function filter_cars() {
    $rentprog_api = carbon_get_theme_option('rentprog_api');

    $current_language = $_POST['lang'];
    $search_start = "{$_POST['date_start']} {$_POST['time_start']}";
    $search_end = "{$_POST['date_end']} {$_POST['time_end']}";
    $free_cars = fetch_free_cars($rentprog_api, $search_start, $search_end);
    $free_cars_ids = array();
    foreach ($free_cars as $car) {
        $free_cars_ids[] = "{$car['id']}";
    }

    $flight_number = $_POST['flight_number'];
    $location_start = $_POST['location_start'];

    $args = array(
        'post_type' => 'cars', // Replace with your post type
        'posts_per_page' => -1, // Use -1 to retrieve all posts
        'lang'           => $current_language, // Указываем текущий язык
        'meta_query' => array(
            array(
                'key' => 'rentprog_id', // Replace with your actual custom field name
                'value' => $free_cars_ids, // Replace with the value you want to filter by
                'compare' => 'IN', // Use '=' for exact match
            ),
        ),
    );

    $query = new WP_Query($args);

    $free_cars = array();
    if (!empty($free_cars_ids)) {
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $car = get_car_content(get_the_ID());
                $free_cars[] = $car;
            endwhile;
            wp_reset_postdata();
        else :
            // No posts found
        endif;
    }

    echo json_encode(array(
        'cars' => $free_cars,
        'ids' => $free_cars_ids,
        'search_start' => $search_start,
        'search_end' => $search_end,
        'flight_number' => $flight_number,
        'location_start' => $location_start,
        'messages' => array(
            'empty' => pll__('Cars empty', 'crrt'),
        ),
    ), JSON_UNESCAPED_UNICODE);
    wp_die();
}

function fetch_car_bookings($car_id) {
    $api_token = carbon_get_theme_option('rentprog_api');
    $token = fetch_token($api_token);

    $data_url = 'https://rentprog.pro/api/v1/public/car_data_with_bookings';
    $headers = [
        'Authorization' => $token
    ];
    $body = array(
        "car_id" => $car_id,
    );
    $data_response = wp_remote_get($data_url, [
        'headers' => $headers,
        'body' => $body,
    ]);

    if (is_wp_error($data_response)) {
        $error_message = $data_response->get_error_message();
    } else {
        $data_body = wp_remote_retrieve_body($data_response);
        $data = json_decode($data_body, true);
        return $data;
    }
}

//add_action('wp_ajax_get_car_bookings', 'get_car_bookings');
//add_action('wp_ajax_nopriv_get_car_bookings', 'get_car_bookings');
function get_car_bookings($car_id) {

    $rentprog_id = carbon_get_post_meta($car_id, 'rentprog_id');

//    echo json_encode(array(
//        'id' => fetch_car_bookings($rentprog_id),
//    ));
    return fetch_car_bookings($rentprog_id);

//    wp_die();
}

function get_car_bookings_timestamps($id) {
    $bookings = get_car_bookings($id);
    if (isset($bookings['active_bookings'])) {
        $bookings = $bookings['active_bookings'];
    } else {
        $bookings = [];
    }

    $active_bookings = array();
    foreach ($bookings as $book) {
        $disabled_dates = get_dates_range($book['start_date'], $book['end_date']);
        foreach ($disabled_dates as $date) {
            $active_bookings[] = $date * 1000;
        }
    }

    return json_encode($active_bookings);
}

function get_car_stats($id) {
    setup_postdata($id);

    $stats = array(
        'number_seats' => carbon_get_the_post_meta('number_seats'),
        'trunk_volume' => carbon_get_the_post_meta('trunk_volume'),
        'transmission' => carbon_get_the_post_meta('transmission'),
        'year' => carbon_get_the_post_meta('year'),
        'color' => carbon_get_the_post_meta('color'),
        'engine_capacity' => carbon_get_the_post_meta('engine_capacity'),
        'car_class' => carbon_get_the_post_meta('car_class'),
        'car_type' => carbon_get_the_post_meta('car_type'),
        'number_doors' => carbon_get_the_post_meta('number_doors'),
        'tank_value' => carbon_get_the_post_meta('tank_value'),
        'drive_unit' => carbon_get_the_post_meta('drive_unit'),
        'engine_power' => carbon_get_the_post_meta('engine_power'),
        'airbags' => carbon_get_the_post_meta('airbags'),
        'roof' => carbon_get_the_post_meta('roof'),
        'gas_mileage' => carbon_get_the_post_meta('gas_mileage'),
        'steering_side' => carbon_get_the_post_meta('steering_side'),
        'interior' => carbon_get_the_post_meta('interior'),
        'window_lifters' => carbon_get_the_post_meta('window_lifters'),
    );

    wp_reset_postdata();

    return $stats;
}
function get_car_options($id)
{
    setup_postdata($id);

    $options = array(
        'is_air' => carbon_get_the_post_meta('is_air'),
        'is_electropackage' => carbon_get_the_post_meta('is_electropackage'),
        'abs' => carbon_get_the_post_meta('abs'),
        'ebd' => carbon_get_the_post_meta('ebd'),
        'esp' => carbon_get_the_post_meta('esp'),
        'heated_seats' => carbon_get_the_post_meta('heated_seats'),
        'heated_seats_front' => carbon_get_the_post_meta('heated_seats_front'),
        'parktronic' => carbon_get_the_post_meta('parktronic'),
        'parktronic_back' => carbon_get_the_post_meta('parktronic_back'),
        'parktronic_camera' => carbon_get_the_post_meta('parktronic_camera'),
        'wheel_adjustment' => carbon_get_the_post_meta('wheel_adjustment'),
        'wheel_adjustment_full' => carbon_get_the_post_meta('wheel_adjustment_full'),
        'audio_system' => carbon_get_the_post_meta('audio_system'),
        'video_system' => carbon_get_the_post_meta('video_system'),
        'tv_system' => carbon_get_the_post_meta('tv_system'),
        'cd_system' => carbon_get_the_post_meta('cd_system'),
        'usb_system' => carbon_get_the_post_meta('usb_system'),
        'climate_control' => carbon_get_the_post_meta('climate_control'),
        'folding_seats' => carbon_get_the_post_meta('folding_seats'),
        'heated_windshield' => carbon_get_the_post_meta('heated_windshield'),
        'rain_sensor' => carbon_get_the_post_meta('rain_sensor'),
    );

    wp_reset_postdata();

    return $options;
}
