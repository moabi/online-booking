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
 	define('BOOKING_URL','reservation-service');
	define('CONFIRMATION_URL','validation-devis');
	define('SEJOUR_URL','nos-sejours');
	
class Online_Booking_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	public function get_plugin_utilities($name){
		$utility = '';
		if($name == 'thumb'):
			$utility = plugin_dir_url( __FILE__ ) ."img/default.jpg";
		endif;
		
		echo $utility;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/online-booking-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'plugins', plugin_dir_url( __FILE__ ) . 'css/onlyoo-plugins.css', array(), $this->version, 'all' );
		wp_enqueue_style($this->plugin_name.'jquery-ui', plugin_dir_url( __FILE__ ) . 'js/jquery-ui/jquery-ui.min.css',array(), $this->version, 'all');

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_enqueue_script( $this->plugin_name.'moment', plugin_dir_url( __FILE__ ) . 'js/moment-with-locales.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'jqueryUi', plugin_dir_url( __FILE__ ) . 'js/jquery-ui/jquery-ui.min.js', array( 'jquery' ), $this->version, true );
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/online-booking-plugins.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'booking-custom', plugin_dir_url( __FILE__ ) . 'js/online-booking-custom.js', array( 'jquery' ), $this->version, true );

	}



public function get_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'reservation') {
          $single_template = plugin_dir_path( __FILE__ ) . 'tpl/single-reservation.php';
     } else if ($post->post_type == 'sejour') {
          $single_template = plugin_dir_path( __FILE__ ) . 'tpl/single-sejour.php';
     } 
     return $single_template;
}


/*
 * add page templates
*/
public function booking_page_template( $page_template )
{
    if ( is_page( BOOKING_URL ) ) {
        $page_template = plugin_dir_path( __FILE__ ) .'tpl/tpl-booking.php';
        
    }
    elseif ( is_page( SEJOUR_URL ) ) {
        $page_template = plugin_dir_path( __FILE__ ) .'tpl/archive-sejours.php';
        
    }
    elseif ( is_page( 'compte' ) ) {
        $page_template = plugin_dir_path( __FILE__ ) .'tpl/tpl-compte.php';
        
    } elseif ( is_page( 'public' ) ) {
        $page_template = plugin_dir_path( __FILE__ ) .'tpl/tpl-public.php';
        
    }
    return $page_template;
}

/**
 * A function used to programmatically create a post in WordPress. The slug, author ID, and title
 * are defined within the context of the function.
 *
 * @returns -1 if the post was never created, -2 if a post with the same title exists, or the ID
 *          of the post if successful.
 */
public function create_booking_pages() {

	// Initialize the page ID to -1. This indicates no action has been taken.
	$post_id = -1;

	// Setup the author, slug, and title for the post
	$author_id = 1;

	// If the page doesn't already exist, then create it
	if( null == get_page_by_title( 'Nos séjours' ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'		=>	SEJOUR_URL,
				'post_title'		=>	'Nos séjours',
				'post_status'		=>	'publish',
				'post_type'		=>	'page'
			)
		);

	// Otherwise, we'll stop
	} elseif( null == get_page_by_title( 'Validation demande de devis' ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'			=>	CONFIRMATION_URL,
				'post_title'		=>	__('Validation demande de devis','onlyoo'),
				'post_status'		=>	'publish',
				'post_type'			=>	'page',
			)
		);
	
	}elseif( null == get_page_by_title( 'Réservation' ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'		=>	BOOKING_URL,
				'post_title'		=>	'Réservation',
				'post_status'		=>	'publish',
				'post_type'		=>	'page'
			)
		);

	// Otherwise, we'll stop
	} elseif( null == get_page_by_title( 'public' ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'		=>	'public',
				'post_title'		=>	'public',
				'post_status'		=>	'publish',
				'post_type'		=>	'page'
			)
		);

	// Otherwise, we'll stop
	} elseif( null == get_page_by_title( 'Mon compte' ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'		=>	'compte',
				'post_title'		=>	'Mon compte',
				'post_status'		=>	'publish',
				'post_type'		=>	'page'
			)
		);

	// Otherwise, we'll stop
	}else {

    		// Arbitrarily use -2 to indicate that the page with the title already exists
    		$post_id = -2;

	} // end if

} 


