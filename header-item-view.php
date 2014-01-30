<?php
/**
 * HTML view for a cell of the bidding header
 * 
 * @file Bidding.class.php
 * @author Ther <bszirmay@gmail.com>
 * @date 2014-01-30
 * @license GPL
 */

function renderHeaderItem($item, &$alerts){
	$i = null;
	if(isset($item['alert'])){
		$i = count($alerts) + 1;
		$alerts[$i] = $item['alert'];
	}
?>
<?php if(isset($item['alert'])){?>
	<span class="bidding-alert-header" title="<?php print $item['alert']; ?>"><?php print replace_suits_with_spans2($item['bid'],true); ?></span>
<?php } else { ?>
	<?php print replace_suits_with_spans2($item['bid'],true); ?><?php }?>
<?php 

}
