<?php 

add_shortcode( 'nbsp', 'nbsp' );
function nbsp($attrs, $content) {
	return '&nbsp;';
}

add_shortcode( 'hl', 'text_color' );
function text_color($attrs, $content) {
	$attrs = shortcode_atts( [
		'text' => '',
	], $attrs );

	ob_start(); ?>
    <span class="text-colored"><?php echo $content; ?></span>
	<?php return ob_get_clean();
}

add_shortcode( 'b', 'text_bold' );
function text_bold($attrs, $content) {
	$attrs = shortcode_atts( [
		'text' => '',
	], $attrs );

	ob_start(); ?>
    <span class="text-bold"><?php echo $content; ?></span>
	<?php return ob_get_clean();
}

add_shortcode( 'strong', 'text_strong' );
function text_strong($attrs, $content) {
    $attrs = shortcode_atts( [
        'text' => '',
    ], $attrs );

    ob_start(); ?>
    <strong><?php echo $content; ?></strong>
    <?php return ob_get_clean();
}

add_shortcode( 'i', 'text_italic' );
function text_italic($attrs, $content) {
    $attrs = shortcode_atts( [
        'text' => '',
    ], $attrs );

    ob_start(); ?>
    <span class="text-italic"><?php echo $content; ?></span>
    <?php return ob_get_clean();
}

add_shortcode( 'email', 'text_email' );
function text_email($attrs, $content) {
	$attrs = shortcode_atts( [
		'text' => '',
	], $attrs );

	ob_start(); ?>
    <a href="mailto:<?php echo $content; ?>" class="js_copyclicker"><?php echo $content; ?></a>
	<?php return ob_get_clean();
}

add_shortcode( 'tel', 'text_tel' );
function text_tel($attrs, $content) {
	$attrs = shortcode_atts( [
		'country' => '+7',
	], $attrs );

	ob_start(); ?>
    <a href="<?php echo phone_to_href($content, $attrs['country']); ?>" class="js_copyclicker" data-copy-text="Скопировано!"><?php echo $content; ?></a>
	<?php return ob_get_clean();
}

add_shortcode( 'url', 'text_link' );
function text_link($attrs, $content) {
	$attrs = shortcode_atts( [
		'link' => '',
	], $attrs );

	ob_start(); ?>
    <a href="<?php echo $attrs['link']; ?>"><?php echo $content; ?></a>
	<?php return ob_get_clean();
}
