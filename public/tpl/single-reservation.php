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
	$ux = new online_booking_ux;
?>
<?php if (has_post_thumbnail( $post->ID ) ): ?>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) ); ?>
<div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')">
</div>
<?php endif; ?>
<!-- SINGLE RESERVATION -->
<div class="pure-g inner-content">
	<div id="primary-b" class="site-content single-animations pure-u-1 ">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<h1 class="entry-title"><?php the_title(); ?></h1>
<div class="clearfix"></div>
<div class="pure-g">

<!-- SLIDER -->
	<div id="activity-gallery" class="pure-u-1 pure-u-md-7-12">
		<?php echo $ux->slider(); ?>
	</div><!-- #activity -->
<!-- #SLIDER -->

		<div id="single-top-information" class="pure-u-1 pure-u-md-5-12">
			<!-- DETAILS -->
			<div class="box-price">
				<?php echo $ux->get_place($post->ID); ?>
				
				<?php if(get_field('duree')): ?>
				<div class="pure-u-1">
					<div class="fs1" aria-hidden="true" data-icon="}"></div>
					Durée : <strong><?php the_field('duree'); ?>h</strong>
				</div>	
				<?php endif; ?>
				<?php if(get_field('nombre_de_personnes')): ?>
				<div class="pure-u-1">
					<div class="fs1" aria-hidden="true" data-icon=""></div>
					Jusqu’à : <strong><?php the_field('nombre_de_personnes'); ?> personnes</strong>
				</div>	
				<?php endif; ?>
				
				<?php if(get_field('prix')): ?>
				<div class="pure-u-1">
					<div class="fs1" aria-hidden="true" data-icon=""></div>
					Tarif : <strong><?php the_field('prix'); ?>€ / pers</strong>
				</div>	
				<?php endif; ?>
				
				<?php echo $ux->single_reservation_btn($post->ID); ?>
				<?php //echo $ux->get_theme_terms($post->ID); ?>	
			</div>
			<!-- #DETAILS -->
		</div>	
		

	
		</div><!-- pure -->
		
</div>

<div id="main-content">
	 
<div id="middle-bar" class="pure-g">
	<div class="pure-u-md-15-24">
<!-- NAVIGATION -->
<div class="pure-g" id="single-tabber">
	<div class="pure-u-1-3 active">
		<a href="#" class="tabsto" data-target="0">Description</a>
	</div>
	<div class="pure-u-1-3">
		<a href="#" class="tabsto" data-target="1">Informations pratiques</a>
	</div>
	<div class="pure-u-1-3">
		<a href="#" class="tabsto" data-target="2">Lieu</a>
	</div>
</div>

</div>
<div class="pure-u-md-9-24">
	<?php echo $ux->socialShare(); ?>
</div>
</div>
<!-- TABS -->
<div id="tabs-single" class="slick-single">
	
	<div class="single-el">
		<div class="comprend">
			<div id="animation-text">
						<?php 
					$default_attr = array(
			'class' => "alignleft"
		);
					the_post_thumbnail('thumbnail',$default_attr); ?>
				<?php 
					if(get_the_content()){
						the_content(); 
					}else{
						_e('Contenu non disponible','online-booking');
					}
					
					?>
			
			</div>
		</div>
	</div>
	
	<div class="single-el">
		<?php 
			if(get_field('infos_pratiques')){
				the_field('infos_pratiques');
			}else{
				_e('Contenu non disponible','online-booking');
			}
			 ?>
	</div>
	
	<div class="single-el">
		<?php 
			if(get_field('lieu')){
				the_field('lieu');
			}else{
				_e('Contenu non disponible','online-booking');
			}
			?>
	</div>
	
</div>

<script type="text/javascript">
	jQuery(function(){
		var $ = jQuery;
		$('.slick-single').slick({
		  dots: false,
		  arrows: false,
		  infinite: true,
		  speed: 500,
		   slidesToShow: 1,
		  slidesToScroll: 1
		});
				
		$('.tabsto').on('click', function() {
			$target = $(this).attr('data-target');
			$(this).parent().addClass('active').siblings().removeClass('active');
			$('.slick-single').slick('slickGoTo',$target);
});
	})
</script>	
<!-- #tabs -->
</div>
	</div>
		
<?php if ( function_exists( 'echo_crp' ) ) echo_crp(); ?>

	</article><!-- #post -->

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
</div>
<?php get_footer(); ?>
