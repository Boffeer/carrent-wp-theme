<?php

if ( !function_exists('m_get_form_params')) :
	function m_get_form_params($post_object) {
		// $post_object have to be passed $_POST
		$params = array(
			's' => stripslashes($post_object['s']),
			'category'       => stripslashes($post_object['category']),
			'taxonomy'       => stripslashes($post_object['taxonomy']),
			'post_type'      => stripslashes($post_object['post_type']),
			'offset'         => stripslashes($post_object['offset']),
			'is_more_button' => stripslashes($post_object['is_more_button']),
			'posts_per_page' => stripslashes($post_object['posts_per_page']),
			'action' => stripslashes($post_object['action']),
      'post_status' => 'publish',
      'post__not_in' => stripslashes($post_object['exclude']),
		  // 'posts_per_page' => 2, // для отладки
		);

		if ($params['category'] == 'on') {
			$params['category'] = null;
		}

		return $params;
	}
endif;

if ( !function_exists('m_get_query_args')) :
	function m_get_query_args($params) {

		$args = array(
		  's' => $params['s'],
		  'post_type' => $params['post_type'],
		  'posts_per_page' => $params['posts_per_page'], // для отладки
		);

		if ($params['category'] != null) :
			$args['tax_query'] = array(
				array(
					'taxonomy' => $params['taxonomy'],
		      'field'    => 'term_id',
		      'terms'    => $params['category'],
				),
			);
		endif;

		if (isset($params['offset']) && $params['is_more_button'] == 'true') :
			$args['offset'] = $params['offset'];
		else :
			$args['offset'] = 0;
		endif;

		return $args;
	}
endif;


if ( !function_exists('m_make_result_object')) :
	function m_make_result_object($posts, $terms, $params) {
		$result = array(
			'posts' => $posts,
			'posts_count' => count($posts),
			'is_empty' => false,
			'is_more_button' => $params['is_more_button'],
		);

		if (isset($params['offset'])) :
			$result['offset'] = $params['offset'] + $result['posts_count'];
		endif;

		if ( $result['posts_count'] < $params['posts_per_page']) :
			$result['is_empty'] = true;
		endif;

		if ( empty($posts) && !$params['is_more_button'] ) :
			ob_start();
				get_template_part( 'template-parts/content-none', get_post_type() );
			$result['content'] = ob_get_clean();
		endif;

		if ($params['taxonomy']) :
			$result['terms'] = $terms;
		endif;

		if ($params['category']) :
			$result['category'] = $params['category'];
		endif;

		if ($params['post_type']) :
			$result['post_type'] = $params['post_type'];
		endif;

		return $result;
	}
endif;

