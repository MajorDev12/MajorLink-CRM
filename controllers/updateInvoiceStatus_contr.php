<?php


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_once  '../database/pdo.php';
    require_once  '../modals/updateInvoiceStatus_mod.php';
    require_once  '../modals/validate_mod.php';
    $connect = connectToDatabase($host, $dbname, $username, $password);


    if (isset($_POST["invoiceID"])) {
        $status = inputValidation($_POST["status"]);
        $invoiceID = inputValidation($_POST["invoiceID"]);

        if (empty($status)) {
            $output = array(
                'success'  =>  false,
                'message' => "something went wrong"
            );
            echo json_encode($output);
            exit();
        }


        $success = updateInvoice($connect, $invoiceID, $status);

        $output = array(
            'success'  =>  $success ? true : false,
            'message' => $success ? 'Updated invoice successfully' : 'Something went wrong'
        );
        echo json_encode($output);
        exit();
    }




    if (isset($_POST["SaleID"])) {
        $status = inputValidation($_POST["status"]);
        $SaleID = inputValidation($_POST["SaleID"]);

        if (empty($status)) {
            $output = array(
                'success'  =>  false,
                'message' => "something went wrong"
            );
            echo json_encode($output);
            exit();
        }


        $success = updateSales($connect, $SaleID, $status);

        $output = array(
            'success'  =>  $success ? true : false,
            'message' => $success ? 'Updated successfully' : 'Something went wrong'
        );
        echo json_encode($output);
        exit();
    }



    $output = array(
        'success'  =>  false,
        'message' => "No Data Recieved"
    );
    echo json_encode($output);
    exit();
}
