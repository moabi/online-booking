<?php
/*
	Class to embed utilities
*/
	
class online_booking_ux  {
	

	
	
	/*
		Provide a slider template
	*/
		
	public function slider(){
		$images = get_field('gallerie');
		$slider = '';
		if( $images ): 
	        $slider .= '<ul class="slickReservation img-gallery">';
	           foreach( $images as $image ): 
	                $slider .= '<li style="background: url('.$image['sizes']['full-size'].');">';
	                $slider .= '</li>';
	           endforeach;
	        $slider .= '</ul>';
		endif; 
		
		return $slider;
		
	}
	
	public function socialShare(){

		$shortURL = get_permalink();
		$shortTitle = get_the_title();
		
		// Get URLS
		$twitterURL = 'https://twitter.com/intent/tweet?text='.$shortTitle.'&amp;url='.$shortURL.'&amp;via=onlyoo';
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$shortURL;
		$googleURL = 'https://plus.google.com/share?url='.$shortURL;
		$linkedin = 'https://www.linkedin.com/shareArticle?mini=true&url='.$shortURL.'&title=Onlyoo&summary=&source='.$shortURL;
	
		// Add sharing button at the end of page/page content
		$content = '<div class="crunchify-social">';
		$content .= '<span class="cr-txt">Partager</span>';
		$content .= '<a class="crunchify-link crunchify-twitter" href="'. $twitterURL .'" target="_blank"><div class="fs1" aria-hidden="true" data-icon=""></div></a>';
		$content .= '<a class="crunchify-link crunchify-facebook" href="'.$facebookURL.'" target="_blank"><div class="fs1" aria-hidden="true" data-icon=""></div></a>';
		$content .= '<a class="crunchify-link crunchify-googleplus" href="'.$googleURL.'" target="_blank"><div class="fs1" aria-hidden="true" data-icon=""></div></a>';
		$content .= '<a class="crunchify-link crunchify-linkedin" href="'.$linkedin.'" target="_blank"><div class="fs1" aria-hidden="true" data-icon=""></div></a>';

		$content .= '</div>';
		
		
		return $content;
	}
	
	public function get_activity_time(){
		$time = get_field('duree');
		$time_s = get_field('duree-s');
		if( is_int($time) ){
			
			$duree = $time.' '.$time_s;
			
		}else{
			
			$timeduration = explode('.', $time);
			$mn = (isset($timeduration[1])) ? $timeduration[1] : '';
			$firsttable = ($timeduration[0] == 0 )? '' : $timeduration[0];
			$duree = $firsttable.' '.$time_s.' '.$mn;
		}

		 return $duree;
	}
	
	/*
		get_reservation_type()
		get the location for an activity
		@param integer ($ID) post ID
		@param bolean ($class) if true, output only class
	*/
	public function get_reservation_type($id, $class = false){
		$term_reservation_type = wp_get_post_terms($id, 'reservation_type');
		$data = '';
		
		if(!empty($term_reservation_type) && $id):
			$i = 0;
			foreach($term_reservation_type as $key=>$value){
				//get only top taxonomy
			  if(intval($value->parent) == 0 && $i == 0):
			  	$i++;
			  	//get fa icon linked to taxonomy in the custom field
			  	$id_term = 'reservation_type_'.$value->term_id;
			  	$icon =  get_field('fa_icon', $id_term);
			  	if($class == false ){
				  	$data .= '<i class="fa '.$icon.'"></i>'.$value->name;
			  	} else {
				  	$data .= $icon;
			  	}
			  	
			  endif;

			  
			}
		endif;
		
		return $data;
	}
										
	
	/*
		get_place
		get the location for an activity
		@param integer ($ID) post ID
	*/
	public function get_place($id){
		$term_lieu = wp_get_post_terms($id, 'lieu');
		$data = '';
		if(!empty($term_lieu) && $id):
			$data .= '<span class="fs1" aria-hidden="true" data-icon=""></span>';
			$i = 0;
			foreach($term_lieu as $key=>$value){
				$term_link = get_term_link( $value );
				if($i == 0){
					$data .= '<span>Lieu : <a href="' . esc_url( $term_link ) . '">'.$value->name.'</a></span>';
				} else {
					$data .= ', <a href="' . esc_url( $term_link ) . '">'.$value->name.'</a>';
				}
				
				$i++;
			}
		endif;
		
		return $data;
	}
	
	
	/*
		get_theme_terms
		get the theme terms for a post
		@param int ($ID) WP post ID
	*/
	public function get_theme_terms($id){
		$term_type = wp_get_post_terms($id, 'theme');
		$data = '';
		if(!empty($id)){
			$data .= '<div class="tags-s pure-g">';
			$data .= '<span class="fs1" aria-hidden="true" data-icon=""></span>';
			foreach($term_type as $key=>$value){
				$term_link = get_term_link( $value );
				$data .= '<span><a href="' . esc_url( $term_link ) . '">'.$value->name.'</a></span> ';
			}
			$data .= '</div>';
		}
		return $data;	
	}
	
	
	/*
		single_reservation_btn
		get some buttons for a single activity
		@param int ($id) WP post ID
	*/
	
