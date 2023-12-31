<?php

//use Carbon_Fields\Container;
//use Carbon_Fields\Field;

//require_once THEME_INC . '/stripe-php/init.php';

add_action('wp_ajax_get_stripe_paylink', 'get_stripe_paylink');
add_action('wp_ajax_nopriv_get_stripe_paylink', 'get_stripe_paylink');
function get_stripe_paylink()
{
    $post_id = $_POST['post_id'];
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];

    $date_start = $date_start . ' ' . $time_start;
    $date_end = $date_end . ' ' . $time_end;
    if (empty($date_end)) {
        $date_end = $date_start;
    }

    $user_phone = $_POST['user_phone'];
    $user_email = $_POST['user_email'];

    $location_start = isset($_POST['location_start']) ? $_POST['location_start'] : '';
    $location_end = isset($_POST['location_end']) ? $_POST['location_end'] : $_POST['location_start'];

    $flight_number = empty($_POST['flight_number']) ? '' . $_POST['flight_number'] : '';
    $cancel_page = $_POST['cancel_page'];

    $car = array(
        'crm_id' => carbon_get_post_meta($post_id, 'rentprog_id'),
        'name' => carbon_get_post_meta($post_id, 'car_name'),
        'prices' => explode(',', carbon_get_post_meta($post_id, 'prices')),
        'image' => get_image_url_by_id(carbon_get_post_meta($post_id, 'photos')[0]),
    );

    $car['price'] = get_price_per_day($car['prices'], $date_start, $date_end);
    unset($car['prices']);


    $options_to_count = array();
    $options_list = carbon_get_post_meta($post_id, 'car_options');
    $active_options = explode(',', $_POST['options']);
    foreach ($active_options as $option) {
        foreach ($options_list as $option_info) {
            if ($option_info['name'] === $option) {
                $option_prices = explode(',', $option_info['prices']);
                $current_option = get_price_per_day($option_prices, $date_start, $date_end);
                $current_option['name'] = $option;
                $options_to_count[] = $current_option;
            }
        }
    }

    $currencies = array(
        'eur' => 'eur',
        '€' => 'eur',
        'usd' => 'usd',
        '$' => 'usd',
    );
    $currency = $currencies[carbon_get_theme_option('currency')];

    $options_strings = [];
    foreach ($options_to_count as $option) {
        $name = $option['name'];
        $total = $option['total'];
        $options_strings[] = "$name: $total{$currency}";
    }
    $options_string = implode(', ', $options_strings);

    $agree = $_POST['agree'];
    $date_of_birth = $_POST['dob'];

    $product_info = array(
        'name' => "{$car['name']} (" . count($car['price']['range']) . " days)",
        'price' => $car['price']['total'] * 100,
        'image' => $car['image'],
        'description' => "{$location_start}, {$date_start} - {$date_end}, {$options_string}, date of birth: {$date_of_birth}, Agreement: {$agree}",
    );
    $booking_id = uniqid();

    $domain = $_SERVER['SERVER_NAME'];

    $stripe_api_url = 'https://api.stripe.com/v1/checkout/sessions';
    $stripe_secret_key = get_stripe_secret();

    $line_items = array(
        [
            'price_data' => [
                'currency' => $currency,
                'product_data' => [
                    'name' => $product_info['name'],
                    'images' => [$product_info['image']],
                    'description' => $product_info['description'],
                ],
                'unit_amount' => $product_info['price'], // Amount in cents (e.g., $19.99)
            ],
            'quantity' => 1,
        ],
    );
    foreach ($options_to_count as $option) {
        $line_items[] = [
            'price_data' => [
                'currency' => $currency,
                'product_data' => [
                    'name' => $option['name'],
                ],
                'unit_amount' => $option['total'] * 100, // Amount in cents (e.g., $19.99)
            ],
            'quantity' => 1,
        ];
    }

    // Set your Stripe secret key
    $headers = [
        'Authorization: Bearer ' . $stripe_secret_key,
        'Content-Type: application/x-www-form-urlencoded',
    ];
    $data = [
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => "https://{$domain}/success?car_booking_id={$booking_id}",
        'cancel_url' => $cancel_page,
//        'cancel_url' => "https://{$domain}",
        'payment_intent_data' => [
            'metadata' => [
                'user_phone' => $user_phone,
                'product_id' => $post_id,
                'booking_id' => $booking_id,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'time_start' => $time_start,
                'time_end' => $time_end,
                'location_start' => $location_start,
                'location_end' => $location_end,
                'flight_number' => $flight_number,
                'options' => $options_string,
                'agree' => $agree,
                'date_of_birth' => $date_of_birth,
            ],
        ],
//        'discounts' => [['coupon' => 'shit']],
        'allow_promotion_codes' => 'true', // Enable promotion codes
        'customer_email' => $user_email,
        'client_reference_id' => $user_phone,
    ];
    // Convert the payload to a URL-encoded string
    $post_data = http_build_query($data);

    // Initialize cURL session
    $ch = curl_init();
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $stripe_api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Execute cURL session
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Decode the response JSON
    $session_data = json_decode($response, true);
    $paylink = $session_data['url'];

    echo json_encode(array(
//        'session_data' => $session_data,
//        'car' => $car,
//        'options' => $options_to_count,
        'paylink' => $paylink,
//        'coupons' => check_stripe_coupon('test'),
    ), JSON_UNESCAPED_UNICODE);
    wp_die();
}

