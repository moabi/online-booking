<?php
/**
 * The Template for displaying animations post.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
<div class="pure-g inner-content">
	<div id="primary-b" class="site-content single-animations pure-u-1 pure-u-md-24-24">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="entry-header">
			
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php $postid = get_the_ID(); ?> 
		<?php Online_Booking_Public::the_sejour($postid); ?>
			
		</header><!-- .entry-header -->
<div class="clearfix"></div>
<div class="pure-g">
	<div class="pure-u-1 pure-u-md-1-2">
		<div class="padd-l">
		<?php the_post_thumbnail(); ?>
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
					<ul id="event-trip-planning">
					<?php 

					// loop through rows (parent repeater)
					$i = 1;
					while( have_rows('votre_sejour') ): the_row(); ?>
					

							<?php 
								echo '<li><span class="etp-day">Jour '.$i.'</span>';
							// check for rows (sub repeater)
							if( have_rows('activites') ): ?>
								<ul class="etp-days">
								<?php 

								// loop through rows (sub repeater)
								while( have_rows('activites') ): the_row();

									// display each item as a list - with a class of completed ( if completed )
									?>
									<?php $postActivity = get_sub_field('activite'); ?>
									<?php //var_dump($postActivity); ?>
									<?php foreach($postActivity as $data){
										echo '<li><h3>';
										echo '<a href="'.get_permalink($data->ID).'">';
										echo $data->post_title;
										
										echo get_the_post_thumbnail( $data->ID, 'thumbnail' );
										echo '</a>';
										echo '</li>';
									} ?>
								
						
									
								<?php endwhile; ?>
								</ul>
							<?php endif; //if( get_sub_field('items') ): ?>
						
							<?php $i++; ?>
					<?php endwhile; // while( has_sub_field('to-do_lists') ): ?>
					</ul>
				<?php endif; // if( get_field('to-do_lists') ): ?>

			
			
<h2>Photos de l'event</h2>
	<div class="sej">
			<?php
	$images = get_field('gallerie');

if( $images ): ?>
<div class="clearfix"></div>
        <ul class="slick-multi">
            <?php foreach( $images as $image ): ?>
                <li>
                    <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
                    
                </li>
            <?php endforeach; ?>
        </ul>


<?php endif; ?>
		
	</div>	



	</article><!-- #post -->
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->



</div>
<?php get_footer(); ?>