<?php
// Database credentials
$host = "localhost";
$database = "majorlink";
$username = "root";
$password = "123456";
$tables = ['clients', 'admins', 'advancepayments', 'areas', 'companysettings', 'emails', 'emailtemplate', 'expenses', '	expensetypes', 'invoiceproducts', 'invoices', 'messages', 'paymentoptions', 'payments', 'plans', 'plan_change_schedule', 'products', 'sales', 'sent_email_reminders', 'stripepayments', 'subareas', 'smstemplate', 'systemlogs'];

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="database_backup.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Fetch data from each table and write to CSV
    foreach ($tables as $table) {
        // Fetch column names for the current table
        $stmt = $pdo->prepare("SHOW COLUMNS FROM $table");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Write table name as a header for the section
        fputcsv($output, [$table]);

        // Write column names to CSV
        fputcsv($output, $columns);

        // Fetch data from the current table
        $stmt = $pdo->prepare("SELECT * FROM $table");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Write data rows to CSV
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        // Add an empty line between tables
        fputcsv($output, []);
    }

    // Close output stream
    fclose($output);

    // Exit script
    exit();
} catch (PDOException $e) {
    // Handle database connection error
    echo "Error: " . $e->getMessage();
    exit();
}
