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
add_shortcode("ml-tarot-cards-overview", "ml_tarot_cards_overview_handler");
add_shortcode("ml-tarot-cardinterpretation", "ml_tarot_cardinterpretation_handler");

add_action( 'wp_enqueue_scripts', 'ml_tarot_scripts' );
add_action( 'wp_ajax_ml_generatereading', 'ml_generatereading_callback' );
add_action( 'wp_ajax_nopriv_ml_generatereading', 'ml_generatereading_callback' );

function ml_generatereading_callback() {
	global $wpdb; // this is how you get access to the database

    $mlSpreadid = $_POST['spreadid'];
    $mlSpreadRow = $wpdb->get_row($wpdb->prepare("SELECT * FROM tarotspread WHERE id = '%d';", $mlSpreadid));

	$mlSpreadTotalCards = $mlSpreadRow->totalcards;
    $mlReadingGuid = mlNewGuid();
    $mlDeckId = '29'; // TODO

    // get random cards
    $mlAllCardIds = range(1, 78);
    shuffle($mlAllCardIds);
    $mlRandomCards = array_slice($mlAllCardIds, 0, (int)$mlSpreadTotalCards);
    
    $mlCardIdList = '';

    foreach($mlRandomCards as $mlCardId )
    {
        $mlCardIdList .= sprintf("%02s", $mlCardId);
    }

    $readingdata = sprintf("%02s", $mlSpreadTotalCards) . $mlReadingGuid .sprintf("%02s", $mlDeckId) .sprintf("%02s", $mlSpreadid) .$mlCardIdList .'260814081918';

    $pg1 = array(
       'readingdata' => $readingdata
    );

    echo json_encode($pg1);	

	die(); // this is required to return a proper result
}

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
  $mlSpreadsData = $wpdb->get_results("SELECT * FROM tarotspread ORDER BY visitorcount DESC LIMIT 15");

    $demolp_output = '<ul id="spreadoverview">';
    foreach( $mlSpreadsData as $mlSpreadData ) {
        
        $demolp_output = $demolp_output . '<li>
        <div class="spreadpanel" id="spreadpanel-' .$mlSpreadData->id .'">
            <div class="spreadimagepanel">
                <img src="' .plugins_url( 'images/legpatronen/' .$mlSpreadData->image , __FILE__ ) .'" class="spreadimage" />
            </div>
            <h4>' . $mlSpreadData->name .'</h4>
            <p>Geeft antwoord op de vraag: <strong>' .$mlSpreadData->question .'</strong></p>
        </div>
        </li>';
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

  $readingstring = $_GET["ml_reading"];
  $totalCards = (int)substr($readingstring, 0, 2);

  $demolp_output = '';
  //$demolp_output = $demolp_output ."<br />totalcards: " .$totalCards ."<br />";

  // calculate length temp
  define("LENGTHTOTALCARDSSTRING", 2);
  define("LENGTHGUIDSTRING", 32);
  define("LENGTHTAROTDECKIDSTRING", 2);
  define("LENGTHTAROTSPREADIDSTRING", 2);
  define("LENGTHCARDIDSTRING", 2);
  define("LENGTHDATETIME", 12);
  define("DECKIMAGESBASEFOLDER", 'images/decks');
    
  $totalLengthString = constant("LENGTHGUIDSTRING") + constant("LENGTHTOTALCARDSSTRING") + constant("LENGTHTAROTSPREADIDSTRING") + constant("LENGTHTAROTDECKIDSTRING") + ($totalCards * constant("LENGTHCARDIDSTRING")) + constant("LENGTHDATETIME");

  //$demolp_output = $demolp_output ."<br />total length: " .$totalLengthString ."<br />";

  // TODO: show datetime

  $tempReadingString = $readingstring;

  if (strlen($tempReadingString) == $totalLengthString)   {
      $readingguid;
      $tarotDeckId = 0;
      $tarotSpreadId = 0;
      $mltarotCardNumbers = array();
      $mlSpreadName;
      $mlSpreadPositions = array();
      $mlDeckImagesFolder = 'tarotofdreams'; //todo: uit database halen

      $readingguid = substr($tempReadingString, constant("LENGTHTOTALCARDSSTRING"), constant("LENGTHGUIDSTRING"));
      $tarotDeckId = substr($tempReadingString, constant("LENGTHTOTALCARDSSTRING") + constant("LENGTHGUIDSTRING"), constant("LENGTHTAROTDECKIDSTRING"));
      $tarotDeckId = absint($tarotDeckId);
      $tarotSpreadId = substr($tempReadingString, constant("LENGTHTOTALCARDSSTRING") + constant("LENGTHGUIDSTRING") + constant("LENGTHTAROTDECKIDSTRING"), constant("LENGTHTAROTSPREADIDSTRING"));

      $startIndexCardIds = constant("LENGTHTOTALCARDSSTRING") + constant("LENGTHGUIDSTRING") + constant("LENGTHTAROTDECKIDSTRING") + constant("LENGTHTAROTSPREADIDSTRING");

      for ($i = 0; $i < $totalCards; $i++) {
          $cardNumber = 0;
          $cardNumber = (int)substr($tempReadingString, $startIndexCardIds + $i * constant("LENGTHCARDIDSTRING"), constant("LENGTHCARDIDSTRING"));         

          $mltarotCardNumbers[$i] = $cardNumber;
      }

      //$mlCardsString = implode(",", $mltarotCardNumbers);
      //$demolp_output = $demolp_output ."<br />reading guid: " .$readingguid ."<br />";
      //$demolp_output = $demolp_output ."tarotdeck id: " .$tarotDeckId ."<br />";
      //$demolp_output = $demolp_output ."tarotspread id: " .$tarotSpreadId ."<br />";
      //$demolp_output = $demolp_output ."cardnumbers: " .$mlCardsString ."<br />";

      /*for ($i=0; $i<$cardscount; $i++) {
        $demolp_output = $demolp_output ."  cardnumber: " .$mltarotCardNumbers[$i] ."<br />";
      }*/

      // get spread data
      global $wpdb;

      $mlSpread = $wpdb->get_row($wpdb->prepare("SELECT * FROM tarotspread WHERE id = '%d';", $tarotSpreadId));
      $mlSpreadName = $mlSpread->name;
      $mlSpreadSummary = $mlSpread->summary;
      $mlSpreadQuestion = $mlSpread->question;
      $mlSpreadId = $mlSpread->id;
      //$demolp_output = $demolp_output ."<br />  spreadname: " .$mlSpreadName ."<br />";

      $mlSpreadPositionsData = $wpdb->get_results("SELECT * FROM tarotspreadposition WHERE tarotspread = $tarotSpreadId order by positionnumber" );

      //$demolp_output = $demolp_output ."<ul>";
      for($i=0; $i<count($mlSpreadPositionsData); $i++) {
        $mlCardRow = $wpdb->get_row($wpdb->prepare("SELECT ml_tarotcard.id as tarotcardid, ml_tarotcard.name, ml_tarotcard.interpretationsummary, tarotcarddeck.image FROM ml_tarotcard INNER JOIN tarotcarddeck ON ml_tarotcard.id = tarotcarddeck.tarotcard WHERE tarotdeck = '%d' AND ml_tarotcard.id = '%d';", $tarotDeckId, $mltarotCardNumbers[$i]));
        $mlCardId = -1;
        if(isset($mlCardRow->id))
        {
            $mlCardId = $mlCardRow->id;
        }

        $mlSpreadPositionObj = (object) array('SpreadPositionDescription' => $mlSpreadPositionsData[$i]->description, 
                                                'SpreadPositionName' => $mlSpreadPositionsData[$i]->positionname,
                                                'SpreadPositionNumber' => $mlSpreadPositionsData[$i]->positionnumber,
                                                'SpreadPositionDescription' => $mlSpreadPositionsData[$i]->description,
                                                'CardName' => $mlCardRow->name,
                                                'CardInterpretationSummary' => $mlCardRow->interpretationsummary,
                                                'CardId' => $mlCardId,
                                                'CardImage' => $mlCardRow->image,
                                                'CardImagePath' => plugins_url( 'images/decks/' .$mlDeckImagesFolder . '/' .$mlCardRow->image , __FILE__ ) );

        $mlSpreadPositions[$i] = $mlSpreadPositionObj;
        /*$demolp_output = $demolp_output ."<li>position: " . $mlSpreadPositions[$i]->SpreadPositionName . ", card: " .$mlSpreadPositions[$i]->CardName . 
                            ", <br />interpretation: " . $mlSpreadPositions[$i]->CardInterpretationSummary . ", <img src='" .$mlSpreadPositions[$i]->CardImagePath ."' width='100px' /></li>";
      */
    }

    //render container div
    $demolp_output = $demolp_output .'<h3>' .$mlSpreadName .'</h3>';
    $demolp_output = $demolp_output .'<p>' .$mlSpreadSummary .'</p>';
    $demolp_output = $demolp_output .'<p>Geeft antwoord op de vraag <strong>' .$mlSpreadQuestion .'</strong></p>';

    if ($tarotDeckId == 29 and false) {
        $demolp_output .= '<div>Liever een ander deck? <a href="#" id="switchdeck-7">Switch naar Rider Waite</a></div>';
    }

    $demolp_output = $demolp_output .'<div class="spread" id="spread' .$mlSpreadId .'">';

    // render card images
    for($i=0; $i<count($mlSpreadPositions); $i++) {
        $demolp_output = $demolp_output .'<div class="position" id="position' .$mlSpreadPositions[$i]->SpreadPositionNumber .'">';
        $demolp_output = $demolp_output .'<img src="' .$mlSpreadPositions[$i]->CardImagePath .'" />';
        $demolp_output = $demolp_output .'<div>'.$mlSpreadPositions[$i]->SpreadPositionNumber . ': '  .$mlSpreadPositions[$i]->CardName .'</div>';
        $demolp_output = $demolp_output ."</div>";
    }

    $demolp_output = $demolp_output .'</div>'; // end rendering container div cardimages

     // render interpretations
    $demolp_output = $demolp_output .'<div class="interpretations">';

    // render positions
    for($i=0; $i<count($mlSpreadPositions); $i++) {
        $demolp_output = $demolp_output .'<div class="interpretation" id="interpretation' .$mlSpreadPositions[$i]->SpreadPositionNumber .'">';
        //$demolp_output = $demolp_output .'<img src="' .$mlSpreadPositions[$i]->CardImagePath .'" />';
        $demolp_output = $demolp_output .'<h4>Positie '.$mlSpreadPositions[$i]->SpreadPositionNumber . ': <strong>'  .$mlSpreadPositions[$i]->CardName .'</strong></h4>';
        $demolp_output = $demolp_output .'<p><strong>Betekenis positie:</strong> '.$mlSpreadPositions[$i]->SpreadPositionDescription   .'</p>';
        $demolp_output = $demolp_output .'<p><strong>Betekenis kaart</strong>: '.$mlSpreadPositions[$i]->CardInterpretationSummary   .'</p>';
        $demolp_output = $demolp_output .'</div>'; // end interpretation div
    }

    $demolp_output = $demolp_output .'</div>'; // end rendering container div interpretation

    //$demolp_output = $demolp_output ."</ul>";
  }

    return $demolp_output;
}

