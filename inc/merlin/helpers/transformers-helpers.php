<?php

if (!function_exists('explode_textarea_matrix')) :
	function explode_textarea_matrix($input)
	{
		$shift_enter = explode("\r\n\r\n", $input);
		return $shift_enter;
	}
endif;

if (!function_exists('explode_tinymc')) :
	function explode_tinymc($input)
	{
		$shift_enter = explode("\r\n\r\n", $input);
		return array_map('nl2br', $shift_enter);
	}
endif;

if (!function_exists('explode_textarea')) :
	/**
	 * Transform textarea saved as string to array where every new line is a item of the array
	 */
	function explode_textarea($input)
	{
		return explode("\n", str_replace("\r", "", $input));
	}
endif;

function phone_to_href($phone, $country =  '+7') {
    $phone = $phone == null ? '' : $phone;
    $theme_country_code = carbon_get_theme_option('phone_country_code');
    if ($country != $theme_country_code) {
        $country = $theme_country_code;
    }

	return 'tel:' . substr_replace( preg_replace( "/[^0-9]/" , '' ,  $phone) , $country , 0 , strlen($theme_country_code) - 1);
}

if ( !function_exists('hex2rgb') ) :
	function hex2rgb( $color, $alpha = false ) {
	    $color = str_replace( '#', '', $color );

	    if ( strlen( $color ) == 3 ) {
	        $r = hexdec( substr( $color, 0, 1 ) . substr( $color, 0, 1 ) );
	        $g = hexdec( substr( $color, 1, 1 ) . substr( $color, 1, 1 ) );
	        $b = hexdec( substr( $color, 2, 1 ) . substr( $color, 2, 1 ) );
	    } else {
	        $r = hexdec( substr( $color, 0, 2 ) );
	        $g = hexdec( substr( $color, 2, 2 ) );
	        $b = hexdec( substr( $color, 4, 2 ) );
	    }

	    if ($alpha) {
		    return "rgba({$r}, {$g}, {$b}, {$alpha})";
	    }

	    return "rgb({$r}, {$g}, {$b})";
	}
endif;