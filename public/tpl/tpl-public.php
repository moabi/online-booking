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

<?php get_header(); ?>

	<div id="primary-invite" class="content-area pure-g">
		<div id="content-b" class="site-content-invite pure-u-1 post-content">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				

				
				<div class="entry-content default-page">
					<?php the_content(); ?>
				</div>
				<?php $data = explode('-',$_GET['ut']); ?>
				<?php 
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
			$booking = $results[0]->booking_object; 
			echo '<div id="page-header">';
			echo '<h1>'.$results[0]->booking_ID.'</h1></div>';
			echo '<script>var trip = '.$booking.'</script>';
			$online_booking_budget = new online_booking_budget();
			online_booking_budget::the_trip($results[0]->booking_ID , $booking, '22/07/22');
			
				?>
				
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
		$content .= '<h5>Partager votre Event Onlyoo</h5> <a class="crunchify-link crunchify-twitter" href="'. $twitterURL .'" target="_blank">Twitter</a>';
		$content .= '<a class="crunchify-link crunchify-facebook" href="'.$facebookURL.'" target="_blank">Facebook</a>';
		$content .= '<a class="crunchify-link crunchify-googleplus" href="'.$googleURL.'" target="_blank">Google+</a>';
		$content .= '<a class="crunchify-link crunchify-buffer" href="'.$bufferURL.'" target="_blank">Buffer</a>';
		$content .= '</div>';
		echo $content;
		?>
			<?php endwhile; ?>
			
			

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>