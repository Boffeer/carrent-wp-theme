<?php
    $thumb = get_image_url_by_id(carbon_get_the_post_meta('user_pic'));
    $name = carbon_get_the_post_meta('user_name');
    $feedback = carbon_get_the_post_meta('feedback');
    $video = carbon_get_the_post_meta('video');
    $feedback_pic = get_image_url_by_id(carbon_get_the_post_meta('feedback_pic'));
?>

<article class="reviews-card">
    <div class="reviews-card__user">
        <?php if (!empty ($thumb)) : ?>
            <picture class="reviews-card__user-pic">
<!--                <source data-srcset="./img/reviews/review-photo.webp" type="image/webp">-->
                <img src="<?php echo $thumb; ?>" alt="<?php echo $name; ?>" class="reviews-card__user-img">
            </picture>
        <?php endif; ?>
        <h3 class="reviews-card__user-name"><?php echo $name; ?></h3>
    </div>

    <?php if (!empty($video)) : ?>
        <div class="reviews-card__media">
            <div class="b_video" data-video-url="<?php echo $video; ?>"></div>
        </div>
    <?php endif; ?>
    <?php if (!empty($feedback_pic)) : ?>
        <div class="reviews-card__media">
            <picture class="reviews-card__media-pic">
                <!--                <source data-srcset="./img/reviews/review-photo.webp" type="image/webp">-->
                <img src="<?php echo $feedback_pic; ?>" alt="<?php echo $name; ?>" class="reviews-card__media-img">
            </picture>
        </div>
    <?php endif; ?>
    <?php if (!empty($feedback)) : ?>
        <p class="reviews-card__feedback">
            <?php echo typograph($feedback); ?>
    </p>
    <?php endif; ?>
</article>