function ml_tarot_cards_overview_handler() {
    $mltarot_output = '';

    global $wpdb;
    // get cards 'grote arcana'
    $mltarot_output .= '<h3>Grote Arcana</h3>';
    $mltarot_output .=  '<ul>';

    $mlCardsGroteArcanaData = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 1 ORDER BY sortorder" );
    for($i=0; $i<count($mlCardsGroteArcanaData); $i++) {
        $mltarot_output .=  '<li><a href="' .'#' .'">' .$mlCardsGroteArcanaData[$i]->romannumber .' ' .$mlCardsGroteArcanaData[$i]->name .'</a></li>';
    }

    $mltarot_output .=  '</ul>';

    // get cards 'bekers'
    $mltarot_output .= '<h3>Kleine Arcana</h3>';
    $mltarot_output .= '<h4>Bekers</h4>';
    $mltarot_output .=  '<ul>';

    $mlCardsBekersData = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 2 ORDER BY sortorder" );
    for($i=0; $i<count($mlCardsBekersData); $i++) {
        $mltarot_output .=  '<li><a href="' .'#' .'">' .$mlCardsBekersData[$i]->romannumber .' ' .$mlCardsBekersData[$i]->name .'</a></li>';
    }

    $mltarot_output .=  '</ul>';

    // get cards 'zwaarden'
    $mltarot_output .= '<h4>Zwaarden</h4>';
    $mltarot_output .=  '<ul>';

    $mlCardsZwaardenData = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 3 ORDER BY sortorder" );
    for($i=0; $i<count($mlCardsZwaardenData); $i++) {
        $mltarot_output .=  '<li><a href="' .'#' .'">' .$mlCardsZwaardenData[$i]->romannumber .' ' .$mlCardsZwaardenData[$i]->name .'</a></li>';
    }

    $mltarot_output .=  '</ul>';

    // get cards 'staven'
    $mltarot_output .= '<h4>Staven</h4>';
    $mltarot_output .=  '<ul>';

    $mlCardsStavenData = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 4 ORDER BY sortorder" );
    for($i=0; $i<count($mlCardsStavenData); $i++) {
        $mltarot_output .=  '<li><a href="' .'#' .'">' .$mlCardsStavenData[$i]->romannumber .' ' .$mlCardsStavenData[$i]->name .'</a></li>';
    }

    $mltarot_output .=  '</ul>';

    // get cards 'pentakels'
    $mltarot_output .= '<h4>Pentakels</h4>';
    $mltarot_output .=  '<ul>';

    $mlCardsPentakelsData = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 5 ORDER BY sortorder" );
    for($i=0; $i<count($mlCardsPentakelsData); $i++) {
        $mltarot_output .=  '<li><a href="' .'#' .'">' .$mlCardsPentakelsData[$i]->romannumber .' ' .$mlCardsPentakelsData[$i]->name .'</a></li>';
    }

    $mltarot_output .=  '</ul>';

    return $mltarot_output; 
}

