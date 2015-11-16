<?php
/**
 * The Template for displaying animations post.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
<!-- SINGLE SEJOUR -->
<div class="pure-g inner-content">
	<div id="primary-b" class="site-content single-animations pure-u-1 pure-u-md-24-24">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="entry-header">
			<?php 
				$postid = get_the_ID(); 
				$term_lieu = wp_get_post_terms($postid, 'lieu');
			?> 
			<h1 class="entry-title">
				<?php the_title(); ?> 
				<?php Online_Booking_Public::the_sejour($postid); ?>
				<span class="locate-place">
			<?php 
				echo '<span class="fs1" aria-hidden="true" data-icon=""></span>';
				foreach($term_lieu as $key=>$value){
				  echo '<span>'.$value->name.'</span> ';
				} 
			?>
				</span>
			</h1>
		</header><!-- .entry-header -->
		
		
<div class="clearfix"></div>
<div class="pure-g">
	<div class="pure-u-1 pure-u-md-1-2">
		<div class="padd-l">
	<div class="sej">
			<?php
	$images = get_field('gallerie');

if( $images ): ?>
<div class="clearfix"></div>
        <ul class="slickReservation img-gallery">
            <?php foreach( $images as $image ): ?>
                <li>
                	<a href="<?php echo $image['sizes']['full-size']; ?>" class="img-pop">
                    <img src="<?php echo $image['sizes']['full-size']; ?>" alt="<?php echo $image['alt']; ?>" />
                	</a>
                    
                </li>
            <?php endforeach; ?>
        </ul>
<?php else: ?>
<?php the_post_thumbnail(); ?>

<?php endif; ?>
		
	</div>	
	
		
		</div>
	</div>
	<div class="pure-u-1 pure-u-md-1-2">
		<div class="padd-l">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
		</div>
		
		</div>
</div>

<h2>Activités de l'event</h2>

<?php 

				// check for rows (parent repeater)
				if( have_rows('votre_sejour') ): ?>
					<div id="event-trip-planning" class="pure-g">
					<?php 

					// loop through rows (parent repeater)
					$i = 1;
					while( have_rows('votre_sejour') ): the_row(); ?>
					

							<?php 
								echo '<div class="pure-u-1"><span class="etp-day">Jour '.$i.'</span>';
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
										$term_lieu = wp_get_post_terms($data->ID, 'lieu');
										$term_reservation_type = wp_get_post_terms($data->ID, 'reservation_type');
										$term_type = wp_get_post_terms($data->ID, 'theme');
										
										
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
											
											echo '<div class="tags-s">';
											/*
											echo '<span class="fs1" aria-hidden="true" data-icon=""></span>';
											foreach($term_lieu as $key=>$value){
											  echo '<span>'.$value->name.'</span> ';
											}*/
											echo '<span class="fs1" aria-hidden="true" data-icon=""></span>';
											foreach($term_type as $key=>$value){
											  echo '<span>'.$value->name.'</span> ';
											}
											
											
											echo '</div>';
											
											
											
										echo '</div><div class="clearfix"></div>';
										echo '</div>';
										endif;
									} ?>
								
						
									
								<?php endwhile; ?>
								</div>
							<?php endif; //if( get_sub_field('items') ): ?>
						
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