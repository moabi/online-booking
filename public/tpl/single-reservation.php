<?php
/**
 * The Template for displaying animations post.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
<!-- SINGLE RESERVATION -->
<div class="pure-g inner-content">
	<div id="primary-b" class="site-content single-animations pure-u-1 ">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<?php
	$term_lieu = wp_get_post_terms($post->ID, 'lieu');
	$term_reservation_type = wp_get_post_terms($post->ID, 'reservation_type');
	$term_type = wp_get_post_terms($post->ID, 'theme');
	$ux = new online_booking_ux;
	

										?>
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
				<?php if(get_field('prix')): ?>
				
				<div class="pure-u-1">
					<?php the_field('prix'); ?>€ / pers
				</div>	
				<?php endif; ?>
				<?php if(get_field('duree')): ?>
				<div class="pure-u-1">
					Durée <?php the_field('duree'); ?>h
				</div>	
				<?php endif; ?>
				<?php if(get_field('nombre_de_personnes')): ?>
				<div class="pure-u-1">
					Jusqu’à <?php the_field('nombre_de_personnes'); ?> personnes
				</div>	
				<?php endif; ?>
				<a class="btn btn-reg" href="<?php echo site_url(); ?>/reservation-service/?addId=<?php the_ID(); ?>">Ajouter cette activité</a>
				<a href="<?php echo site_url(); ?>/nos-sejours/">Voir toutes nos activités</a>
				
								<?php
	echo '<div class="tags-s pure-g">';
	echo '<span class="fs1" aria-hidden="true" data-icon=""></span>';
	foreach($term_type as $key=>$value){
		$term_link = get_term_link( $value );
		echo '<span><a href="' . esc_url( $term_link ) . '">'.$value->name.'</a></span> ';
	}
	echo '</div>';
?>	

			</div>
			<!-- #DETAILS -->
			
		</div>	
		

	
		</div><!-- pure -->
		
		


<div class="pure-u-1 pure-u-md-1-2">


<?php echo $ux->socialShare(); ?>
</div>
</div>


<div class="pure-g">
	<div class="pure-u-md-4-5">
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

<!-- TABS -->
<div class="slick-single">
	
	<div class="single-el">
		<div class="comprend">
			<div id="animation-text">
						<?php 
					$default_attr = array(
			'class' => "alignleft"
		);
					the_post_thumbnail('thumbnail',$default_attr); ?>
				<?php the_content(); ?>
			
			</div>
		</div>
	</div>
	
	<div class="single-el">
		<?php the_field('infos_pratiques'); ?>
	</div>
	
	<div class="single-el">
		<?php the_field('lieu'); ?>
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
<!-- resumee & add -->
<div class="resume pure-u-md-1-5">

</div>
<!-- #resumee & add -->
</div>
	
		
<?php if ( function_exists( 'echo_crp' ) ) echo_crp(); ?>

	</article><!-- #post -->

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
	<!--
<div id="secondary" class="pure-u-1 pure-u-md-6-24">
	<h2><?php _e("D'autres activités qui peuvent vous intéresser","twentyfifteen"); ?></h2>
	
</div>
-->
</div>
<?php get_footer(); ?>



		<?php
			/*
				$postID = $post->ID;
                $price = get_field('prix');
                $personnes = get_field('personnes');
                $budget_min = get_field('budget_min');
                $budget_max = get_field('budget_max');
                $budgMin = $budget_min * $personnes;
                $budgMax = $budget_max * $personnes;
                $theme = get_field('theme');
                $lieu = get_field('lieu');
                $rows = get_field('votre_sejour');
                $row_count = count($rows);
                $lastDay = 86400 * $row_count;
                $departure_date = date("d/m/Y", time()+$lastDay); 

                $arrival_date = date("d/m/Y", time()+86400); 
                
					
					$activityObj = 1;
					$dayTrip = '{';
					if( have_rows('votre_sejour') ):
					    while ( have_rows('votre_sejour') ) : the_row();
					    	$calcDay = 86400 * $activityObj;
					    	$actual_date = date("d/m/Y", time()+$calcDay); 
					    	$dayTrip .= '"'.$actual_date.'" : {';
							if( have_rows('activites') ):
					        while ( have_rows('activites') ) : the_row();
					        	$activityArr = get_sub_field('activite');
					        	$i = 0;
								$len = count($activityArr);
					        	foreach($activityArr as $data){
									$field = get_field('prix', $data->ID);
									$comma = ($i == $len - 1) ? '' : ',';
						        	$dayTrip .= '"'.$data->ID.'": { "name" : "'.$data->post_title.'","price": '.$field.'}'.$comma;
						        	$i++;
					        	}
					        endwhile;
					        endif;
							$dayTrip .= '},';
							$activityObj++;
					    endwhile;
					endif;
					$dayTrip .= '}';*/

					
			?>
			