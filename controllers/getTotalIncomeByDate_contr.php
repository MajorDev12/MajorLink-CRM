<?php
if (!isset($_GET["d"])) {
    echo json_encode(array("error" => "Date is missing"));
    exit();
}

require_once '../database/pdo.php';
require_once '../modals/reports_mod.php';

// Connect to the database
$connect = connectToDatabase($host, $dbname, $username, $password);

$date = $_GET["d"];
$totalIncome = getTotalIncomeByDate($connect, $date);

// var_dump($totalIncome);
// exit();

// var_dump($totalIncome);
// exit();

if ($totalIncome) {
    $output = array(
        'success' => true,
        'message' => '',
        'results' => $totalIncome

    );
    echo json_encode($output);
    exit();
} else {
    // Return an error message if the template does not exist
    $output = array(
        'success' => false,
        'message' => 'No Data Found'

    );
    echo json_encode($output);
    exit();
}
