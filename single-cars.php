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

<?php
$title = carbon_get_the_post_meta('car_name');
$homepage_id = pll_get_post(get_option('page_on_front'));
?>

    <div class="breadcrumbs">
    <div class="breadcrumbs__container container">
        <ul class="breadcrumbs__list">
            <li class="breadcrumbs__item"><a href="/" class="breadcrumbs__link"><?php pll_e('Homepage', 'crrt'); ?></a></li>
            <li class="breadcrumbs__item"><?php echo $title; ?></li>
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
                                    <img src="<?php echo get_image_url_by_id($photo_id); ?>" alt="<?php echo $title; ?>" class="product-hero__img">
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
                                    <img src="<?php echo get_image_url_by_id($photo_id); ?>" alt="<?php echo $title; ?>" class="product-hero__img">
                                </picture>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="swiper-button-next product-hero__thumbs-button-next"></div>
                </div>
            </div>
            <h1 class="product-hero__title"><?php echo $title; ?></h1>
        </div>
        <div class="product-hero__info">
            <h3 class="product-hero__info-title"><?php pll_e('Free Dates'); ?></h3>
            <div class="product-hero__bookform js_form js_form--no-lock-button js_form--no-reset"
                 data-route="<?php echo FORM_URLS['ajax']?>"
                 data-action="get_stripe_paylink"
            >
                <input class="is-hidden" hidden name="post_id" type="text" value="<?php the_id(); ?>">
                <?php
                    $date_start = isset($_GET['date_start']) ? esc_html($_GET['date_start']) : '';
                    $date_end = isset($_GET['date_end']) ? esc_html($_GET['date_end']) : '';
                ?>
                <div class="product-hero__calendar b_rangepicker b_rangepicker--inline js_form__control"
                     data-disabled="<?php echo $disabled_dates; ?>"
                     data-hour-gap="<?php echo carbon_get_theme_option('min_hour_booking_gap'); ?>"
                     data-default-date="<?php echo $date_start.','.$date_end; ?>"
                     data-lang="<?php echo pll_current_language(); ?>"
                >
                    <input class="input__field" type="text" required>
                    <input class="is-hidden" name="date_start" type="text">
                    <input class="is-hidden" name="date_end" type="text">
                </div>


                <?php
                    $locations_from = explode_textarea(carbon_get_post_meta($homepage_id, 'location_start_names'));
                    $locations_to = explode_textarea(carbon_get_post_meta($homepage_id, 'location_end_names'));

                    $timepicker_settings = array(
                        'min' => carbon_get_theme_option('time_min'),
                        'max' => carbon_get_theme_option('time_max'),
                        'step' => carbon_get_theme_option('time_step'),
                    );
                ?>
                <div class="product-hero__form product-hero__info-columns">
                    <input class="is-hidden" type="text" value="" name="cancel_page">
                    <div class="timepicker hero__bookform-timepicker"
                         data-min="<?php echo $timepicker_settings['min']; ?>"
                         data-max="<?php echo $timepicker_settings['max']; ?>"
                         data-step="<?php echo $timepicker_settings['step']; ?>"
                    >
                        <div class="timepicker__control">
                            <div class="timepicker__drag"></div>
                        </div>
                        <input class="timepicker__value" type="text" name="time_start" inputmode="numeric">
                    </div>
                    <div class="timepicker hero__bookform-timepicker"
                         data-min="<?php echo $timepicker_settings['min']; ?>"
                         data-max="<?php echo $timepicker_settings['max']; ?>"
                         data-step="<?php echo $timepicker_settings['step']; ?>"
                    >
                        <div class="timepicker__control">
                            <div class="timepicker__drag"></div>
                        </div>
                        <input class="timepicker__value" type="text" name="time_end" inputmode="numeric" value="14:10">
                    </div>
                    <div class="select hero__bookform-select js_form__control">
                        <select class="select__input"
                                name="location_start"
                                tabindex="-1"
                                required>
                            <option value="" disabled selected><?php pll_e('A pickup location', 'crrt');?></option>
                            <?php foreach ($locations_from as $location) : ?>
                                <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="select__toggle" type="button"><?php pll_e('A pickup location', 'crrt');?></button>
                        <ul class="select__list">
                            <?php foreach ($locations_from as $location) : ?>
                                <li>
                                    <button class="select__option" type="button"><?php echo $location; ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php /*
                    <div class="select hero__bookform-select">
                        <select class="select__input"
                                name="location_end"
                                tabindex="-1"
                                required>
                            <option value="" disabled selected><?php pll_e('Flight number','crrt'); ?></option>
                            <?php foreach ($locations_to as $location) : ?>
                                <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="select__toggle" type="button"><?php pll_e('Flight number','crrt'); ?></button>
                        <ul class="select__list">
                            <?php foreach ($locations_to as $location) : ?>
                                <li>
                                    <button class="select__option" type="button"><?php echo $location; ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    */?>

                    <label class="input js_form__control">
                        <input
                                class="input__field"
                                name="flight_number"
                                type="text"
                                placeholder="<?php pll_e('Flight number', 'crrt'); ?>"
                        >
                    </label>

                    <label class="input js_form__control">
                        <input
                                class="input__field"
                                name="user_phone"
                                type="tel"
                                placeholder="<?php pll_e('Phone', 'crrt'); ?>"
                                required
                        >
                    </label>
                    <label class="input js_form__control">
                        <input
                                class="input__field"
                                name="user_email"
                                type="email"
                                placeholder="<?php pll_e('Email', 'crrt'); ?>"
                                required
                        >
                    </label>
                </div>
                <div class="product-hero__bookform-tariffs">
                    <h3 class="product-hero__bookform-tariffs-title"><?php pll_e('Rates', 'crrt'); ?></h3>

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
                            <?php echo typograph($tariff_names[$key]); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <button class="button-primary product-hero__bookform-submit js_form__submit"><?php pll_e('Book a car', 'crrt'); ?></button>
                    <p class="product-hero__bookform-caption">
                        <?php
                            $link_privacy = get_carbon_association_ids(carbon_get_post_meta($homepage_id, 'link_privacy'));
                            $link_offer = get_carbon_association_ids(carbon_get_post_meta($homepage_id, 'link_offer'));
                        ?>