function ml_tarot_cardinterpretation_handler()
{
    $mltarot_output = '';
    $mlCardId = absint($_GET["id"]);

    global $wpdb;
    $mlCardRow = $wpdb->get_row($wpdb->prepare("SELECT * FROM ml_tarotcard WHERE id = '%d';", $mlCardId));

    if (count($mlCardRow)  > 0) {
         $mltarot_output .= '<h2>' .$mlCardRow->name .'</h2>';
         $mltarot_output .= '<h3>Kernwoorden</h3>';
         $mltarot_output .= '<ul>';
         $mltarot_output .= '<li><strong>Drang: </strong>' .$mlCardRow->interpretation_drang .'</li>';
         $mltarot_output .= '<li><strong>Doel: </strong>' .$mlCardRow->interpretation_doel .'</li>';
         $mltarot_output .= '<li><strong>Licht: </strong>' .$mlCardRow->interpretation_licht .'</li>';
         $mltarot_output .= '<li><strong>Aanmoediging: </strong>' .$mlCardRow->interpretation_aanmoediging .'</li>';
         $mltarot_output .= '<li><strong>Schaduw: </strong>' .$mlCardRow->interpretation_schaduw .'</li>';
         $mltarot_output .= '<li><strong>Waarschuwing: </strong>' .$mlCardRow->interpretation_waarschuwing .'</li>';
         $mltarot_output .= '<li><strong>Kwaliteit: </strong>' .$mlCardRow->interpretation_kwaliteit .'</li>';
         $mltarot_output .= '</ul>';

         $mltarot_output .= '<div class="wpb_row  vc_row-fluid  mk-fullwidth-false add-padding-0 attched-false">';

         // begin algemeen
         $mltarot_output .= '<div class="vc_span4 wpb_column column_container " style="">';
         $mltarot_output .= '<div class="   simple_ultimate-style mk-box-icon" style="margin-bottom:30px;" id="box-icon-797">';
         $mltarot_output .= '<div class="left-side ">';
         $mltarot_output .= '<a href="#"><i class="mk-moon-star-4 small mk-main-ico" style="color:#0bb697;"></i></a>';
         $mltarot_output .= '<div class="box-detail-wrapper small-size">';
         $mltarot_output .= '<h3>Algemeen</h3>';
         $mltarot_output .= '<ul>';
         $mlKeywordsAlgemeen = explode(";", $mlCardRow->interpretation_keywords_algemeen);
         foreach($mlKeywordsAlgemeen as $mlKeywordAlgemeen) {
            $mltarot_output .= '<li>' .$mlKeywordAlgemeen .'</li>';
         }
         $mltarot_output .= '</ul>';
         $mltarot_output .= '<div class="clearboth"></div>';
         $mltarot_output .= '</div>'; // end div box-detail
         $mltarot_output .= '<div class="clearboth"></div></div>'; // end div left-side
         $mltarot_output .= '</div>'; // end div simple_ultimate_style
         $mltarot_output .= '</div>'; // end div vc_span4
         //end algemeen
         
         // begin beroep
         $mltarot_output .= '<div class="vc_span4 wpb_column column_container " style="">';
         $mltarot_output .= '<div class="   simple_ultimate-style mk-box-icon" style="margin-bottom:30px;" id="box-icon-797">';
         $mltarot_output .= '<div class="left-side ">';
         $mltarot_output .= '<a href="#"><i class="mk-icon-building small mk-main-ico" style="color:#0bb697;"></i></a>';
         $mltarot_output .= '<div class="box-detail-wrapper small-size">';
         $mltarot_output .= '<h3>Beroep</h3>';
         $mltarot_output .= '<ul>';
         $mlKeywordsBeroep = explode(";", $mlCardRow->interpretation_keywords_beroep);
         foreach($mlKeywordsBeroep as $mlKeywordBeroep) {
            $mltarot_output .= '<li>' .$mlKeywordBeroep .'</li>';
         }
         $mltarot_output .= '</ul>';
         $mltarot_output .= '<div class="clearboth"></div>';
         $mltarot_output .= '</div>'; // end div box-detail
         $mltarot_output .= '<div class="clearboth"></div></div>'; // end div left-side
         $mltarot_output .= '</div>'; // end div simple_ultimate_style
         $mltarot_output .= '</div>'; // end div vc_span4
         //end beroep

         // begin relatie
         $mltarot_output .= '<div class="vc_span4 wpb_column column_container " style="">';
         $mltarot_output .= '<div class="   simple_ultimate-style mk-box-icon" style="margin-bottom:30px;" id="box-icon-797">';
         $mltarot_output .= '<div class="left-side ">';
         $mltarot_output .= '<a href="#"><i class="mk-moon-heart-6 small mk-main-ico" style="color:#0bb697;"></i></a>';
         $mltarot_output .= '<div class="box-detail-wrapper small-size">';
         $mltarot_output .= '<h3>Relatie</h3>';
         $mltarot_output .= '<ul>';
         $mlKeywordsRelatie = explode(";", $mlCardRow->interpretation_keywords_relatie);
         foreach($mlKeywordsRelatie as $mlKeywordRelatie) {
            $mltarot_output .= '<li>' .$mlKeywordRelatie .'</li>';
         }
         $mltarot_output .= '</ul>';
         $mltarot_output .= '<div class="clearboth"></div>';
         $mltarot_output .= '</div>'; // end div box-detail
         $mltarot_output .= '<div class="clearboth"></div></div>'; // end div left-side
         $mltarot_output .= '</div>'; // end div simple_ultimate_style
         $mltarot_output .= '</div>'; // end div vc_span4
         // end relatie

         $mltarot_output .= '</div>'; // end div row keywords

         $mltarot_output .= '<h3>Dagkaart</h3>';
         $mltarot_output .= '<p>' .$mlCardRow->interpretation_dagkaart .'</p>';
    }

    return $mltarot_output;
}

function ml_tarot_scripts()
{
    wp_enqueue_style( 'style-name', plugins_url( '/css/tarot.css', __FILE__ ) );
    // Register the script like this for a plugin:
    wp_register_script( 'ml-tarot-script', plugins_url( '/js/tarotreading.js', __FILE__ ), array( 'jquery' )  );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'ml-tarot-script' );

    wp_localize_script( 'ml-tarot-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
}

// Generate Guid 
function mlNewGuid() { 
    $s = strtoupper(md5(uniqid(rand(),true))); 
    $mlguidText = 
        substr($s,0,8) . 
        substr($s,8,4) . 
        substr($s,12,4) . 
        substr($s,16,4) . 
        substr($s,20); 
    return $mlguidText;
}
?>