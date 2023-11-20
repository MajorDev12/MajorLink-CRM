<?php
$errors = array();

if (isset($_POST["productName"]) && isset($_POST["ProductPrice"])) {
    sleep(1);
    //db info
    require_once  '../database/pdo.php';
    require_once  '../modals/addProduct_mod.php';
    require_once  '../modals/validate_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);



    $name = inputValidation($_POST["productName"]);
    $price = inputValidation($_POST["ProductPrice"]);
    $notes = inputValidation($_POST["notes"]);
    // $image = inputValidation($_POST["ProductImage"]);


    // Call the sanitizeImage function
    // $errorMsg = sanitizeImage($errorMsg);

    // Check if there's an error from the sanitizeImage function
    // if (!empty($errorMsg)) {
    //     // Display the error message
    //     $errors[] = $errorMsg;
    // }

    if (empty($name)) {
        $errors[] = 'Name is Required';
        // header("location: ../views/addProduct.php?error=");
    } else {
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
            $errors[] = 'Only Letters and White Space Allowed in First name';
            // header("location: ../views/addProduct.php?error=");
        }
    }

    if (empty($price)) {
        $errors[] = 'Volume Name is Required';
        // header("location: ../views/addProduct.php?error=");
    } else {
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $price)) {
            $errors[] = 'Only Letters and White Space Allowed';
            // header("location: ../views/addProduct.php?error=");
        }
    }

    if (empty($notes)) {
        $errors[] = 'price Cannot be empty';
        // header("location: ../views/addProduct.php?error=");
    } else {
        if (!is_numeric($notes)) {
            $errors[] = 'Price Can Only be a Number';
            // header("location: ../views/addProduct.php?error=");
        }
    }


    // sanitizeImage($errorMsg);

    // If there are no errors, insert data
    if (empty($errors)) {
        insertProductData($name, $price, $notes, $connect);
        $success = '<div class="alert alert-success">Data Saved Successfuly</div>';
        // header("location: ../views/addProduct.php?error=success");
    } else {
        // header("location: ../views/addProduct.php?error=error");
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }

    $successinsert = insertProductData($name, $price, $notes, $connect);

    $output = array(
        "success" => $successinsert ? true : false
    );

    echo json_encode($output);
}
