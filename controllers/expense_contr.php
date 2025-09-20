<?php

require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include necessary files
    require_once '../database/pdo.php';
    require_once '../modals/reports_mod.php';
    $connect = connectToDatabase($host, $dbname, $username, $password);


    if (isset($_POST["ProductSelected"])) {
        $ProductSelected = $_POST["ProductSelected"];

        $totalIncome = getTotalIncomeByProduct($connect, $ProductSelected);
        // var_dump($totalIncome);
        // return;

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
    }
    if (isset($_POST["expenseDateInput"])) {
        $expenseDateInput = $_POST["expenseDateInput"];

        $totalIncome = getTotalExpenseByDate($connect, $expenseDateInput);
        // var_dump($totalIncome);
        // return;

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
    }
    if (isset($_POST["month"]) || isset($_POST["year"])) {
        $month = $_POST["month"];
        $year = $_POST["year"];

        $totalIncome = getTotalExpenseByMonthYear($connect, $month, $year);
        // var_dump($totalIncome);
        // return;

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
    }
    if (isset($_POST["expenseyearSelected"])) {
        $yearSelected = $_POST["expenseyearSelected"];

        $totalIncome =  getTotalExpenseByYear($connect, $yearSelected);
        // var_dump($totalIncome);
        // return;

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
    } else {
        $output = array(
            'success' => false,
            'message' => 'Something is missing'

        );
        echo json_encode($output);
        exit();
    }
}
