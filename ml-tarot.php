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
//add_action( 'init', 'ml_tarot_setup_rewrites' );
add_action('init', 'ml_tarot_rewrite_rule');

add_filter( 'body_class', 'ml_tarot_body_class_handler' );
add_filter( 'query_vars', 'ml_tarot_query_vars' );

define("mltarot_divider", '<div class="wpb_row  vc_row-fluid  mk-fullwidth-false add-padding-0 attched-false">
    <div class="vc_span12 wpb_column column_container " style="">
    <div class="mk-divider mk-shortcode divider_full_width single_dotted " style="padding: 20px 0 40px;"><div class="divider-inner"></div></div><div class="clearboth"></div>
    </div></div>');

function ml_generatereading_callback() {
	global $wpdb; // this is how you get access to the database

    $mlSpreadid = $_POST['spreadid'];
    $mlSpreadRow = $wpdb->get_row($wpdb->prepare("SELECT * FROM ml_tarotspread WHERE id = '%d';", $mlSpreadid));

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
  //process plugin
  $demolp_output = "Leggingen: ";
  //send back text to calling function

  global $wpdb;
  $mlSpreadsData = $wpdb->get_results("SELECT id, name, image, question FROM ml_tarotspread WHERE id IN (30, 6, 21, 40, 9, 13, 32, 10) ORDER BY CASE WHEN id = 6 THEN 1 WHEN id = 40 THEN 2 WHEN id = 21 THEN 3 WHEN id = 30 THEN 4 WHEN id = 32 THEN 5 WHEN id = 9 THEN 6 WHEN id = 10 THEN 7 WHEN id = 13 THEN 8 END ASC");

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
  $readingstring = '';

  if(isset($_GET['r']))
  {
      $readingstring = $_GET['r'];
  }

  if(isset($_GET['ml_reading']))
  {
      $readingstring = $_GET['ml_reading'];
  }
  
  if ($readingstring == '')
  {
        return '';
  }

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

      $mlSpread = $wpdb->get_row($wpdb->prepare("SELECT * FROM ml_tarotspread WHERE id = '%d';", $tarotSpreadId));
      $mlSpreadName = $mlSpread->name;
      $mlSpreadSummary = $mlSpread->summary;
      $mlSpreadQuestion = $mlSpread->question;
      $mlSpreadId = $mlSpread->id;
      //$demolp_output = $demolp_output ."<br />  spreadname: " .$mlSpreadName ."<br />";

      $mlSpreadPositionsData = $wpdb->get_results("SELECT * FROM ml_tarotspreadposition WHERE tarotspread = $tarotSpreadId order by positionnumber" );

      //$demolp_output = $demolp_output ."<ul>";
      for($i=0; $i<count($mlSpreadPositionsData); $i++) {
        $mlCardRow = $wpdb->get_row($wpdb->prepare("SELECT ml_tarotcard.id as tarotcardid, ml_tarotcard.name, ml_tarotcard.interpretationsummary, ml_tarotcarddeck.image, ml_tarotcard.id, ml_tarotcard.slug FROM ml_tarotcard INNER JOIN ml_tarotcarddeck ON ml_tarotcard.id = ml_tarotcarddeck.tarotcard WHERE tarotdeck = '%d' AND ml_tarotcard.id = '%d';", $tarotDeckId, $mltarotCardNumbers[$i]));
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
                                                'CardImagePath' => plugins_url( 'images/decks/' .$mlDeckImagesFolder . '/' .$mlCardRow->image , __FILE__ ),
                                                'CardInterpretationUrl' => mlGetCardInterpretationUrl($mlCardRow)
                                                );

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
        $demolp_output = $demolp_output .'<p><strong>Betekenis kaart</strong>: '.$mlSpreadPositions[$i]->CardInterpretationSummary   .'</p>
        <p><a class="icon-box-readmore" href="' .$mlSpreadPositions[$i]->CardInterpretationUrl .'">Lees meer over ' .$mlSpreadPositions[$i]->CardName .' <i class="mk-icon-caret-right"></i></a></p>';
        $demolp_output = $demolp_output .'</div>'; // end interpretation div
    }

    $demolp_output = $demolp_output .'</div>'; // end rendering container div interpretation

    //$demolp_output = $demolp_output ."</ul>";
  }

    return $demolp_output;
}

