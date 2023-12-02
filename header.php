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
//        $phones = explode_textarea(THEME_OPTIONS['phones']);
//        $email = THEME_OPTIONS['emails'];
        $logo = THEME_OPTIONS['logo'];
    ?>

    <header class="header">
        <div class="container header__container">
            <a href="/" class="header__logo logo">
                <!-- <img src="./img/common/logo-header.svg" alt="" class="logo__img"> -->
                <?php echo $logo; ?>
            </a>

            <div class="header__menu menu">
                <nav class="menu__nav">
                    <ul class="menu__nav-links">
                        <li class="menu__nav-item active">
                            <a href="#" class="menu__nav-link">Главная</a>
                        </li>
                        <li class="menu__nav-item menu__nav-item--has-children">
                            <a href="#cars" class="menu__nav-link">Автопарк</a>
                            <div class="menu__nav-dropdown">
                                <a href="#" class="menu__nav-link menu__nav-dropdown-link">Купе</a>
                                <a href="#" class="menu__nav-link menu__nav-dropdown-link">Сенданы</a>
                                <a href="#" class="menu__nav-link menu__nav-dropdown-link">Маслкары</a>
                            </div>
                        </li>
                        <li class="menu__nav-item">
                            <a href="#reviews" class="menu__nav-link">Отзывы</a>
                        </li>
                        <li class="menu__nav-item">
                            <a href="#blog" class="menu__nav-link">Блог</a>
                        </li>
                        <li class="menu__nav-item">
                            <a href="#faq" class="menu__nav-link">Вопрос-ответ</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="header__contacts">
                <a href="#" class="header__contacts-phone">
                    <svg class="header__contacts-icon">
                        <use xlink:href="./img/common.crrt/icon-phone.svg#icon-phone" />
                    </svg>
                    <span class="header__contacts-content">
                      <span class="header__contacts-label">Общие вопросы:</span>
                      <span class="header__contacts-text">+7 (922) 506 33 22</span>
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
