<?php

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

if (!function_exists('get_theme_options')) {
	function get_theme_options()
	{
		return array(
            'logo' => carbon_get_theme_option('logo_text'),
            'loader' => carbon_get_theme_option('loader'),
            'emails' => carbon_get_theme_option('emails'),
			'phones' => carbon_get_theme_option('phones'),
			'phone_href' => phone_to_href(carbon_get_theme_option('phone')),
			'og_default' => carbon_get_theme_option('default_og_img'),
			'socials' => carbon_get_theme_option('socials'),
            'address' => carbon_get_theme_option('address'),
            'work_time' => carbon_get_theme_option('work_time'),
            'currency' => carbon_get_theme_option('currency'),
		);
	}
}

if (!function_exists('get_home_link')) {
	function get_home_link()
	{
		if (is_front_page()) {
			return '#';
		} else {
			return home_url();
		}
	}
}

if (!function_exists('get_yt_id')) :
	function get_yt_id($url)
	{
		$url = parse_url($url, PHP_URL_QUERY);
		parse_str($url, $query_params);
    if (!isset($query_params['v'])) return null;

		return $query_params['v'];
	}
endif;
if (!function_exists('get_yt_thumb')) :
	function get_yt_thumb($url)
	{
		$id = get_yt_id($url);
		$thumb_size = 'maxresdefault';

		return "https://img.youtube.com/vi/$id/$thumb_size.jpg";
	}
endif;

if (!function_exists('get_vimeo_thumb')) :
  function get_vimeo_thumb($video_url)
  {
    // Extract the video ID from the provided URL
    preg_match('/vimeo\.com\/([0-9]+)/', $video_url, $matches);
    $videoId = $matches[1];

    // Make a request to Vimeo API to get video information
    $apiUrl = "https://vimeo.com/api/oembed.json?url=https%3A//vimeo.com/{$videoId}";
    $response = file_get_contents($apiUrl);
    $videoInfo = json_decode($response);

    // Return the thumbnail URL from the video information
    return $videoInfo->thumbnail_url;
  }
endif;


if (!function_exists('get_video_thumb')) :
  function get_video_thumb($url)
  {
    $patterns = array(
      'youtube' => '/(youtube\.com\/(watch\?v=|embed\/)|youtu\.be\/)/i',
      'vimeo' => '/(vimeo\.com\/(video\/|channels\/|groups\/|album\/\d+\/|))(\d+)(|\/\S+)/i',
    );

    if (preg_match($patterns['youtube'], $url)) {
      return get_yt_thumb($url);
    } elseif (preg_match($patterns['vimeo'], $url)) {
      return get_vimeo_thumb($url);
    }
    // Если ссылка не является ни ссылкой на YouTube, ни ссылкой на Vimeo
    else {
      return 'Неизвестный видеохостинг';
    }
  }
endif;


if (!function_exists('get_youtube_id')) :
function get_youtube_id($url)
	{
		preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);

		echo $matches[1];
}
endif;

if (!function_exists('multi_format_thumbnail')) :
	function multi_format_thumbnail($thumbnail_url, $post, $size)
	{

			if (false == $thumbnail_url) {
				$thumbnail_url = get_field('default_product_img', 'options');
			}
			return $thumbnail_url;
	}
endif;

if (!function_exists('get_image_url_by_id')) :
		function get_image_url_by_id($id) {
			return wp_get_attachment_image_url($id, 'full');
		}
endif;

if (!function_exists('get_post_thumb')) {
	function get_post_thumb($post_id) {
		if (!has_post_thumbnail($post_id)) {
			return "";
		}

    $thumb_id = get_post_thumbnail_id($post_id);
    $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'full', true);
    $thumb = $thumb_url_array[0];
    return $thumb;
	}
}

if (!function_exists('get_carbon_association_ids')) :
		function get_carbon_association_ids($crb_posts) {

			$ids = array();
      foreach ($crb_posts as $post_position => $crb_post) {
        $ids[] = $crb_post['id'];
      }

      return $ids;
		}
endif;


