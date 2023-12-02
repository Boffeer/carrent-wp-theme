<?php

/**
 * Carbon Fields
 */
add_action('carbon_fields_register_fields', 'merlin_register_custom_fields');
function merlin_register_custom_fields()
{
	require MERLIN_INC . '/carbonfields/theme-options.php';
	require MERLIN_INC . '/carbonfields/common-page-meta.php';
	require MERLIN_INC . '/carbonfields/metabox.php';
	define('THEME_OPTIONS', get_theme_options());
}

/*
 	Load helpers
 */
require MERLIN_INC . '/helpers/debugger-helpers.php';
require MERLIN_INC . '/helpers/get-helpers.php';
require MERLIN_INC . '/helpers/is-helpers.php';
require MERLIN_INC . '/helpers/text-helpers.php';
require MERLIN_INC . '/helpers/transformers-helpers.php';
require MERLIN_INC . '/helpers/ajax-archive-helpers.php';
require MERLIN_INC . '/helpers/post-helpers.php';
require MERLIN_INC . '/helpers/date-helpers.php';

require MERLIN_INC . '/shortcodes.php';
require MERLIN_INC . '/quick-templates.php';

require MERLIN_INC . '/removers.php';

require MERLIN_INC . '/plugins-actions/yoast-duplicate.php';
