<?php $car = get_car_content(get_the_ID()); ?>

<article class="car-card" data-id="<?php echo $car['id']; ?>">
    <div class="car-card__media">
        <picture class="car-card__media-pic">
            <img class="car-card__media-img" src="<?php echo $car['thumb']; ?>" alt="<?php echo $car['title']; ?>">
        </picture>
    </div>
    <div class="car-card__body">
        <header class="car-card__header">
            <h3 class="car-card__title">
                <a href="<?php echo $car['url']; ?>" class="car-card__link"><?php echo $car['title']; ?></a>
            </h3>
            <div class="car-card__header-info">
				<span class="car-card__price">
					<span><?php echo $car['price_cheap']; ?></span>
					<span class="currency"><?php echo $car['currency'] ?></span>
				</span>
                <span class="car-card__caption">
                    <?php echo $car['text_price_hint']; ?>
				</span>
            </div>
        </header>
        <div class="car-card__bullets">
            <?php if (!empty($car['fuel'])) : ?>
            <div class="car-card-bullet">
                <div class="car-card-bullet__media">
                    <picture class="car-card-bullet__media-pic">
                        <img class="car-card-bullet__media-img" src="<?php echo THEME_STATIC; ?>/img/common.crrt/icon-fuel.svg" alt="">
                    </picture>
                </div>
                <p class="card-card-bullet__caption"><?php echo $car['fuel']; ?></p>
            </div>
            <?php endif; ?>
            <?php if (!empty($car['number_seats'])) : ?>
            <div class="car-card-bullet">
                <div class="car-card-bullet__media">
                    <picture class="car-card-bullet__media-pic">
                        <img class="car-card-bullet__media-img" src="<?php echo THEME_STATIC; ?>/img/common.crrt/icon-capacity.svg" alt="">
                    </picture>
                </div>
                <p class="card-card-bullet__caption"><?php echo $car['number_seats']; ?></p>
            </div>
            <?php endif; ?>
            <?php if (!empty($car['trunk_volume'])) : ?>
            <div class="car-card-bullet">
                <div class="car-card-bullet__media">
                    <picture class="car-card-bullet__media-pic">
                        <img class="car-card-bullet__media-img" src="<?php echo THEME_STATIC; ?>/img/common.crrt/icon-volume.svg" alt="">
                    </picture>
                </div>
                <p class="card-card-bullet__caption"><?php echo $car['trunk_volume']; ?></p>
            </div>
            <?php endif; ?>
            <?php if (!empty($car['transmission'])) : ?>
            <div class="car-card-bullet">
                <div class="car-card-bullet__media">
                    <picture class="car-card-bullet__media-pic">
                        <img class="car-card-bullet__media-img" src="<?php echo THEME_STATIC; ?>/img/common.crrt/icon-transmission.svg" alt="">
                    </picture>
                </div>
                <p class="card-card-bullet__caption"><?php echo $car['transmission']; ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="car-card__body">
        <a href="<?php echo $car['url'] ?>" class="button-primary car-card__button"><?php echo $car['text_book']; ?></a>
    </div>
</article>