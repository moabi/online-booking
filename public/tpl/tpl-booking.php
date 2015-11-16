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
			'selected'           => false,
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
	<div class="pure-u-1 pure-u-md-12-24">	
		<label class="floating-label" for="arrival"><span class="fs1" aria-hidden="true" data-icon=""></span> Arrivée sur place</label>	
	</div>
	<div class="pure-u-1 pure-u-md-12-24">					
		<input data-value="" value="<?php echo date("d/m/Y"); ?>" class="datepicker bk-form form-control" id="arrival">
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





<div class="pure-u-1 pure-u-md-8-24 on-field hidden">							
	<div class="pure-u-1 pure-u-md-2-4 hidden">
		<label class="floating-label" for="departure"><span class="fs1" aria-hidden="true" data-icon=""></span> Retour</label>	
		<input data-value="" value="<?php echo date("d/m/Y", time()+86400); ?>" class="datepicker bk-form form-control" id="departure">
	</div>
</div>

<!-- budget -->		
<div class="pure-u-1 pure-u-md-8-24 on-field">
			<label for=""><span id="budget-icon" class="fs1" aria-hidden="true" data-icon=""></span>Budget/personne ( entre <span id="st">45</span> et <span id="end">300</span> Euros )
			</label>
			<div id="slider-range"></div>
			<input type="hidden" id="budget" value="45/300" class="bk-form form-control"  />
</div>
<!-- #budget -->

<!-- Number of days -->
<div class="pure-u-1 pure-u-md-8-24 on-field">
		<div class="pure-u-1 pure-u-md-12-24">
		<label class="floating-label" for="days"><span class="fs1" aria-hidden="true" data-icon=""></span> Nombre de jours</label>	
		</div>
		<div id="days-modifier" class="pure-u-1 pure-u-md-12-24">
			<button onclick="removeLastDay();">-</button>
			<button onclick="addADay();">+</button>	
		</div>
	
</div>
<!-- #Number of days -->

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
       echo '<span><input type="checkbox" name="typeactivite" value="'.$term->term_id.'" />' . $term->name.'</span>';
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
			echo '<li><span><input type="checkbox" name="typeactivite" value="'.$childterm->term_id.'" />' . $childterm->name.'</span></li>';
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

<?php echo Online_Booking_Public::wp_query_thumbnail_posts(); ?>
	<?php
		//START POST LISTING
		 echo '<div id="activities-content" class="blocks">';
		//order posts by terms ? => yes and use $i to add data-order attr to element
	$terms_array_order = get_terms( 'reservation_type', array(
			    'orderby'    => 'count',
			    'hide_empty' => 0,
			    'parent'	=> 0,
	)); 

	foreach($terms_array_order as $term_item):
		
		$data_order = Online_Booking_Public::get_term_order($term_item->slug);
		if(!$data_order):
			$data_order = 0;
		endif;
		
		echo '<div class="clearfix"></div><h4>'.$term_item->slug.'</h4><div class="clearfix"></div>';
		
		
        $args = array(
	        'post_type' => 'reservation',
			'posts_per_page' => -1,
			'post_status'		=> 'publish',
			'reservation_type' => $term_item->slug
        );
        
        $the_query = new WP_Query( $args );
        // The Loop
        if ( $the_query->have_posts() ):
           
            while ( $the_query->have_posts() ):
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
                if(has_post_thumbnail()){
	                the_post_thumbnail('thumbnail');
                } else{
	                
	                echo '<img width="150" height="150" src="" class="attachment-thumbnail wp-post-image" alt="missing-image-onlyoo">';
                }
               
                
                echo '<div class="presta"><h3>la prestation comprend : </h3>';
                echo get_field("la_prestation_comprend").'</div>';
                echo '<a href="javascript:void(0)" onClick="addActivity('.$postID.',\''.get_the_title().'\','.$price.',\''.$typearray.'\',\''.$url.'\','.$data_order.')" class="addThis">Ajouter <span class="fs1" aria-hidden="true" data-icon="P"></span></a>';
                echo '<a class="booking-details" href="'.get_permalink().'">Details <span class="fs1" aria-hidden="true" data-icon="U"></span></a>';
                echo '</div>';
                
            endwhile;
            
         endif;
	endforeach;
	echo '</div>';
		?>

		<h2 class="upptitle">Vous aimerez également...</h2>

		<?php Online_Booking_Public::the_sejours(5,false); ?>
		</div>
		</div><!-- #content -->


<!-- SIDEBAR -->
<div id="sidebar-booking-b" class="pure-u-1 pure-u-md-6-24">
	<div id="sidebar-sticky">
		
		 <?php if ( is_active_sidebar( 'right_sidebar' ) ) : ?>
    <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
      <?php dynamic_sidebar( 'right_sidebar' ); ?>
    </div><!-- #primary-sidebar -->
  <?php endif; ?>
  
<!-- JOURNEES -->
	<h2 class="upptitle">Votre séjour</h2>
	
		<div id="daysTrip"></div>
<!-- #JOURNEES -->	

<?php  if ( !is_user_logged_in() ): ?>
<!-- FORMUAIRE SEND -->	
	<h2 class="upptitle">Votre devis sur mesure</h2>
	
		<form action="tpl-booking.php" method="" accept-charset="utf-8">
			        <input type="text" name="s" id="name" placeholder="Votre nom" value="" type="text"  /> <br />
			        <input type="tel" name="s" id="name" placeholder="Téléphone" value="" type="text"  />
			        <br />
			        <input type="email" name="s" id="name" placeholder="Votre mail" value="" type="text"  />
			        <br />
			        <input type="submit" name="s" id="name" value="Envoyer" type="text"  />
			        	
		</form>
<!-- #formulaire send -->
<?php endif; ?>

<?php  if ( is_user_logged_in() ): ?>
<h2 class="upptitle">Votre devis sur mesure</h2>
<div class="pure-g" id="user-actions">
	<div id="savetrip" >
		<div class="pure-u-1">
			<input maxlength="20" id="tripName" type="text" value="" placeholder="Nom de votre reservation" />
		</div>
		
		<div class="pure-u-1">
		<?php 
			$eventid = 0;
			$btn_Name = __('Enregistrer','onlyoo');
					
			if(isset($_COOKIE['reservation'])):
				$bookink_json = stripslashes( $_COOKIE['reservation'] );
				$data = json_decode($bookink_json, true);
				
				if(!empty($data['eventid'])):
					$eventid = $data['eventid'];
					$btn_Name = __('Mettre à jour','onlyoo');
					
				endif;
			endif;
			
			
			echo '<a href="#" onclick="saveTrip(\''.$eventid.'\')" class="btn btn-reg">';
			echo $btn_Name;
			echo '<span class="fs1" aria-hidden="true" data-icon=""></span></a>';
			?>

		</div>
		<div class="pure-u-1">
			<a href="#"  class="btn btn-reg">
			Demande de devis
			</a>
		</div>
		</div>
</div>
<?php else: ?>
Connectez-vous pour sauvegarder
<?php endif; ?>


	
</div>
</div>
<!-- #SIDEBAR -->
</div><!-- pure-g -->

<?php get_footer(); ?>