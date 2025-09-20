<?php

if (isset($_POST["ExpenseTypeId"])) {
    sleep(2);
    require_once  '../database/pdo.php';
    require_once  '../modals/deleteExpenseType_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);


    $ExpenseTypeId = $_POST['ExpenseTypeId'];

    $deletedExpenseType = deleteExpenseTypeId($ExpenseTypeId, $connect);

    $output = array(
        'success' => $deletedExpenseType ? true : false
    );

    echo json_encode($output);
}
