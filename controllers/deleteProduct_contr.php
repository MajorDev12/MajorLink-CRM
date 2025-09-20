<?php

if (isset($_POST["ProductId"])) {
    // sleep(2);
    require_once  '../database/pdo.php';
    require_once  '../modals/deleteProduct_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);


    $ProductId = $_POST['ProductId'];

    $deletedProduct = deleteProduct($ProductId, $connect);

    $output = array(
        'success' => $deletedProduct ? true : false
    );

    echo json_encode($output);
}
