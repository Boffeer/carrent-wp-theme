<?php

//use Carbon_Fields\Container;
//use Carbon_Fields\Field;

require_once THEME_INC . '/crrt/Car.php';
require_once THEME_INC . '/crrt/CheckoutSession.php';
require_once THEME_INC . '/crrt/Client.php';
require_once THEME_INC . '/crrt/DatesRange.php';
require_once THEME_INC . '/crrt/PriceCalculator.php';
require_once THEME_INC . '/crrt/Product.php';
require_once THEME_INC . '/crrt/Reservation.php';
require_once THEME_INC . '/crrt/Stripe.php';

class Order
{
    private $car_post_id;
    private $car_crm_id;

    private $date_start;
    private $date_end;
    private $location_start;
    private $location_end;
    private $flight_number;

    private $user_phone;
    private $user_email;

    private $options;


    private $cancel_page;
    private $currency;
    private $agree;
    private $date_of_birth;

    private $product;

    public function __construct($post_data) {
        $this->car_post_id = $post_data['post_id'];
        $this->car_crm_id = carbon_get_post_meta($this->car_post_id, 'rentprog_id');

        $this->date_start = $post_data['date_start'] . ' ' . $post_data['time_start'];
        $this->date_end = $post_data['date_end'] . ' ' . $post_data['time_end'];
        if (empty($this->date_end)) {
            $this->date_end = $this->date_start;
        }

        $this->user_phone = $post_data['user_phone'];
        $this->user_email = $post_data['user_email'];

        $this->location_start = $post_data['location_start'] ?? '';
        $this->location_end = $post_data['location_end'] ?? $post_data['location_start'];

        $this->flight_number = empty($post_data['flight_number']) ? '' . $post_data['flight_number'] : '';

        $this->cancel_page = $post_data['cancel_page'];
        $this->currency = $this->getCurrencyCode(carbon_get_theme_option('currency'));

        $this->setOptions($post_data['options']);

        $this->agree = $post_data['agree'];
        $this->date_of_birth = $post_data['dob'];
    }

    public function getDates(): array
    {
        return array(
            'start' => $this->date_start,
            'end' => $this->date_end,
        );
    }

    public function getCurrencyCode($currencyAbstract): string
    {
        $currencies = array(
            'eur' => 'eur',
            '€' => 'eur',
            'usd' => 'usd',
            '$' => 'usd',
        );

        return $currencies[$currencyAbstract];
    }
    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCarId(): string
    {
        return $this->car_post_id;
    }

    public function getProductTariff($prices, $extraHoursPrice, $dateStart, $dateEnd): array
    {
        $bookingDuration = DateHelper::getDatesDuration($dateStart, $dateEnd);

        $price_index = 0;
        if ($bookingDuration['full_days'] < 4) {
            $price_index = 0;
        } elseif ($bookingDuration['full_days'] < 8) {
            $price_index = 1;
        } elseif ($bookingDuration['full_days'] < 16) {
            $price_index = 2;
        } elseif ($bookingDuration['full_days'] < 31) {
            $price_index = 3;
        } else {
            $price_index = 4;
        }
        $pricePerDay = $prices[$price_index] ?? end($prices);

        if ($bookingDuration['full_days'] === 0) {
            $bookingDuration['full_days'] = 1;
        }

        $fullDaysPrice = $pricePerDay  * $bookingDuration['full_days'];

        $fullDaysText = declension($bookingDuration['full_days'], ['day', 'days', 'days']);
        $extraHoursText = '';
        if ($bookingDuration['extra_hours'] > 0) {
            $extraHoursPrice = $extraHoursPrice * $bookingDuration['extra_hours'];
            $extraHoursText = declension($bookingDuration['extra_hours'], ['hour', 'hours', 'hours']);
        }

        return array(
            'per_day' => $pricePerDay,
            'price' => $fullDaysPrice + $extraHoursPrice,
            'full_days' => $bookingDuration['full_days'],
            'extra_hours' => $bookingDuration['extra_hours'],
            'full_days_text' => $fullDaysText,
            'extra_hours_text' => $extraHoursText,
            'price_index' => $price_index,
        );
    }

