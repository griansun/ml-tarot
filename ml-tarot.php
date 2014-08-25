<?php
/*
Plugin Name: Mysticlight Tarot
Description: Mysticlight Tarot
Version: 0.1 BETA
Author: Ariska Keldermann
*/

//tell wordpress to register the demolistposts shortcode
add_shortcode("ml-tarot-spread-overview", "ml_tarot_spread_overview_handler");

function ml_tarot_spread_overview_handler() {
  //run function that actually does the work of the plugin
  $demolph_output = ml_tarot_spread_overview_function();
  //send back text to replace shortcode in post
  return $demolph_output;
}

function ml_tarot_spread_overview_function() {
  //process plugin
  $demolp_output = "Leggingen: ";
  //send back text to calling function
  global $wpdb;
  $result = $wpdb->get_results(
	"
	SELECT * FROM tarotspread
	"
    );

    $demolp_output = '<ul>';
    foreach( $result as $results ) {

        $demolp_output = $demolp_output . '<li>' . $results->name .'</li>';
    }
    $demolp_output = $demolp_output . '</ul>';
    return $demolp_output;
}


?>