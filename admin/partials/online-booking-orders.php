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
<h1>Online Booking - Devis</h1>
<?php Online_Booking_Admin::list_table_page(); ?>
