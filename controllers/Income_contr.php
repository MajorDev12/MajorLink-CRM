<?php

require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include necessary files
    require_once '../database/pdo.php';
    require_once '../modals/reports_mod.php';
    $connect = connectToDatabase($host, $dbname, $username, $password);


    if (isset($_POST["yearSelected"]) && isset($_POST["monthSelected"])) {
        $month = $_POST["monthSelected"];
        $year = $_POST["yearSelected"];

        $totalIncome = getTotalIncomeByYearMonth($connect, $year, $month);
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
    if (isset($_POST["yearSelected2"])) {
        $year = $_POST["yearSelected2"];

        $totalIncome = getTotalIncomeByYear($connect, $year);
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
    if (isset($_POST["areaSelected"])) {
        $areaID = $_POST["areaSelected"];

        $totalIncome = getTotalIncomeByArea($connect, $areaID);
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
    if (isset($_POST["subareaSelect"])) {
        $subareaID = $_POST["subareaSelect"];

        $totalIncome = getTotalIncomeBySubArea($connect, $subareaID);
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
    if (isset($_POST["ProductSelect"])) {
        $ProductSelect = $_POST["ProductSelect"];

        $totalIncome =  getTotalIncomeByProduct($connect, $ProductSelect);
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
    if (isset($_POST["planSelected"])) {
        $planSelected = $_POST["planSelected"];

        $totalIncome =  getTotalIncomeByPlan($connect, $planSelected);
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
    if (isset($_POST["clientSelected"])) {
        $clientSelected = $_POST["clientSelected"];

        $totalIncome =  getClientTotalAmount($connect, $clientSelected);
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
            'message' => 'Year or Month is missing'

        );
        echo json_encode($output);
        exit();
    }
}
