<?php
/**
 * Template Name: booking
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

get_header(); ?>

<div class="pure-g form-booking" id="booking-wrapper">



	<div id="primary-b" class="booking pure-u-1 pure-u-md-18-24">
		
		<div class="padd-l">
		
<! -- SETTINGS -->
<div id="on-settings">
	<!--
<h2 id="settings-title" class="upptitle"><span class="fs1" aria-hidden="true" data-icon=""></span> Paramètres de votre séjour</h2>	-->
		
			<?php $args = array(
			'show_option_all'    => '',
			'show_option_none'   => '',
			'option_none_value'  => '-1',
			'orderby'            => 'ID', 
			'order'              => 'ASC',
			'show_count'         => 0,
			'hide_empty'         => true, 
			'child_of'           => 0,
			'exclude'            => '',
			'echo'               => 1,
			'selected'           => 0,
			'hierarchical'       => 0, 
			'name'               => 'cat',
			'id'                 => 'theme',
			'class'              => 'postform terms-change form-control',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'theme',
			'hide_if_empty'      => true,
			'value_field'	     => 'term_id',	
		); ?>
			<?php $argsLieux = array(
			'show_option_all'    => '',
			'show_option_none'   => '',
			'option_none_value'  => '-1',
			'orderby'            => 'ID', 
			'order'              => 'ASC',
			'show_count'         => 0,
			'hide_empty'         => true, 
			'child_of'           => 0,
			'exclude'            => '',
			'echo'               => 1,
			'selected'           => 0,
			'hierarchical'       => 1, 
			'name'               => 'categories',
			'id'                 => 'lieu',
			'class'              => 'postform terms-change form-control',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'lieu',
			'hide_if_empty'      => true,
			'value_field'	     => 'term_id',	
		); ?>
		


<div class="pure-g">
	
	<div class="pure-u-1 pure-u-md-8-24 on-field">
		<div class="pure-u-1 pure-u-md-8-24">							
			<label class="floating-label" for="float-select"><span class="fs1" aria-hidden="true" data-icon="g"></span>Secteur d'activité</label>
		</div>
		<div class="pure-u-1 pure-u-md-12-24">
			<?php wp_dropdown_categories( $args ); ?> 
		</div>

	</div>
	
	<div class="pure-u-1 pure-u-md-8-24 on-field">
		<div class="pure-u-1 pure-u-md-8-24">
		<label class="floating-label" for="float-select"><span class="fs1" aria-hidden="true" data-icon=""></span>Le lieu</label>
		</div>
		<div class="pure-u-1 pure-u-md-12-24">
		<?php wp_dropdown_categories( $argsLieux ); ?> 
		</div>
	</div>
	

							
	<div class="pure-u-1 pure-u-md-8-24 on-field">
		<div class="pure-u-1 pure-u-md-8-24">
			<label class="floating-label" for="participants"><span class="fs1" aria-hidden="true" data-icon=""></span>Nombre de participants</label>
		</div>
		<div class="pure-u-1 pure-u-md-10-24">	
			<input type="number" id="participants" value="5" class="bk-form form-control" />
		</div>
	</div>


<div class="pure-u-1 pure-u-md-8-24 on-field">
	<div class="pure-u-1 pure-u-md-12-24">	
		<label class="floating-label" for="arrival"><span class="fs1" aria-hidden="true" data-icon=""></span> Arrivée sur place</label>	
	</div>
	<div class="pure-u-1 pure-u-md-12-24">					
		<input data-value="" value="<?php echo date("d/m/Y"); ?>" class="datepicker bk-form form-control" id="arrival">
	</div>
</div>


<div class="pure-u-1 pure-u-md-8-24 on-field hidden">							
	<div class="pure-u-1 pure-u-md-2-4 hidden">
		<label class="floating-label" for="departure"><span class="fs1" aria-hidden="true" data-icon=""></span> Retour</label>	
		<input data-value="" value="<?php echo date("d/m/Y", time()+86400); ?>" class="datepicker bk-form form-control" id="departure">
	</div>
</div>

<div class="pure-u-1 pure-u-md-8-24 on-field">
		<div class="pure-u-1 pure-u-md-12-24">
		<label class="floating-label" for="days"><span class="fs1" aria-hidden="true" data-icon=""></span> Nombre de jours</label>	
		</div>
		<div id="days-modifier" class="pure-u-1 pure-u-md-12-24">
			<button onclick="addADay();">+</button>
			<button onclick="removeLastDay();">-</button>
		</div>
	
</div>
		
<div class="pure-u-1 pure-u-md-8-24 on-field">
			<label for=""><span id="budget-icon" class="fs1" aria-hidden="true" data-icon=""></span>Budget/personne ( entre <span id="st">45</span> et <span id="end">300</span> Euros )
			</label>
			<div id="slider-range"></div>
			<input type="hidden" id="budget" value="45/300" class="bk-form form-control"  />
</div>

</div>

	
</div>
<!-- #SETTING -->


<!-- ACTIVITES -->
	<h2 class="upptitle">Package sur mesure <span>Sélectionnez vos activités à la carte</span></h2>
	<div class="clearfix"></div>
	
							<div class="pure-g">

<div class="pure-u-1 pure-u-md-24-24">

	<?php
		// no default values. using these as examples
$taxonomies = array( 
    'reservation_type'
);

$args = array(

    'hide_empty'        => true, 
    'exclude'           => array(), 
    'exclude_tree'      => array(), 
    'include'           => array(),
    'number'            => '', 
    'fields'            => 'all', 
    'slug'              => '',
    'parent'            => 0,
    'hierarchical'      => true, 
    'child_of'          => 0,
    'childless'         => false,
    'get'               => '', 
    'name__like'        => '',
    'description__like' => '',
    'pad_counts'        => false, 
    'offset'            => '', 
    'search'            => '', 
    'cache_domain'      => 'core',
    'order'				=> 'ASC'
); 

$terms = get_terms($taxonomies, $args);

//var_dump($terms);
 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
     echo '<ul id="typeterms" class="sf-menu">';
     foreach ( $terms as $term ) {
	 	//var_dump($term);
	 	echo '<li>';
       echo '<span><input type="checkbox" name="typeactivite" value="'.$term->name.'" />' . $term->name.'</span>';
       	     $args = array(
		    'hide_empty'        => true, 
		    'child_of'          => $term->term_id,
		    'cache_domain'      => 'core',
		    'order'				=> 'ASC'
		); 
		$childTerms = get_terms($taxonomies, $args);
		 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			 echo '<ul class="sub">';
		foreach ( $childTerms as $childterm ) {
			echo '<li><span><input type="checkbox" name="typeactivite" value="'.$childterm->name.'" />' . $childterm->name.'</span></li>';
		}
		echo '</ul>';
		}
		echo '</li>';
        
     }
     echo '</ul>';
 }
	
		?>
</div>
</div>


	<?php
        $args = array(
	        'post_type' => 'reservation',
			'posts_per_page' => -1,
			'post_status'		=> 'publish',
        );
        $the_query = new WP_Query( $args );
        // The Loop
        if ( $the_query->have_posts() ) {
            echo '<div id="activities-content" class="blocks">';
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                global $post;
                $postID = $the_query->post->ID;
                $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                $termstheme = wp_get_post_terms($postID,'theme');
                $terms = wp_get_post_terms($post->ID,'lieu');
                $term_list = wp_get_post_terms($post->ID, 'reservation_type');
                $type = json_decode(json_encode($term_list), true);
                $price = get_field('prix');
                $termsarray = json_decode(json_encode($terms), true);
                $themearray = json_decode(json_encode($termstheme), true);
                //var_dump($term_list);
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
                
                
				

                echo '<div class="block" id="ac-'.get_the_id().'" data-price="'.$price.'" '.$lieu.' '.$themes.'>';
                echo '<div class="head"><h2>'.get_the_title().'</h2><span class="price-u">'.$price.' euros</span></div>';
               
                the_post_thumbnail('thumbnail');
                echo '<div class="presta"><h3>la prestation comprend : </h3>';
                echo get_field("la_prestation_comprend").'</div>';
                echo '<a href="javascript:void(0)" onClick="addActivity('.$postID.',\''.get_the_title().'\','.$price.',"'.$typearray.'",'.$url.')" class="addThis">Ajouter <span class="fs1" aria-hidden="true" data-icon="P"></span></a>';
                echo '<a class="booking-details" href="'.get_permalink().'">Voir les details <span class="fs1" aria-hidden="true" data-icon="U"></span></a>';
                echo '</div>';
                
            }
            echo '</div>';
         }
		
		?>

		<h2 class="upptitle">Vous aimerez également...</h2>

		<?php Online_Booking_Public::the_sejours(5,false); ?>
		</div>
		</div><!-- #content -->



<div id="sidebar-booking-b" class="pure-u-1 pure-u-md-6-24">
	<div id="sidebar-sticky">
<!-- JOURNEES -->
	<h2 class="upptitle">Votre séjour</h2>
	
		<div id="daysTrip"></div>
<!-- #JOURNEES -->	

<!-- FORMUAIRE SEND -->	
	<h2 class="upptitle">Votre devis sur mesure</h2>
		<form action="tpl-booking.php" method="" accept-charset="utf-8">
			        <input type="text" name="s" id="name" placeholder="Votre nom" value="" type="text"  /> <br />
			        <input type="tel" name="s" id="name" placeholder="Téléphone" value="" type="text"  />
			        <br />
			        <input type="email" name="s" id="name" placeholder="Votre mail" value="" type="text"  />
			        <br />
			        <input type="submit" name="s" id="name" value="Envoyer" type="text"  />
			        	<?php  if ( is_user_logged_in() ): ?>
<div class="pure-g" id="user-actions">
	<div id="savetrip" onclick="saveTrip()">
		<input maxlength="20" id="tripName" type="text" value="" placeholder="Nom de votre reservation" />
		Sauvegarder votre voyage<div class="fs1" aria-hidden="true" data-icon=""></div>
		</div>
</div>
<?php else: ?>
Connectez-vous pour sauvegarder
<?php endif; ?>

		</form>
<!-- #formulaire send -->
	
</div>
</div>
</div><!-- pure-g -->

<?php get_footer(); ?>