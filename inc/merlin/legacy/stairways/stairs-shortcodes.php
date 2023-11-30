<?php

add_shortcode( 'text_tooltip', 'text_tooltip' );
function text_tooltip($attrs, $content) {

	$attrs = shortcode_atts( [
		'text' => '',
	], $attrs );

	ob_start(); ?>
		<span
			class="sect-delivery__step--text--info"
			data-tooltip="<?php echo $attrs['text']; ?>"><?php echo $content; ?></span>
	<?php return ob_get_clean();
}

add_shortcode( 'bold_title', 'bold_title' );
function bold_title($attrs, $content) {

	$attrs = shortcode_atts( [
		'text' => '',
	], $attrs );
	ob_start(); ?>
  <span class="sect-delivery__step--count"><?php echo $content; ?></span>
	<?php return ob_get_clean();
}

add_shortcode( 'big_link', 'big_link' );
function big_link($attrs, $content) {
	$attrs = shortcode_atts( [
		'href' => '',
	], $attrs );

	ob_start(); ?>
    <a href="<?php echo $attrs['href']?>" class="sect-delivery__step--download"><?php echo $content; ?></a>
	<?php return ob_get_clean();
}

add_shortcode( 'info_hint', 'info_hint' );
function info_hint($attrs, $content) {

	$attrs = shortcode_atts( [
		'text' => '',
	], $attrs );

	ob_start(); ?>
  <div class="sect-delivery__step--info">
      <p class="sect-delivery__step--info-text"><?php echo $content; ?></p>
  </div>
	<?php return ob_get_clean();
}

add_shortcode( 'text_form', 'text_form' );
function text_form($attrs) {
	$attrs = shortcode_atts( [
		'title' => 'Узнайте стоимость лестницы за 5 минут!',
		'desc' => "
      Заполните форму, и мы подберем лучший вариант лестницы специально под ваши запросы.
      Или сделаем что-то абсолютно уникальное.
      
      Просто укажите известные размеры проема в заявке, а мы перезвоним вам и назовем точную стоимость лестницы.
		",
		'phone' => true,
		'name' => true,
		'message' => true,
		'attach' => true,
	], $attrs );

	ob_start(); ?>
    <div class="shifter-callback-card">
      <div class="shfiter-form__info">
          <h3 class="shifter-callback__title section-title" data-shorcode-attr="title:text"><?php echo $attrs['title']?></h3>
          <p class="shifter-callback__desc" data-shordcode-attr="desc:text"> <?php echo $attrs['desc']; ?> </p>
      </div>
      <form action="<?php echo FORM_URLS['mail']; ?>" class="shifter-callback__form">
	      	<input type="hidden" name="form_name" readonly value="<?php echo $attrs['title']; ?>">
          <div class="shifter-callback__form-fields">
	          	<?php if ($attrs['phone']) : ?>
	              <label class="input input--tel input--init" data-shortcode-attr="phone">
	                <input class="input__field" required="" name="user_tel" type="tel" placeholder="+7 (___) ___-__-__" maxlength="25" minlength="10" data-value-missing="Напишите телефон" data-value-invalid="Проверьте корректность телефона" autocomplete="tel">
	              </label>
	            <?php endif; ?>

	            <?php if ($attrs['name']) : ?>
	              <label class="input input--name input--init">
	                <input class="input__field" name="user_name" type="text" placeholder="Ваше имя" data-value-missing="Напишите имя" data-value-invalid="Что-то не так с введенным именем" autocomplete="name">
	              </label>
	            <?php endif; ?>

	            <?php if ($attrs['message']) : ?>
		            <label class="textarea" data-initial-height="94">
		                <textarea class="textarea__field" name="user_message" placeholder="Опишите свою задачу и укажите известные размеры проема"></textarea>
		            </label>
		          <?php endif; ?>

							<?php if ($attrs['attach']) : ?>
	            <label class="input-attach">
                <input class="input-attach__field" name="user_file" type="file">
                <span class="input-attach__box-icon">
                <svg class="input-attach__icon">
                  <use xlink:href="<?php echo THEME_STATIC; ?>/img/common/attach.svg#attach"></use>
                </svg>
	              </span>
	                <span class="input-attach__text link link--underlined"><span class="link__text">Прикрепить файл</span></span>
	              </label>
		          <?php endif; ?>
          </div>
          <div class="shifter-callback__form--box">
              <button class="button button--primary shifter-callback__submit">Отправить</button>
              <p class="shifter-callback__privacy">
                  Нажимая кнопку “Отправить”, вы даете согласие на<br> обработку
                  <a href="<?php echo THEME_OPTIONS['privacy_url']; ?>" class="link">персональных данных</a>
              </p>
          </div>
      </form>
    </div>
	<?php return ob_get_clean();
}
