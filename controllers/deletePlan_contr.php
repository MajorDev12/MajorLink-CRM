<?php

if (isset($_POST["planId"])) {
    // sleep(2);
    require_once  '../database/pdo.php';
    require_once  '../modals/deletePlan_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);


    $planId = $_POST['planId'];

    $deletedplan = deletePlan($planId, $connect);

    $output = array(
        'success' => $deletedplan ? true : false
    );

    echo json_encode($output);
}
