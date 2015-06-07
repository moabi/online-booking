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
	<div id="primary-b" class="site-content single-animations pure-u-1 pure-u-md-18-24">
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


<div id="secondary" class="pure-u-1 pure-u-md-6-24">
	<h2>D'autres séjours qui peuvent vous intéresser</h2>
	
</div>

</div>
<?php get_footer(); ?>