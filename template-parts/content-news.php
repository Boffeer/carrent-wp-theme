<?php
    $thumb = get_post_thumb(get_the_ID());
    $excerpt = get_the_excerpt();
?>
<article class="blog-card">
    <div class="blog-card__info">
        <?php if (!empty($thumb)) : ?>
        <a href="<?php the_permalink(); ?>" class="blog-card__media">
            <picture class="blog-card__pic">
                <img  src="<?php echo THEME_STATIC; ?>/img/common.b/loader.svg" data-src="<?php echo $thumb; ?>" alt="<?php the_title(); ?>" class="blog-card__img lazy">
            </picture>
        </a>
        <?php endif; ?>
        <h3 class="blog-card__title">
            <a href="<?php the_permalink(); ?>" class="blog-card__link"><?php the_title(); ?></a>
        </h3>
        <?php if (!empty($excerpt)) : ?>
        <p class="blog-card__desc"><?php echo $excerpt; ?></p>
        <?php endif;?>
    </div>
    <div class="blog-card__buttons">
        <a href="<?php the_permalink(); ?>" class="blog-card__more">
            <span class="blog-card__more-text">Подробнее</span>
            <svg class="blog-card__more-icon">
                <use href="<?php echo THEME_STATIC; ?>/img/common.b/link-angle.svg#angle" />
            </svg>
        </a>
    </div>
</article>