	public function single_reservation_btn($id){
		
		$content = '<a id="CTA" class="btn btn-reg" href="'.site_url().'/'.BOOKING_URL.'/?addId='.$id.'">'.__('Ajouter cette activité','online-booking').'</a>';
		$content .= '<a class="btn btn-reg grey" href="'.site_url().'/'.SEJOUR_URL.'/">'.__('Voir toutes nos activités','online-booking').'</a>';
		
		return $content;
	}
	
	/*
		trash an activity for a sejour
		@param integer $day_number
		@param integer ID
	*/
	public function get_trash_btn($day_number,$id){
		global $post;
		$data = '';
		$price = get_field('prix',$id);
		$object_name = 'Uniquesejour'.$post->ID;
		$data = '<i title="Supprimer cette activité" onclick="deleteSejourActivity('.$day_number.','.$id.','.$price.','.$object_name.');" class="fa fa-trash-o"></i>';
		
		return $data;
		
	}
	
	
	public function get_sejour(){
		
				// check for rows (parent repeater)
				if( have_rows('votre_sejour') ): 
					echo '<div id="event-trip-planning">';
					$i = 1;
					while( have_rows('votre_sejour') ): the_row();
								
								echo '<div class="event-day day-content">';
								echo '<div class="etp-day">';
								echo '<div class="day-title">';
								echo '<i class="fa fa-calendar"></i><br />';
								_e('Journée','online-bookine');
								
								echo ' '.$i.'</div></div>';
							// check for rows (sub repeater)
							
							if( have_rows('activites') ):
								echo '<div class="etp-days">';
								
							
								while( have_rows('activites') ): the_row();
								

									// display each item as a list - with a class of completed ( if completed )
									
									$postActivity = get_sub_field('activite'); 
									foreach($postActivity as $data){
										
										$post_status = get_post_status( $data->ID );
										
										if($post_status == "publish"):
											
										
										echo '<div data-id="'.$data->ID.'" class="pure-u-1 single-activity-row">';
										echo '<span class="round"></span><span class="trait s-'.$i.'"></span>';
										
										echo '<div class="pure-g"><div class="pure-u-1 head">';
										echo '<div class="tags">'.$this->get_reservation_type($data->ID).'</div>';
										echo $this->get_trash_btn($i - 1, $data->ID);
										echo '</div></div>';
										
										echo '<div class="pure-g">';
										echo '<div class="pure-u-1 pure-u-md-7-24">';
										echo '<a href="'.get_permalink($data->ID).'">';
										echo get_the_post_thumbnail( $data->ID, array(250,180) );
										echo '</a>';
										echo '</div>';
										
										echo '<div class="pure-u-1 pure-u-md-17-24">';
											echo '<h3>';
											echo '<a href="'.get_permalink($data->ID).'"><i class="fa fa-search"></i>';
											echo $data->post_title;
											echo '</a></h3>';
											echo get_field('la_prestation_comprend', $data->ID);
											
											
										echo '</div>';
										echo '</div>';
										
										echo '</div>';
										endif;
									} 
									
								endwhile; 
								echo '</div>';
							endif; //if( get_sub_field('items') ): 
						echo '</div>';
						$i++; 
							endwhile; // while( has_sub_field('to-do_lists') ): 
					echo '</div>';
				endif; // if( get_field('to-do_lists') ): 


	}
	

}