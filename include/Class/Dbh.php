<?php

/**
 *
 */
class Dbh
{
	private static $host = "localhost";
	private static $username = "root";
	private static $password = "";
	private static $database = "gallery";

	protected function connect()
	{
		$data_source_name = "mysql:host=".self::$host.";dbname=".self::$database;
		$conn = new PDO($data_source_name, self::$username, self::$password);
		$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		return $conn;
	}
}