function get_price_per_day($prices, $date_start, $date_end) {
    $date_range = get_dates_range($date_start, $date_end);
    $days_count = count($date_range);

    $price_index = 0;
    if ($days_count < 4) {
        $price_index = 0;
    } elseif ($days_count < 8) {
        $price_index = 1;
    } elseif ($days_count < 16) {
        $price_index = 2;
    } elseif ($days_count < 31) {
        $price_index = 3;
    } else {
        $price_index = 4;
    }

    $price_per_day = isset($prices[$price_index]) ? $prices[$price_index] : end($prices);
//    $price_per_day = $prices[$price_index];
    $total = $price_per_day  * $days_count;

    return array(
        'per_day' => $price_per_day,
        'total' => $total,
        'range' => $date_range,
    );

}


add_action('wp_ajax_handle_stripe_webhook', 'handle_stripe_webhook');
add_action('wp_ajax_nopriv_handle_stripe_webhook', 'handle_stripe_webhook');

function handle_stripe_webhook() {
    // Получите данные от Stripe
    $body = @file_get_contents('php://input');
    $event_json = json_decode($body);

//    echo json_encode(array('test' => 'test', 'event' => $event_json));

    if ($event_json->type === 'charge.succeeded') {
        $event_id = $event_json->id;

        $amount = $event_json->data->object->amount;
        $email = $event_json->data->object->billing_details->email;
        $name = $event_json->data->object->billing_details->name;

        $receipt_url = $event_json->data->object->receipt_url;

        $phone = $event_json->data->object->metadata->user_phone;
        $product_id = $event_json->data->object->metadata->product_id;
        $date_start = $event_json->data->object->metadata->date_start;
        $date_end = $event_json->data->object->metadata->date_end;
        $time_start = $event_json->data->object->metadata->time_start;
        $time_end = $event_json->data->object->metadata->time_end;
        $location_start = $event_json->data->object->metadata->location_start;
        $location_end = $event_json->data->object->metadata->location_end;
        $booking_id = $event_json->data->object->metadata->booking_id;
        $flight_number = $event_json->data->object->metadata->flight_number;
        $agree = $event_json->data->object->metadata->agree;
        $date_of_birth = $event_json->data->object->metadata->date_of_birth;
        $options = $event_json->data->object->metadata->options;



        $payment_intent = $event_json->data->object->payment_intent;
        $created = $event_json->data->object->created;


        $title = $phone .' - '. $name .' - '. $email .' - '. $payment_intent;
        $post_data = array(
            'post_title' => $title, // Заголовок вашего кастомного поста
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'car_booking', // Тип вашего кастомного поста
        );

        // Создайте кастомный пост
        $post_id = wp_insert_post($post_data);

        if (!is_wp_error($post_id)) {
            // Set the post slug to be the post ID
            $post_data = array(
                'ID'  => $post_id,
                'post_name' => $booking_id,
            );
            wp_update_post($post_data);
        }

        // Выполните другие действия, если необходимо
        carbon_set_post_meta( $post_id, 'phone', $phone);
        carbon_set_post_meta( $post_id, 'email', $email);
        carbon_set_post_meta( $post_id, 'name', $name);
        carbon_set_post_meta( $post_id, 'created', $created);
        carbon_set_post_meta( $post_id, 'amount', $amount);
        carbon_set_post_meta( $post_id, 'id', $event_id);
        carbon_set_post_meta( $post_id, 'json', json_encode($event_json));
        carbon_set_post_meta( $post_id, 'product_id', $product_id);
        carbon_set_post_meta( $post_id, 'payment_intent', $payment_intent);
        carbon_set_post_meta( $post_id, 'booking_id', $booking_id);

        carbon_set_post_meta( $post_id, 'date_start', $date_start);
        carbon_set_post_meta( $post_id, 'date_end', $date_end);
        carbon_set_post_meta( $post_id, 'time_start', $time_start);
        carbon_set_post_meta( $post_id, 'time_end', $time_end);
        carbon_set_post_meta( $post_id, 'location_start', $location_start);
        carbon_set_post_meta( $post_id, 'location_end', $location_end);
        carbon_set_post_meta( $post_id, 'flight_number', $flight_number);
        carbon_set_post_meta( $post_id, 'receipt_url', $receipt_url);

        carbon_set_post_meta( $post_id, 'options', $options);

        carbon_set_post_meta( $post_id, 'agree', $agree);
        carbon_set_post_meta( $post_id, 'date_of_birth', $date_of_birth);

        $booking = create_booking($post_id);

        $payment = create_payment($booking['booking']['id'], (int) $amount / 100);

        carbon_set_post_meta( $post_id, 'crm_booking_id', $payment['ids']);

        carbon_set_post_meta( $post_id, 'crm_booking_id', $booking['booking']['id']);

        $gallery = carbon_get_post_meta($product_id, 'photos');
        $car_thumb = '';
        if (!empty($gallery)) {
            $car_thumb = get_image_url_by_id($gallery[0]);
        }
        send_email_booking(array(
            'name' => $name,
            'date_of_birth' => $date_of_birth,
            'email' => $email,
            'phone' => $phone,
//            'crm_booking_id' => $payment['ids'][0],
            'crm_booking_id' => $booking['booking']['id'],
            'location_start' => $location_start,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'flight_number' => $flight_number,
            'receipt_url' => $receipt_url,
            'options' => $options,
            'car_name' => carbon_get_post_meta($product_id, 'car_name'),
            'car_thumb'=> $car_thumb,
        ));
    } elseif ($event_json->type === 'customer.discount.created') {
        $coupon = $event_json->data->object->coupon;


        $checkout_session_id = $event_json->data->object->checkout_session;
        $checkout = get_stripe_checkout($checkout_session_id);
        $payment_intent_id = $checkout['payment_intent'];
        $payment_intent = get_stripe_payment_intend($payment_intent_id);

        $metadata = $payment_intent['metadata'];

        $table_data = array(
            'pi_id' => $payment_intent['id'],
            'coupon_name' => $coupon->name,
            'percent_off' => $coupon->percent_off,
            'amount' => $payment_intent['amount'] / 100,
            'user_phone' => $metadata['user_phone'],
            'user_email' => $checkout['customer_details']['email'],
            'user_name' => $checkout['customer_details']['name'],
        );


        send_promocode_sheet($table_data);
    }

    // Отправьте ответ, чтобы подтвердить успешное получение данных
    status_header(200);
    die();
}

