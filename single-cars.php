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

<?php $disabled_dates = get_car_bookings_timestamps(get_the_ID()); ?>

    <section class="section product-hero" data-id="<?php the_ID(); ?>">
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
            <div class="product-hero__bookform js_form js_form--no-lock-button js_form--no-reset"
                 data-route="<?php echo FORM_URLS['ajax']?>"
                 data-action="get_stripe_paylink"
            >
                <input class="is-hidden" hidden name="post_id" type="text" value="<?php the_id(); ?>">
                <?php
                    $date_start = $_GET['date_start'];
                    $date_end = $_GET['date_end'];
                ?>
                <div class="product-hero__calendar b_rangepicker b_rangepicker--inline"
                     data-disabled="<?php echo $disabled_dates; ?>"
                     data-hour-gap="<?php echo carbon_get_theme_option('min_hour_booking_gap'); ?>"
                     data-default-date="<?php echo $date_start.','.$date_end; ?>"
                >
                    <input class="input__field" type="text">
                    <input class="is-hidden" name="date_start" type="text">
                    <input class="is-hidden" name="date_end" type="text">
                </div>


                <?php
                    $homepage_id = pll_get_post(get_option('page_on_front'));
                    $locations_from = explode_textarea(carbon_get_post_meta($homepage_id, 'location_start_names'));
                    $locations_to = explode_textarea(carbon_get_post_meta($homepage_id, 'location_end_names'));

                ?>
                <div class="product-hero__form product-hero__info-columns">
                    <div class="timepicker hero__bookform-timepicker"
                         data-min="08:00"
                         data-max="20:00"
                         data-step="15"
                    >
                        <div class="timepicker__control">
                            <div class="timepicker__drag"></div>
                        </div>
                        <input class="timepicker__value" type="text" name="time_start" inputmode="numeric">
                    </div>
                    <div class="timepicker hero__bookform-timepicker"
                         data-min="08:00"
                         data-max="20:00"
                         data-step="15"
                    >
                        <div class="timepicker__control">
                            <div class="timepicker__drag"></div>
                        </div>
                        <input class="timepicker__value" type="text" name="time_end" inputmode="numeric" value="14:10">
                    </div>
                    <div class="select hero__bookform-select">
                        <select class="select__input"
                                name="location_start"
                                tabindex="-1"
                                required>
                            <option value="" disabled selected>Pick-up location</option>
                            <?php foreach ($locations_from as $location) : ?>
                                <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="select__toggle" type="button">Pick-up location</button>
                        <ul class="select__list">
                            <?php foreach ($locations_from as $location) : ?>
                                <li>
                                    <button class="select__option" type="button"><?php echo $location; ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="select hero__bookform-select">
                        <!-- <div class="select__label">Откуда вы узнали о нас?</div> -->
                        <select class="select__input"
                                name="location_end"
                                tabindex="-1"
                                required>
                            <!-- <option value="" disabled selected>Выберите место получения</option> -->
                            <option value="" disabled selected>Drop-off location</option>
                            <?php foreach ($locations_to as $location) : ?>
                                <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="select__toggle" type="button">Drop-off location</button>
                        <ul class="select__list">
                            <?php foreach ($locations_to as $location) : ?>
                                <li>
                                    <button class="select__option" type="button"><?php echo $location; ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <label class="input js_form__control">
                        <input
                                class="input__field"
                                name="user_phone"
                                type="tel"
                                placeholder="Phone"
                                required
                        >
                    </label>
                    <label class="input js_form__control">
                        <input
                                class="input__field"
                                name="user_email"
                                type="email"
                                placeholder="Email"
                                required
                        >
                    </label>
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

                    <button class="button-primary product-hero__bookform-submit js_form__submit">Подобрать автомобиль</button>
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
