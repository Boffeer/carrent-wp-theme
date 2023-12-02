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
                <li class="footer__links-item">
                    <a href="#" class="link footer__links-link">Политика конфиденциальности</a>
                </li>
                <li class="footer__links-item">
                    <a href="#" class="link footer__links-link">Договор оферты</a>
                </li>
            </ul>
        </div>


        <div class="footer__contact">
            <a href="#" class="footer__phone">
                <span class="footer__phone-caption">Связаться с нами:</span>
                <span class="footer__phone-text">+7 (495) 968-32-27</span>
                <span class="footer__phone-caption">09:00 - 22:00 Мск</span>
            </a>
            <a href="#" class="footer__location">
                <svg class="footer__location-icon">
                    <use xlink:href="<?php echo THEME_STATIC; ?>/img/common.crrt/icon-geo.svg#icon-geo" />
                </svg>
                <span class="footer__location-address">109316, Мадейра, ул. За ухо, 25</span>
            </a>
        </div>
    </div>
</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
