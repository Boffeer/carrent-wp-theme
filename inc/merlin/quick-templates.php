<?php 

/*
	Header meta
 */
if (!function_exists('m_get_header_meta')) :
	function m_get_header_meta() {
		ob_start(); ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<meta name="format-detection" content="telephone=no" />
		<?php
			$title = get_any_page_title();
			$desc = get_any_page_desc();
			$og_image = get_page_og();
		?>
		<meta name="test" value="<?php echo $og_image?>">
		<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo get_site_url(); ?>" />
		<?php if ($title) : ?>
			<title><?php echo $title; ?></title>
			<meta property="og:title" content="<?php echo $title; ?>" />
		<?php endif; ?>
		<?php if ($desc) : ?>
			<meta name="description" content="<?php echo $desc; ?>">
			<meta property="og:description" content="<?php echo $desc; ?>" />
		<?php endif; ?>
		<?php if ($og_image) : ?>
			<meta property="og:image" content="<?php echo $og_image; ?>" />
            <meta name="twitter:card" content="summary_large_image">
        <?php endif; ?>
        <meta name="msapplication-TileColor" content="#1A1A1A">
        <meta name="theme-color" content="#1A1A1A">
		<?php return ob_get_clean();
	}
endif;

if ( !function_exists('get_any_page_title') ) :
	function get_any_page_title() {
		if (is_home()) { // Проверяем, является ли текущая страница домашней страницей
	        $title = get_bloginfo('name'); // Получаем заголовок сайта
	    } elseif (is_search()) { // Проверяем, является ли текущая страница страницей поиска
	        $title = sprintf(__('Поиск: %s', 'textdomain'), get_search_query()); // Получаем заголовок страницы поиска
	    } elseif (is_category()) { // Проверяем, является ли текущая страница страницей категории
	        $title = single_cat_title('', false); // Получаем заголовок текущей категории
	    } elseif (is_tag()) { // Проверяем, является ли текущая страница страницей метки
	        $title = single_tag_title('', false); // Получаем заголовок текущей метки
	    } elseif (is_author()) { // Проверяем, является ли текущая страница страницей автора
	        $title = get_the_author_meta('display_name'); // Получаем имя автора
	    } elseif (is_day()) { // Проверяем, является ли текущая страница страницей конкретного дня
	        $title = sprintf(__('Архивы по дням: %s', 'textdomain'), get_the_date()); // Получаем заголовок страницы с архивами по дням
	    } elseif (is_month()) { // Проверяем, является ли текущая страница страницей конкретного месяца
	        $title = sprintf(__('Архивы по месяцам: %s', 'textdomain'), get_the_date('F Y')); // Получаем заголовок страницы с архивами по месяцам
	    } elseif (is_year()) { // Проверяем, является ли текущая страница страницей конкретного года
	        $title = sprintf(__('Архивы по годам: %s', 'textdomain'), get_the_date('Y')); // Получаем заголовок страницы с архивами по годам
	    } elseif (is_single()) { // Проверяем, является ли текущая страница отдельной записью
	        $title = get_the_title(); // Получаем заголовок отдельной записи
	    } elseif (is_page()) { // Проверяем, является ли текущая страница отдельной страницей
	        $title = get_the_title(); // Получаем заголовок отдельной страницы
	    } else {
	        $title = get_bloginfo('name'); // Если текущая страница не соответствует ни одному из вышеуказанных условий, то используем заголовок сайта
	    }
	    return $title;
	}
endif;

if ( !function_exists('get_page_og') ) :
	function get_page_og() {
		$og = carbon_get_theme_option('default_og_img');

		if (is_single()) {
			$og = get_post_thumb(get_the_ID());
		}

		if ($og == '') {
			return false;
		}

		return get_image_url_by_id($og);
	}
endif;