    public function getCar(): array
    {
        $crm_id = carbon_get_post_meta($this->car_post_id, 'rentprog_id');
        $name = carbon_get_post_meta($this->car_post_id, 'car_name');
        $prices = explode(',', carbon_get_post_meta($this->car_post_id, 'prices'));
        $extraHoursPrice = carbon_get_post_meta($this->car_post_id, 'franchise');
        $images = [get_image_url_by_id(carbon_get_post_meta($this->car_post_id, 'photos')[0])];

        $tariff = $this->getProductTariff($prices, $extraHoursPrice, $this->date_start, $this->date_end);
        $tariff['name'] = $name;
        $this->product = $tariff;

        $name = "{$name} ({$tariff['full_days_text']}";
        if ($tariff['extra_hours'] > 0) {
            $name .= " and {$tariff['extra_hours_text']}";
        }
        $name .= ")";

        $product = array(
            'name' => $name,
            'price' => $tariff['price'] * 100,
            'images' => $images,
            'description' => '',
        );

        if ($this->location_start) {
            $product['description'] .= "{$this->location_start}, ";
        }

        $product['description'] .= "{$this->date_start} - {$this->date_end}";

        if ($this->location_end) {
            $product['description'] .= " {$this->location_end}";
        }

        /*
        if ($this->options_string) {
            $product['description'] .= $this->options_string;
        }
        if ($this->date_of_birth) {
            $product['description'] .= $this->date_of_birth;
        }
        if ($this->agree) {
            $product['description'] .= $this->agree;
        }
        */

        return $product;
    }

    public function getCarProduct(): array
    {
        $product = $this->getCar();
        return array(
            'price_data' => [
                'currency' => $this->getCurrency(),
                'product_data' => [
                    'name' => $product['name'],
                    'images' => $product['images'],
                    'description' => $product['description'],
                ],
                'unit_amount' => $product['price'], // Amount in cents (e.g., $19.99)
            ],
            'quantity' => 1,
        );
    }

    public function setOptions($post_options) {

        /*
         * array(
         *      array(
         *          name => string,
         *          prices => csv
         *      )
         * )
         */
        $available_options = carbon_get_post_meta($this->car_post_id, 'car_options');
        $selected_options_names = explode(',', $post_options);

        $selected_options = array();

        foreach ($available_options as $available_option) {
            if (!in_array($available_option['name'], $selected_options_names)) continue;

            $selected_option_prices = explode(',', $available_option['prices']);
            $current_option = $this->getProductTariff($selected_option_prices, 0, $this->date_start, $this->date_end);
            $current_option['name'] = $available_option['name'];
            $selected_options[] = $current_option;
        }

        $this->options = $selected_options;
    }
    public function getOptions() {
        return $this->options;
    }

    public function getOptionsText(): string
    {
        $options_strings = [];
        foreach ($this->getOptions() as $option) {
            $options_strings[] = "{$option['name']} {$option['price']}{$this->getCurrency()}";
        }
        return implode(', ', $options_strings);
    }

    public function getOptionsProducts(): array
    {
        $cart_options = array();
        foreach ($this->getOptions() as $option) {
            $cart_options[] = [
                'price_data' => [
                    'currency' => $this->getCurrency(),
                    'product_data' => [
                        'name' => $option['name'],
                        'description' => $option['full_days_text'] . ' ' . $option['extra_hours_text'],
                    ],
                    'unit_amount' => $option['price'] * 100, // Amount in cents (e.g., $19.99)
                ],
                'quantity' => 1,
            ];
        }

        return $cart_options;
    }

    public function getUser(): array
    {
        return array(
            'phone' => $this->user_phone,
            'email' => $this->user_email,
        );
    }

