<?php
/**
 * Plugin Name: Bridge Bidding Filter
 * Plugin URI: http://webther.net/
 * Description: TinyMCE plugin providing a Hand editor feature
 * Version: 1.14.01.30
 * Author: Ther <bszirmay@gmail.com>
 * Author URI: http://webthernet
 * License: GNU All Permissive License
 */

require_once('Bidding.class.php');
require_once('bidding-view.php');

//[bidding]
function bidding_func( $atts, $content = null ){
	try {
		$bidding = Bidding::fromCode($content);
		renderView($bidding);
	} catch(BiddingFormatException $e){
		return $e->getMessage();
	}
}
add_shortcode( 'bidding', 'bidding_func' );


?>