if ( !function_exists('get_any_page_desc') ) :
	function get_any_page_desc() {
		if (is_home()) { // Проверяем, является ли текущая страница домашней страницей
        $description = get_bloginfo('description'); // Получаем описание сайта
    } elseif (is_category() || is_tag() || is_tax()) { // Проверяем, является ли текущая страница страницей категории, метки или таксономии
        $term = get_queried_object(); // Получаем объект текущей категории, метки или таксономии
        if ($term && isset($term->description)) { // Если у текущей категории, метки или таксономии есть описание
            $description = $term->description; // Используем его
        }
    } elseif (is_author()) { // Проверяем, является ли текущая страница страницей автора
        $author_id = get_queried_object_id(); // Получаем ID текущего автора
        $description = get_the_author_meta('description', $author_id); // Получаем описание текущего автора
    } elseif (is_search()) { // Проверяем, является ли текущая страница страницей поиска
        $description = sprintf(__('Результаты поиска: %s', 'textdomain'), get_search_query()); // Получаем описание страницы поиска
    } elseif (is_single()) { // Проверяем, является ли текущая страница отдельной записью
        $post_id = get_queried_object_id(); // Получаем ID текущей записи
        $description = get_post_meta($post_id, '_yoast_wpseo_metadesc', true); // Получаем описание записи, если оно есть
    } elseif (is_page()) { // Проверяем, является ли текущая страница отдельной страницей
        $page_id = get_queried_object_id(); // Получаем ID текущей страницы
        $description = get_post_meta($page_id, '_yoast_wpseo_metadesc', true); // Получаем описание страницы, если оно есть
    } else {
        $description = ''; // Если текущая страница не соответствует ни одному из вышеуказанных условий, то используем пустое описание
    }
    return $description;
	}
endif;

/*
 	Yoast breadcrumbs
 */
if (false) :
function get_breadcrumbs()
{
	ob_start();
	$breadcrumbs = '';
	if (function_exists('yoast_breadcrumb')) {
		$breadcrumbs = yoast_breadcrumb('<ul class="breadcrumbs__list">', '</ul>', false);
		$search  = [
			'<span><a href',
			'</a></span>',
			'<span><span>',
			'<span class="breadcrumb_last" aria-current="page"',
			'</span></ul>',
			'<span>',
		];
		$replace = [
			'<li class="breadcrumbs__item"><a href',
			' </a></li>',
			'',
			'<li class="breadcrumbs__item"',
			'</ul>',
			'</span>',
		];
		$breadcrumbs  = str_replace($search, $replace, $breadcrumbs);
	}
?>
	<div class="breadcrumbs container">
		<?php echo $breadcrumbs; ?>
	</div>
<?php
	return ob_get_clean();
}
endif;

/*
 	Multilevel menu
 */
if (!function_exists('get_multilevel_menu') && false) :
	function get_multilevel_menu($menu_id, $depth = 4) {
		$header_menu_id = $menu_id;
		$header_menu_items =  wp_get_nav_menu_items($header_menu_id, [
			'output_key'  => 'menu_order',
			'depth' => $depth,
		]);

		$top_menu = array();
		$relations = array();
		$has_children = array();
		foreach ($header_menu_items as $key => $item) {
			if (0 == $item->menu_item_parent) {
				$top_menu[] = $item;
			}
			foreach ($header_menu_items as $key_child => $child) {
				if ($item->db_id == $child->menu_item_parent) {
					$relations[$child->menu_item_parent][] = $child->db_id;
				}
			}
		}

		$submenus = array();
		foreach ($header_menu_items as $key => $item) {
			if (!in_array($item, $top_menu)) {
				$submenus[] = $item;
			}
		}

		foreach ($relations as $key => $item) {
			$has_children[] = $key;
		}

		foreach ($header_menu_items as $key => $item) {
			$current_item = $item;
			$header_menu_items[$key] = array(
				'item' => $item,
			);
			if (isset($relations[$item->db_id])) {
				$header_menu_items[$key]['children'] = $relations[$item->db_id];
			}
		}
		return $header_menu_items;
	}
endif;


/*
	Search hints
 */
if (!function_exists('get_search_hints')) :
function get_search_hints()
{
		if ($_GET['s'] == '') {
			return;
		}

		$max_hints_count = 8;

		$args = array(
			's' => wp_slash($_GET['s']),
			'post_type' => 'product',
			'posts_per_page' => $max_hints_count,
		);
		$hints = get_posts($args);
		// echo json_encode($hints);

		ob_start(); ?>
		<?php if (count($hints) > 0) : ?>
			<?php foreach ($hints as $hint) : ?>
				<a href="<?php the_permalink($hint->ID); ?>" class="search-form__item"><?php echo get_the_title($hint->ID); ?></a>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="search-form__item">Ничего не найдено</div>
		<?php endif; ?>
<?php echo ob_get_clean();
		wp_die();
}
endif;
