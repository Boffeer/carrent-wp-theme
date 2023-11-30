<?php

if (!function_exists('m_get_post_terms')) {
	function m_get_post_terms($post_id, $term_slug, $ignore_categories = array()) {
		$taxonomy = $term_slug;
		$portfolio_categories_raw = get_the_terms($post_id, $taxonomy);
		$portfolio_categories = array();

		foreach ($portfolio_categories_raw as $category) {

			if ( !empty($ignore_categories) && in_array($category->term_id, $ignore_categories)) continue;

			$portfolio_categories[] = array(
				'id' => $category->term_id,
				'name' => $category->name,
				'url' => get_term_link($category->term_id),
			);
		}
		unset($portfolio_categories_raw);

		return $portfolio_categories;
	}
}

