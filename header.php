<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package crrt
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php wp_head(); ?>
    <?php echo m_get_header_meta(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="wrapper">

    <?php
        $socials = get_socials(THEME_OPTIONS['socials']);
        $phone = explode_textarea(THEME_OPTIONS['phones'])[0];
        $logo = THEME_OPTIONS['logo'];
    ?>

    <header class="header">
        <div class="container header__container">
            <a href="/" class="header__logo logo">
                <!-- <img src="<?php echo THEME_STATIC; ?>/img/common/logo-header.svg" alt="" class="logo__img"> -->
                <?php echo $logo; ?>
            </a>


            <div class="header__menu menu">
                <nav class="menu__nav">
                    <ul class="menu__nav-links">
                        <?php
                            $header_menu = get_menu_location('nav-burger');
                            $languages = pll_the_languages(array('raw' => 1));
                        ?>

                        <?php foreach ($header_menu as $menu) : ?>
                            <?php
                                $has_children = $menu['href'] === '#cars';
                                $is_lang = $menu['href'] === '#languages';
                            ?>
                            <li class="menu__nav-item <?php echo $has_children || $is_lang ? 'menu__nav-item--has-children' : ''; ?>">
                                <a href="<?php echo $menu['href']; ?>" class="menu__nav-link"><?php pll_e($menu['title'], 'crrt'); ?></a>
                                <?php if ($has_children) : ?>
                                <div class="menu__nav-dropdown">
                                    <div class="menu__nav-dropdown-content">
                                    <?php
                                    $cars_args = array(
                                        'post_type'      => 'cars',
                                        'posts_per_page' => -1,
                                        'orderby'        => 'date',
                                        'order'          => 'DESC',
                                        'post_status'    => 'publish',
                                    );
                                    $cars = new WP_Query($cars_args);
                                    ?>
                                    <?php if ($cars->have_posts()) : ?>
                                        <?php while ($cars->have_posts()) : $cars->the_post(); ?>
                                            <a href="<?php the_permalink(); ?>" class="menu__nav-link menu__nav-dropdown-link">
                                                <?php echo carbon_get_the_post_meta('car_name'); ?>
                                            </a>
                                        <?php endwhile; wp_reset_query(); ?>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <?php elseif($is_lang) : ?>
                                    <?php if (!empty($languages)) : ?>
                                    <div class="menu__nav-dropdown">
                                        <div class="menu__nav-dropdown-content">
                                            <?php foreach ($languages as $language) : ?>
                                                <?php
                                                $name = $language['name'];
                                                $url = $language['url'];
                                                ?>
                                                <a href="<?php echo esc_url($url); ?>" class="menu__nav-link menu__nav-dropdown-link <?php echo $language['current_lang'] ? 'active' : '';?>">
                                                    <?php echo esc_html($name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>

            <div class="header__contacts">
                <a href="<?php echo phone_to_href($phone); ?>" class="header__contacts-phone">
                    <svg class="header__contacts-icon">
                        <use xlink:href="<?php echo THEME_STATIC; ?>/img/common.crrt/icon-phone.svg#icon-phone" />
                    </svg>
                    <span class="header__contacts-content">
                      <span class="header__contacts-label"><?php pll_e('Call us', 'crrt'); ?></span>
                      <span class="header__contacts-text"><?php echo $phone; ?></span>
                    </span>
                </a>

                <div class="socials header__socials">
                    <?php foreach ($socials as $social) : ?>
                        <a class="socials__link socials__link--<?php echo $social['key']; ?>" aria-label="<?php echo isset($social['text']) ? $social['text'] : ''; ?>"
                           href="<?php echo $social['href']; ?>" target="_blank" rel="noopener noreferrer"
                        >
                            <img class="socials__icon" src="<?php echo $social['icon']; ?>" alt="<?php echo isset($social['text']) ? $social['text'] : ''; ?>">
                        </a>
                    <?php endforeach; ?>
                </div>

            </div>

            <button class="burger header__burger" id="burger">
                <span class="burger__line"></span>
                <span class="burger__line"></span>
                <span class="burger__line"></span>
            </button>
        </div>
    </header>

    <main class="main">
