<?php

class Stripe
{
    public static function getSecret() {
        $key_type = carbon_get_theme_option('stripe_key_type');
        if ($key_type === 'prod') {
            $key_type = '';
        } else {
            $key_type.= '_';
        }
        return carbon_get_theme_option($key_type.'stripe_secret_key');;
    }

    public static function getCheckoutSession(Reservation $reservation, Client $client, $products, $metadata) {
        $domain = $_SERVER['SERVER_NAME'];

        $stripe_api_url = 'https://api.stripe.com/v1/checkout/sessions';
        $bookingId = $metadata['booking_id'];

        $headers = array(
            'Authorization: Bearer ' . self::getSecret(),
            'Content-Type: application/x-www-form-urlencoded',
        );


        $data = [
            'payment_method_types' => ['card'],
            'line_items' => $products,
            'mode' => 'payment',
            'success_url' => "https://{$domain}/success?car_booking_id={$bookingId}",
            'cancel_url' => $reservation->getCancelPage(),
            'payment_intent_data' => [
                'metadata' => $metadata,
            ],
            'metadata' => $metadata,
            'allow_promotion_codes' => 'true', // Enable promotion codes
            'customer_email' => $client->getEmail(),
            'client_reference_id' => $client->getPhone(),
        ];

        $post_data = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $stripe_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }
}