/*
* provide a way to work with date range
*/	
public function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}
// Register Custom Taxonomy
public function lieu() {

	$labels = array(
		'name'                       => _x( 'lieux', 'Taxonomy General Name', 'twentyfifteen' ),
		'singular_name'              => _x( 'lieu', 'Taxonomy Singular Name', 'twentyfifteen' ),
		'menu_name'                  => __( 'lieux', 'twentyfifteen' ),
		'all_items'                  => __( 'Tous les lieux', 'twentyfifteen' ),
		'parent_item'                => __( 'Parent', 'twentyfifteen' ),
		'parent_item_colon'          => __( 'Parent lieu', 'twentyfifteen' ),
		'new_item_name'              => __( 'Nouveau lieu', 'twentyfifteen' ),
		'add_new_item'               => __( 'Ajouter nouveau lieu', 'twentyfifteen' ),
		'edit_item'                  => __( 'Editer lieu', 'twentyfifteen' ),
		'update_item'                => __( 'Mettre à jout ', 'twentyfifteen' ),
		'view_item'                  => __( 'Voir lieu', 'twentyfifteen' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'twentyfifteen' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'twentyfifteen' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'twentyfifteen' ),
		'popular_items'              => __( 'Popular Items', 'twentyfifteen' ),
		'search_items'               => __( 'Search Items', 'twentyfifteen' ),
		'not_found'                  => __( 'Not Found', 'twentyfifteen' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'lieu', array( 'reservation','sejour' ), $args );

}

// Register Custom Taxonomy
public function reservation_type() {

	$labels = array(
		'name'                       => _x( 'type', 'Taxonomy General Name', 'twentyfifteen' ),
		'singular_name'              => _x( 'type', 'Taxonomy Singular Name', 'twentyfifteen' ),
		'menu_name'                  => __( 'types', 'twentyfifteen' ),
		'all_items'                  => __( 'Tous les types', 'twentyfifteen' ),
		'parent_item'                => __( 'Parent', 'twentyfifteen' ),
		'parent_item_colon'          => __( 'Parent type', 'twentyfifteen' ),
		'new_item_name'              => __( 'Nouveau type', 'twentyfifteen' ),
		'add_new_item'               => __( 'Ajouter nouveau type', 'twentyfifteen' ),
		'edit_item'                  => __( 'Editer type', 'twentyfifteen' ),
		'update_item'                => __( 'Mettre à jout ', 'twentyfifteen' ),
		'view_item'                  => __( 'Voir type', 'twentyfifteen' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'twentyfifteen' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'twentyfifteen' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'twentyfifteen' ),
		'popular_items'              => __( 'Popular Items', 'twentyfifteen' ),
		'search_items'               => __( 'Search Items', 'twentyfifteen' ),
		'not_found'                  => __( 'Not Found', 'twentyfifteen' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'reservation_type', array( 'reservation' ), $args );

}

// Register Custom Taxonomy
public function theme() {

	$labels = array(
		'name'                       => _x( 'Secteur d\'activité', 'Taxonomy General Name', 'twentyfifteen' ),
		'singular_name'              => _x( 'Secteur d\'activité', 'Taxonomy Singular Name', 'twentyfifteen' ),
		'menu_name'                  => __( 'Secteurs d\'activités', 'twentyfifteen' ),
		'all_items'                  => __( 'Tous les Secteurs d\'activités', 'twentyfifteen' ),
		'parent_item'                => __( 'Parent', 'twentyfifteen' ),
		'parent_item_colon'          => __( 'Parent thème', 'twentyfifteen' ),
		'new_item_name'              => __( 'Nouveau thème', 'twentyfifteen' ),
		'add_new_item'               => __( 'Ajouter nouveau thème', 'twentyfifteen' ),
		'edit_item'                  => __( 'Editer thème', 'twentyfifteen' ),
		'update_item'                => __( 'Mettre à jout ', 'twentyfifteen' ),
		'view_item'                  => __( 'Voir thème', 'twentyfifteen' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'twentyfifteen' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'twentyfifteen' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'twentyfifteen' ),
		'popular_items'              => __( 'Popular Items', 'twentyfifteen' ),
		'search_items'               => __( 'Search Items', 'twentyfifteen' ),
		'not_found'                  => __( 'Not Found', 'twentyfifteen' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'theme', array( 'reservation' ), $args );

}

// Register Custom Taxonomy
public function theme_activity() {

	$labels = array(
		'name'                       => _x( 'Theme', 'Taxonomy General Name', 'twentyfifteen' ),
		'singular_name'              => _x( 'Theme', 'Taxonomy Singular Name', 'twentyfifteen' ),
		'menu_name'                  => __( 'Theme', 'twentyfifteen' ),
		'all_items'                  => __( 'Tous les Themes', 'twentyfifteen' ),
		'parent_item'                => __( 'Parent', 'twentyfifteen' ),
		'parent_item_colon'          => __( 'Parent thème', 'twentyfifteen' ),
		'new_item_name'              => __( 'Nouveau thème', 'twentyfifteen' ),
		'add_new_item'               => __( 'Ajouter nouveau thème', 'twentyfifteen' ),
		'edit_item'                  => __( 'Editer thème', 'twentyfifteen' ),
		'update_item'                => __( 'Mettre à jout ', 'twentyfifteen' ),
		'view_item'                  => __( 'Voir thème', 'twentyfifteen' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'twentyfifteen' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'twentyfifteen' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'twentyfifteen' ),
		'popular_items'              => __( 'Popular Items', 'twentyfifteen' ),
		'search_items'               => __( 'Search Items', 'twentyfifteen' ),
		'not_found'                  => __( 'Not Found', 'twentyfifteen' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'theme_activity', array( 'reservation' ), $args );

}

// Register Custom Post Type
public function car_post_type() {

	$labels = array(
		'name'                => _x( 'Activités', 'Post Type General Name', 'twentyfifteen' ),
		'singular_name'       => _x( 'Activité', 'Post Type Singular Name', 'twentyfifteen' ),
		'menu_name'           => __( 'Activité', 'twentyfifteen' ),
		'name_admin_bar'      => __( 'Activités', 'twentyfifteen' ),
		'parent_item_colon'   => __( 'Parent Activity:', 'twentyfifteen' ),
		'all_items'           => __( 'Toutes les Activités', 'twentyfifteen' ),
		'add_new_item'        => __( 'Ajouter Activité', 'twentyfifteen' ),
		'add_new'             => __( 'Ajouter nouvelle', 'twentyfifteen' ),
		'new_item'            => __( 'Nouvelle Activité', 'twentyfifteen' ),
		'edit_item'           => __( 'Editer Activité', 'twentyfifteen' ),
		'update_item'         => __( 'Mettre à jour Activité', 'twentyfifteen' ),
		'view_item'           => __( 'Voir Activité', 'twentyfifteen' ),
		'search_items'        => __( 'Chercher une reservation', 'twentyfifteen' ),
		'not_found'           => __( 'Non trouvée', 'twentyfifteen' ),
		'not_found_in_trash'  => __( 'Non trouvée dans la poubelle', 'twentyfifteen' ),
	);
	$args = array(
		'label'               => __( 'reservation', 'twentyfifteen' ),
		'description'         => __( 'Booking for SB', 'twentyfifteen' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail','author'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'reservation', $args );

}




// Register Custom Post Type
public function sejour_post_type() {

	$labels = array(
		'name'                => _x( 'sejours', 'Post Type General Name', 'twentyfifteen' ),
		'singular_name'       => _x( 'sejour', 'Post Type Singular Name', 'twentyfifteen' ),
		'menu_name'           => __( 'sejour', 'twentyfifteen' ),
		'name_admin_bar'      => __( 'sejour', 'twentyfifteen' ),
		'parent_item_colon'   => __( 'Parent sejour:', 'twentyfifteen' ),
		'all_items'           => __( 'Tous les sejours', 'twentyfifteen' ),
		'add_new_item'        => __( 'Ajouter sejour', 'twentyfifteen' ),
		'add_new'             => __( 'Nouveau sejour', 'twentyfifteen' ),
		'new_item'            => __( 'Nouveau sejour', 'twentyfifteen' ),
		'edit_item'           => __( 'Editer sejour', 'twentyfifteen' ),
		'update_item'         => __( 'Mettre à jour sejour', 'twentyfifteen' ),
		'view_item'           => __( 'Voir sejour', 'twentyfifteen' ),
		'search_items'        => __( 'Chercher un sejour', 'twentyfifteen' ),
		'not_found'           => __( 'Non trouvé', 'twentyfifteen' ),
		'not_found_in_trash'  => __( 'Non trouvé dans la poubelle', 'twentyfifteen' ),
	);
	$args = array(
		'label'               => __( 'sejour', 'twentyfifteen' ),
		'description'         => __( 'sejour for SB', 'twentyfifteen' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail','author'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'sejour', $args );

}


/*
	ajax FUNCTIONS
*/
public function ajxfn(){

     if(!empty($_REQUEST['theme']) && !empty($_REQUEST['geo'])){
	     $type = isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : null;
         $output = Online_Booking_Public::ajax_get_latest_posts($_REQUEST['theme'],$_REQUEST['geo'],$type);
    } else if(!empty($_REQUEST['reservation'])){
	    $tripName = htmlspecialchars($_REQUEST['bookinkTrip']);
	    $output = online_booking_user::save_trip($tripName);
    }  else if(!empty($_REQUEST['deleteUserTrip'])){
	    $userTrip = intval($_REQUEST['deleteUserTrip']);
	    $output = online_booking_user::delete_trip($userTrip);
	}else {
         $output = 'No function specified, check your jQuery.ajax() call';
 
     }
 
	$output=json_encode($output);
	if(is_array($output)){
		print_r($output);   
	} else{
		echo $output;
	}
	die;
}

/*
	//@param $term_resa : string - slug
	//use : get_term_order('repas-soiree');
*/
	public static function get_term_order($term_resa){
				$terms_array_order = get_terms( 'reservation_type', array(
			    'orderby'    => 'count',
			    'hide_empty' => 0,
			    'parent'	=> 0,
				)); 
		
				$i = 0;
				foreach($terms_array_order as $term){
					$i++;
					$slug_term = $term->slug;
					if($term_resa == $slug_term):
						return $i;
					endif;
				}
				
	}
		
		
/*
	display selected post in the thumbnail way
	@param
	
*/

public static function wp_query_thumbnail_posts(){
	
	if(isset($_GET['addId'])){
		wp_reset_query();
         wp_reset_postdata(); 
		$post_ID = intval($_GET['addId']);
	
	$filter_type = "filter-user";
	$reservation_type_obj = wp_get_post_terms( $post_ID, 'reservation_type' );
	//var_dump($reservation_type_obj);
	$reservation_type_name = $reservation_type_obj[0]->name;
	$reservation_type_ID   = $reservation_type_obj[0]->term_id;
	$reservation_type_slug = $reservation_type_obj[0]->slug;
	$data_order = Online_Booking_Public::get_term_order($reservation_type_slug);
	$data_order_val = (!empty($data_order)) ? $data_order : 0;
			
	$args = array(
		      'post_type' => 'reservation',
	          'post_status' => 'publish',
			  'posts_per_page' => 1,
			  'p' => $post_ID
			 
	        ); 
	        
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts()) {
	        
	        $count_post = 0;
            $posts = '<div id="selectedOne" class="blocks selectedOne animated shake">';
            while ( $the_query->have_posts() ) {
	            if($count_post == 0 && !isset($_GET['addId'])): 
		            $posts .= '<h4 class="ajx-fetch">';
					$posts .= $reservation_type_name;
					$posts .= '</h4><div class="clearfix"></div>';
	            endif;
                $the_query->the_post();
                global $post;
                $postID = $the_query->post->ID;
                $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                $term_list = wp_get_post_terms($post->ID, 'reservation_type');
                $type = json_decode(json_encode($term_list), true);
                //var_dump($type);
                $termstheme = wp_get_post_terms($postID,'theme');
                $terms = wp_get_post_terms($postID,'lieu');
                $acf_price = get_field('prix');
                $price = (!empty($acf_price)) ? $acf_price : '0' ;
                $termsarray = json_decode(json_encode($terms), true);
                $themearray = json_decode(json_encode($termstheme), true);
                //var_dump($termsarray);
                $lieu = 'data-lieux="';
                foreach($termsarray as $activity){
	                $lieu .= $activity['slug'].', ';
                }
                $lieu .= '"';
                
                $themes = 'data-themes="';
                foreach($themearray as $activity){
	                $themes .= $activity['slug'].', ';
                }
                $themes .= '"';
                $typearray = '';
                foreach($type as $singleType){
	               $typearray .= ' '.$singleType['slug'];
                }
                
                $posts .=  '<div data-type="'.$reservation_type_slug.'" class="block" id="ac-'.get_the_id().'" data-price="'.$price.'" '.$lieu.' '.$themes.'>';
                
                $posts .= '<div class="head"><h2>'.get_the_title().'</h2><span class="price-u">'.$price.' euros</span></div>';
                
                $posts .= '<div class="presta"><h3>la prestation comprend : </h3>';
                $posts .= get_field("la_prestation_comprend").'</div>';
                
                $posts .= get_the_post_thumbnail($postID, 'square');
                
                $posts .= '<a href="javascript:void(0)" onClick="addActivity('.$postID.',\''.get_the_title().'\','.$price.',\''.$typearray.'\',\' '.$url.' \','.$data_order_val.')" class="addThis">Ajouter <span class="fs1" aria-hidden="true" data-icon="P"></span></a>';
                
                $posts .= '<a class="booking-details" href="'.get_permalink().'">Voir les details <span class="fs1" aria-hidden="true" data-icon="U"></span></a>';
                
                $posts.= '</div>';
                
                $count_post++;
                
            }
            
            
         } else {
	         $posts = "";
         }
         $posts .= '</div>';
         wp_reset_query();
         wp_reset_postdata(); 
	return $posts;

	
	}
}
/*
	ajax_get_latest_posts function
	filter by term according to user choice
	$theme && $lieu should be mandatory
	order by term : reservation type
	@param $theme : integer - single term only
	@param $lieu  : integer - single term only
	@param $type  : array multiple choice, !$type == all $type elements
	
*/


public function ajax_get_latest_posts($theme,$lieu,$type){
	
	//order posts by terms ? => yes and use $i to add data-order attr to element
	$terms_array_order = get_terms( 'reservation_type', array(
			    'orderby'    => 'count',
			    'hide_empty' => 0,
			    'parent'	=> 0,
	)); 
	
	$global_theme = intval($theme);
	$global_lieu = intval($lieu);
	
	if(is_array($type)):
		$errors = array_filter($type);
	else:
		$errors = "no array";
	endif;
	//iterate through all terms or selected ones
	if($type == null | empty($errors) ):
		$array_custom_term = $terms_array_order; 
	else: 
		$array_custom_term = $type;
	endif;

	$posts = '<div id="filtered">';
	$i = 0;

	foreach($array_custom_term as $term_item){

		
		if(!is_int($term_item) && is_object($term_item) ):
			//no filter, take all top terms
			$filter_type = "filter-top-term";
			$reservation_type_name = $term_item->name;
			$reservation_type_ID   = $term_item->term_id;
			$reservation_type_slug = $term_item->slug;
			
		else:
			//we are filtering, we get term by id
			$filter_type = "filter-user";
			$reservation_type_obj = get_term_by('id', $term_item, 'reservation_type');
			$reservation_type_name = $reservation_type_obj->name;
			$reservation_type_ID   = $reservation_type_obj->term_id;
			$reservation_type_slug = $reservation_type_obj->slug;
		endif;
		
		$data_order = Online_Booking_Public::get_term_order($reservation_type_slug);
		
		
		//var_dump($term_reservation);
		$i++;
		
		$posts .= '<div class="term_wrapper" data-place="'.$global_lieu.'" data-theme="'.$global_theme.'" data-id="'.$reservation_type_ID.'-'.$reservation_type_slug.'-- '.$filter_type.'">';
		

		
		$args = array(
		      'post_type' => 'reservation',
	          'post_status' => 'publish',
			  'posts_per_page' => 20,
			  'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'theme',
						'field'    => 'term_id',
						'terms'    => $global_theme,
					),
					array(
						'taxonomy' => 'lieu',
						'field'    => 'term_id',
						'terms'    => $global_lieu,
					),
					array(
						'taxonomy' => 'reservation_type',
						'field'    => 'term_id',
						'terms'    => $reservation_type_ID,
					),
					
				),
	        );      


        $the_query = new WP_Query( $args );
        // The Loop
        if ( $the_query->have_posts() ) {
	        
	        $count_post = 0;
            
            while ( $the_query->have_posts() ) {
	            if($count_post == 0): 
		            $posts .= '<h4 class="ajx-fetch">';
					$posts .= $reservation_type_name;
					$posts .= '</h4><div class="clearfix"></div>';
	            endif;
                $the_query->the_post();
                global $post;
                $postID = $the_query->post->ID;
                $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                $term_list = wp_get_post_terms($post->ID, 'reservation_type');
                $type = json_decode(json_encode($term_list), true);
                //var_dump($type);
                $termstheme = wp_get_post_terms($postID,'theme');
                $terms = wp_get_post_terms($postID,'lieu');
                
                $price = get_field('prix');
                $termsarray = json_decode(json_encode($terms), true);
                $themearray = json_decode(json_encode($termstheme), true);
                //var_dump($termsarray);
                $lieu = 'data-lieux="';
                foreach($termsarray as $activity){
	                $lieu .= $activity['slug'].', ';
                }
                $lieu .= '"';
                
                $themes = 'data-themes="';
                foreach($themearray as $activity){
	                $themes .= $activity['slug'].', ';
                }
                $themes .= '"';
                $typearray = '';
                foreach($type as $singleType){
	               $typearray .= ' '.$singleType['slug'];
                }
                
                $posts .=  '<div data-type="'.$reservation_type_slug.'" class="block" id="ac-'.get_the_id().'" data-price="'.$price.'" '.$lieu.' '.$themes.'>';
                $posts .= '<div class="head"><h2>'.get_the_title().'</h2><span class="price-u">'.$price.' euros</span></div>';
                $posts .= '<div class="presta"><h3>la prestation comprend : </h3>';
                $posts .= get_field("la_prestation_comprend").'</div>';
                $posts .= get_the_post_thumbnail($postID, 'square');
                $posts .= '<a href="javascript:void(0)" onClick="addActivity('.$postID.',\''.get_the_title().'\','.$price.',\''.$typearray.'\',\' '.$url.' \','.$data_order.')" class="addThis">Ajouter <span class="fs1" aria-hidden="true" data-icon="P"></span></a>';
                $posts .= '<a class="booking-details" href="'.get_permalink().'">Voir les details <span class="fs1" aria-hidden="true" data-icon="U"></span></a>';
                $posts.= '</div>';
                
                $count_post++;
                
            }
            
            
         } else {
	         
         }
         $posts.= '</div>';
		 //wp_reset_postdata();
	}
	

	$posts .= '</div>';
	wp_reset_query();
         wp_reset_postdata(); 
	
    return $posts;
}


/*
* INVITE YOU
*/

public static function the_sejours($nb = 5,$onBookingPage = false){
    	
    	
    	$terms = get_terms( 'lieu', array(
		    'orderby'    => 'count',
		    'hide_empty' => 1,
		    'parent'	=> 0,
		) );
		//var_dump($terms);
		foreach( $terms as $term ) {
        $goToBookingPage = $onBookingPage ? 'true' : 'false';
        $is_slider = $onBookingPage ? 'grid-style' : 'slick-multi';
        // The Loop
       
	    	$args = array(
	        'post_type' => 'sejour',
			'posts_per_page' => $nb,
			'post_status'		=> 'publish',
			'lieu' => $term->slug
			);
			
			
        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) {
            $sejour = '<div class="blocks sejour-content pure-g"><div class="'.$is_slider.'">';
            echo'<h4><div class="fs1" aria-hidden="true" data-icon=""></div>' . $term->name . '</h4>';
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                global $post;
                $postID = $the_query->post->ID;
                $term_lieu = wp_get_post_terms($postID, 'lieu');
                foreach($term_lieu as $key=>$value){
				  //echo '<span>'.$value->name.'</span> ';
				}
                
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
									$url = wp_get_attachment_url( get_post_thumbnail_id($data->ID) );
									$term_list = wp_get_post_terms($data->ID, 'reservation_type');
									$type = json_decode(json_encode($term_list), true);
									$comma = ($i == $len - 1) ? '' : ',';
						        	$dayTrip .= '"'.$data->ID.'":';
						        	$dayTrip .= '{ "name" : "'.$data->post_title.'","';
						        	if(!empty($field)):
						        		$dayTrip .= 'price": '.$field.',';
						        	else:
						        		$dayTrip .= 'price": 0,';
						        	endif;
						        	
						        	if(isset($type[0])):
						        		$type_slug = $type[0]['slug'];
						        		$dayTrip .= '"type": "'.$type[0]['slug'].'","';
						        	else:
										$type_slug = (isset($type_slug)) ? $type_slug : "undefined var";
						        		$dayTrip .= '"type": "'.$type_slug.'","';
						        	endif;
						        	$dayTrip .= 'img": "'.$url.'"}'.$comma;
						        	$i++;
					        	}
					        endwhile;
					        endif;
							$dayTrip .= '},';
							$activityObj++;
					    endwhile;
					endif;
					$dayTrip .= '}';

                $sejour .= '<div id="post-'.$postID.'" class="block-trip pure-u-1 pure-u-md-1-4"><div class="block-trip">';
                $sejour .= '<h2>'.get_the_title().'</h2>';
                $sejour .= get_the_post_thumbnail($postID,'square');
                $sejour .= '<script>';
                $sejour .= 'sejour'.$postID.' = {
	                		"sejour" : "'.get_the_title().'",
	                		"theme" : "'.$theme[0].'",
	                		"lieu"  : "'.$lieu[0].'",
	                		"arrival": "'.$arrival_date.'",
							"departure": "'.$departure_date.'",
							"days": '.$row_count.',
							"participants": "'.$personnes.'",
							"budgetPerMin": "'.$budget_min.'",
							"budgetPerMax": "'.$budget_max.'",
							"globalBudgetMin": '.$budgMin.',
							"globalBudgetMax": '.$budgMax.',
							"currentBudget" :'.$activityObj.',
							"currentDay": "'.$arrival_date.'",
							"tripObject": '.$dayTrip.'
							};';
                $sejour .= '</script>';
                $sejour .= '<a href="'.get_permalink().'" class="seeit">Voir ce séjour</a>';
                $sejour .= '<a href="javascript:void(0)" class="loadit" onclick="loadTrip(sejour'.$postID.','.$goToBookingPage.');">'.__('Sélectionnez cet évènement','online-booking').'</a></div></div>';
                
            }
            wp_reset_postdata();
            $sejour .= '</div></div>';
         }
        else {
	        $sejour = "";
        }
         
         echo $sejour;
         }

}

/*
	the_sejour
	@param obj ($postid) display 2 buttons, add to trip && back to sejours
*/
public static function the_sejour_btn($postid){
                $postID = $postid;
                $sejours_url = 'nos-sejours';
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
									$url = wp_get_attachment_url( get_post_thumbnail_id($data->ID) );
									$term_list = wp_get_post_terms($data->ID, 'reservation_type');
									$type = json_decode(json_encode($term_list), true);
								
									$comma = ($i == $len - 1) ? '' : ',';
						        	$dayTrip .= '"'.$data->ID.'":';
						        	$dayTrip .= '{ "name" : "'.$data->post_title.'","';
						        	if(!empty($field)):
						        		$dayTrip .= 'price": '.$field.',';
						        	else:
						        		$dayTrip .= 'price": 0,';
						        	endif;
						        	
						        	if(isset($type[0])):
						        		$type_slug = $type[0]['slug'];
						        		$dayTrip .= '"type": "'.$type[0]['slug'].'","';
						        	else:
										$type_slug = (isset($type_slug)) ? $type_slug : "undefined var";
						        		$dayTrip .= '"type": "'.$type_slug.'","';
						        	endif;
						        	$dayTrip .= 'img": "'.$url.'"}'.$comma;
						        	
						        	//var_dump($type[0]);
						        	$i++;
					        	}
					        endwhile;
					        endif;
							$dayTrip .= '},';
							$activityObj++;
					    endwhile;
					endif;
					$dayTrip .= '}';

                $sejour = '<script>';
                $sejour .= 'Uniquesejour'.$postID.' = {
	                		"sejour" : "'.get_the_title().'",
	                		"theme" : "'.$theme[0].'",
	                		"lieu"  : "'.$lieu[0].'",
	                		"arrival": "'.$arrival_date.'",
							"departure": "'.$departure_date.'",
							"days": '.$row_count.',
							"participants": "'.$personnes.'",
							"budgetPerMin": "'.$budget_min.'",
							"budgetPerMax": "'.$budget_max.'",
							"globalBudgetMin": '.$budgMin.',
							"globalBudgetMax": '.$budgMax.',
							"currentBudget" :'.$activityObj.',
							"currentDay": "'.$arrival_date.'",
							"tripObject": '.$dayTrip.'
							};';
                $sejour .= '</script>';
                $sejour .= '<a id="CTA" href="javascript:void(0)" class="loadit" onclick="loadTrip(Uniquesejour'.$postID.',true);">'.__('Sélectionnez cet évènement','online-booking').'</a>';
                $sejour .= '<a class="btn btn-reg grey" href="'.get_site_url().'/'.$sejours_url.'">'.__('Voir Toutes nos activités','online-booking').'</a>';
         echo $sejour;

}



