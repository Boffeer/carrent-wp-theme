<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sklh
 */

get_header();
?>

<div class="breadcrumbs">
    <div class="breadcrumbs__container container">
        <ul class="breadcrumbs__list">
            <li class="breadcrumbs__item"><a href="/" class="breadcrumbs__link">Главная</a></li>
            <li class="breadcrumbs__item"><?php the_title(); ?></li>
        </ul>
    </div>
</div>

<section class="section product-hero">
    <div class="container product-hero__container">
        <div class="product-hero__main">

            <?php
                $photos = carbon_get_post_meta(get_the_ID(), 'photos')
            ?>
            <div class="product-hero__carousel">
                <div class="product-hero__gallery">
                    <div class="swiper product-hero__gallery-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($photos as $photo_id) : ?>
                            <div class="swiper-slide product-hero__gallery-slide">
                                <picture class="product-hero__pic">
                                    <img src="<?php echo get_image_url_by_id($photo_id); ?>" alt="<?php the_title(); ?>" class="product-hero__img">
                                </picture>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="product-hero__thumbs">
                    <div class="swiper-button-prev product-hero__thumbs-button-prev"></div>
                    <div class="swiper product-hero__thumbs-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($photos as $photo_id) : ?>
                            <div class="swiper-slide product-hero__thumbs-slide">
                                <picture class="product-hero__pic">
                                    <img src="<?php echo get_image_url_by_id($photo_id); ?>" alt="<?php the_title(); ?>" class="product-hero__img">
                                </picture>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="swiper-button-next product-hero__thumbs-button-next"></div>
                </div>
            </div>
            <h1 class="product-hero__title"><?php the_title(); ?></h1>
        </div>
        <div class="product-hero__info">
            <h3 class="product-hero__info-title">Выберите свободную дату</h3>
            <div class="product-hero__bookform">
                <div class="product-hero__calendar b_rangepicker b_datepicker--calendar">
                    <input class="input__field" type="text">
                    <input class="input__field" type="text">
                </div>
                <div class="product-hero__bookform-tariffs">
                    <h3 class="product-hero__bookform-tariffs-title">Тарифы</h3>

                    <?php
                    $prices = [];
                        $prices = explode(',', carbon_get_the_post_meta('prices'));
                        $tariff_names = explode_textarea(carbon_get_post_meta(HOMEPAGE_ID, 'price_tariffs'));
                    ?>
                    <?php foreach ($prices as $key => $price) : ?>
                    <div class="product-hero__bookform-tariff">
                        <div class="product-hero__bookform-tariff-price">
                            <span><?php echo $price; ?></span>
                            <span class="currency"><?php echo THEME_OPTIONS['currency']; ?></span>
                        </div>
                        <div class="product-hero__bookform-tariff-caption">
                            <?php echo $tariff_names[$key]; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <a href="#" class="button-primary product-hero__bookform-button">Оплатить бронь в Stripe</a>
                    <p class="product-hero__bookform-caption">
                        оплачивая, я соглашусь и принимаю <a class="link" href="#">политику конфиденциальности</a> и <a class="link" href="#">terms and conditions</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="product-hero__content">
            <div class="product-hero__stat-list">
                <p class="product-hero__stat">
                    <span class="product-hero__stat-title">Класс:</span>
                    <span class="product-hero__stat-value">5751941</span>
                </p>
                <p class="product-hero__stat">
                    <span class="product-hero__stat-title">Объем багажа:</span>
                    <span class="product-hero__stat-value">5751941</span>
                </p>
                <p class="product-hero__stat">
                    <span class="product-hero__stat-title">Цвет:</span>
                    <span class="product-hero__stat-value">4.7</span>
                </p>
                <p class="product-hero__stat">
                    <span class="product-hero__stat-title">Трансмиссия:</span>
                    <span class="product-hero__stat-value">4.7</span>
                </p>
                <p class="product-hero__stat">
                    <span class="product-hero__stat-title">Пробег:</span>
                    <span class="product-hero__stat-value">1334x750</span>
                </p>
                <p class="product-hero__stat">
                    <span class="product-hero__stat-title">Вместимость:</span>
                    <span class="product-hero__stat-value">1334x750</span>
                </p>
                <p class="product-hero__stat">
                    <span class="product-hero__stat-title">Тип кузова:</span>
                    <span class="product-hero__stat-value">64</span>
                </p>
                <p class="product-hero__stat">
                    <span class="product-hero__stat-title">Количество дверей:</span>
                    <span class="product-hero__stat-value">64</span>
                </p>
            </div>
            <div class="product-hero__options">
                <h3 class="product-hero__options-title">Опции</h3>
                <div class="product-hero__options-content wysiwyg">
                    <p>Кондиционер, Система входа с бесконтактным ключом, Задние датчики парковки, Датчики парковки, Технология мобильного телефона, Bluetooth, USB, Аудио/iPod, Android Auto, Адаптивные фары</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
