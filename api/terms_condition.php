<?php
	require '../config.php';
	global $db;
	
	$terms  = $db->single('SELECT data_value FROM settings where data_key = "terms"' );
?>

<html>
	<head>
		<title>Terms & Conditions</title>
	</head>
	<body>
		<?php echo $terms;?>
	</body>	
</html>