    public function getLocations(): array
    {
        return array(
            'start' => $this->location_start,
            'end' => $this->location_end
        );
    }

    public function getFlightNumber(): string
    {
        return $this->flight_number;
    }

    public function getCancelPage(): string
    {
        return $this->cancel_page;
    }

    public function getAgree() {
        return $this->agree;
    }
    public function getDateOfBirth() {
        return $this->date_of_birth;
    }

    public function getMetaData($bookingId): array
    {
        $user = $this->getUser();
        $dates = $this->getDates();
        $locations = $this->getLocations();
        return array(
            'user_phone' => $user['phone'],
            'user_email' => $user['email'],
            'product_id' => $this->getCarId(),
            'booking_id' => $bookingId,
            'date_start' => $dates['start'],
            'date_end' => $dates['end'],
//            'time_start' => $time_start,
//            'time_end' => $time_end,
            'location_start' => $locations['start'],
            'location_end' => $locations['end'],
            'flight_number' => $this->getFlightNumber(),
            'options' => $this->getOptionsText(),
            'agree' => $this->getAgree(),
            'date_of_birth' => $this->getDateOfBirth(),
        );
    }

    public function getTotal(): array
    {
        $cartItems = array(
            $this->product,
        );
        $cartItems = array_merge($cartItems, $this->options);

        $cart = array(
            'per_day' => 0,
            'price' => 0,
            'full_days' => 0,
            "extra_hours" => 0,
            "full_days_text" => "",
            "extra_hours_text" => "",
            'price_index' => 0,
            'currency' => carbon_get_theme_option('currency'),
        );

        foreach ($cartItems as $cartItem) {
            $cart['per_day'] += $cartItem['per_day'];
            $cart['price'] += $cartItem['price'];

            if ($cart['full_days_text'] !== "") continue;

            $cart['price_index'] = $cartItem['price_index'];
            $cart['full_days'] = $cartItem['full_days'];
            $cart['extra_hours'] = $cartItem['extra_hours'];
            $cart['full_days_text'] = $cartItem['full_days_text'];
            $cart['extra_hours_text'] = $cartItem['extra_hours_text'];
        }

        return $cart;
    }
}

class Booking
{
    private $booking_post_id;
    private $paymentSessionId;

    public function __construct(Order $order) {
        $user = $order->getUser();
        $title = "Пользователь начал бронирование {$user['phone']} - {$user['email']}";
        $post_data = array(
            'post_title' => $title, // Заголовок вашего кастомного поста
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'car_booking', // Тип вашего кастомного поста
        );
        $this->booking_post_id = wp_insert_post($post_data);


        carbon_set_post_meta( $this->booking_post_id, 'booking_id', $this->booking_post_id);
        carbon_set_post_meta( $this->booking_post_id, 'phone', $user['phone']);
        carbon_set_post_meta( $this->booking_post_id, 'email', $user['email']);

        $locations = $order->getLocations();
        carbon_set_post_meta( $this->booking_post_id, 'location_start', $locations['start']);
        carbon_set_post_meta( $this->booking_post_id, 'location_end', $locations['end']);

        $dates = $order->getDates();
        carbon_set_post_meta( $this->booking_post_id, 'date_start', $dates['start']);
        carbon_set_post_meta( $this->booking_post_id, 'date_end', $dates['end']);

        carbon_set_post_meta( $this->booking_post_id, 'product_id', $order->getCarId());
        carbon_set_post_meta( $this->booking_post_id, 'flight_number', $order->getFlightNumber());
        carbon_set_post_meta( $this->booking_post_id, 'options', $order->getOptionsText());

        carbon_set_post_meta( $this->booking_post_id, 'agree', $order->getAgree());
        carbon_set_post_meta( $this->booking_post_id, 'date_of_birth', $order->getDateOfBirth());
    }

