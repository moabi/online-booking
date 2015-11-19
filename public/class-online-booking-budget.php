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
 
class online_booking_budget  {

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
	
	public static function the_budget($tripID , $item, $tripDate){
		

		$budget = json_decode($item, true);
		$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];
		
		$newDate = date("d/m/y", strtotime($tripDate));
		$newDateDevis = date("dmy", strtotime($tripDate));
		
		//VISIBLE LINK
		echo '<span class="user-date-invoice"><a class="open-popup-link" href="#tu-'.$tripID.'">Devis n°ol'.$newDateDevis.$tripID.' ('.$newDate.')</a></span>';
		
		
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
		echo '</div>';

	}
	
	
	/*
		Display a SEJOUR from the jSON file in DB
		@param integer ($tripID)
		@param string ($item)
	*/
	public static function the_trip($tripID , $item){

		$budget = json_decode($item, true);
		$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];
		$ux = new online_booking_ux;
		//var_dump($budget);
		echo '<div id="event-trip-planning" class="trip-public">';
		echo '<div class="trip-public-user">';
		echo '<h4>'.__('Détails de votre event','online-booking').' : </h4>';
		$trips = $budget['tripObject'];
		$budgetSingle = array();
		//var_dump(is_array($trips));
		echo '<div class="activity-budget-user pure-g">';
		            echo '<div class="pure-u-1-3">'.$budget['participants'].' personnes</div>';
		echo '</div>';
		
		$trip_dates =  array_keys($trips);
		$days_count = 0;
		foreach ($trips as $trip) {
			echo '<div class="event-day day-content">';
			echo '<div class="etp-day">';
			echo '<div class="day-title"';
			echo '<span class="fs1" aria-hidden="true" data-icon=""></span><br />';
			echo $trip_dates[$days_count].'</div>';
			echo '</div>';
			
		    //  Check type
		    if (is_array($trip)){
		        //  Scan through inner loop
		        //var_dump($trip);
		        $trip_id =  array_keys($trip);
		        $i = 0;
		        echo '<div class ="etp-days" >';
		        foreach ($trip as $value) {
			        //calculate 
			        
			        array_push($budgetSingle, $value['price']);
			        //html
			        echo '<div data-id="'.$trip_id[$i].'" class="pure-u-1 single-activity-row">';
			        echo '<div class="sejour-type">';
					echo $ux->get_reservation_type($trip_id[$i]);
					echo '</div>';
										
			        echo '<div class="pure-u-1 pure-u-md-7-24">';
			        echo get_the_post_thumbnail($trip_id[$i],'thumbnail');
			        echo'</div>';
			        
			        echo '<div class="pure-u-1 pure-u-md-17-24">';
			        echo '<h3><a href="'.get_permalink($trip_id[$i]).'" target="_blank">';
			        echo $value['name'].'</a></h3>';
		            echo get_field('la_prestation_comprend',$trip_id[$i]);
		            echo '</div>';
		            echo '</div>';
		            $i++;
		        }
		        echo '</div>';
		        $days_count++;
		    }else{
		        // one, two, three
		        echo $trip;
		    }
		    echo '</div>';
		}

		
		
		echo '</div>';
		echo '</div>';

	}





}