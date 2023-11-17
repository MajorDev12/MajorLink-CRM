<?php

// db info
$host = "localhost";
$dbname = "majorlink";
$username = "root";
$password = "123456";

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
