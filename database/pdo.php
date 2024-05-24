<?php
require_once "../modals/config.php";
// db info
$host = DB_HOST;
$dbname = DATABASE_NAME;
$username = DB_USERNAME;
$password = DB_PASSWORD;

function connectToDatabase($host, $dbname, $username, $password)
{

    try {
        // Establish a database connection
        $connect = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

        // Set PDO to throw exceptions on errors
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Set the default fetch mode to associative array
        $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connect;
    } catch (PDOException $e) {
        // Handle connection errors
        die("Connection failed: " . $e->getMessage());
    }
}
