<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area archive-reservations">
		<main id="main" class="site-main" role="main">


			<header class="page-header">
				<h1><?php _e('Nos séjours', 'twentyfifteen'); ?></h1>
			</header><!-- .page-header -->
			<?php the_content(); ?>
			<?php Online_Booking_Public::the_sejours(20,true); ?>



		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
