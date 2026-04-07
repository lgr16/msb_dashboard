<?php

require_once( 'analytics_config.php');

function connect_analytics($host, $db, $user, $password)
{
	$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

	try {
		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
			// PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_520_ci"
			];

		return new PDO($dsn, $user, $password, $options);
	} catch (PDOException $e) {
		die($e->getMessage());
	}
}

return connect_analytics($hosta, $dba, $usera, $passworda);