<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/admin
 * @author     little-dream.fr <david@loading-data.com>
 */
class Online_Booking_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Online_Booking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Online_Booking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/online-booking-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Online_Booking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Online_Booking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/online-booking-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	public function online_booking_menu(){	
		add_options_page( 'Online plugin Help', 'Online Booking Help', 'publish_pages', 'online-booking-admin-display', array( $this, 'helper' ) );
	}
	
	public function helper(){
		$admin_view = plugin_dir_path( __FILE__ ) . 'partials/online-booking-admin-display.php';
		include_once $admin_view;
	}
	
	public static function get_users_booking($validation = '0'){
			global $wpdb;
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID	
			$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.validation = %d
						",$validation); 
					
			$results = $wpdb->get_results($sql);
			//var_dump($results);
			echo '<table id="ut-onlinebook" class="wp-list-table widefat fixed striped posts">';
			echo '<thead><tr>';
			echo '<td>delete</td>';
			echo '<td>User Name</td>';
			echo '<td>Email</td>';
			echo '<td>see trip</td>';
			echo '<td>invoice date</td>';
			echo '</tr></thead>';
			foreach ( $results as $result ) 
				{
					$booking = $result->booking_object; 
					$bdate = $result->booking_date;
					$tripID = $result->ID;
					$tripName = $result->booking_ID;
					$tripDate = $result->booking_date;
					$newDate = date("d/m/y", strtotime($tripDate));
					$userID = $result->user_ID;
					$user_info = get_userdata( $userID );
					
					echo '<tr>';
					echo '<td><script>var trip'.$result->ID.' = '.$booking.'</script>';
					echo '<span class="fs1 js-delete-user-trip" aria-hidden="true" data-icon="" onclick="deleteUserTrip('.$tripID.')"></span>';
					echo '<input type="radio"" name="deleteSingleTrip" value="'.$result->ID.'">';
					echo '</td>';
					echo '<td>'.$user_info->user_login . "</td>";
					echo '<td>'.$user_info->user_email . "</td>";
					echo '<td><a title="Voir l\'event" onclick="loadTrip(trip'.$result->ID.',true)" href="#">'.$tripName.'</a></td>';
					echo '<td><span class="user-date-invoice">'.$newDate.'</span></td>';
					
					echo '<tr>';
				}
				echo '</table><br />';
				echo '<button>Effacer</button>';
				echo '<button>Valider</button>';

	}



}