function replacePostKey($key) {
    $keys = array(
        'name' => pll__('Name', 'crrt'),
        'email' => pll__('Email', 'crrt'),
        'phone' => pll__('Phone', 'crrt'),
        'flight_number' => pll__('Flight number', 'crrt'),
        'receipt_url' => pll__('Receipt', 'crrt'),
        'options' => pll__('Options', 'crrt'),
        'date_of_birth' => pll__('Date of birth', 'crrt'),
    );


    return isset($keys[$key]) ? $keys[$key] : $key;
}

function sortArrayByKeyNames($keyNames, $arrayToSort) {
    $sortedArray = array();
    foreach ($keyNames as $keyName) {
        if (array_key_exists($keyName, $arrayToSort)) {
            $sortedArray[$keyName] = $arrayToSort[$keyName];
            unset($arrayToSort[$keyName]);
        }
    }
    return array_merge($sortedArray, $arrayToSort);
}

function send_email_booking($data) {
    $to = $data['email'];
    $subject = "#{$data['crm_booking_id']} {$data['car_name']}: {$data['date_start']} - {$data['date_end']}"; // Тема письма

    $message = '<html><body>';
    $message .= pll__('Your Car booking: ', 'crrt') . " {$data['crm_booking_id']}" . '<br>';
    $message .= '<img src="'.$data['car_thumb'].'" style="width: 100%;"/>';
    $message .= '<h3>'. pll__('Start', 'crrt') . '</h3>';
    $message .= '<p>'. $data['location_start'] . '</p>';
    $message .= '<p>'. $data['date_start'] . '</p><br>';

    $message .= '<h3>'. pll__('End', 'crrt') . '</h3>';
    $message .= '<p>'. $data['location_start'] . '</p>';
    $message .= '<p>'. $data['date_end'] . '</p><br>';

    $IGNORE_KEYS = array(
        'user_file',
        'date_start',
        'date_end',
        'location_start',
        'crm_booking_id',
        'car_name',
        'car_thumb',
    );
    foreach ($data as $key => $value) {
        if (in_array($key, $IGNORE_KEYS)) continue;
        if (empty($value)) continue;
        $message .= '<p><strong>' . replacePostKey($key) . ':</strong> ' . $value . '</p>';
    }
    $message .= '</body></html>';

    $headers = array(
        'Content-Type: text/html; charset=UTF-8', // Установка заголовка для HTML-сообщения
    );

    // Отправка письма
    $result = wp_mail($to, $subject, $message, $headers);
}