/*
* front_form_shortcode
* add a form to set default values to trip on another page
* @param string ($booking_url) the booking url to go to
*/
public function front_form_shortcode($booking_url) {
	// Code
			$args = array(
			'show_option_all'    => '',
			'show_option_none'   => '',
			'option_none_value'  => '-1',
			'orderby'            => 'ID', 
			'order'              => 'ASC',
			'show_count'         => 0,
			'hide_empty'         => 0, 
			'child_of'           => 0,
			'exclude'            => '',
			'echo'               => 0,
			'selected'           => 0,
			'hierarchical'       => 0, 
			'name'               => 'cat',
			'id'                 => 'theme',
			'class'              => 'postform terms-change form-control',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'theme',
			'hide_if_empty'      => false,
			'value_field'	     => 'term_id',	
		); 
		$argsLieux = array(
			'show_option_all'    => '',
			'show_option_none'   => '',
			'option_none_value'  => '-1',
			'orderby'            => 'ID', 
			'order'              => 'ASC',
			'show_count'         => 0,
			'hide_empty'         => 0, 
			'child_of'           => 0,
			'exclude'            => '',
			'echo'               => 0,
			'selected'           => 0,
			'hierarchical'       => 1, 
			'name'               => 'categories',
			'id'                 => 'lieu',
			'class'              => 'postform terms-change form-control',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'lieu',
			'hide_if_empty'      => false,
			'value_field'	     => 'term_id',	
		); 
		
	if(!isset($_COOKIE['reservation'])):
	
		$front_form = '<div id="front-form" class="booking" data-url="'.get_bloginfo('url').'/'.BOOKING_URL.'/">';
		$front_form .= '<div class="pure-g">';
		$front_form .= '<div class="pure-u-1 pure-u-sm-5-24">';
		$front_form .= wp_dropdown_categories( $argsLieux );
		$front_form .= '</div><div class="pure-u-1 pure-u-sm-5-24">';
		$front_form .= wp_dropdown_categories( $args );
		$front_form .= '</div><div class="pure-u-1 pure-u-sm-5-24">';
		$front_form .= '<div class="date-wrapper"><input data-value="" value="'.date("d/m/Y").'" class="datepicker bk-form form-control" id="arrival">';
		$front_form .= '<div class="fs1" aria-hidden="true" data-icon=""></div></div>';
		$front_form .= '</div><div class="pure-u-1 pure-u-sm-3-24">';
		$front_form .= '<div class="people-wrapper"><input type="number" id="participants" value="5" class="bk-form form-control" />';
		$front_form .= '<div class="fs1" aria-hidden="true" data-icon=""></div></div>';
		$front_form .= '</div><div class="pure-u-1 pure-u-sm-6-24">';
		$front_form .= '<input type="submit" value="GO" />';
		$front_form .= '</div></div></div>';
		$front_form .= '<div class="clearfix"></div>';
	
	else: 
	
		$front_form = '<div id="front-form" class="booking exists"><a href="'.get_bloginfo('url').'/'.BOOKING_URL.'/" title="'.__('Voir votre réservation','twentyfifteen').'">'.__('Voir votre réservation','twentyfifteen').'</a></div>';
	
	endif;
	
	return $front_form;
}

