<?php

function isCurrentUrlMatch($url) {
	$currentUrl = preg_replace('%[^A-Za-zА-Яа-я0-9]%', '', home_url( $_SERVER['REQUEST_URI'] ));
	$url = preg_replace('%[^A-Za-zА-Яа-я0-9]%', '', $url);
	return $url == $currentUrl;
}

function hasHtmlTags($string) {
    return preg_match('/<[^>]*>/', $string) === 1;
}
