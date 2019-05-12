<?php
	$dbName   = "library";
	$username = "library";
	$password = "diurnal1980";
	$host = "localhost";
	
	$dsn = "mysql:host=$host;dbname=$dbName";
	
	try {
		$db = new PDO($dsn, $username, $password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		error_log("Database connection error: Reason: " . $e->getMessage(), 0);
		include('database_error.html');
		exit();
	}
?>