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
                <li class="breadcrumbs__item"><a href="/" class="breadcrumbs__link"><?php pll_e('Homepage', 'crrt'); ?></a></li>
                <li class="breadcrumbs__item"><?php the_title(); ?></li>
            </ul>
        </div>
    </div>

    <section class="section faq success" id="faq">
        <?php
        $socials = get_socials(THEME_OPTIONS['socials']);
        $car_booking_id = isset($_GET['car_booking_id']) ? $_GET['car_booking_id'] : false;

//        if (!$car_booking_id) {
//            wp_redirect(home_url());
//            exit;
//        }

        /*
        $args = array(
            'post_type' => 'car_booking',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => 'booking_id', // Replace with your actual custom field name
                    'value' => $car_booking_id, // Replace with the value you want to filter by
                    'compare' => '=', // Use '=' for exact match
                ),
            ),
        );
        */

        $booking = get_post($car_booking_id);
        $booking_id = $car_booking_id;

//        if ($booking->have_posts()) {
//            $booking_id = $booking->posts[0]->ID;
            $booking_info = getBookingInfo($booking_id);
            $car = get_car_content($booking_info['product_id']);
//        }

        ?>
        <div class="container faq__container">
            <h1 class="section-title faq__title" data-aos="fade-up">
                <?php if ($booking_id) : ?>
                    <?php pll_e('You have booked a', 'crrt'); ?> <br>
                    <?php echo $car['title']; ?>
                <?php else: ?>
                    <?php pll_e('There is no such booking', 'crrt'); ?> <br>
                <?php endif; ?>
            </h1>
            <?php if ($booking_id) : ?>
            <div class="faq__content">
                <div class="faq__list">
                    <article class="blog-card">
                        <div class="blog-card__info">
                            <h3 class="blog-card__title">
                                <?php pll_e('Order number', 'crrt'); ?> <br>
                            </h3>
                            <p class="blog-card__desc">
                                <?php if (!empty($booking_info['crm_booking_id'])) : ?>
                                    <?php echo $booking_info['crm_booking_id']; ?>
                                <?php else : ?>
                                    <?php echo $booking_info['booking_id']; ?>
                                <?php endif; ?>
                            </p>

                            <h3 class="blog-card__title">
                                <?php pll_e('Start', 'crrt'); ?> <br>
                            </h3>
                            <p class="blog-card__desc">
                                <?php echo $booking_info['location_start']; ?> <br>
                                <?php echo $booking_info['date_start']; ?> <br>
                            </p>

                            <h3 class="blog-card__title">
                                <?php pll_e('End', 'crrt'); ?> <br>
                            </h3>
                            <p class="blog-card__desc">
                                <?php echo !empty($booking_info['location_end']) ? $booking_info['location_end'] : $booking_info['location_start'] ?> <br>
                                <?php echo $booking_info['date_end']; ?> <br>
                            </p>
                            <br>
                            <p class="blog-card__desc">Email sent. Check spam.</p>

                            <?php /*
                            <h3 class="blog-card__title">
                            Ваши контакты
                            </h3>
                            <p class="blog-card__desc">
                            <?php echo $booking_info['name']; ?> <br>
                            <?php echo $booking_info['email']; ?> <br>
                            <?php echo $booking_info['phone']; ?>
                            </p>
                               */ ?>
                        </div>
                    </article>
                </div>
                <div class="faq-help">
                    <div class="faq-help__media">
                        <picture class="faq-help__media-pic">
                            <!-- <source srcset="<?php echo THEME_STATIC; ?>/img/faq/faq-hero.jpg.webp" type="image/webp"> -->
                            <img src="<?php echo THEME_STATIC; ?>/img/common/loader.svg" data-src="<?php echo $car['thumb']; ?>" alt="" class="faq-help__media-img lazy">
                        </picture>
                    </div>
                    <h3 class="faq-help__title"></h3>
                    <?php $phone = explode_textarea(THEME_OPTIONS['phones'])[0]; ?>
                    <p class="faq-help__desc">
                        <?php echo pll_e('Success message', 'crrt');?>
                        <a href="<?php echo phone_to_href($phone);?>" class="link"><?php echo $phone; ?></a>
                    </p>

                    <div class="faq__socials socials">
                        <?php foreach ($socials as $social) : ?>
                            <a class="socials__link"
                               href="<?php echo $social['href']; ?>" target="_blank" rel="noopener noreferrer"
                            >
                                <img class="socials__icon" src="<?php echo $social['icon']; ?>" alt="<?php echo isset($social['text']) ? $social['text'] : ''; ?>">
                            </a>
                        <?php endforeach; ?>

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
            <?php endif; ?>
        </div>
    </section>

<?php
get_footer();
