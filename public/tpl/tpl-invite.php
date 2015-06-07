<?php
/**
 * Template Name: booking-invite
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
?>
<?php acf_form_head(); 
	
	
/**
 * Deregister the admin styles outputted when using acf_form
 */
add_action( 'wp_print_styles', 'tsm_deregister_admin_styles', 999 );
function tsm_deregister_admin_styles() {
	// Bail if not logged in or not able to post
	if ( ! ( is_user_logged_in() || current_user_can('publish_posts') ) ) {
		return;
	}
	wp_deregister_style( 'wp-admin' );
}

?>
<?php get_header(); ?>

	<div id="primary-invite" class="content-area pure-g">
		<div id="content-b" class="site-content-invite pure-u-1 pure-u-md-1-2">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php acf_form(array(
					'post_id'		=> 'new_post',
					/* (boolean) Whether or not to show the post title text field. Defaults to false */
	'post_title' => true,
	
	/* (boolean) Whether or not to show the post content editor field. Defaults to false */
	'post_content' => true,
					'new_post'		=> array(
						'post_type'		=> 'reservation',
						'post_status'		=> 'pending'
					),
					'submit_value'		=> 'Proposez votre event'
				)); ?>

			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>