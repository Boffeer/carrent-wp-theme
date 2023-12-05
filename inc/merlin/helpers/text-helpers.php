<?php

if (!function_exists('nbsp_callback')) :
	function nbsp_callback($matches) {
		$newText = substr($matches[0], -1) == " " ? substr($matches[0], 0, -1) . "&nbsp;" : $matches[0];
		return $newText;
	}
endif;

if (!function_exists('get_nbsp_text')) :
	function get_nbsp_text($text) {
		$regexp = '/(?:^|[^а-яёА-ЯЁ0-9_])(за|из|в|без|а|до|к|я|на|но|по|о|от|что|перед|при|через|с|у|над|об|под|про|для|и|или|со)(?:^|[^а-яёА-ЯЁ0-9_])/u';

		$text = preg_replace_callback(
			$regexp,
			"nbsp_callback",
			$text
		);

		return $text;
	}
endif;

if (!function_exists('the_truncated_post')) {
	function the_truncated_post($symbol_amount = 100) {
		$filtered = strip_tags(preg_replace('@<style[^>]*?>.*?</style>@si', '', preg_replace('@<script[^>]*?>.*?</script>@si', '', apply_filters('the_content', get_the_content()))));
		return substr($filtered, 0, strrpos(substr($filtered, 0, $symbol_amount), ' ')) . '...';
	}
}

if (!function_exists('get_alt_title')) {
	function get_alt_title()
	{
		return $_SERVER['SERVER_NAME'];
	}
}

if (!function_exists('typograph')) {
	function typograph($text, $is_shortcode = true)
	{
		if ($is_shortcode) {
			return do_shortcode( nl2br(get_nbsp_text($text)) );
		}
		return esc_html(nl2br(get_nbsp_text($text)));
	}
}

if (!function_exists('declension')) {
	function declension($number, $words, $is_join = true) {
		if ( !is_numeric($number) ) return;
		
    // список окончаний для падежей
    $cases = array(2, 0, 1, 1, 1, 2);
    // определяем нужный падеж
    $case_key = ($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)];
    // выбираем нужное окончание из списка слов
    if ($is_join) {
    	return $number . " " . $words[$case_key];
    }
    return $words[$case_key];
	}
	/*
		echo declension( 0, ['урок', 'урока', 'уроков'] );
	*/
}