    public static function getBookingPostByCheckoutSessionId($session_id) {
        $args = array(
            'post_type' => 'car_booking',
            'meta_query' => array(
                array(
                    'key' => 'payment_session_id',
                    'value' => $session_id,
                    'compare' => '='
                )
            )
        );

        $post_id = null;
        $posts = get_posts($args);
        if ($posts) {
            foreach ($posts as $post) {
                $post_id = $post->ID;
            }
        }

        return $post_id;
    }

    public function getBookingPostId() {
        return $this->booking_post_id;
    }

    public function setPaymentSessionId($id) {
           $this->paymentSessionId = $id;
           carbon_set_post_meta( $this->booking_post_id, 'payment_session_id', $id);
    }

    public function setMetaData(Order $order) {
        carbon_set_post_meta( $post_id, 'name', $name);
        carbon_set_post_meta( $post_id, 'created', $created);
        carbon_set_post_meta( $post_id, 'amount', $amount);
        carbon_set_post_meta( $post_id, 'id', $event_id);
        carbon_set_post_meta( $post_id, 'json', json_encode($event_json));
        carbon_set_post_meta( $post_id, 'payment_intent', $payment_intent);

        carbon_set_post_meta( $post_id, 'time_start', $time_start);
        carbon_set_post_meta( $post_id, 'time_end', $time_end);
        carbon_set_post_meta( $post_id, 'receipt_url', $receipt_url);



        $booking = create_booking($post_id);

        $payment = create_payment($booking['booking']['id'], (int) $amount / 100);

        carbon_set_post_meta( $post_id, 'crm_booking_id', $payment['ids']);
        carbon_set_post_meta( $post_id, 'crm_booking_id', $booking['booking']['id']);
    }
}


add_action('wp_ajax_get_order_total', 'get_order_total');
add_action('wp_ajax_nopriv_get_order_total', 'get_order_total');
function get_order_total()
{
    $car = new Car($_POST['post_id']);
    $datesRange = new DatesRange($_POST['date_start'], $_POST['time_start'], $_POST['date_end'], $_POST['time_end']);

    $reservation = new Reservation($car, $_POST['options'], $datesRange);

    echo json_encode(array(
        'total' => $reservation->getTotal(),
        'paylink' => null,
    ), JSON_UNESCAPED_UNICODE);
    wp_die();
}

