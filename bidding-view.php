<?php
/**
 * HTML view for a Bidding object view.
 * 
 * @file Bidding.class.php
 * @author Ther <bszirmay@gmail.com>
 * @date 2014-01-30
 * @license GPL
 */

 
function renderView($bidding){
	$alerts = array();
?>

<div class="bidding">

<?php if($bidding->hasHeader()){ ?>
	<table class="bidding-header">
		<?php foreach($bidding->getHeader() as $line){ ?>
			<tr>
				<?php foreach($line as $item){ ?>
					<td><?php print renderHeaderItem($item, $alerts); ?></td>
				<?php } ?>
			</tr>
		<?php } ?>
	</table>
<?php } ?>


<?php if($bidding->hasContent()){ ?>
	<?php print renderContent($bidding->getContent()); ?>
<?php } ?>

</div>

<?php }