function create_booking($booking_post_id) {
    $api_token = carbon_get_theme_option('rentprog_api');
    $token = fetch_token($api_token);

    $id = $booking_post_id;
    $car_post_id = carbon_get_post_meta($id, 'product_id');

    $date_start = carbon_get_post_meta($id, 'date_start') . ' ' . carbon_get_post_meta($id, 'time_start');
    $date_end = carbon_get_post_meta($id, 'date_end') . ' ' . carbon_get_post_meta($id, 'time_end');

    $booking = array(
        'name' => carbon_get_post_meta($id, 'name'),
        'car_id' => carbon_get_post_meta($car_post_id, 'rentprog_id'),
        'start_date' => $date_start,
        'end_date' => $date_end,
        'start_place' =>  carbon_get_post_meta($id, 'location_start'),
        'end_place' =>  carbon_get_post_meta($id, 'location_end'),
        'phone' =>  carbon_get_post_meta($id, 'phone'),
        'email' =>  carbon_get_post_meta($id, 'email'),
        'days' => count(get_dates_range($date_start, $date_end)),
        'price' => (int) carbon_get_post_meta($id, 'amount') / 100,
        'receipt_url' => carbon_get_post_meta($id, 'receipt_url'),
        'flight_number' => carbon_get_post_meta($id, 'flight_number'),
        'options' => carbon_get_post_meta($id, 'options'),
        'date_of_birth' => carbon_get_post_meta($id, 'date_of_birth'),
        'agree' => carbon_get_post_meta($id, 'agree'),
    );

    $description = "Receipt: {$booking['receipt_url']};";
    if (!empty($booking['flight_number'])) {
        $description.= "\n\n{$booking['flight_number']};";
    }
    if (!empty($booking['agree'])) {
        $description.= "Is Agree: \n\n{$booking['agree']};";
    }
    if (!empty($booking['date_of_birth'])) {
        $description.= "Date of birth: \n\n{$booking['date_of_birth']};";
    }
    if (!empty($booking['options'])) {
        $description.= " \n\n{$booking['options']};";
    }


    $data_url = 'https://rentprog.pro/api/v1/public/create_booking';
    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => $token
    ];
    $body = array(
        "active" => true,
        "car_id" => $booking['car_id'],
        "start_date" => $booking['start_date'],
        "end_date" =>   $booking['end_date'],
        "start_place" => $booking['start_place'],
        "end_place" => $booking['end_place'],
        "price" => $booking['price'],

        "email" => $booking['email'],
        "phone" => $booking['phone'],
        "name" => $booking['name'],
        "middlename" => "",
        "lastname" => "",

        "passport_series" => "",
        "passport_number" => "",
        "passport_issued" => "",
        "driver_series" => "",
        "driver_number" => "",
        "driver_issued" => "",
        "birthday" => "",
        "address" => "",

//        "description" => "",
        "description" => $description,
        "days" => $booking['days'],
    );

    $data_response = wp_remote_post($data_url, [
        'headers' => $headers,
        'body' => json_encode($body),
    ]);

    if (is_wp_error($data_response)) {
        $error_message = $data_response->get_error_message();
        return $error_message;
    } else {
        $data_body = wp_remote_retrieve_body($data_response);
        $data = json_decode($data_body, true);
        return $data;
    }

    wp_die();
}


