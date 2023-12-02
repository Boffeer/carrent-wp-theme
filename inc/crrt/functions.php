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
    $user_phone = $_POST['user_phone'];
    $user_email = $_POST['user_email'];


    $car = array(
        'crm_id' => carbon_get_post_meta($post_id, 'rentprog_id'),
        'name' => carbon_get_post_meta($post_id, 'car_name'),
        'prices' => explode(',', carbon_get_post_meta($post_id, 'prices')),
    );

    $car['price'] = get_price_per_day($car['prices'], $date_start, $date_end);
    unset($car['prices']);

    $currencies = array(
        'eur' => 'eur',
        '€' => 'eur',
        'usd' => 'usd',
        '$' => 'usd',
    );
    $currency = $currencies[carbon_get_theme_option('currency')];

    $product_info = array(
        'name' => "{$car['name']} (" . count($car['price']['range']) . " days)",
        'price' => $car['price']['total'] * 100,
    );

    $domain = $_SERVER['SERVER_NAME'];
    $stripe_secret_key = carbon_get_theme_option('stripe_secret_key');
    $stripe_api_url = 'https://api.stripe.com/v1/checkout/sessions';

    // Set your Stripe secret key
    $headers = [
        'Authorization: Bearer ' . $stripe_secret_key,
        'Content-Type: application/x-www-form-urlencoded',
    ];
    $data = [
        'payment_method_types' => ['card'],
        'line_items' => [
            [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $product_info['name'],
                    ],
                    'unit_amount' => $product_info['price'], // Amount in cents (e.g., $19.99)
                ],
                'quantity' => 1,
            ],
        ],
        'mode' => 'payment',
        'success_url' => "https://{$domain}/success",
        'cancel_url' => "https://{$domain}/cancel",
        'payment_intent_data' => [
            'metadata' => [
                'user_phone' => $user_phone, // Добавляем телефонный номер в метаданные
            ],
        ],
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
        'post_id' => $post_id,
        'session_data' => $session_data,
        'paylink' => $paylink,
        'car' => $car,
        'date_start' => $date_start,
        'date_end' => $date_end,
    ), JSON_UNESCAPED_UNICODE);
    wp_die();
}

function get_price_per_day($prices, $date_start, $date_end) {
    $date_range = get_dates_range($date_start, $date_end);
//    $date_range = getDatesRange($date_start, $date_end);
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

    $price_per_day = $prices[$price_index];
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



    if ($event_json->type === 'checkout.session.async_payment_succeeded' || $event_json->type === 'checkout.session.completed') {
        $email = $event_json->data->object->customer_details->email;
        $phone = $event_json->data->object->client_reference_id;
        $name = $event_json->data->object->customer_details->name;
        $id = $event_json->id;
        $payment_intent = $event_json->data->object->payment_intent;
        $amount_total = $event_json->data->object->amount_total;
        $created = $event_json->data->object->created;


        $title = $phone .' - '. $name .' - '. $email .' - '. $payment_intent;
        $post_data = array(
            'post_title' => $title, // Заголовок вашего кастомного поста
            'post_content' => print_r($event_json, true),
            'post_status' => 'publish',
            'post_type' => 'car_booking', // Тип вашего кастомного поста
        );

        // Создайте кастомный пост
        $post_id = wp_insert_post($post_data);

        if (!is_wp_error($post_id)) {
            // Set the post slug to be the post ID
            $post_data = array(
                'ID'  => $post_id,
                'post_name' => $payment_intent,
            );
            wp_update_post($post_data);
        }

        // Выполните другие действия, если необходимо
        carbon_set_post_meta( $post_id, 'phone', $phone);
        carbon_set_post_meta( $post_id, 'email', $email);
        carbon_set_post_meta( $post_id, 'name', $name);
        carbon_set_post_meta( $post_id, 'created', $created);
        carbon_set_post_meta( $post_id, 'amount_total', $amount_total);
        carbon_set_post_meta( $post_id, 'id', $id);
    }

    // Отправьте ответ, чтобы подтвердить успешное получение данных
    status_header(200);
    die();
}
