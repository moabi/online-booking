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
	
	private $booking_url = "reservation-service";

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
    if ( is_page( $this->booking_url ) ) {
        $page_template = plugin_dir_path( __FILE__ ) .'tpl/tpl-booking.php';
        
    }
    elseif ( is_page( 'nos-sejours' ) ) {
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
				'post_name'		=>	'nos-sejours',
				'post_title'		=>	'Nos séjours',
				'post_status'		=>	'publish',
				'post_type'		=>	'page'
			)
		);

	// Otherwise, we'll stop
	} elseif( null == get_page_by_title( 'Réservation' ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'		=>	$this->booking_url,
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
	FUNCTIONS
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






public function ajax_get_latest_posts($theme,$lieu,$type){
	if($type != null){
		$typetoArray = $type;
		$types = array(
					'taxonomy' => 'reservation_type',
					'field'    => 'name',
					'terms'    => $type,
				);
	} else {
		$types = '';
	}
	
     $args = array(
	      'post_type' => 'reservation',
          'post_status' => 'publish',
		  'posts_per_page' => 20,
		  'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'theme',
					'field'    => 'term_id',
					'terms'    => array($theme),
				),
				array(
					'taxonomy' => 'lieu',
					'field'    => 'term_id',
					'terms'    => array($lieu),
				),
				$types
				
			),
        );
        $the_query = new WP_Query( $args );
        // The Loop
        if ( $the_query->have_posts() ) {
            $posts = '<div id="activities-content" class="blocks">';
            while ( $the_query->have_posts() ) {
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
                
                $posts .=  '<div class="block" id="ac-'.get_the_id().'" data-price="'.$price.'" '.$lieu.' '.$themes.'>';
                $posts .= '<div class="head"><h2>'.get_the_title().'</h2><span class="price-u">'.$price.' euros</span></div>';
                $posts .= '<div class="presta"><h3>la prestation comprend : </h3>';
                $posts .= get_field("la_prestation_comprend").'</div>';
                $posts .= get_the_post_thumbnail($postID, 'thumbnail');
                $posts .= '<a href="javascript:void(0)" onClick="addActivity('.$postID.',\''.get_the_title().'\','.$price.',\''.$typearray.'\',\' '.$url.' \')" class="addThis">Ajouter <span class="fs1" aria-hidden="true" data-icon="P"></span></a>';
                $posts .= '<a class="booking-details" href="'.get_permalink().'">Voir les details <span class="fs1" aria-hidden="true" data-icon="U"></span></a>';
                $posts.= '</div>';
                
            }
            $posts .= '</div>';
         } else {
	         $posts = 'Nous sommes désolé, il n\'y a aucun résultat dans ce lieu pour votre recherche';
         }

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
		

        $goToBookingPage = $onBookingPage ? 'true' : 'false';
        // The Loop
       
	    	$args = array(
	        'post_type' => 'sejour',
			'posts_per_page' => $nb,
			'post_status'		=> 'publish',
			//'lieu' => $term->slug
			);
        $the_query = new WP_Query( $args );
         //echo'<h4>' . $term->name . '</h4>';
        if ( $the_query->have_posts() ) {
            $sejour = '<div id="sejour-content" class="blocks pure-g"><div class="slick-multi">';
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
						        	$dayTrip .= '"'.$data->ID.'": { "name" : "'.$data->post_title.'","price": '.$field.',"type": "'.$type[0]['slug'].'","img": "'.$url.'"}'.$comma;
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
                $sejour .= get_the_post_thumbnail($postID,'thumbnail');
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
                $sejour .= '<a href="javascript:void(0)" class="loadit" onclick="loadTrip(sejour'.$postID.','.$goToBookingPage.');">Charger ce séjour</a></div></div>';
                
            }
            $sejour .= '</div></div>';
         }
         
         echo $sejour;

}

public static function the_sejour($postid){
                $postID = $postid;
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
						        	$dayTrip .= '"'.$data->ID.'": { "name" : "'.$data->post_title.'","price": '.$field.',"type": "'.$type[0]['slug'].'","img": "'.$url.'"}'.$comma;
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
                $sejour .= '<a id="CTA" href="javascript:void(0)" class="loadit" onclick="loadTrip(Uniquesejour'.$postID.',true);">Charger ce séjour</a>';
         echo $sejour;

}



// Add Shortcode
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
	
		$front_form = '<div id="front-form" class="booking" data-url="'.get_bloginfo('url').'/'.$this->booking_url.'/">'.wp_dropdown_categories( $argsLieux ).' '.wp_dropdown_categories( $args ).'<input data-value="" value="'.date("d/m/Y").'" class="datepicker bk-form form-control" id="arrival"><input type="number" id="participants" value="5" class="bk-form form-control" /><input type="submit" value="GO" /><div class="clearfix"></div></div><div class="clearfix"></div>';
	
	else: 
	
		$front_form = '<div id="front-form" class="booking exists"><a href="'.get_bloginfo('url').'/'.$this->booking_url.'/" title="'.__('Voir votre réservation','twentyfifteen').'">'.__('Voir votre réservation','twentyfifteen').'</a></div>';
	
	endif;
	
	return $front_form;
}

/*
	add a login form to header.php
*/
public function header_form(){
	global $current_user;
    get_currentuserinfo();
    
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
	       $output .= '<a class="log-out" href="'.wp_logout_url( home_url() ).'">'.__('Déconnexion', 'online-booking').'</a>';
	       	$output .= '</div>';
     endif;

	echo $output;

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
