<?php
/**
 * Template Name: compte
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<section id="primary" class="content-area archive-reservations">
		<main id="main" class="site-main" role="main">


			<header class="page-header">
				<h1><?php _e('Mon compte', 'online-booking'); ?></h1>
			</header><!-- .page-header -->

<div class="pure-g" id="account-wrapper">
	
	<div id="primary-b" class="booking pure-u-1 pure-u-md-12-24">
		<div class="padd-l">
			<h2><?php _e('Mes réservations', 'online-booking'); ?></h2>
			<?php online_booking_user::get_user_booking(); ?>

		</div>
	</div>
	<div id="primary-b" class="booking pure-u-1 pure-u-md-12-24">
		<div class="padd-l">

			<h2>Mon profil</h2>
				<?php echo do_shortcode('[userpro template=edit]'); ?>
		</div>
	</div>
	
</div>


		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>