<?php
/**
 * The Template for displaying animations post.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

<?php 
	$postid = get_the_ID(); 
	$ux = new online_booking_ux;
?>
<!-- SINGLE SEJOUR -->
<div class="pure-g inner-content">
	<div id="primary-b" class="site-content single-animations pure-u-1 pure-u-md-24-24">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="entry-header">
			<h1 class="entry-title">
				<?php the_title(); ?> 
				<span class="locate-place">
			<?php echo $ux->get_place($postid); ?>
				</span>
			</h1>
		</header><!-- .entry-header -->
		
		
<div class="clearfix"></div>
<div class="pure-g">
	<div class="pure-u-1 pure-u-md-7-12">
		<div class="padd-l">
	<div class="sej">
<?php echo $ux->slider(); ?>
		
	</div>	
	
		
		</div>
	</div>
	<div class="pure-u-1 pure-u-md-5-12">
		<div class="box-price">
			
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
			<?php Online_Booking_Public::the_sejour_btn($postid); ?>
		</div>
		
		</div>
</div>
<?php echo $ux->socialShare(); ?>
<h2>Activités de l'event</h2>

<?php 

				// check for rows (parent repeater)
				if( have_rows('votre_sejour') ): ?>
					<div id="event-trip-planning">
					<?php 

					// loop through rows (parent repeater)
					$i = 1;
					while( have_rows('votre_sejour') ): the_row(); ?>
					

							<?php 
								echo '<div class="event-day"><span class="etp-day">'.__('Journée','online-bookine').' '.$i.'</span>';
							// check for rows (sub repeater)
							if( have_rows('activites') ): ?>
								<div class="etp-days pure-g">
								<?php 

								// loop through rows (sub repeater)
								while( have_rows('activites') ): the_row();

									// display each item as a list - with a class of completed ( if completed )
									?>
									<?php $postActivity = get_sub_field('activite'); ?>
									<?php //var_dump($postActivity); ?>
									<?php foreach($postActivity as $data){
										
										$post_status = get_post_status( $data->ID );
										
										if($post_status == "publish"):

										$term_reservation_type = wp_get_post_terms($data->ID, 'reservation_type');

										echo '<div class="pure-u-1 single-activity-row">';
										
										echo '<div class="pure-u-1 pure-u-md-3-24">';
										echo '<a href="'.get_permalink($data->ID).'">';
										echo get_the_post_thumbnail( $data->ID, 'square' );
										echo '</a>';
										echo '</div>';
										
										echo '<div class="pure-u-1 pure-u-md-21-24">';
											echo '<h3>';
											foreach($term_reservation_type as $key=>$value){
											  echo '<span class="dcicons '.$value->slug.'"></span>';
											}
											echo '<a href="'.get_permalink($data->ID).'">';
											echo $data->post_title;
											echo '</a></h3>';
											echo get_field('la_prestation_comprend', $data->ID);
											echo '<div class="tags-s">';
											echo $ux->get_theme_terms($data->ID);
											echo '</div>';
										echo '</div>';
										
										echo '</div>';
										endif;
									} ?>
								
						
									
								<?php endwhile; ?>
								</div>
							<?php endif; //if( get_sub_field('items') ): ?>
						</div>
							<?php $i++; ?>
					<?php endwhile; // while( has_sub_field('to-do_lists') ): ?>
					</div>
				<?php endif; // if( get_field('to-do_lists') ): ?>

			
			




	</article><!-- #post -->
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->



</div>
<?php get_footer(); ?>