function ml_tarot_cards_overview_handler() {
    $mltarot_output = '';
    $mlCardUrl = '';
    $mlCardsBekersData = '';
    
    global $wpdb;
    // get cards 'grote arcana'
    $mltarot_output .= '<div class="tarot-interpretation-overview">';
    $mltarot_output .= '<h3>Grote Arcana</h3>';
    $mltarot_output .= '<div class="wpb_row  vc_row-fluid  mk-fullwidth-false add-padding-0 attched-false">';

    // column 1
    $mltarot_output .= '<div class="vc_span6 wpb_column column_container">';
    $mltarot_output .=  '<ul>';
    $mlCardsGroteArcanaCol1Data = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 1 AND number BETWEEN 0 AND 10 ORDER BY sortorder " );
    for($i=0; $i<count($mlCardsGroteArcanaCol1Data); $i++) {
        $mlCardUrl = mlGetCardInterpretationUrl($mlCardsGroteArcanaCol1Data[$i]);
        $mltarot_output .=  '<li><a href="' .$mlCardUrl .'">' .$mlCardsGroteArcanaCol1Data[$i]->romannumber .' ' .$mlCardsGroteArcanaCol1Data[$i]->name .'</a></li>';
    }
    $mltarot_output .=  '</ul>';
    $mltarot_output .=  '</div>'; // end column 1

    // column 2
    $mltarot_output .= '<div class="vc_span6 wpb_column column_container">';
    $mltarot_output .=  '<ul>';
    $mlCardsGroteArcanaCol2Data = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 1 AND number BETWEEN 11 AND 22 ORDER BY sortorder " );
    for($i=0; $i<count($mlCardsGroteArcanaCol2Data); $i++) {
        $mlCardUrl = mlGetCardInterpretationUrl($mlCardsGroteArcanaCol2Data[$i]);
        $mltarot_output .=  '<li><a href="' .$mlCardUrl .'">' .$mlCardsGroteArcanaCol2Data[$i]->romannumber .' ' .$mlCardsGroteArcanaCol2Data[$i]->name .'</a></li>';
    }
    $mltarot_output .=  '</ul>';
    $mltarot_output .=  '</div>'; // end column 2

    $mltarot_output .=  '</div>'; // end row

    $mltarot_output .= constant("mltarot_divider");

    $mltarot_output .= '<h3>Kleine Arcana</h3>';

    // staven and bekers columns
    $mltarot_output .= '<div class="wpb_row  vc_row-fluid  mk-fullwidth-false add-padding-0 attched-false">';

    // get cards 'staven'
    $mltarot_output .= '<div class="vc_span6 wpb_column column_container">';
    $mltarot_output .= '<h4>Staven</h4>';
    $mltarot_output .=  '<ul>';
    $mlCardsStavenData = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 4 ORDER BY sortorder" );
    for($i=0; $i<count($mlCardsStavenData); $i++) {
        $mlCardUrl = mlGetCardInterpretationUrl($mlCardsStavenData[$i]);
        $mltarot_output .=  '<li><a href="' .$mlCardUrl .'">' .$mlCardsStavenData[$i]->romannumber .' ' .$mlCardsStavenData[$i]->name .'</a></li>';
    }
    $mltarot_output .=  '</ul>';
    $mltarot_output .=  '</div>'; // end staven

    // get cards 'bekers'
    $mltarot_output .= '<div class="vc_span6 wpb_column column_container">';
    $mltarot_output .= '<h4>Bekers</h4>';
    $mltarot_output .=  '<ul>';
    $mlCardsBekersData = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 2 ORDER BY sortorder" );
    for($i=0; $i<count($mlCardsBekersData); $i++) {
        $mlCardUrl = mlGetCardInterpretationUrl($mlCardsBekersData[$i]);
        $mltarot_output .=  '<li><a href="' .$mlCardUrl .'">' .$mlCardsBekersData[$i]->romannumber .' ' .$mlCardsBekersData[$i]->name .'</a></li>';
    }
    $mltarot_output .=  '</ul>';
    $mltarot_output .=  '</div>'; // end staven
    $mltarot_output .=  '</div>'; // end row

    // zwaarden and pentakels columns
    $mltarot_output .= '<div class="wpb_row  vc_row-fluid  mk-fullwidth-false add-padding-0 attched-false">';

    // get cards 'zwaarden'
    $mltarot_output .= '<div class="vc_span6 wpb_column column_container">';
    $mltarot_output .= '<h4>Zwaarden</h4>';
    $mltarot_output .=  '<ul>';

    $mlCardsZwaardenData = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 3 ORDER BY sortorder" );
    for($i=0; $i<count($mlCardsZwaardenData); $i++) {
        $mlCardUrl = mlGetCardInterpretationUrl($mlCardsZwaardenData[$i]);
        $mltarot_output .=  '<li><a href="' .$mlCardUrl .'">' .$mlCardsZwaardenData[$i]->romannumber .' ' .$mlCardsZwaardenData[$i]->name .'</a></li>';
    }
    $mltarot_output .=  '</ul>';
    $mltarot_output .=  '</div>'; // end zwaarden

    // get cards 'pentakels'
    $mltarot_output .= '<div class="vc_span6 wpb_column column_container">';
    $mltarot_output .= '<h4>Pentakels</h4>';
    $mltarot_output .=  '<ul>';
    $mlCardsPentakelsData = $wpdb->get_results("SELECT * FROM ml_tarotcard WHERE tarotelement = 5 ORDER BY sortorder" );
    for($i=0; $i<count($mlCardsPentakelsData); $i++) {
        $mlCardUrl = mlGetCardInterpretationUrl($mlCardsPentakelsData[$i]);
        $mltarot_output .=  '<li><a href="' .$mlCardUrl .'">' .$mlCardsPentakelsData[$i]->romannumber .' ' .$mlCardsPentakelsData[$i]->name .'</a></li>';
    }
    $mltarot_output .=  '</ul>';
    $mltarot_output .=  '</div>'; // end pentakels

    $mltarot_output .=  '</div>'; // end row

    $mltarot_output .=  '</div>'; // end div tarot-interpretation-overview

    return $mltarot_output; 
}

