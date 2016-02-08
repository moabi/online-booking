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
 
 $ux = new online_booking_ux;
?>

<?php get_header(); ?>

	<div id="primary-invite" class="content-area pure-g">
		<div id="content-b" class="site-content-invite pure-u-1 post-content">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<div class="entry-content default-page">
					<?php the_content(); ?> 
				</div>
				<?php 
					//get user && trip data
					$obp = new Online_Booking_Public('ob',1);
					$uri = $_GET['ut'];
					
					$public_url = $obp->decode_str($uri);
					
					 ?>
				<?php 
			if(isset($public_url)){	
				
			$data = explode('-',$public_url);
			$user = $data[1]; 
			$trip = $data[0];			
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID	
			$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.user_ID = %d
						AND a.ID = %d
						",$user, $trip); 
					
			$results = $wpdb->get_results($sql);
			
			if($results){
				$booking = $results[0]->booking_object; 
				echo '<div id="page-header">';

				echo '<div class="pure-g"><div class="pure-u-1">';
				echo '<h1>'.$results[0]->booking_ID.'</h1></div>';
				echo '</div></div>';
				echo '<script>var trip = '.$booking.'</script>';
				$online_booking_budget = new online_booking_budget();
				online_booking_budget::the_trip($results[0]->booking_ID , $booking);
				echo $ux->socialShare();
				
				
			} else {
				_e('Désolé, nous ne parvenons pas à retrouver cette reservation','online-booking');
			}
			} else {
				_e('Désolé, nous ne parvenons pas à retrouver cette reservation','online-booking');
			}
			
				?>
				

			<?php endwhile; ?>
			
			

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>