<!--                        оплачивая, я соглашусь и принимаю-->
                        <?php if (isset($link_privacy[0])) : ?>
                        <?php pll_e('Agree', 'crrt'); ?>
                        <?php endif ;?>

                        <?php if (isset($link_privacy[0])) : ?>
                            <a class="link" href="<?php the_permalink($link_privacy[0]); ?>">
                                <?php echo get_the_title($link_privacy[0]); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (isset($link_privacy[0])) : ?>
                            <?php pll_e('and', 'crrt'); ?>
                        <?php endif; ?>
                        <?php if (isset($link_offer[0])) : ?>
                            <a class="link" href="<?php the_permalink($link_offer[0]); ?>">
                                <?php echo get_the_title($link_offer[0]); ?>
                            </a>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        <?php $stats = get_car_stats(get_the_ID()); ?>
        <div class="product-hero__content">
            <div class="product-hero__stat-list">
                <?php foreach ($stats as $stat_name => $stat) : ?>
                <?php if (empty($stat)) continue?>
                    <p class="product-hero__stat">
                        <span class="product-hero__stat-title"><?php echo pll_e($stat_name, 'crrt'); ?></span>
                        <span class="product-hero__stat-value"><?php echo $stat; ?></span>
                    </p>
                <?php endforeach; ?>
            </div>
            <div class="product-hero__options">
                <h3 class="product-hero__options-title"><?php pll_e('Options', 'crrt'); ?></h3>
                    <div class="product-hero__options-content wysiwyg">
                        <p>
                            <?php
                            $options = get_car_options(get_the_ID());
                            $option_names = array_keys($options);
                            ?>
                            <?php foreach ($option_names as $index => $option_name) : ?>
                                <?php if ($options[$option_name] === 'false') continue; ?>
                                <?php pll_e($option_name, 'crrt'); echo ($index < count($option_names) - 1) ? ',' : ''; ?>
                            <?php endforeach; ?>
                        </p>
                    </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
