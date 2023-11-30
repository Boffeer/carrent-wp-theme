<?php

if (!defined('ABSPATH')) {
	exit; // exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'init', 'register_case_post_types' );
function register_case_post_types() {
	register_post_type( 'courses', [
		'label'  => null,
		'labels' => [
			'name'               => 'Курсы',
			'singular_name'      => 'Курс',
			'add_new'            => 'Добавить курс',
			'add_new_item'       => 'Добавление курса',
			'edit_item'          => 'Редактирование курса',
			'new_item'           => 'Новый курс',
			'view_item'          => 'Смотреть курс',
			'search_items'       => 'Искать курс',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'parent_item_colon'  => '',
			'menu_name'          => 'Курсы',
		],
		'description'            => '',
		'public'                 => true,
		'show_in_menu'           => null, // показывать ли в меню админки
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-editor-table',
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor' ],
		'taxonomies'          => [],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}

function is_field_value($field, $value) {
	return array(
		'relation' => 'AND', // Optional, defaults to "AND"
      array(
        'field' => $field,
        'value' => $value, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
	      'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
    )
	);
}

add_action('carbon_fields_register_fields', 'register_case_fields');
function register_case_fields() {

	$bold_hint = 'Окружи нужный текст [b]<b>и он будет жирным</b>[/b]';
	$hl_help_text = 'Окружи нужный текст [hl]<span style="color: #E7AE44;">и он будет цветным</span>[/hl]';

	$for_who_bullets_labels = array(
	    'plural_name' => 'буллеты',
	    'singular_name' => 'буллет',
	);
	$course_programm_labels = array(
	    'plural_name' => 'модули',
	    'singular_name' => 'модуль',
	);
	$will_learn_labels = array(
	    'plural_name' => 'модули',
	    'singular_name' => 'модуль',
	);
	$how_process_labels = array(
	    'plural_name' => 'особенности',
	    'singular_name' => 'особенность',
	);

	Container::make('post_meta', 'courses_info', 'О курсе')
		->where('post_type', '=', 'courses')
		->add_tab('Первый экран', array(
		))
		;
}

function getCaseContent($id) {
  $case = array(
    'title' => carbon_get_post_meta($id, 'case_title'),
    'hero_image' => carbon_get_post_meta($id, 'case_image'),
    'bullets_title' => carbon_get_post_meta($id, 'case_bullets_title'),
    'bullets' => explode_textarea(carbon_get_post_meta($id, 'case_bullets')),
    'button_text' => carbon_get_post_meta($id, 'case_button'),
    'content' => carbon_get_post_meta($id, 'case_slides'),
  );

  return $case;
}

function getCaseCard($id) {
	$case = getCaseContent($id);
	ob_start();
	?>
    <aritcle class="cases-card" data-aos="zoom-out-up" data-case-id="<?php echo $id?>">
      <div class="cases-card__offer">
        <h3 class="cases-card__title">
          <?php echo typograph(do_shortcode($case['title']));?>
        </h3>
        <h4 class="cases-card__subtitle"><?php echo $case['bullets_title']; ?></h4>
        <ul class="section-ul cases-card__bullets">
          <?php foreach ($case['bullets'] as $bullet) : ?>
            <li class="cases-card__bullet">
              <?php echo typograph($bullet); ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <button class="button-primary cases-card__button"
                type="button"
                data-poppa-open="case-<?php echo $id; ?>"
        >
          <span class="button__text">
            <?php echo $case['button_text']?>
          </span>
          <span class="button__shade"></span>
        </button>
      </div>
      <div class="cases-card__media">
        <picture class="cases-card__pic">
          <!-- <source data-srcset="<?php echo THEME_STATIC; ?>/img/cases/case-1-after.webp" type="image/webp"> -->
          <img src="<?php echo get_image_url_by_id($case['hero_image']); ?>" alt="<?php echo get_alt_title(); ?>" class="cases-card__img"> 
        </picture>
      </div>
    </aritcle>
  <?php
  $case_card = ob_get_clean();
  // echo $case_card;
  return $case_card;
}

function getCasePop($id) {
	$case = getCaseContent($id);
	ob_start();
	?>
	  <article class="poppa modal-case" id="case-<?php echo $id; ?>">
	    <?php foreach ($case['content'] as $slide_id => $slide) : ?>
	        <?php //get_vd($slide); ?>
	      <section class="modal-case__section">
	        <h2 class="modal-case__title section-title section-text--center">
	          <?php echo typograph(do_shortcode($slide['title'])); ?>
	        </h2>
	        <?php if ($slide['subtitle']) : ?>
	        <p class="modal-case__subtitle  section-text--center">
	          <?php echo typograph(do_shortcode($slide['subtitle'])); ?>
	        </p>
	        <?php endif; ?>


	        <?php if ($slide['case_layout'] == 'compare') : ?>
	          <div class="modal-case-comparer">
	            <?php foreach ($slide['cards'] as $card_id => $card) : ?>
	            <article class="modal-case-comparer-card">
	              <h3 class="modal-case-comparer-card__title"><?php echo $card['title']?></h3>
	              <?php if ($card['bullets_type'] === 'ul') : ?>
	                <ul class="section-ul modal-case-comparer-card__ul">
	                  <?php foreach (explode_textarea($card['ul_bullets']) as $li) : ?>
	                    <li><?php echo typograph(do_shortcode($li));?></li>
	                  <?php endforeach; ?>
	                </ul>
	              <?php elseif ($card['bullets_type'] === 'ol') : ?>
	                <ol class="section-ol modal-case-comparer-card__ol">
	                  <?php foreach (explode_textarea($card['ol_bullets']) as $li) : ?>
	                    <li><?php echo typograph(do_shortcode($li));?></li>
	                  <?php endforeach; ?>
	                </ol>
	              <?php endif; ?>
	            </article>
	            <?php endforeach; ?>
	          </div>
	        <?php elseif ($slide['case_layout'] == 'compare_pics') : ?>
	          <div class="modal-case-comparer">
	            <?php foreach ($slide['cards_pic'] as $card) : ?>
	            <article class="modal-case-comparer-card">
	              <h3 class="modal-case-comparer-card__title section-text--center">
	                <?php echo $card['title']; ?>
	              </h3>
	              <div class="modal-case-card__media-gallery">
	                <?php foreach ($card['pics'] as $pic_id) : ?>
	                  <div class="modal-case-comparer-card__media">
	                    <picture class="cases-card__pic">
	                      <img src="<?php echo get_image_url_by_id($pic_id); ?>" alt="<?php echo get_alt_title(); ?>" class="cases-card__img">
	                    </picture>
	                    <a href="<?php echo get_image_url_by_id($pic_id); ?>" data-fancybox class="modal-case-comparer-card__media-fancy"></a>
	                  </div>
	                <?php endforeach;?>
	              </div>
	            </article>
	            <?php endforeach; ?>
	          </div>
	        <?php elseif ($slide['case_layout'] == 'compare_vertical') : ?>
	          <div class="modal-case-comparer modal-case-comparer--vertical">
	            <?php foreach ($slide['cards_pic_vertical'] as $card) : ?>
	              <article class="modal-case-comparer-card">
	                <h3 class="modal-case-comparer-card__title">
	                  <?php echo $card['title']; ?>
	                </h3>
	                <div class="modal-case-card__media-gallery">
	                  <?php foreach ($card['pics'] as $pic_id) : ?>
	                    <div class="modal-case-comparer-card__media">
	                      <picture class="modal-case-comparer-card__pic cases-card__pic">
	                        <img src="<?php echo get_image_url_by_id($pic_id); ?>" alt="<?php echo get_alt_title(); ?>" class="cases-card__img modal-case-comparer-card__img">
	                      </picture>
	                      <a href="<?php echo get_image_url_by_id($pic_id); ?>" data-fancybox class="modal-case-comparer-card__media-fancy"></a>
	                    </div>
	                  <?php endforeach; ?>
	                </div>
	              </article>
	            <?php endforeach; ?>
	          </div>
	        <?php elseif ($slide['case_layout'] == 'gallery') : ?>
	          <div class="modal-case-gallery">
	            <?php foreach ($slide['gallery_pics'] as $card) : ?>
	            <div class="modal-case-gallery-card">
	              <picture class="modal-case-gallery-card__pic cases-card__pic">
	                <img src="<?php echo get_image_url_by_id($card); ?>" alt="<?php echo get_alt_title(); ?>" class="modal-case-gallery-card__img cases-card__img">
	              </picture>
	            </div>
	            <?php endforeach; ?>
	          </div>
	        <?php endif;?>
	      </section>
	    <?php endforeach; ?>
	  </article>
  <?php
  $case_popup = ob_get_clean();
  // echo $case_popup;
  return $case_popup;
}

add_action( 'wp_ajax_get_home_cases', 'get_home_cases' );
add_action( 'wp_ajax_nopriv_get_home_cases', 'get_home_cases' );
function get_home_cases() {

	$numberposts = $_POST['numberposts'];
	$iteration = $_POST['iteration'];
	$offset = (int) $numberposts * (int) $iteration;

	$exclude = json_decode(stripslashes($_POST['exclude']));

	$homepage_id = get_option('page_on_front');

	$home_cases = carbon_get_post_meta($homepage_id, 'home_cases');
	$home_cases_ids = get_carbon_association_ids($home_cases);

	$home_cases_diff = array_values(array_diff($home_cases_ids, $exclude));

	$cases = '';
	$cases_count = 0;
	foreach ($home_cases_diff as $case_position => $case_id) {
		if ($case_position >= $offset) break;

		$cases.= getCaseCard($case_id) . getCasePop($case_id);
		$cases_count++;
	}

	$is_remove_button = count($home_cases_diff) <= $offset ? true : false;

	echo json_encode(array(
		'cases' => $cases,
		'is_remove_button' => $is_remove_button,
	));

	wp_die();
}