if (!function_exists('get_ru_date')) :
	function get_ru_date($the_date) {
		// Создаем объект DateTime из строки с датой
		$date = new DateTime($the_date);

		// Получаем номер дня месяца
		$day = $date->format('j');

		// Получаем название месяца на русском языке
		$months = [
	    'января', 'февраля', 'марта', 'апреля',
	    'мая', 'июня', 'июля', 'августа',
	    'сентября', 'октября', 'ноября', 'декабря'
		];
		$month_name = $months[$date->format('n') - 1];

		// Склеиваем результат и выводим
		return $day . ' ' . $month_name; // выведет "4 апреля"
	}
endif;


if (!function_exists('get_post_taxonomies')) {
	function get_post_taxonomies($post_id, $post_type) {
		$taxonomies = get_object_taxonomies($post_type, 'objects');
    $result = array();

    foreach ($taxonomies as $taxonomy) {
        $taxonomy_slug = $taxonomy->name;
        $taxonomy_name = $taxonomy->label;
        $taxonomy_link = get_term_link(get_the_terms($post_id, $taxonomy_slug)[0]->term_id, $taxonomy_slug);
        $taxonomy_id = get_the_terms($post_id, $taxonomy_slug)[0]->term_id;

        $result[] = array(
          'id' => $taxonomy_id,
          'name' => $taxonomy_name,
          'url' => $taxonomy_link,
          'slug' => $taxonomy_slug,
        );
    }

    return $result;
	}
}

if ( !function_exists('get_post_type_slug') ) {
	function get_post_type_slug($post_id) {
    $post_type = get_post_type($post_id);
    $post_type_object = get_post_type_object($post_type);

    return $post_type_object->rewrite['slug'];
	}
}

if ( !function_exists('calc_reading_time') ) :
	function calc_reading_time( $content, $has_value = false ) {
      // Определяем среднюю скорость чтения (в словах в минуту)
      $average_words_per_minute = 200;

      // Удаляем лишние теги из контента статьи
      $content = strip_tags($content);

      // Получаем количество слов в контенте статьи
      $word_count = preg_match_all('/\p{L}+/u', $content, $matches);

      // Рассчитываем время чтения (в минутах)
      $reading_time = ceil($word_count / $average_words_per_minute);

	    if ($reading_time < 5) {
	    	$reading_time = '5';
	    }

	    if ($has_value == false) {
		    return $reading_time . ' мин.';
	    }

	    return $reading_time;
	}
endif;


if ( !function_exists('get_breadcrumbs') ) {
	function get_breadcrumbs($additional_calss_name = '', $has_container = false) {
		ob_start(); ?>
     <div class="breadcrumbs">
      <?php
        $post_type = get_post_type(get_the_ID());
        $post_type_object = get_post_type_object($post_type);

        $archive_name = $post_type_object->labels->name;
        $archive_link = get_post_type_archive_link($post_type);
      ?>
      <div class="<?php echo $has_container ? 'container' : ''; ?> breadcrumbs__container">
        <ul class="breadcrumbs__list">
          <li class="breadcrumbs__item">
            <a class="breadcrumbs__link"
              href="<?php echo get_home_link(); ?>"
            >
              Главная
            </a>
          </li>
          <li class="breadcrumbs__item">
            <a class="breadcrumbs__link"
              href="<?php echo $archive_link; ?>"
            >
              <?php echo $archive_name; ?>
            </a>
          </li>
          <li class="breadcrumbs__item"><?php the_title(); ?></li>
        </ul>
      </div>
    </div>
		<?php return ob_get_clean();
	}
}

