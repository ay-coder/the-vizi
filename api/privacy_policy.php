<?php
	require '../config.php';
	global $db;

	$policy = $db->single('SELECT data_value FROM settings where data_key = "policy"');
?>

<html>
	<head>
		<title>Privacy Policy</title>
	</head>
	<body>
		<?php echo $policy;?>
	</body>	
</html>