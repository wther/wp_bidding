<!DOCTYPE html>
<html>
	<head>
	      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	      <link href="bootstrap.min.css" rel="stylesheet">
		  <link href="bridge-bidding.css" rel="stylesheet">
		  <title>Sandbox | Bridge Bidding Plugin</title>
		  <style type="text/css">
				div.main {
					width: 600px;
					margin: 40px auto;
					
				}
		  </style>
	</head>
<body>
<div class="main">
	<?php
$default = "
1NT(Strong)-?

2C: Stayman
2D/2H: Transfer
2S: NAT BAL INV";
	
	?>
	<h1>Sandbox for Bridge Bidding Plugin</h1>
	<form role="form" method="post">
	  <div class="form-group">
		<label for="exampleInputEmail1">Bidding:</label>
		<textarea type="email" class="form-control" id="exampleInputEmail1" name="bidding" rows='20'><?php print isset($_POST['bidding']) ? $_POST['bidding'] : $default; ?></textarea>
	  </div>
	  <button type="submit" class="btn btn-default">Try!</button>
	</form>
	
	
	<?php

	require_once('Bidding.class.php');
	require_once('bidding-view.php');


	if(isset($_POST['bidding'])){
		$error = "";
		try {
			$bidding = Bidding::fromCode($_POST['bidding']);
		} catch(BiddingFormatException $e){
			$error = $e->getMessage();
		}
	}

	?>
	<p class="text-danger"><?php print $error; ?></p>
	
	<div class="result">
	<?php if(isset($bidding)){renderView($bidding);}?>
	</div>
</div>
</body>
</html>
