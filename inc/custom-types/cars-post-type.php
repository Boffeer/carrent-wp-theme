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
        'text_price_hint' => '*При аренде от 30 до 45 дней',
        'text_book' => 'Забронировать машину',
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
        $free_cars_ids[] = $car['id'];
    }

    $args = array(
        'post_type' => 'cars', // Replace with your post type
        'posts_per_page' => -1, // Use -1 to retrieve all posts
        'lang'           => $current_language, // Указываем текущий язык
        'meta_query' => array(
            array(
                'key' => 'rentprog_id', // Replace with your actual custom field name
                'value' => $free_cars_ids, // Replace with the value you want to filter by
                'compare' => 'IN', // Use '=' for exact match
//                 'type'    => 'CHAR', // You can specify the data type if needed
            ),
        ),
    );

    $query = new WP_Query($args);

    $free_cars = array();
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $car = get_car_content(get_the_ID());
            $free_cars[] = $car;
        endwhile;
        wp_reset_postdata();
    else :
        // No posts found
    endif;

    echo json_encode(array(
        'cars' => $free_cars,
        'ids' => $free_cars_ids,
        'search_start' => $search_start,
        'search_end' => $search_end,
        'current_lang' => $current_language,
    ), JSON_UNESCAPED_UNICODE);
    wp_die();
}