function create_payment($booking_id, $amount) {
    $api_token = carbon_get_theme_option('rentprog_api');
    $token = fetch_token($api_token);

    $data_url = 'https://rentprog.pro/api/v1/public/create_payments';
    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => $token
    ];
    $body = array(
        'active' => true,
        'booking_id' => $booking_id,
        'source' => 'site',
        "payments" => array(
            array(
                "sum" => $amount,
                "group" => 0,
                "active" => true
            )
        )
    );

    $data_response = wp_remote_post($data_url, [
        'headers' => $headers,
        'body' => json_encode($body),
    ]);

    if (is_wp_error($data_response)) {
        $error_message = $data_response->get_error_message();
        return $error_message;
    } else {
        $data_body = wp_remote_retrieve_body($data_response);
        $data = json_decode($data_body, true);
        return $data;
    }

    wp_die();
}

add_action('after_setup_theme', 'crrt_register_translate');
function crrt_register_translate() {
    if (!function_exists('pll_register_string')) return;

    pll_register_string('Blog', 'Blog', 'crrt', false);
    pll_register_string('CallUs', 'Call us', 'crrt', false);
    pll_register_string('PickupLocation', 'A pickup location', 'crrt', false);
    pll_register_string('FlightNumber', 'Flight number', 'crrt', false);
    pll_register_string('RentDates', 'Rent Dates', 'crrt', false);
    pll_register_string('ChooseYourCar', 'Choose Your Car', 'crrt', false);
    pll_register_string('RentCaption', 'Rent Caption', 'crrt', false);
    pll_register_string('SelectCar', 'Select a Car', 'crrt', false);
    pll_register_string('Reviews', 'Reviews', 'crrt', false);
    pll_register_string('Read More', 'Read More', 'crrt', false);
    pll_register_string('FAQ', 'FAQ', 'crrt', false);
    pll_register_string('ShowMore', 'Show more', 'crrt', false);
    pll_register_string('MoreNews', 'More news', 'crrt', false);
    pll_register_string('FindCar', 'Find a Car', 'crrt', false);
    pll_register_string('Fleet', 'Fleet', 'crrt', false);
    pll_register_string('Homepage', 'Homepage', 'crrt', false);
    pll_register_string('FreeDates', 'Free Dates', 'crrt', false);
    pll_register_string('Rates', 'Rates', 'crrt', false);
    pll_register_string('BookCar', 'Book a car', 'crrt', false);
    pll_register_string('Phone', 'Phone', 'crrt', false);
    pll_register_string('Email', 'Email', 'crrt', false);
    pll_register_string('Agree', 'Agree', 'crrt', false);
    pll_register_string('And', 'and', 'crrt', false);
    pll_register_string('Options', 'Options', 'crrt', false);
    pll_register_string('Languages', 'Languages', 'crrt', false);
    pll_register_string('Promocode', 'Promocode', 'crrt', false);

    pll_register_string('NumberSeats', 'number_seats', 'crrt', false);
    pll_register_string('Transmission', 'transmission', 'crrt', false);
    pll_register_string('Color', 'color', 'crrt', false);
    pll_register_string('CarClass', 'car_class', 'crrt', false);
    pll_register_string('NumberDoors', 'number_doors', 'crrt', false);
    pll_register_string('DriveUnit', 'drive_unit', 'crrt', false);
    pll_register_string('Airbags', 'airbags', 'crrt', false);
    pll_register_string('GasMileage', 'gas_mileage', 'crrt', false);
    pll_register_string('Interior', 'interior', 'crrt', false);
    pll_register_string('TrunkVolume', 'trunk_volume', 'crrt', false);
    pll_register_string('Year', 'year', 'crrt', false);
    pll_register_string('EngineCapacity', 'engine_capacity', 'crrt', false);
    pll_register_string('CarType', 'car_type', 'crrt', false);
    pll_register_string('TankValue', 'tank_value', 'crrt', false);
    pll_register_string('EnginePower', 'engine_power', 'crrt', false);
    pll_register_string('Roof', 'roof', 'crrt', false);
    pll_register_string('SteeringSide', 'steering_side', 'crrt', false);
    pll_register_string('WindowLifters', 'window_lifters', 'crrt', false);

    pll_register_string('IsAir', 'is_air', 'crrt', false);
    pll_register_string('IsElectropackage', 'is_electropackage', 'crrt', false);
    pll_register_string('HeatedSeatsFront', 'heated_seats_front', 'crrt', false);
    pll_register_string('Parktronic', 'parktronic', 'crrt', false);
    pll_register_string('AudioSystem', 'audio_system', 'crrt', false);
    pll_register_string('TvSystem', 'tv_system', 'crrt', false);
    pll_register_string('UsbSystem', 'usb_system', 'crrt', false);
    pll_register_string('ClimateControl', 'climate_control', 'crrt', false);
    pll_register_string('RainSensor', 'rain_sensor', 'crrt', false);
    pll_register_string('ParktronicBack', 'parktronic_back', 'crrt', false);
    pll_register_string('ParktronicCamera', 'parktronic_camera', 'crrt', false);
    pll_register_string('WheelAdjustment', 'wheel_adjustment', 'crrt', false);
    pll_register_string('WheelAdjustmentFull', 'wheel_adjustment_full', 'crrt', false);
    pll_register_string('VideoSystem', 'video_system', 'crrt', false);
    pll_register_string('CdSystem', 'cd_system', 'crrt', false);
    pll_register_string('FoldingSeats', 'folding_seats', 'crrt', false);
    pll_register_string('HeatedWindshield', 'heated_windshield', 'crrt', false);

    pll_register_string('Fleet', 'Fleet', 'crrt', false);
    pll_register_string('OrderNumber', 'Order number', 'crrt', false);
    pll_register_string('Start', 'Start', 'crrt', false);
    pll_register_string('End', 'End', 'crrt', false);
    pll_register_string('YouHaveBooked', 'You have booked a', 'crrt', false);

    pll_register_string('SuccessMessage', 'Success message', 'crrt', false);
    pll_register_string('ExploreMore', 'Explore More', 'crrt', false);

    pll_register_string('CarsEmpty', 'Cars empty', 'crrt', false);
}

