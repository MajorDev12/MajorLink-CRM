<?php

if (isset($_POST["PaymentMethodID"])) {
    sleep(2);
    require_once  '../database/pdo.php';
    require_once  '../modals/deletePaymentMethod_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);


    $PaymentMethodID = $_POST['PaymentMethodID'];

    $deletePaymentMethod = deletePaymentMethodId($PaymentMethodID, $connect);

    $output = array(
        'success' => $deletePaymentMethod ? true : false
    );

    echo json_encode($output);
}