add_action('wp_ajax_get_stripe_paylink', 'get_stripe_paylink');
add_action('wp_ajax_nopriv_get_stripe_paylink', 'get_stripe_paylink');
function get_stripe_paylink()
{
    $car = new Car($_POST['post_id']);
    $datesRange = new DatesRange($_POST['date_start'], $_POST['time_start'], $_POST['date_end'], $_POST['time_end']);

    $locations = array(
        'start'  => $_POST['location_start'],
        'end'  => $_POST['location_start'],
    );
    if (isset($_POST['location_end'])) {
        $locations['end'] = $_POST['location_end'];
    }
    $client = new Client(
        $_POST['user_phone'],
        $_POST['user_email'],
        $_POST['dob'],
        $_POST['agree'],
        $_POST['flight_number'],
        $locations
    );
    $reservation = new Reservation($car, $_POST['options'], $datesRange);
    $reservation->setCancelPage($_POST['cancel_page']);

    $checkoutSession = new CheckoutSession($reservation, $client);
    $checkoutSessionData = $checkoutSession->create();


    echo json_encode(array(
//        'session' => $checkoutSessionData,
        'total' => $reservation->getTotal(),
        'paylink' => $checkoutSessionData['url'],
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

//    log_telegram(json_encode($event_json));

    $checkoutSessionId = $event_json->data->object->id;
    if ($event_json->type === 'checkout.session.completed') {
        $reservationId = CheckoutSession::handleComplete($event_json);

        $booking = create_booking($reservationId);
        carbon_set_post_meta( $reservationId, 'crm_booking_id', $booking['booking']['id']);
        $amount = carbon_get_post_meta( $reservationId, 'amount');

        send_email_booking($reservationId);

        if (!empty($amount)) {
            $payment = create_payment($booking['booking']['id'], (int) $amount / 100);
        }
    }

    if ($event_json->type === 'customer.discount.created') {
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

function send_email_booking($reservationId) {

    $data = Reservation::getReservationData($reservationId);
    $car = new Car($data['car_post_id']);
    $carName = $car->getName();
    $carThumb = $car->getImage();

    $to = $data['email'];
    $subject = "#{$data['crm_booking_id']} {$carName}: {$data['start_date']} - {$data['end_date']}"; // Тема письма

    $message = '<html><body>';
    $message .= '<strong>' . pll__('Your Car booking: ', 'crrt') . " {$data['crm_booking_id']}" . '</strong><br>';
    $message .= '<img src="'.$carThumb.'" style="display: block;max-width: 600px; width: 100%;"/>';
    $message .= '<h3>'. pll__('Start', 'crrt') . '</h3>';
    $message .= '<p>'. $data['start_place'] . '</p>';
    $message .= '<p>'. $data['start_date'] . '</p><br>';

    $message .= '<h3>'. pll__('End', 'crrt') . '</h3>';
    $message .= '<p>'. $data['start_place'] . '</p>';
    $message .= '<p>'. $data['end_date'] . '</p><br>';
    $message .= '<p>'. $data['options'] . '</p><br>';

//    $IGNORE_KEYS = array(
//        'user_file',
//        'date_start',
//        'date_end',
//        'location_start',
//        'crm_booking_id',
//        'car_name',
//        'car_thumb',
//    );
//    foreach ($data as $key => $value) {
//        if (in_array($key, $IGNORE_KEYS)) continue;
//        if (empty($value)) continue;
//        $message .= '<p><strong>' . replacePostKey($key) . ':</strong> ' . $value . '</p>';
//    }
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

    $date_start = carbon_get_post_meta($id, 'date_start');
    $date_end = carbon_get_post_meta($id, 'date_end');

    $duration = DateHelper::getDatesDuration($date_start, $date_end);

    $booking = array(
        'name' => carbon_get_post_meta($id, 'name'),
        'car_id' => carbon_get_post_meta($car_post_id, 'rentprog_id'),
        'start_date' => $date_start,
        'end_date' => $date_end,
        'start_place' =>  carbon_get_post_meta($id, 'location_start'),
        'end_place' =>  carbon_get_post_meta($id, 'location_end'),
        'phone' =>  carbon_get_post_meta($id, 'phone'),
        'email' =>  carbon_get_post_meta($id, 'email'),
        'days' => $duration['full_days'],
        'additional_hours' => $duration['extra_hours'],
        'price' => (int) carbon_get_post_meta($id, 'amount') / 100,
        'receipt_url' => carbon_get_post_meta($id, 'receipt_url'),
        'flight_number' => carbon_get_post_meta($id, 'flight_number'),
        'options' => carbon_get_post_meta($id, 'options'),
        'date_of_birth' => carbon_get_post_meta($id, 'date_of_birth'),
        'agree' => carbon_get_post_meta($id, 'agree'),
    );

    $description = "";

    if ($booking['price'] == 0) {
        $description.= "Free;";
    }
    if (!empty($booking['options'])) {
        $description.= "Options: {$booking['options']}; ";
    }
    if ($booking['email']) {
        $description.= "Email: {$booking['email']}; ";
    }
    if ($booking['phone']) {
        $description.= "Phone: {$booking['phone']}; ";
    }
    if ($booking['receipt_url']) {
        $description.= "Receipt: {$booking['receipt_url']}; ";
    }
    if (!empty($booking['flight_number'])) {
        $description.= "Flight number: {$booking['flight_number']}; ";
    }
    if (!empty($booking['date_of_birth'])) {
        $description.= "Date of birth: \n\n{$booking['date_of_birth']}; ";
    }
    if (!empty($booking['agree'])) {
        $description.= "Is Agree with Privacy: \n\n{$booking['agree']}; ";
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
        "additional_hours" => $booking['additional_hours'],
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