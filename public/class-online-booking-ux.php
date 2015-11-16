<?php
/*
	Class to embed utilities
*/
	
class online_booking_ux  {
	
	
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
	
		// Add sharing button at the end of page/page content
		$content = '<div class="crunchify-social">';
		$content .= '<a class="crunchify-link crunchify-twitter" href="'. $twitterURL .'" target="_blank"><div class="fs1" aria-hidden="true" data-icon=""></div></a>';
		$content .= '<a class="crunchify-link crunchify-facebook" href="'.$facebookURL.'" target="_blank"><div class="fs1" aria-hidden="true" data-icon=""></div></a>';
		$content .= '<a class="crunchify-link crunchify-googleplus" href="'.$googleURL.'" target="_blank"><div class="fs1" aria-hidden="true" data-icon=""></div></a>';

		$content .= '</div>';
		
		
		return $content;
	}
	
	/*
		get_place
		@param integer ($ID) post ID
	*/
	public function get_place($ID){
		$term_lieu = wp_get_post_terms($ID, 'lieu');
		$place = '';
		if(!empty($term_lieu) && $ID):
			$place .= '<span class="fs1" aria-hidden="true" data-icon=""></span>';
			foreach($term_lieu as $key=>$value){
				$term_link = get_term_link( $value );
				$place .= '<span><a href="' . esc_url( $term_link ) . '">'.$value->name.'</a></span> ';
			}
		endif;
		
		return $place;
	}
	
	public function get_terms(){
		
	}

}