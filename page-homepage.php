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
$socials = get_socials(THEME_OPTIONS['socials']);
$phones = explode_textarea(THEME_OPTIONS['phones']);
?>

    <section class="hero section">
        <div class="hero__container container">
            <?php
                $title = carbon_get_the_post_meta('hero_title');
                $locations_from = explode_textarea(carbon_get_the_post_meta('location_start_names'));
                $locations_to = explode_textarea(carbon_get_the_post_meta('location_end_names'));
            ?>
            <div class="hero__offer">
                <h2 class="section-title hero__title">
                    <?php echo typograph($title); ?>
                </h2>
                <div class="bookform hero__bookform js_form js_form--no-reset"
                     data-route="<?php echo FORM_URLS['ajax']?>"
                     data-action="filter_cars"
                >
                    <input
                            class="is-hidden"
                            name="lang"
                            type="text"
                            readonly
                            required
                            hidden
                            value="<?php echo pll_current_language(); ?>"
                    >
                    <div class="select hero__bookform-select js_form__control">
                        <select class="select__input"
                                name="location_start"
                                tabindex="-1"
                                required>
                            <option value="" disabled selected><?php pll_e('A pickup location', 'crrt'); ?></option>
                            <?php foreach ($locations_from as $location) : ?>
                                <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="select__toggle" type="button"><?php pll_e('A pickup location', 'crrt'); ?></button>
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
                        <select class="select__input js_form__control"
                                name="location_end"
                                tabindex="-1"
                                required>
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
                    */ ?>

                    <label class="input js_form__control">
                        <input
                                class="input__field"
                                name="flight_number"
                                type="text"
                                placeholder="<?php pll_e('Flight number', 'crrt'); ?>"
                        >
                    </label>
                    <div class="hero__bookform-range b_rangepicker" data-lang="<?php echo pll_current_language(); ?>">
                        <label class="input b_datepicker js_form__control" data-lang="<?php echo pll_current_language(); ?>">
                            <input
                                    class="input__field"
                                    type="text"
                                    placeholder="<?php echo pll_e('Rent Dates', 'crrt'); ?>"
                                    readonly
                                    required
                            >
                        </label>
                        <input type="text" name="date_start" hidden readonly>
                        <input type="text" name="date_end" hidden readonly>
                    </div>
                    <?php
                        $timepicker_settings = array(
                                'min' => carbon_get_theme_option('time_min'),
                                'max' => carbon_get_theme_option('time_max'),
                                'step' => carbon_get_theme_option('time_step'),
                        );
                    ?>
                    <div class="timepicker hero__bookform-timepicker"
                         data-min="<?php echo $timepicker_settings['min']; ?>"
                         data-max="<?php echo $timepicker_settings['max']; ?>"
                         data-step="<?php echo $timepicker_settings['step']; ?>"
                    >
                        <div class="timepicker__control">
                            <div class="timepicker__drag"></div>
                        </div>
                        <input class="timepicker__value" type="text" name="time_start" inputmode="numeric" value="14:00">
                    </div>
                    <div class="timepicker hero__bookform-timepicker"
                         data-min="<?php echo $timepicker_settings['min']; ?>"
                         data-max="<?php echo $timepicker_settings['max']; ?>"
                         data-step="<?php echo $timepicker_settings['step']; ?>"
                    >
                        <div class="timepicker__control">
                            <div class="timepicker__drag"></div>
                        </div>
                        <input class="timepicker__value" type="text" name="time_end" inputmode="numeric" value="14:00">
                    </div>
                    <button class="button-primary hero__bookform-submit js_form__submit"><?php pll_e('Find a Car', 'crrt'); ?></button>
                </div>
            </div>

            <?php $banners = carbon_get_the_post_meta('banners'); ?>
            <?php if (!empty($banners)) : ?>
            <div class="hero-gallery">
                <div class="swiper hero-gallery__swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($banners as $banner) : ?>
                        <div class="swiper-slide hero-gallery__slide">
                            <div class="hero__media">
                                <picture class="hero__media-pic">
                                    <img class="hero__media-img"
                                         src="<?php echo get_image_url_by_id($banner['img']); ?>"
                                         alt="<?php echo $banner['text']; ?>"
                                    >
                                </picture>
                                <div class="hero__meida-caption">
                                    <div class="wysiwyg">
                                        <p><?php echo typograph($banner['text']); ?></p>
                                    </div>
                                    <a href="<?php echo $banner['link_url'];?>" class="hero__media-caption-link link"><?php echo $banner['link_text'];?></a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="b_swiper__buttons hero-gallery__buttons">
                    <div class="swiper-button-prev hero-gallery__button-prev"></div>
                    <div class="swiper-button-next hero-gallery__button-next"></div>
                </div>
            </div>
            <?php endif; ?>

            <?php $bullets = carbon_get_the_post_meta('bullets'); ?>
            <?php if (!empty($bullets)) : ?>
            <div class="hero__bullets">
                <?php foreach ($bullets as $bullet) :  ?>
                <div class="bullet-card">
                    <div class="bullet-card__media">
                        <picture class="bullet-card__pic">
                            <img class="bullet-card__img"
                                 src="<?php echo wp_get_attachment_image_url($bullet['icon']) ?>"
                                 alt="<?php echo $bullet['title']; ?>"
                            >
                        </picture>
                    </div>
                    <div class="bullet-card__content wysiwyg">
                        <h3><?php echo typograph($bullet['title']); ?></h3>
                        <p><?php echo typograph($bullet['text']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php
    $cars_args = array(
        'post_type'      => 'cars',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
        'lang' =>  pll_current_language(),
    );
    $cars = new WP_Query($cars_args);
    ?>
    <?php if ($cars->have_posts()) : ?>
    <section class="shelf section" id="cars">
        <div class="shelf__container container">
            <h2 class="section-title shelf__title">
                <?php pll_e('Choose Your Car', 'crrt')?>
            </h2>
            <div class="shelf__content">
                <?php while ($cars->have_posts()) : $cars->the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'cars' ); ?>
                <?php endwhile; wp_reset_query(); ?>
            </div>
            <div class="shelf__buttons is-hidden">
                <button class="button-secondary shelf__button-more"><?php pll_e('Show more', 'crrt'); ?></button>
            </div>
        </div>
    </section>
    <?php endif; ?>