// set up the rewrite rule
/*function ml_tarot_setup_rewrites(){
    add_rewrite_rule(
        'tarotkaart-betekenissen/tarotkaart-betekenis/([0-9]+)/?$',
        'index.php?pagename=tarotkaart-betekenissen/tarotkaart-betekenis&tarotcardid=$matches[1]',
        'top'
    );
}*/

function ml_tarot_rewrite_rule() {    
    global $wp; 
    $wp->add_query_var('tarotcardslug');
    add_rewrite_rule('tarotkaart-betekenissen/tarotkaart-betekenis/([a-z-]+)','index.php?pagename=tarotkaart-betekenissen/tarotkaart-betekenis&tarotcardslug=$matches[1]','top');
    
    /*global $wp_rewrite;
    $wp_rewrite->flush_rules();*/
}


function ml_tarot_query_vars( $query_vars ){
    $query_vars[] = 'tarotcardslug';
    return $query_vars;
}

function ml_tarot_cardinterpretation_handler()
{
    $mltarot_output = '';
    $mlCardSlug = get_query_var("tarotcardslug");

    global $wpdb;
    $mlCardRow = $wpdb->get_row($wpdb->prepare("SELECT * FROM ml_tarotcard WHERE slug = '%s';", $mlCardSlug));

    if (count($mlCardRow)  > 0) {
         //$mltarot_output .= '<h3>' .$mlCardRow->name .'</h3>';
         // add title
         $mlCardId = $mlCardRow->id;
         $mltarot_output .= '<div class="wpb_row  vc_row-fluid  mk-fullwidth-false add-padding-0 attched-false">
        <div class="vc_span12 wpb_column column_container " style="">
        <h1 class="mk-shortcode mk-fancy-title simple-style " id="fancy-title-432" style="font-size: 38px;text-align:center;color: #333333;font-weight:300;margin-top:0px;margin-bottom:20px; "><span style="">
        <p>' .$mlCardRow->name .'</p>
        </span></h1><div class="clearboth"></div>
        </div></div>';

        $mltarot_output .= '<div class="wpb_row  vc_row-fluid  mk-fullwidth-false add-padding-0 attched-false">
        <div class="vc_span2 wpb_column column_container " style="">
        </div>
        <div class="vc_span8 wpb_column column_container " style="">
        <div class="mk-text-block  " style="text-align: center;" id="interpretationsummary">
        <p><strong>' .$mlCardRow->interpretationsummary .'</strong></p>
        <div class="clearboth"></div></div>
        </div>
        <div class="vc_span2 wpb_column column_container " style="">
        </div></div>';

        $mltarot_output .= constant("mltarot_divider");

        // set images
        $mltarot_output .= '<div id="tarotimages">';
        $mlCardImagesQuery = 'SELECT image, imagesfolder, ml_tarotdeck.name FROM ml_tarotcarddeck INNER JOIN ml_tarotdeck ON (ml_tarotcarddeck.tarotdeck = ml_tarotdeck.id) WHERE tarotcard = ' .$mlCardId .' AND tarotdeck IN (7, 29, 3) ORDER BY CASE WHEN tarotdeck = 7 THEN 1 WHEN tarotdeck = 29 THEN 2 WHEN tarotdeck = 3 THEN 3 END ASC';
        $mlCardImagesData = $wpdb->get_results($mlCardImagesQuery);
        for($i=0; $i<count($mlCardImagesData); $i++) {
            $mlCardImageUrl = mlGetCardImageUrl($mlCardImagesData[$i]);
            $mltarot_output .=  '<div id="' .$mlCardImagesData[$i]->imagesfolder .'"><img src="' .$mlCardImageUrl . '" alt="' .$mlCardImagesData[$i]->name .'" /><div>' .$mlCardImagesData[$i]->name .'</div></div>';
        }
        $mltarot_output .= '</div>';
        //end set images

        $mltarot_output .= constant("mltarot_divider");

         $mltarot_output .= '<h4>Kernwoorden</h4>';
         $mltarot_output .= '<ul>';
         $mltarot_output .= '<li><strong>Drang: </strong>' .$mlCardRow->interpretation_drang .'</li>';
         $mltarot_output .= '<li><strong>Doel: </strong>' .$mlCardRow->interpretation_doel .'</li>';
         $mltarot_output .= '<li><strong>Licht: </strong>' .$mlCardRow->interpretation_licht .'</li>';
         $mltarot_output .= '<li><strong>Aanmoediging: </strong>' .$mlCardRow->interpretation_aanmoediging .'</li>';
         $mltarot_output .= '<li><strong>Schaduw: </strong>' .$mlCardRow->interpretation_schaduw .'</li>';
         $mltarot_output .= '<li><strong>Waarschuwing: </strong>' .$mlCardRow->interpretation_waarschuwing .'</li>';
         $mltarot_output .= '<li><strong>Kwaliteit: </strong>' .$mlCardRow->interpretation_kwaliteit .'</li>';
         $mltarot_output .= '</ul>';

         $mltarot_output .= constant("mltarot_divider");

         $mltarot_output .= '<div class="wpb_row  vc_row-fluid  mk-fullwidth-false add-padding-0 attched-false">';

         // begin algemeen
         $mltarot_output .= '<div class="vc_span4 wpb_column column_container " style="">';
         $mltarot_output .= '<div class="   simple_ultimate-style mk-box-icon" style="margin-bottom:30px;" id="box-icon-797">';
         $mltarot_output .= '<div class="left-side ">';
         $mltarot_output .= '<i class="mk-moon-star-4 small mk-main-ico" style="color:#bcc747;"></i>';
         $mltarot_output .= '<div class="box-detail-wrapper small-size">';
         $mltarot_output .= '<h4>Algemeen</h4>';
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
         $mltarot_output .= '<i class="mk-moon-user-7 small mk-main-ico" style="color:#bcc747;"></i>';
         $mltarot_output .= '<div class="box-detail-wrapper small-size">';
         $mltarot_output .= '<h4>Beroep</h4>';
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
         $mltarot_output .= '<i class="mk-moon-heart-6 small mk-main-ico" style="color:#bcc747;"></i>';
         $mltarot_output .= '<div class="box-detail-wrapper small-size">';
         $mltarot_output .= '<h4>Relatie</h4>';
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

         $mltarot_output .= constant("mltarot_divider");

         $mltarot_output .= '<div class="wpb_row  vc_row-fluid  mk-fullwidth-false add-padding-0 attched-false">
        <div class="vc_span12 wpb_column column_container " style="">
        <div class="   boxed-style mk-box-icon" style="margin-bottom:30px;" id="box-icon-406"><div class="icon-box-boxed  left"><i class="mk-moon-eye-4 mk-main-ico" style="border:1px solid #7F1944;background-color:#a92159;color:#ffffff;"></i>
        <h4 style="font-size:16px;font-weight:inhert;">' .$mlCardRow->name .' als dagkaart</h4>
        <p>' .$mlCardRow->interpretation_dagkaart .'</p>
        <div class="clearboth"></div><div class="clearboth"></div></div></div><style type="text/css"></style>
        </div>
        </div>';

         //$mltarot_output .= '<h3>Dagkaart</h3>';
         //$mltarot_output .= '<p>' .$mlCardRow->interpretation_dagkaart .'</p>';
    }

    return $mltarot_output;
}

function ml_tarot_body_class_handler($classes)
{
    $classes[] = 'tarot-interpretation';  
	     
    return $classes;
}

function ml_tarot_scripts()
{
    wp_enqueue_style( 'style-name', plugins_url( '/css/tarot.css', __FILE__ ) );
    // Register the script like this for a plugin:
    wp_enqueue_script( 'ml-tarot-script', plugins_url( '/js/tarotreading.js', __FILE__ ), array( 'jquery' )  );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'ml-tarot-script' );

    wp_localize_script( 'ml-tarot-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
}

function mlGetCardInterpretationUrl($mlCardRow) {
    return '/tarotkaart-betekenissen/tarotkaart-betekenis/' .$mlCardRow->slug;
}

function mlGetCardImageUrl($mlCardDeckRow) {
    $mlDeckImagesFolder = $mlCardDeckRow->imagesfolder;
    $mlCardDeckImage = $mlCardDeckRow->image;
    return plugins_url( 'images/decks/' .$mlDeckImagesFolder . '/' .$mlCardDeckImage , __FILE__ );
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