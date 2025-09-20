<?php
// Database credentials
$host = "localhost";
$database = "majorlink";
$username = "root";
$password = "123456";
$table = "clients"; // Specify the table name
$columnsToExclude = ['PasswordHash', 'ProfilePictureURL', 'LastPayment']; // Specify the columns to exclude

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

// Query to select all data from the specified table
$query = "SELECT * FROM $table";

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute();

// Fetch all rows as associative arrays
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Remove excluded columns from each row
foreach ($rows as &$row) {
    foreach ($columnsToExclude as $column) {
        unset($row[$column]);
    }
}

// Set CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $table . '_backup.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream
$output = fopen('php://output', 'w');

// Write headers
fputcsv($output, array_keys($rows[0]));

// Write data
foreach ($rows as $row) {
    fputcsv($output, $row);
}

// Close output stream
fclose($output);
