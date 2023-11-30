<?php
/*
    $review_id = get_the_ID();
    $review = array(
        'feedback' => typograph(carbon_get_post_meta($review_id, 'feedback')),
        'name' => carbon_get_post_meta($review_id, 'name'),
        'desc' => carbon_get_post_meta($review_id, 'desc'),
        'video' => carbon_get_post_meta($review_id, 'video'),
        'source' => get_the_terms($review_id, 'reviews-sources'),
        'source_url' => carbon_get_post_meta($review_id, 'source_url'),
        'title' => carbon_get_post_meta($review_id, 'title'),
    );
    $review['source_name'] = $review['source'][0]->name;
    $review['source_id'] = $review['source'][0]->term_id;
    unset($review['source']);
    $review['source_img'] = carbon_get_term_meta($review['source_id'], 'logo');
    $review['source_img'] = get_image_url_by_id($review['source_img']);
*/
?>

<article class="car-card">
    <div class="car-card__media">
        <picture class="car-card__media-pic">
            <img class="car-card__media-img" src="./img/single-car.crrt/car-image.jpg" alt="">
        </picture>
    </div>
    <div class="car-card__body">
        <header class="car-card__header">
            <h3 class="car-card__title">
                <a href="#" class="car-card__link">Nissan Micra 1.0 Automatic</a>
            </h3>
            <div class="car-card__header-info">
				<span class="car-card__price">
					<span>40</span>
					<span class="currency">€</span>
				</span>
                <span class="car-card__caption">
					*При аренде от 30 до 45 дней
				</span>
            </div>
        </header>
        <div class="car-card__bullets">
            <div class="car-card-bullet">
                <div class="car-card-bullet__media">
                    <picture class="car-card-bullet__media-pic">
                        <img class="car-card-bullet__media-img" src="./img/common.crrt/icon-fuel.svg" alt="">
                    </picture>
                </div>
                <p class="card-card-bullet__caption">Дизель</p>
            </div>
            <div class="car-card-bullet">
                <div class="car-card-bullet__media">
                    <picture class="car-card-bullet__media-pic">
                        <img class="car-card-bullet__media-img" src="./img/common.crrt/icon-capacity.svg" alt="">
                    </picture>
                </div>
                <p class="card-card-bullet__caption">5 мест</p>
            </div>
            <div class="car-card-bullet">
                <div class="car-card-bullet__media">
                    <picture class="car-card-bullet__media-pic">
                        <img class="car-card-bullet__media-img" src="./img/common.crrt/icon-volume.svg" alt="">
                    </picture>
                </div>
                <p class="card-card-bullet__caption">5 мест</p>
            </div>
            <div class="car-card-bullet">
                <div class="car-card-bullet__media">
                    <picture class="car-card-bullet__media-pic">
                        <img class="car-card-bullet__media-img" src="./img/common.crrt/icon-transmission.svg" alt="">
                    </picture>
                </div>
                <p class="card-card-bullet__caption">Автомат</p>
            </div>
        </div>
        <a href="#" class="button-primary car-card__button">Забронировать машину</a>
    </div>
</article>