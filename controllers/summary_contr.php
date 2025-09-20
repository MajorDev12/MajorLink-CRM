<?php

require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include necessary files
    require_once '../database/pdo.php';
    require_once '../modals/reports_mod.php';
    $connect = connectToDatabase($host, $dbname, $username, $password);


    if (isset($_POST["dateSelectedNetProfit"])) {
        $dateSelected = $_POST["dateSelectedNetProfit"];

        $totalExpense = getTotalExpenseByDate($connect, $dateSelected);
        $totalIncome = getTotalIncomeByDate($connect, $dateSelected);

        $Income = $totalIncome['total_income'];
        $Expense = $totalExpense['TotalExpense'];
        $netProfit = 0;
        if ($Income == null) {
            $Income = "0.00";
            $netProfit = $totalExpense;
        }
        $netProfit = floatval($Income) - floatval($Expense);

        if ($totalIncome && $totalExpense) {
            $output = array(
                'success' => true,
                'message' => '',
                'income' => number_format($Income, 2),
                'expense' => number_format($Expense, 2),
                'profit' => number_format($netProfit, 2)
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
    if (isset($_POST["monthInput"]) || isset($_POST["yearInput"])) {
        $month = $_POST["monthInput"];
        $year = $_POST["yearInput"];

        $totalExpense = getTotalExpenseByMonthYear($connect, $month, $year);
        $totalIncome = getTotalIncomeByYearMonth($connect, $year, $month);

        $Income = $totalIncome['total_income'];
        $Expense = $totalExpense['TotalExpense'];
        $netProfit = 0;
        if ($Income == null) {
            $Income = "0.00";
            $netProfit = $totalExpense;
        }
        $netProfit = floatval($Income) - floatval($Expense);



        if ($Income && $Expense) {
            $output = array(
                'success' => true,
                'message' => '',
                'income' => number_format($Income, 2),
                'expense' => number_format($Expense, 2),
                'profit' => number_format($netProfit, 2)
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
    if (isset($_POST["yearNetSelected"])) {
        $yearSelected = $_POST["yearNetSelected"];

        $totalExpense = getTotalExpenseByYear($connect, $yearSelected);
        $totalIncome =  getTotalIncomeByYear($connect, $yearSelected);

        $Income = $totalIncome['total_income'];
        $Expense = $totalExpense['TotalExpense'];
        $netProfit = 0;
        if ($Income == null) {
            $Income = "0.00";
            $netProfit = $totalExpense;
        }
        $netProfit = floatval($Income) - floatval($Expense);

        if ($totalIncome && $totalExpense) {
            $output = array(
                'success' => true,
                'message' => '',
                'income' => number_format($Income, 2),
                'expense' => number_format($Expense, 2),
                'profit' => number_format($netProfit, 2)
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
