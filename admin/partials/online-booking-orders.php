<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/admin/partials
 */
?>
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php add_thickbox(); ?>
<div class="wrap">
<h1>Online Booking - Events en cours</h1>
<form method="post">
<?php 
	
	
	$args = array(
		'validation_state' => 0
	);
	$wp_list_table = new Quotation_Table($args);
	
	
	
	echo '<input type="hidden" name="page" value="'.$_REQUEST['page'].'">';
	$wp_list_table->search_box( 'search', 'search_id' );
	$wp_list_table->prepare_items();
	$wp_list_table->display();
	
	 ?>
</form>
</div>


