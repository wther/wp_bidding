<?php
/**
 * Bidding data object representing a bidding scenario, e.g.
 * a convention over an 1N-2C-2D sequance.
 * 
 * @file Bidding.class.php
 * @author Ther <bszirmay@gmail.com>
 * @date 2014-01-30
 * @license GPL
 */
 
/**
 * Exception thrown when parsing a Bidding object
 * failed due to malformatted bidding
 */
class BiddingFormatException extends Exception { }
 
//=============
class Bidding {
//=============

		/**
		 * 2xn or 4xn array containing bids
		 */
		private $header = array();
		
		/**
		 * Content of the table, with rows e.g.
		 * '2C-2D' => array('class' => 'important', 'value' => 'value')
		 */
		private $content = array();
		
		/**
		 * Use builder contructors instead of default
		 */
		private function __construct(){
			
		}
		
		/**
		 * Builder constructor to generate bidding object from
		 * shortcode.
		 */
		public static function fromCode($code){
			$lines = explode("\n", trim($code));
			
			// First lines are part of the header, then 
			// we except a few empty lines, and then the
			// bidding content.
			$filling_header = true;
			
			$retval = new Bidding();
			
			for($i = 0; $i < count($lines); $i++){
				$line = trim($lines[$i]);
				
				// Empty line
				if($line == ''){
					if($filling_header){
						$filling_header = false;
					} else {
						continue;
					}
					
				// Line is not empty
				} else {
					if($filling_header){
						$items = explode("-", $line);
						$head = array();
						foreach($items as $item){
							$head[] = Bidding::stringToHeaderBid($item);
						}
						$retval->addHeader($head);
					} else {
						$fields = explode(":", $line, 2);
						if(count($fields) != 2){
							throw new BiddingFormatException("No separating : found in:  $line");
						}
						
						$sequence = strpos($fields[0], '@') !== false ? explode("@", $fields[0],2) : array($fields[0]);

						
						$class = count($sequence) > 1 ? $sequence[1] : "normal";
						$items = explode("-",$sequence[0]);
						if(count($items) < 1){
							throw new BiddingFormatException("No bidding sequence found for: $line");
						}
						
						$error_msg = "";
						foreach($items as &$item){
							$item = trim($item);
							if(!Bidding::validBidString($item, $error_msg)){
								throw new BiddingFormatException("Failed to parse $item, because: $error_msg");
							}
						}
						
						$retval->addContentLine(array(
							'bidding' => $items,
							'class' => $class,
							'content' => trim($fields[1])
						));
					}
				}
			}
			
			return $retval;
		}
		
		/**
		 * Adds new line to header
		 */
		public function addHeader($head){
			$count = array();
			foreach($this->header as $array){
				$count[count($array)] = true;
			}
			
			if(count($count) >= 2){
				throw new BiddingFormatException("Invalid bidding header, bidding is seemingly finished in line " . count($this->header)+1);
			}
			
			if(count($count) == 1 && !isset($count[count($head)])){
				if(count($head) > count($this->header[0])){
					throw new BiddingFormatException("Line " . count($this->header). " contains longer sequence than the first line");
				}
			}
			
			$this->header[] = $head;
		}
		
		/**
		 * Adds new line to content
		 */
		public function addContentLine($line){
			$this->content[] = $line;
		}
		
		/**
		 * Returns with a valude indicating whether $bid is valid bid
		 */
		public static function validBidString($bid, &$error_msg = ""){
			if(strpos($bid, "/") !== false){
				$bids = explode("/", $bid);
				if(count($bids) <= 1){
					$error_msg = "Separating / should have bids on both sides for $bid";
				}
				foreach($bids as $rec_bid){
					if(!Bidding::validBidString($rec_bid, $error_msg)){
						return false;
					}
				}
				return true;
			}
			
			$bid = trim($bid);
			$allowed_short = array('X', 'XX', 'P', '?');
			if(in_array($bid, $allowed_short)){
				return true;
			} else if(strlen($bid) < 2){
				$txt = implode(",", $allowed_short);
				$error_msg = "Bids should be at least 2 characters long or one of $txt, $bid isn't.";
				return false;
			}
			
			if(strpos('1234567', $bid[0]) === false){
				$error_msg = "Bids have to start with a numeric value from the 1-7 interval, $bid[0] isn't in " . $bid;
				return false;
			}
			
			$suit = substr($bid, 1);
			$allowed_suits = array('S','H','D','C','NT', 'X','Y','Z', 'M', 'm');
			if(!in_array($suit, $allowed_suits)){
				$error_msg = "Bids have to have a suit from the following value: ".implode(",", $allowed_suits)." not: $suit";
				return false;
			}
			
			return true;
		}
		
		/**
		 * Converts string to bid for the header, e.g. 
		 */
		private static function stringToHeaderBid($string){
			$string = trim($string);
			
			$bid = $alert = "";
			if(strpos($string,"(") !== false){
				$bid = substr($string, 0, strpos($string,"("));
				$alert = substr($string, strpos($string,"(")+1, strpos($string,")") - strpos($string,"(") - 1);
			} else {
				$bid = $string;
			}
			
			$error_msg = "";
			if(!Bidding::validBidString($bid, $error_msg)){
				throw new BiddingFormatException($error_msg);
			}
			
			$retval = array(
				'bid' => $bid
			);
			
			if($alert != ''){
				$retval['alert'] = $alert;
			}
			
			return $retval;
		}
		
		/**
		 * Returns a value indicating whether bidding sequence has header 
		 */
		public function hasHeader(){
			return count($this->header) > 0;
		}
		
		/**
		 * Returns a value indiciting whether bidding sequence has a content
		 */
		public function hasContent(){
			return count($this->content) > 0;
		}
		
		public function getHeader(){
			return $this->header;
		}
		
		public function getContent(){
			return $this->content;
		}
}