if ( !function_exists('get_menu_location')) {
	function get_menu_location($location) {
    $locations = get_nav_menu_locations();
    if (isset($locations[$location])) {
      $menu_id = $locations[$location];
      // $menu_object = wp_get_nav_menu_object($menu_id);
      $menu = wp_get_nav_menu_items($menu_id);

      $menu_items = array();
      foreach ($menu as $item) {
      	$menu_items[] = array(
      		'title' => $item->title,
      		'href' => $item->url,
      	);
      }
      unset($menu);

      // Do something with $menu_object
      return $menu_items;
    } else {
        // Location not found
    	return false;
    }
	}
}
if (!function_exists('get_socials')) {
  function get_socials($socials_textarea) {
    $img_path = '/img/common.crrt/';
    $socials_config = array(
      'vimeo' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?vimeo\.com\/.*/i',
        'icon' => THEME_STATIC . $img_path . 'icon-vimeo.svg',
        'text' => 'Наш Vimeo',
      ),
      'artstation' => array(
          'pattern' => '/^(?:https?:\/\/)?(?:www\.)?artstation\.com\/[a-zA-Z0-9_-]+(?:\/)?$/',
          'icon' => THEME_STATIC . $img_path .'icon-artstation.svg',
          'text' => 'Наш Artstation',
      ),
      'linkedin' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?linkedin\.com\/.*/i',
        'icon' => THEME_STATIC . $img_path . 'icon-linkedin.svg',
          'text' => 'Наш Linkedin',
      ),
      'whatsapp' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?(m\.)?wa\.me\/.*/i',
        'icon' => THEME_STATIC . $img_path . 'icon-whatsapp.svg',
          'text' => 'Наш Whatsapp',
      ),
      'vk' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?(m\.)?vk\.com\/.*/i',
        'icon' => THEME_STATIC . $img_path . 'icon-vk.svg',
          'text' => 'Наш VK',
      ),
      'twitter' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?twitter\.com\/.*/i',
        'icon' => THEME_STATIC . $img_path . 'icon-twitter.svg',
          'text' => 'Наш Twitter',
      ),
      'email' => array(
        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'icon' => THEME_STATIC . $img_path . 'icon-email.svg',
          'text' => 'Наш Email',
      ),
      'behance' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?behance\.(net|com)\/.*/i',
        'icon' => THEME_STATIC . $img_path . 'icon-behance.svg',
          'text' => 'Наш Behance',
      ),
      'instagram' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?instagram\.com\/.+$/i',
        'icon' => THEME_STATIC . $img_path . 'icon-instagram.svg',
          'text' => 'Наш Instagram',
      ),
      'imdb' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?imdb\.com\/.+/i',
        'icon' => THEME_STATIC . $img_path . 'icon-imdb.svg',
          'text' => 'Наш imdb',
      ),
      'telegram' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?t\.me\/.+$/i',
        'icon' => THEME_STATIC . $img_path . 'icon-telegram.svg',
          'text' => 'Наш Telegram',
      ),
      'youtube' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?(youtube|youtu)\.(com|be)\/.+$/i',
        'icon' => THEME_STATIC . $img_path . 'icon-youtube.svg',
          'text' => 'Наш Youtube',
      ),
      'facebook' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?(facebook|fb)\.com\/.+$/i',
        'icon' => THEME_STATIC . $img_path . 'icon-facebook.svg',
          'text' => 'Наш Facebook',
      ),
      'discord' => array(
        'pattern' => '/^(https?:\/\/)?(www\.)?discord\.com\/.+$/i',
        'icon' => THEME_STATIC . $img_path . 'icon-discord.svg',
          'text' => 'Наш Discord',
      ),
      'globe' => array(
        'pattern' => '/.*/',
        'icon' => THEME_STATIC . $img_path . 'icon-globe.svg',
          'text' => '',
      ),
    );

    $globe_icon = THEME_STATIC . $img_path . 'icon-globe.svg';

    $socials = explode_textarea($socials_textarea);

    foreach ($socials as $key => $link) {
      if ($link == '') {
        unset($socials[$key]);
        continue;
      }
      $pattern = "/,/";
      if (preg_match($pattern, $link, $matches)) {
        $array = explode(",", $link);
        $socials[$key] = array(
            'href' => $array[0],
            'icon' => $array[1],
        );
          continue;
      }

      foreach ($socials_config as $social_key => $social) {
        if (preg_match($social['pattern'], $link, $matches)) {
          $socials[$key] = array(
              'href' => $link,
              'icon' => $social['icon'],
          );
          break;
        } else {
          $socials[$key] = array(
            'href' => $link,
            'icon' => $globe_icon,
            'text' => $social['text'],
          );
          continue;
        }
      $socials[$key]['text'] = $social['text'];
      $socials[$key]['key'] = $social[$social_key];
      }
    }

    return $socials;
  }
}
