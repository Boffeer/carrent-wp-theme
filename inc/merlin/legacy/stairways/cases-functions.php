<?php

/*
	@params $categories_ids array
 */

add_action( 'wp_ajax_get_filtered_cases', 'get_filtered_cases' );
add_action( 'wp_ajax_nopriv_get_filtered_cases', 'get_filtered_cases' );

function get_filtered_cases() {
	$categories_ids = json_decode(stripslashes($_POST['categories']));

	$filtered_cases;

	if (in_array(-1, $categories_ids)) {
		$filtered_cases = new WP_Query( array(
	    'post_type' => 'cases',
	    'post_status' => 'publish',
	    'posts_per_page' => '-1',
		));
	} else {
		$filtered_cases = new WP_Query( array(
	    'post_type' => 'cases',
	    'post_status' => 'publish',
	    'posts_per_page' => '-1',
			'tax_query' => array(
				array(
	       'relation' => 'OR',
	        [
	            'taxonomy' => 'categories',
	            'field' => 'tag_id',
	            'terms' => $categories_ids,
	        ],
				),
			),
		));
	}

	$cases = array();
	while ($filtered_cases->have_posts()) :
		$filtered_cases->the_post();
		ob_start();
		get_template_part( 'template-parts/content-cases', get_post_type() );
	endwhile;
	echo ob_get_clean();
	wp_die();
}

add_action( 'wp_ajax_get_more_cases', 'get_more_cases' );
add_action( 'wp_ajax_nopriv_get_more_cases', 'get_more_cases' );
function get_more_cases() {
	$initial = json_decode(stripslashes($_POST['initial']));
	$offset = json_decode(stripslashes($_POST['offset']));
	$include = json_decode(stripslashes($_POST['include']));

	$more_cases = new WP_Query( array(
    'post_type' => 'cases',
    'post_status' => 'publish',
    'posts_per_page' => $offset,
    // 'offset' => $offset,
    'post__in' => $include,
    'orderby' => 'post__in',
	));

	$cases = array();
	while ($more_cases->have_posts()) :
		$more_cases->the_post();
		ob_start();?>
      <div class="projects-gallery-slide projects-gallery-carousel__slide" dataset>
			<?php get_template_part( 'template-parts/content-cases', get_post_type() ); ?>
			</div>
		<?php endwhile;
	echo ob_get_clean();
	wp_die();
}


function get_case_stats($id) {
  $case_stats = explode_textarea_matrix(carbon_get_post_meta($id, 'stats'));
  if ($case_stats[0] != '') : ob_start(); ?>
      <ul class="projects-gallery-list">
      <?php foreach($case_stats as $stat) :
          $current_stat = boffeer_explode_textarea($stat);
          if (isset($current_stat[0]) || isset($current_stat[1])) : ?>
              <li class="projects-gallery-list-element">
                  <?php echo $current_stat[0]; ?>
                  <p><?php echo isset($current_stat[1]) ? $current_stat[1] : ''; ?></p>
              </li>
          <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
<?php 
	return ob_get_clean();
	wp_die();
}

add_action( 'wp_ajax_get_case', 'get_case' );
add_action( 'wp_ajax_nopriv_get_case', 'get_case' );
function get_case() {
	$id = $_POST['id'];

	$gallery = array();
	foreach (carbon_get_post_meta($id, "gallery") as $key => $img) {
		$gallery[] = wp_get_attachment_url($img);
	}

	$gallery_HTML = '';
	foreach ($gallery as $img_key => $img_src) {
		$gallery_HTML.= "<div class=\"swiper-slide poppa-slider--slide\"><img src=\"{$img_src}\" alt=\"Слайд {$img_key}\"></div>";
	}

	// $bullets = array();
	// foreach (boffeer_explode_textarea(carbon_get_post_meta($id, "case_desc")) as $key => $bullet) {
	// 	$bullets[] = esc_html($bullet);
	// }

	$case = array(
		"gallery" => $gallery_HTML,
		"title" => carbon_get_post_meta($id, "title"),
		'stats' => get_case_stats($id),
		"desc" => carbon_get_post_meta($id, "desc"),
	);
	echo json_encode($case);
	die;
}