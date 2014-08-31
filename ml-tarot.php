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

  $readingstring = $_GET["ml_reading"];
  $totalCards = (int)substr($readingstring, 0, 2);

  $demolp_output = $demolp_output ."<br />totalcards: " .$totalCards ."<br />";

  // calculate length temp
  define("LENGTHGUIDSTRING", 32);
  define("LENGTHTOTALCARDSSTRING", 2);
  define("LENGTHTAROTDECKIDSTRING", 2);
  define("LENGTHTAROTSPREADIDSTRING", 2);
  define("LENGTHCARDIDSTRING", 2);
  define("LENGTHDATETIME", 12);
  define("DECKIMAGESBASEFOLDER", 'images/decks');
    
  $totalLengthString = constant("LENGTHGUIDSTRING") + constant("LENGTHTOTALCARDSSTRING") + constant("LENGTHTAROTSPREADIDSTRING") + constant("LENGTHTAROTDECKIDSTRING") + ($totalCards * constant("LENGTHCARDIDSTRING")) + constant("LENGTHDATETIME");

  $demolp_output = $demolp_output ."<br />total length: " .$totalLengthString ."<br />";

  $tempReadingString = $readingstring;

  if (strlen($tempReadingString) == $totalLengthString )   {
      $readingguid;
      $tarotDeckId = 0;
      $tarotSpreadId = 0;
      $mltarotCardNumbers = array();
      $mlSpreadName;
      $mlSpreadPositions = array();
      $mlDeckImagesFolder = 'tarotofdreams'; //todo: uit database halen

      $readingguid = substr($tempReadingString, constant("LENGTHTOTALCARDSSTRING"), constant("LENGTHGUIDSTRING"));
      $tarotDeckId = substr($tempReadingString, constant("LENGTHTOTALCARDSSTRING") + constant("LENGTHGUIDSTRING"), constant("LENGTHTAROTDECKIDSTRING"));
      $tarotSpreadId = substr($tempReadingString, constant("LENGTHTOTALCARDSSTRING") + constant("LENGTHGUIDSTRING") + constant("LENGTHTAROTDECKIDSTRING"), constant("LENGTHTAROTSPREADIDSTRING"));

      $startIndexCardIds = constant("LENGTHTOTALCARDSSTRING") + constant("LENGTHGUIDSTRING") + constant("LENGTHTAROTDECKIDSTRING") + constant("LENGTHTAROTSPREADIDSTRING");

      for ($i = 0; $i < $totalCards; $i++) {
          $cardNumber = 0;
          $cardNumber = (int)substr($tempReadingString, $startIndexCardIds + $i * constant("LENGTHCARDIDSTRING"), constant("LENGTHCARDIDSTRING"));         

          $mltarotCardNumbers[$i] = $cardNumber;
      }

      $demolp_output = $demolp_output ."<br />reading guid: " .$readingguid ."<br />";
      $demolp_output = $demolp_output ."tarotdeck id: " .$tarotDeckId ."<br />";
      $demolp_output = $demolp_output ."tarotspread id: " .$tarotSpreadId ."<br />";
      $demolp_output = $demolp_output ."startindex cardid's: " .$startIndexCardIds ."<br />";

      $cardscount = count($tarotCardNumbers);
      for ($i=0; $i<$cardscount; $i++) {
        $demolp_output = $demolp_output ."  cardnumber: " .$mltarotCardNumbers[$i] ."<br />";
      }

      // get spread data
      global $wpdb;

      $mlSpread = $wpdb->get_row($wpdb->prepare("SELECT * FROM tarotspread WHERE id = '%d';", $tarotSpreadId));
      $mlSpreadName = $mlSpread->name;
      $demolp_output = $demolp_output ."<br />  spreadname: " .$mlSpreadName ."<br />";

      $mlSpreadPositions = $wpdb->get_results("SELECT * FROM tarotspreadposition WHERE tarotspread = $tarotSpreadId order by positionnumber" );

      $demolp_output = $demolp_output ."<ul>";
      for($i=0; $i<count($mlSpreadPositions); $i++) {
        $mlCardRow = $wpdb->get_row($wpdb->prepare("SELECT tarotcard.id as tarotcardid, tarotcard.name, tarotcard.interpretationsummary, tarotcarddeck.image FROM tarotcard INNER JOIN tarotcarddeck ON tarotcard.id = tarotcarddeck.tarotcard WHERE tarotdeck = '%d' AND tarotcard.id = '%d';", $tarotDeckId, $mltarotCardNumbers[$i]));
        
        $mlSpreadPositionObj = (object) array('SpreadPositionDescription' => $mlSpreadPositions[$i]->description, 
                                                'SpreadPositionName' => $mlSpreadPositions[$i]->positionname,
                                                'CardName' => $mlCardRow->name,
                                                'CardInterpretationSummary' => $mlCardRow->interpretationsummary,
                                                'CardId' => $mlCardRow->id,
                                                'CardImage' => $mlCardRow->image,
                                                'CardImagePath' => plugins_url( 'images/decks/' .$mlDeckImagesFolder . '/' .$mlCardRow->image , __FILE__ ) );

        $mlSpreadPositions[$i] = $mlSpreadPositionObj;
        $demolp_output = $demolp_output ."<li>position: " . $mlSpreadPositions[$i]->SpreadPositionName . ", card: " .$mlSpreadPositions[$i]->CardName . 
                            ", <br />interpretation: " . $mlSpreadPositions[$i]->CardInterpretationSummary . ", <img src='" .$mlSpreadPositions[$i]->CardImagePath ."' width='100px' /></li>";
      
    }

    $demolp_output = $demolp_output ."</ul>";
  }

    return $demolp_output;
}

function ml_tarot_scripts()
{
    wp_enqueue_style( 'style-name', plugins_url( '/css/tarot.css', __FILE__ ) );
    // Register the script like this for a plugin:
    wp_register_script( 'ml-tarot-script', plugins_url( '/js/tarotreading.js', __FILE__ ), array( 'jquery' )  );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'ml-tarot-script' );
}
?>