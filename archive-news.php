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
?>

<?php get_header(); ?>

    <section class="section blog" id="blog">
        <h1 class="page-title section-title"><?php pll_e('Blog', 'crrt'); ?></h1>
        <div class="container blog__container">
            <div class="blog__masonry">
                <?php
                while (have_posts()) : the_post();
                    get_template_part('template-parts/content', 'news');
                endwhile;
                ?>
            </div>

            <?php
            global $wp_query;

            $big = 999999999; // need an unlikely integer

            $pagination = paginate_links(array(
            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'    => '?paged=%#%',
            'current'   => max(1, get_query_var('paged')),
            'total'     => $wp_query->max_num_pages,
            'prev_text' => '&lt;',
            'next_text' => '&gt;',
            'type'      => 'array', // Change the type to array
            'end_size'  => 1,
            'mid_size'  => 1,
            ));

            if ($pagination) :
            ?>
            <ul class="pagination">
                <?php
                foreach ($pagination as $page_link) {
                    echo '<li>' . $page_link . '</li>';
                }
                ?>
            </ul>
            <?php endif; ?>
<!--                        <ul class="pagination">-->
<!--                            <li><a href="#"><</a></li>-->
<!--                            <li class="active">-->
<!--                                <span>1</span>-->
<!--                            </li>-->
<!--                            <li><a href="#">2</a></li>-->
<!--                            <li><a href="#">3</a></li>-->
<!--                            <li><a href="#">4</a></li>-->
<!--                            <li><a href="#">5</a></li>-->
<!--                            <li><a href="#">></a></li>-->
<!--                        </ul>-->
        </div>
    </section>

<?php get_footer();
