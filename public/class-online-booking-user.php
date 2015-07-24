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
					$tripDate = $result->booking_date;
					$newDate = date("d/m/y", strtotime($tripDate));
					$newDateDevis = date("dmy", strtotime($tripDate));
					
					echo '<li id="ut-'.$tripID.'">';
					echo '<script>var trip'.$result->ID.' = '.$booking.'</script>';
					echo '<div class="fs1 js-delete-user-trip" aria-hidden="true" data-icon="" onclick="deleteUserTrip('.$tripID.')"></div>';
					
					echo '<a title="Voir votre event" onclick="loadTrip(trip'.$result->ID.',true)" href="#">'.$tripName.'</a>';
					
					echo '<div class="sharetrip">partager votre event : <pre>'.get_bloginfo("url").'/public/?ut='.$tripID.'-'.$userID.'</pre></div>';
					echo '<span class="user-date-invoice"><a class="open-popup-link" href="#tu-'.$tripID.'">Devis n°ol'.$newDateDevis.$tripID.' ('.$newDate.')</a></span>';
					
					$budget = json_decode($booking, true);
					$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];
					//var_dump($budget);
					echo '<div class="mfp-hide" id="tu-'.$tripID.'">';
					echo '<div class="trip-budget-user">';
					echo '<h3>Le budget de votre event</h3>';
					echo '<div class="excerpt-user pure-g">';
					echo '<div class="pure-u-1-3">'.$budget['days'].' jours</div>';
					echo '<div class="pure-u-1-3">'.$budget['participants'].' participants </div>';
					echo '<div class="pure-u-1-3">Buget Max Total : '.$budgetMaxTotal.' </div>';
					
					echo 'Budget Minimum par personne : '.$budget['budgetPerMin'].'<br />';
					echo 'Budget Minimum : '.$budget['budgetPerMin'] * $budget['participants'].'<br />';
					echo 'Budget Maximum par personne : '.$budget['budgetPerMax'].'<br />';
					echo 'Budget Maximum : '.$budget['budgetPerMax'] * $budget['participants'].'<br />';
					echo 'Budget global par personne : '.$budget['currentBudget'].'<br />';
					echo '</div>';
					echo 'Budget Total : '.$budget['currentBudget'] * $budget['participants'].'<br />';
					echo '<h4>Détails de votre event : </h4>';
					$trips = $budget['tripObject'];
					$budgetSingle = array();
					//var_dump(is_array($trips));
					echo '<div class="activity-budget-user pure-g">';
						        echo '<div class="pure-u-1-3">Activité</div>';
						        //echo $value['type'].'<br />';
					            echo '<div class="pure-u-1-3">prix/pers</div>';
					            echo '<div class="pure-u-1-3">prix total</div>';
					echo '</div>';
					foreach ($trips as $trip) {
					    //  Check type
					    if (is_array($trip)){
					        //  Scan through inner loop
					        
					        foreach ($trip as $value) {
						        //calculate 
						        array_push($budgetSingle, $value['price']);
						        //html
						        echo '<div class="activity-budget-user pure-g">';
						        echo '<div class="pure-u-1-3">'.$value['name'].'</div>';
						        //echo $value['type'].'<br />';
					            echo '<div class="pure-u-1-3">'.$value['price'].'</div>';
					            echo '<div class="pure-u-1-3">'.$value['price'] * $budget['participants'].'</div>';
					            echo '</div>';
					        }
					    }else{
					        // one, two, three
					        echo $trip;
					    }
					}
					$single_budg = array_sum($budgetSingle);
					$global_budg = $single_budg * $budget['participants'];
					echo '<div class="activity-budget-user pure-g">';
						        echo '<div class="pure-u-1-3">Budget Total</div>';
						        //echo $value['type'].'<br />';
					            echo '<div class="pure-u-1-3">'.$single_budg.'</div>';
					            echo '<div class="pure-u-1-3">'.$global_budg.'</div>';
					echo '</div>';
					

					
					echo '</div>';
					echo '</div>';
					
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
		
		if(!empty($userID) &&  is_user_logged_in() ):
			$date =  current_time('mysql', 1);
			if(!empty($_COOKIE['reservation'])):
				$bookink_obj = stripslashes( $_COOKIE['reservation'] );
				$data = json_decode($bookink_obj, true);
			else: 
				$bookink_obj = 'nothing was recorded';
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
				array_push($trips, $userTrip->booking_ID);       
			}
			
			if (in_array($tripName, $trips) && count($trips) < 11 ) {
			    online_booking_user::updateTrip($bookink_obj,$tripName);
			    
			} elseif (!in_array($tripName, $trips) && count($trips) < 11 ) {
				//online_booking_user::storeTrip($bookink_obj,$tripName);
				$date =  current_time('mysql', 1);
				$table = $wpdb->prefix.'online_booking';
				$wpdb->insert( 
					$table, 
					array( 
						'user_ID' => $userID, 
						'booking_date' => $date,
						'booking_object' => $bookink_obj,
						'booking_ID' => $tripName,	
					), 
					array( 
						'%d', 
						'%s',
						'%s',
						'%s' 
					) 
				);
				return "stored";
			} else {
				return "10";
			}
			//return $bookink_obj;
		else:
			return "fail to  store trip";
		endif;
		
		
		
	}
	
	
	/*
		delete user's trip to DB
	*/
	public static function  delete_trip($tripIDtoDelete){
		global $wpdb;
		
		$userID = get_current_user_id();
		$date =  current_time('mysql', 1);
		if(!empty($userID) &&  is_user_logged_in() ):
			$table = $wpdb->prefix.'online_booking';
			$rowToDelete = $wpdb->delete( $table, array( 
				'ID' 	=> $tripIDtoDelete,
			 ) );
			$userTripsDelete = "success";
		else: 
			$userTripsDelete = 'failed to delete';
		endif;

		return $userTripsDelete;
		
		
		
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
			'booking_ID' => $tripName,	
		);
		$table = $wpdb->prefix.'online_booking';
		$wpdb->insert($table, $data);

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
