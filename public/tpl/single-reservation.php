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
	

										?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<div class="clearfix"></div>
<div class="pure-g">
	<div id="activity-gallery" class="pure-u-1">
		
		<div id="single-top-information">
			<div class="pure-g">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</div>
			<div class="pure-g">
				<div class="pure-u-1-3">
					<?php the_field('prix'); ?>€ / pers
				</div>	
				<div class="pure-u-1-3">
					Durée <?php the_field('duree'); ?>h
				</div>	
				<div class="pure-u-1-3">
					Jusqu’à <?php the_field('nombre_de_personnes'); ?> personnes
				</div>	
			</div>
			
		</div>
		
		
<?php
		//CAROUSSEL
	$images = get_field('gallerie');
if( $images ): ?>
<div class="clearfix"></div>
        <ul class="slickReservation img-gallery">
            <?php foreach( $images as $image ): ?>
                <li style="background: url(<?php echo $image['sizes']['full-size']; ?>);">
                	<!--<a href="<?php echo $image['sizes']['full-size']; ?>" class="img-pop">
                    <img src="<?php echo $image['sizes']['full-size']; ?>" alt="<?php echo $image['alt']; ?>" />
                	</a>-->
                    
                </li>
            <?php endforeach; ?>
        </ul>


<?php endif; ?>

	</div>
<div class="pure-u-1 pure-u-md-1-2">
				<?php
	echo '<div class="tags-s pure-g">';
	echo '<span class="fs1" aria-hidden="true" data-icon=""></span>';
	foreach($term_lieu as $key=>$value){
		$term_link = get_term_link( $value );
	  echo '<span><a href="' . esc_url( $term_link ) . '">'.$value->name.'</a></span> ';
	}
	echo '<div class="clearfix"></div>';
	echo '<span class="fs1" aria-hidden="true" data-icon=""></span>';
	foreach($term_type as $key=>$value){
		$term_link = get_term_link( $value );
		echo '<span><a href="' . esc_url( $term_link ) . '">'.$value->name.'</a></span> ';
	}
	echo '<div class="clearfix"></div>';
	//echo '<span>'.get_field('prix').'euros/personne</span>';
	echo '</div>';
	echo '<div class="clearfix"></div>';
											
											?>	
											
</div>	

<div class="pure-u-1 pure-u-md-1-2">
	<style>
	.crunchify-link {
    padding: 5px 10px;
    color: white;
    font-size: 12px;
}
 
.crunchify-link:hover,.crunchify-link:active {
    color: white;
}
 
.crunchify-twitter {
    background: #41B7D8;
}
 
.crunchify-twitter:hover,.crunchify-twitter:active {
    background: #279ebf;
}
 
.crunchify-facebook {
    background: #3B5997;
}
 
.crunchify-facebook:hover,.crunchify-facebook:active {
    background: #2d4372;
}
 
.crunchify-googleplus {
    background: #D64937;
}
 
.crunchify-googleplus:hover,.crunchify-googleplus:active {
    background: #b53525;
}
 
.crunchify-buffer {
    background: #444;
}
 
.crunchify-buffer:hover,.crunchify-buffer:active {
    background: #222;
}
 
.crunchify-social {
    margin: 20px 0px 25px 0px;
    -webkit-font-smoothing: antialiased;
    font-size: 12px;
}
</style>

<?php
		// Get current page URL
		$shortURL = get_permalink();
		
		// Get current page title
		$shortTitle = get_the_title();
		
		// Construct sharing URL without using any script
		$twitterURL = 'https://twitter.com/intent/tweet?text='.$shortTitle.'&amp;url='.$shortURL.'&amp;via=onlyoo';
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$shortURL;
		$googleURL = 'https://plus.google.com/share?url='.$shortURL;
		$bufferURL = 'https://bufferapp.com/add?url='.$shortURL.'&amp;text='.$shortTitle;
	
		// Add sharing button at the end of page/page content
		$content = '<div class="crunchify-social">';
		$content .= '<a class="crunchify-link crunchify-twitter" href="'. $twitterURL .'" target="_blank">Twitter</a>';
		$content .= '<a class="crunchify-link crunchify-facebook" href="'.$facebookURL.'" target="_blank">Facebook</a>';
		$content .= '<a class="crunchify-link crunchify-googleplus" href="'.$googleURL.'" target="_blank">Google+</a>';
		$content .= '<a class="crunchify-link crunchify-buffer" href="'.$bufferURL.'" target="_blank">Buffer</a>';
		$content .= '</div>';
		echo $content;
		?>
</div>
</div>

<div class="comprend">
	
	<h2>Description</h2>
	<div id="animation-text">
				<?php 
			$default_attr = array(
	'class' => "alignleft"
);
			the_post_thumbnail('thumbnail',$default_attr); ?>
		<?php the_content(); ?>
	
	</div>
</div>

<div class="pure-g">
		<div class="pure-u-1 pure-u-md-1-2">
		<h2>Informations pratiques</h2>
		<?php the_field('infos_pratiques'); ?>
	</div>
	<div class="pure-u-1 pure-u-md-1-2">
		<div id="animation-excerpt">		
		<?php the_field('la_prestation_comprend'); ?>
		</div>
	</div>
</div>
		
<?php if ( function_exists( 'echo_ald_crp' ) ) echo_ald_crp(); ?>

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
			