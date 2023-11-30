<?php

if (!function_exists('get_stair_info')) :
	function get_stair_info($id) {
        $stair_product = array(
            "title" => get_the_title($id),
            "variations" => carbon_get_post_meta($id, 'stairs_variations'),
        );
        return $stair_product;
	}
endif;