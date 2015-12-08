<?php


/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/public
 * @author     little-dream.fr <david@loading-data.com>
 */
 
class online_booking_partners  {

		/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/*
		retrieve trips
	*/
	public static function get_partner_activites(){

		global $wpdb;
		$userID = get_current_user_id();
			
		// The Query
		$args = array( 
		'author' => $userID ,
		'post_status' => array( 'pending', 'draft', 'publish' ),
		'post_type' => 'reservation'
		);
		$the_query = new WP_Query( $args );
		
		// The Loop
		if ( $the_query->have_posts() ) {
			echo '<ul id="userTrips" class="partners u-'.$userID.'">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				echo '<li>';
				echo  get_the_title();
				if ( get_post_status () == 'pending' ) {
					echo 'En attente de publication';
				} elseif(get_post_status () == 'publish') {
					echo 'public';
				}
				echo '</li>';
			}
			echo '</ul>';
		} else {
			// no posts found
			_e('Pas encore d\'activité.','online-booking');
		}
		/* Restore original Post Data */
		wp_reset_postdata();

	}




}