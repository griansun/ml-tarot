<?php
/*
Plugin Name: Mysticlight Tarot
Description: Mysticlight Tarot
Version: 0.1 BETA
Author: Ariska Keldermann
*/

//tell wordpress to register the demolistposts shortcode
add_shortcode("ml-tarot-spread-overview", "ml_tarot_spread_overview_handler");
add_shortcode("ml-tarot-dynamicspread", "ml_tarot_dynamicspread_handler");

add_action( 'wp_enqueue_scripts', 'ml_tarot_scripts' );

/*function ml-custom_rewrite_tag() {
        add_rewrite_tag('%ml_reading%', '([^&]+)');
    }
    add_action('init', 'ml-custom_rewrite_tag', 10, 0);*/

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

        $demolp_output = $demolp_output . '<li><a href="#" class="spreadlink">' . $results->name .'</a></li>';
    }
    $demolp_output = $demolp_output . '</ul>';
    return $demolp_output;
}

function ml_tarot_dynamicspread_handler() {
  //run function that actually does the work of the plugin
  $demolph_output = ml_tarot_dynamicspread_function();
  //send back text to replace shortcode in post
  return $demolph_output;
}

function ml_tarot_dynamicspread_function() {
  //process plugin
  $demolp_output = "Dynamische legging: " .$_GET["ml_reading"];
  //send back text to calling function
  /*global $wpdb;
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
    */
    return $demolp_output;
}

function ml_tarot_scripts()
{
    // Register the script like this for a plugin:
    wp_register_script( 'ml-tarot-script', plugins_url( '/js/tarotreading.js', __FILE__ ), array( 'jquery' )  );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'ml-tarot-script' );
}
?>