function get_stripe_secret() {
    $key_type = carbon_get_theme_option('stripe_key_type');
    if ($key_type === 'prod') {
        $key_type = '';
    } else {
        $key_type.= '_';
    }
    $stripe_secret_key = carbon_get_theme_option($key_type.'stripe_secret_key');
    return $stripe_secret_key;
}

function check_stripe_coupon($couponCode)
{
    $stripe_api_url = 'https://api.stripe.com/v1/coupons';
    $stripe_secret_key = get_stripe_secret();

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $stripe_api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer ".$stripe_secret_key,
            "Content-Type: application/json",
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return $err;
    } else {
        return json_decode($response);
    }
}

function get_stripe_discount() {
    $secret = get_stripe_secret();
//    $data_url = 'https://api.stripe.com/v1/events?type=customer.discount.created&limit=100';
//    $data_url = 'https://api.stripe.com/v1/events';
    $data_url = 'https://api.stripe.com/v1/payment_intents';

    $headers = array(
        'Authorization' => 'Bearer ' . $secret,
    );

    $data_response = wp_remote_get($data_url, array('headers' => $headers));

    if (is_wp_error($data_response)) {
        $error_message = $data_response->get_error_message();
//        log_telegram('Error Message: ' . $error_message);
        return $error_message;
    } else {
        $data_body = wp_remote_retrieve_body($data_response);
        if (empty($data_body)) {
//            log_telegram('Empty response body.');
        } else {
//            log_telegram('Response Body: ' . $data_body);
            $data = json_decode($data_body, true);
            return $data;
        }
    }
}

