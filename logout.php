<?php
	require 'config.php';
	unset($_SESSION['user']);
	header('Location: ' . HOST);
?>