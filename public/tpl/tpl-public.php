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
 $obp = new Online_Booking_Public('ob',1);
 $online_booking_budget = new online_booking_budget();
 $online_booking_user = new online_booking_user;
 
?>

<?php get_header(); ?>

	<div id="primary-invite" class="content-area pure-g tpl-public">
		<div id="content-b" class="site-content-invite pure-u-1">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<div class="entry-content default-page">
					<?php the_content(); ?> 
				</div>
				<?php 
					//get user && trip data
					
					$uri = $_GET['ut'];
					//we should encode the get params at min ?
					$public_url = $obp->decode_str($uri);
					
					 ?>
				<?php 
			if(isset($public_url)){	
			
			$errormsg = "<p>Une erreur est survenue pendant le traitement de votre séjour, merci de revenir vers nous et de nous contacter directement. Nous sommes désolé de cet inconvénient.</p>";
			$data = explode('-',$public_url);
			$user = (isset($data[1])) ? $data[1] : 0; 
			$trip = (isset($data[0])) ? $data[0] : 0;			
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID	
			$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.user_ID = %d
						AND a.ID = %d
						",$user, $trip); 
					
			$results = $wpdb->get_results($sql);
			//var_dump($results);
			if($results){
				$state = $results[0]->validation;
				$booking = $results[0]->booking_object; 
				
				
				$invoiceID = $online_booking_user->get_invoiceID($results[0]);
				$invoicedate = $online_booking_user->get_invoice_date($results[0]);
				//var_dump($booking);
				if($state == 0){
						echo '<script>var trip'.$trip.' = '.$booking.'</script>';
				}
				$editPen = ($state == 0 && is_user_logged_in()) ? '<i class="fa fa-pencil" onclick="loadTrip(trip'.$trip.',true)"></i>' : '';
				echo '<div id="page-header" class="post-content">';

				echo '<div class="pure-g"><div class="pure-u-3-4">';
				echo '<h1>'.$results[0]->booking_ID.' '.$editPen.'</h1></div>';
				echo '<div class="pure-u-1-4 devis-line">';
				if(is_user_logged_in()){
					echo 'Devis n°'.$invoiceID.' du '.$invoicedate;
				}
				echo '</div></div></div>';
				//if validation = 0 -> devis modifiable
				if(intval($state) == 0){
					echo '<script>var trip = '.$booking.'</script>';
					
				}
				
				
				online_booking_budget::the_trip($results[0]->ID , $booking,$state);
				if(intval($state) == 0){
					echo '<div class="post-content">';
					echo $ux->socialShare();
					echo '</div>';
				}
				
				if(intval($state) == 0){
				
				//add a comment section here ?
				}
				
			} else {
				_e('<h1>Désolé, nous ne parvenons pas à retrouver cette reservation</h1>'.$errormsg,'online-booking');
			}
			} else {
				_e('<h1>Désolé, nous ne parvenons pas à retrouver cette reservation</h1>'.$errormsg,'online-booking');
			}
			
				?>
				

			<?php endwhile; ?>
			<?php /*
			<div class="post-content" style="width: 100%;">
				<div class="pure-g">
					<div class="pure-u-1-2">
						<h2>Echangez avec notre spécialiste</h2>
						<?php comment_form('081370138703187'); ?>
					</div>
					<div class="pure-u-1-2">
						<h2>Vos échanges :</h2>
					</div>
				</div>
			</div>
			*/ ?>

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>