function get_stripe_checkout($id) {
    $secret = get_stripe_secret();
    $data_url = "https://api.stripe.com/v1/checkout/sessions/{$id}";

    $headers = array(
        'Authorization' => 'Bearer ' . $secret,
    );

    $data_response = wp_remote_get($data_url, array('headers' => $headers));

    if (is_wp_error($data_response)) {
        $error_message = $data_response->get_error_message();
//        log_telegram('Error Message: ' . $error_message);
        return $error_message;
    } else {
        $data_body = wp_remote_retrieve_body($data_response);
        if (empty($data_body)) {
//            log_telegram('Empty response body.');
        } else {
//            log_telegram('Response Body: ' . $data_body);
            $data = json_decode($data_body, true);
            return $data;
        }
    }
}
function get_stripe_payment_intend($id) {
    $secret = get_stripe_secret();
    $data_url = "https://api.stripe.com/v1/payment_intents/{$id}";

    $headers = array(
        'Authorization' => 'Bearer ' . $secret,
    );

    $data_response = wp_remote_get($data_url, array('headers' => $headers));

    if (is_wp_error($data_response)) {
        $error_message = $data_response->get_error_message();
//        log_telegram('Error Message: ' . $error_message);
        return $error_message;
    } else {
        $data_body = wp_remote_retrieve_body($data_response);
        if (empty($data_body)) {
//            log_telegram('Empty response body.');
        } else {
//            log_telegram('Response Body: ' . $data_body);
            $data = json_decode($data_body, true);
            return $data;
        }
    }
}

function send_promocode_sheet($data) {
    $form_id = carbon_get_theme_option('coupon_sheets_form_id');
    $partial_response = carbon_get_theme_option('coupon_sheets_partial_response');
    $fbzx = carbon_get_theme_option('coupon_sheets_fbzx');

    if (empty($form_id) || empty($partial_response) || empty($fbzx)) return;

//    $url = "https://docs.google.com/forms/d/e/{$form_id}/formResponse";
    $url = "https://docs.google.com/forms/u/0/d/e/{$form_id}/formResponse";


    $post_data = array (
        "entry.189357892" =>  $data['pi_id'],
        "entry.1681842955" => $data['coupon_name'],
        "entry.1689304715" => $data['percent_off'],
        "entry.558131768" =>  $data['amount'],
        "entry.1400895144" => $data['user_phone'],
        "entry.1124245593" => $data['user_email'],
        "entry.768094752" =>  $data['user_name'],
//        'draftResponse' => $partial_response,
        'partialResponse' => $partial_response,
        "pageHistory" => "0",
        "fbzx" => $fbzx,
        "fvv" => "1",
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
//        log_telegram('Ошибка cURL: ' . curl_error($ch));
    } else {
//        log_telegram('Данные успешно отправлены в Google Forms!');
    }

    curl_close($ch);
}