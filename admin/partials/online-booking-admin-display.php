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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h1>Online Booking - Devis</h1>

<h2>Devis non validés</h2>
<?php echo Online_Booking_Admin::get_users_booking(); ?>
<h2>Devis validés</h2>
<?php echo Online_Booking_Admin::get_users_booking(1); ?>
