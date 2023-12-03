<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sklh
 */

?>
</main>

<?php
//    $phones = explode_textarea(THEME_OPTIONS['phones']);
    $socials = get_socials(THEME_OPTIONS['socials']);
//    $email = THEME_OPTIONS['emails'];
    $logo = THEME_OPTIONS['logo'];
?>

<?php
    $homepage_id = pll_get_post(get_option('page_on_front'));
    $phone = explode_textarea(THEME_OPTIONS['phones'])[0];
    $work_time = THEME_OPTIONS['work_time'];
    $address = THEME_OPTIONS['address'];
?>


<footer class="footer">
    <div class="container footer__container">
        <a href="/" class="footer__logo logo">
            <!-- <img src="<?php echo THEME_STATIC; ?>/img/common/logo-header.svg" alt="" class="logo__img"> -->
            <?php echo $logo; ?>
        </a>

        <div class="footer__links">
            <div class="socials footer__socials">
                <?php foreach ($socials as $social) : ?>
                    <a class="socials__link socials__link--<?php echo $social['key']; ?>" aria-label="<?php echo isset($social['text']) ? $social['text'] : ''; ?>"
                       href="<?php echo $social['href']; ?>" target="_blank" rel="noopener noreferrer"
                    >
                        <img class="socials__icon" src="<?php echo $social['icon']; ?>" alt="<?php echo isset($social['text']) ? $social['text'] : ''; ?>">
                    </a>
                <?php endforeach; ?>
            </div>

            <ul class="footer__links-list">
                <?php
                $link_privacy = get_carbon_association_ids(carbon_get_post_meta($homepage_id, 'link_privacy'));
                $link_offer = get_carbon_association_ids(carbon_get_post_meta($homepage_id, 'link_offer'));
                ?>
                <?php if (isset($link_privacy[0])) : ?>
                    <li class="footer__links-item">
                        <a class="link footer__links-link" href="<?php the_permalink($link_privacy[0]); ?>">
                            <?php echo get_the_title($link_privacy[0]); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (isset($link_offer[0])) : ?>
                    <li class="footer__links-item">
                        <a class="link footer__links-link" href="<?php the_permalink($link_offer[0]); ?>">
                            <?php echo get_the_title($link_offer[0]); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>


        <div class="footer__contact">
            <a href="<?php echo phone_to_href($phone) ?>" class="footer__phone">
                <span class="footer__phone-caption">Связаться с нами:</span>
                <span class="footer__phone-text"><?php echo $phone; ?></span>
                <span class="footer__phone-caption"><?php echo $work_time; ?></span>
            </a>
            <span class="footer__location">
                <svg class="footer__location-icon">
                    <use xlink:href="<?php echo THEME_STATIC; ?>/img/common.crrt/icon-geo.svg#icon-geo" />
                </svg>
                <span class="footer__location-address"><?php echo $address; ?></span>
            </span>
        </div>
    </div>
</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
