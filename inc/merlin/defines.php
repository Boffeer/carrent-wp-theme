<?php

define('THEME_URL', get_template_directory());
define('THEME_STATIC', get_template_directory_uri() . '/static/dist');
define('THEME_INC', THEME_URL . '/inc');
define('MERLIN_INC', THEME_INC . '/merlin');
define('FORM_URLS', array(
  'mail' => get_template_directory_uri() . "/inc/merlin/ajax/mail.php",
  'ajax' => get_site_url() . "/wp-admin/admin-ajax.php",
  )
);

define('HOMEPAGE_ID', get_option('page_on_front'));
