<?php
/**
 * Plugin Name: Bridge Bidding Filter
 * Plugin URI: http://webther.net/
 * Description: TinyMCE plugin providing a bidding editor shortcode
 * Version: 1.14.01.30
 * Author: Ther <bszirmay@gmail.com>
 * Author URI: http://webthernet
 * License: GNU All Permissive License
 */

require_once(plugin_dir_path( __FILE__ ) . 'Bidding.class.php');
require_once(plugin_dir_path( __FILE__ ) . 'bidding-view.php');
include_once(plugin_dir_path( __FILE__ ) . 'header-item-view.php');
include_once(plugin_dir_path( __FILE__ ) . 'bidding-content-view.php');

//[bidding]
function bidding_func( $atts, $content = null ){
	$content = str_replace("<br/>", "\n", $content);
	$content = str_replace("<p>", "\n", $content);
	$content = str_replace("</p>", "", $content);
//	$content = strip_tags($content);
	try {
		$bidding = Bidding::fromCode($content);
		ob_start();
		renderView($bidding);
		$retval = ob_get_contents();
		ob_end_clean();
		return $retval;
	} catch(BiddingFormatException $e){
		
		return 'Error: ' . $e->getMessage() . '<br/>' .$content;
	}
}

function bidding_scripts(){
	wp_register_style( 'bidding-style', WP_PLUGIN_URL . '/bidding/bridge-bidding.css' );
	wp_enqueue_style( 'bidding-style' );
}
add_action( 'wp_enqueue_scripts', 'bidding_scripts' ); 
add_shortcode( 'bidding', 'bidding_func' );


?>
