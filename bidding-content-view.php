<?php
/**
 * HTML view for a Bidding object's content table;
 * 
 * @file Bidding.class.php
 * @author Ther <bszirmay@gmail.com>
 * @date 2014-01-30
 * @license GPL
 */

function renderContent($content){
	$html = '<table class="situations_table" cellspacing="0" rowpadding="0">';		
	
	$max_length = 0;
	foreach($content as $key => $convention) {
		$max_length = max(count($convention['bidding']), $max_length);
	}
	
	$extensions = array();
	for($k = 0; $k < count($content); $k++){
		$convention = $content[$k];
		
		$class = $convention['class'];
		$html .= "<tr class='$class'>";
		$bids = $convention['bidding'];
		
		if(isset($bids[0]) && $bids[0] == 'EXT') {
			$extensions[] = array('content' => $convention['content'], 'category' => $convention['class']);
			continue;
		}
		
		$i = 0; $skip = 0;
		for($i = 0; $i < count($bids); $i++) {
			$all = true;
			for($j = 0; $j <= $i; $j++){
				$all = $all && ($k > 0 && isset($content[$k-1]['bidding'][$j]) && $content[$k-1]['bidding'][$j] == $bids[$j]) ;
			}
			if($all){
				++$skip;
				continue;
			}
			$rs = 1;
			for($j = $k+1; $j < count($content); $j++){
				$ok = true ;
				for($l = 0; $l <= $i; $l++) $ok = $ok && (isset($content[$j]['bidding'][$l]) && $content[$j]['bidding'][$l] == $bids[$l]);
				if($ok) ++$rs ;
				else break ;
			}
		
			$last = ($i == (count($bids)-1)) ? 'last' : 'not_last';
			$html .= "<td class='bid_cell cell_$i $last' rowspan='$rs' valign='center'>".replace_suits_with_spans2($bids[$i], true).'</td>';
		}
		
		$colspan = (1 + $max_length - $i + $skip);
		$convention['content'] = replace_suits_with_spans2($convention['content']);
		$html .= "<td class='convention_explain' colspan='$colspan'>$convention[content]</td>";
		$html .= "</tr>\n";
	}
	
	$html .= '</table>';
	
	foreach($extensions as $item){
		$html .= '<div class="extension_'.$item['category'].'">'.$item['content'].'</div>';
	}
	
	return $html;
}

function replace_suits_with_spans2( $content, $all = false ){
	$replacements = array(
		'!S' => '&spades;',
		'!H' => '<span style="color:red;">&hearts;</span>',
		'!D' => '<span style="color:red;">&diams;</span>',
		'!C' => '&clubs;'
	);
	
	if($all){
		$replacements = array(
			'S' => '&spades;',
			'H' => '<span style="color:red;">&hearts;</span>',
			'D' => '<span style="color:red;">&diams;</span>',
			'C' => '&clubs;'
		);
	}
	
	foreach($replacements as $key => $to){
		$content = str_replace($key, $to, $content);
	}
	
	return $content;
	
}