/*
	add a login form to header.php
	If user is logged : display account link and booked trips
	if user is not logged : display a login form
*/
public function header_form(){
	global $current_user;
    get_currentuserinfo();
    //var_dump($current_user);
	if ( !is_user_logged_in() ): 
            $output = '<div id="logger">';
	        $output .= '<a href="#login-popup" class="open-popup-link">';
		    $output .=  __('Connexion','twentyfifteen'); 
		    $output .= '</a>';
	        $output .= '</div>';
			$output .= '<div id="login-popup" class="white-popup mfp-hide">';
        	$output .= do_shortcode('[userpro template=register type=particuliers]');
			$output .= '</div>';
    else:
        	$output = '<div id="logger">';
	        $output .= '<span class="user-name">';
	        $output .= __('Bonjour','online-booking');
	        $output .= $current_user->user_login;
	        $output .= '</span>';
	        $output .= '<a class="my-account" href="'.get_bloginfo('url').'/compte">'. __('Mon compte','online-booking').'</a>';
	       $output .= '<a class="log-out" href="'.wp_logout_url( home_url().'?log=ftl' ).'">'.__('Déconnexion', 'online-booking').'</a>';
	       	$output .= '</div>';
     endif;
	Online_Booking_Public::delete_cookies();
	echo $output;

}

/*
	Clear cookies when log out by user
*/
public function delete_cookies(){

	$logged_out = isset($_GET['log']) ? $_GET['log'] : '';
	if (isset($_SERVER['HTTP_COOKIE']) && $logged_out == 'ftl') {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}

}
/*
	
*/

/*
	add a login form to header.php
*/
public function current_user_infos(){
	global $current_user;
    get_currentuserinfo();
    //var_dump($current_user);
	if ( is_user_logged_in() ): 
            $output = '<div id="logged_in_info" style="display:none;">';
			$output .= '<input id="user-logged-in-infos" data-id="'.$current_user->ID.'" />';
	        $output .= '</div>';
	 else:
	 		$output = '';
     endif;

	echo $output;

}

/*
	Deprecated
	remove for front use the tabs library...
*/
public function remove_media_library_tab($tabs) {
	if(!is_admin()):
    unset($tabs['library']);
    return $tabs;
    endif;
}

 /**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
public function my_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	global $user;
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return $redirect_to;
		} else {
			return home_url();
		}
	} else {
		return $redirect_to;
	}
}


}
