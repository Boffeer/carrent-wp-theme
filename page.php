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
                <li class="breadcrumbs__item"><a href="/" class="breadcrumbs__link">Главная</a></li>
                <li class="breadcrumbs__item"><?php the_title(); ?></li>
            </ul>
        </div>
    </div>


    <div class="article">
        <div class="container">
            <h1 class="article__title section-title"><?php the_title(); ?></h1>
        </div>

        <div class="container article__container">
            <div class="article__content wysiwyg">
                <?php the_content(); ?>
            </div>
        </div>
    </div>

<?php
get_footer();
