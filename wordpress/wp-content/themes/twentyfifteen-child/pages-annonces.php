<?php
/**
Template Name: Page des annonces 
*/
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args=array(
			'caller_get_posts'=>1,
			'post_type' => 'projet',
			'paged'=>$paged
			);
		$wp_query = new WP_Query($args);
		while ( $wp_query->have_posts() ) : $wp_query->the_post();

			// Include the page content template.
		get_template_part( 'content', 'annonces' );

			// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

		// End the loop.
		endwhile;
		?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