<?php $reviews_ids = get_carbon_association_ids(carbon_get_the_post_meta('home_reviews')); ?>
<?php if (!empty($reviews_ids)) : ?>
    <section class="section reviews" id="reviews">
        <div class="container reviews__container">
            <h2 class="section-title section-text--center reviews__title">
                <?php pll_e('Reviews', 'crrt'); ?>
            </h2>
            <div class="reviews-carousel">
                <div class="swiper reviews-carousel__swiper">
                    <div class="swiper-wrapper">
                        <?php
                        $reviews_query = new WP_Query(array(
                            'post_type' => 'reviews',
                            'post__in' => $reviews_ids,
                            'post_status' => 'publish',
                            'orderby'        => 'post__in',
                        ));
                        ?>
                        <?php while ($reviews_query->have_posts()) : $reviews_query->the_post(); ?>
                            <div class="swiper-slide reviews-carousel__slide">
                                <?php get_template_part('template-parts/content', 'reviews'); ?>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
                <div class="swiper-scrollbar reviews-carousel__scrollbar"></div>

                <div class="swiper-buttons reviews-carousel__buttons">
                    <div class="swiper-button-prev reviews-carousel__button-prev"></div>
                    <div class="swiper-button-next reviews-carousel__button-next"></div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>


    <?php
    $news_args = array(
        'post_type'      => 'news',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
        'lang' =>  pll_current_language(),
    );
    $news = new WP_Query($news_args);
    ?>
    <?php if ($news->have_posts()) : ?>
    <section class="section blog" id="blog">
        <h2 class="section-title blog__title"> <?php pll_e('Blog', 'crrt'); ?></h2>
        <div class="container blog__container">
            <div class="blog__masonry">
                <?php while ($news->have_posts()) : $news->the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'news' ); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <div class="section__buttons">
                <a href="<?php echo get_post_type_archive_link('news'); ?>" class="button-secondary shelf__button-more"><?php pll_e('More news', 'crrt'); ?></a>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <?php
        $faq_ids = get_carbon_association_ids(carbon_get_the_post_meta('home_faq'));
        $faq_img = get_image_url_by_id(carbon_get_the_post_meta('faq_img'));
        $faq_title = carbon_get_the_post_meta('faq_title');
        $faq_desc = carbon_get_the_post_meta('faq_text');
    ?>
    <?php if (!empty($faq_ids)) : ?>
    <section class="section faq" id="faq">
        <div class="container faq__container">
            <h2 class="section-title faq__title" data-aos="fade-up"> <?php pll_e('FAQ', 'crrt'); ?></h2>
            <div class="faq__content">
                <div class="faq__list">
                    <?php
                    $faq_query = new WP_Query(array(
                        'post_type' => 'faq',
                        'post__in' => $faq_ids,
                        'post_status' => 'publish',
//                        'orderby'        => 'post__in',
                    ));

                    while ($faq_query->have_posts()) : $faq_query->the_post();
                        get_template_part('template-parts/content', 'faq');
                    endwhile;

                    wp_reset_postdata();
                    ?>
                </div>
                <div class="faq-help">
                    <div class="faq-help__media">
                        <picture class="faq-help__media-pic">
                            <!-- <source srcset="<?php echo THEME_STATIC; ?>/img/faq/faq-hero.jpg.webp" type="image/webp"> -->
                            <img src="<?php echo THEME_STATIC; ?>/img/common/loader.svg" data-src="<?php echo $faq_img; ?>" alt="<?php echo $faq_title; ?>" class="faq-help__media-img lazy">
                        </picture>
                    </div>
                    <h3 class="faq-help__title"><?php echo typograph($faq_title); ?></h3>
                    <p class="faq-help__desc">
                        <?php echo typograph($faq_desc); ?>
                    </p>

                    <div class="faq__socials socials">
                        <?php foreach ($socials as $social) : ?>
                            <a class="socials__link"
                               href="<?php echo $social['href']; ?>" target="_blank" rel="noopener noreferrer"
                            >
                                <img class="socials__icon" src="<?php echo $social['icon']; ?>" alt="<?php echo isset($social['text']) ? $social['text'] : ''; ?>">
                            </a>
                        <?php endforeach; ?>

                        <?php if (!empty($phones)) : ?>
                            <a href="<?php echo phone_to_href($phones[0]); ?>" class="socials__link">
                                <svg class="socials__icon">
                                    <use xlink:href="<?php echo THEME_STATIC; ?>/img/faq.crrt/tel.svg#tel" />
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php $email = explode_textarea(THEME_OPTIONS['emails'])[0]; ?>
                        <?php if (!empty($email)) : ?>
                            <a href="mailto:<?php echo $email; ?>" class="socials__link">
                                <svg class="socials__icon">
                                    <use xlink:href="<?php echo THEME_STATIC; ?>/img/faq.crrt/mail.svg#mail" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>




<?php
get_footer();
