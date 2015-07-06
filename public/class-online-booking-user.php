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
 
class online_booking_user  {

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
	public static function get_user_booking(){
			global $wpdb;
			$userID = get_current_user_id();
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID	
			$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.user_ID = %d
						",$userID); 
					
			$results = $wpdb->get_results($sql);
			//var_dump($results);
			echo '<ul id="userTrips">';
			foreach ( $results as $result ) 
				{
					$booking = $result->booking_object; 
					$bdate = $result->booking_date;
					$tripID = $result->ID;
					$tripName = $result->booking_ID;
					
					echo '<li><div class="fs1" aria-hidden="true" data-icon="" onclick="deleteUserTrip('.$tripID.','.$userID.')"></div>';
					echo '<a href="'.get_bloginfo("url").'/public/?ut='.$tripID.'">'.$tripName.'</a>';
					echo '<script>var trip'.$result->ID.' = '.$booking.'</script>';
					echo '</li>';
				}
			echo '</ul>';
	}
	
	/*
		save user's trip to DB
	*/
	public static function  save_trip($tripName){
		global $wpdb;
		
		$userID = get_current_user_id();
		$date =  current_time('mysql', 1);
		if(!empty($_COOKIE['reservation'])):
			$bookink_obj = stripslashes( $_COOKIE['reservation'] );
		else: 
			$bookink_obj = NULL;
		endif;
		$table = $wpdb->prefix.'online_booking';
		$userTrips = $wpdb->get_results( $wpdb->prepare("
				SELECT * 
				FROM event_wp_online_booking
				WHERE user_ID = %d 
				",
				$userID
				) );
		$trips = array();
		foreach ($userTrips as $userTrip) { 
			echo $userTrip->booking_ID;
			array_push($stack, $userTrip->booking_ID);       
		}
		if (in_array($tripName, $trips)) {

		    online_booking_user::updateTrip($bookink_obj,$tripName);
		} else {
			online_booking_user::storeTrip($bookink_obj,$tripName);
		}
		return $userTrips;
		
		
		
	}
	/*
		store DATA
		@param obj 
	*/
	private static function storeTrip ($bookink_obj,$tripName){
		global $wpdb;
		$userID = get_current_user_id();
		$date =  current_time('mysql', 1);
		$data = array( 
			'user_ID' => $userID, 
			'booking_date' => $date,
			'booking_object' => $bookink_obj,
			'booking_ID' => $tripName	
		);
		$wpdb->insert( 
		$wpdb->prefix.'online_booking', $data);
		
		return 'done';
	}
	
	private static function updateTrip($bookink_obj,$tripName){
		global $wpdb;
		$userID = get_current_user_id();
		$date =  current_time('mysql', 1);
		$data = array( 
			'user_ID' => $userID, 
			'booking_date' => $date,
			'booking_object' => $bookink_obj,
			'booking_ID' => $tripName	
		);
		$wpdb->insert( 
		$wpdb->prefix.'online_booking', $data);
		
		return 'updated';
	}

}
