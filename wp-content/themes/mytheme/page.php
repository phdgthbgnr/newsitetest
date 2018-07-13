<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
// gestion picto header


get_header(); ?>
        <!--<h1 class="titre" style="margin-bottom:20px"><?php  wp_title(''); ?></h1>-->
			<div id="content" role="main">
                lkklklk
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

				<?php endwhile; // end of the loop. ?>


    </div><!-- #container -->
<?php get_footer(); ?>