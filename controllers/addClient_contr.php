
<?php

//process_data.php
$errors = array();

if (isset($_POST["Fname"])) {
    sleep(2);
    require_once  '../database/pdo.php';
    require_once  '../modals/addClient_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);

    $success = '';

    $Fname = $_POST["Fname"];
    $Lname = $_POST["Lname"];
    $email = $_POST["Email"];
    $phoneNumber = $_POST["phoneNumber"];


    if (empty($Fname)) {
        $errors[] = 'First Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $Fname)) {
            $errors[] = 'Only Letters and White Space Allowed in First name';
        }
    }

    if (empty($Lname)) {
        $errors[] = 'Last Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $Lname)) {
            $errors[] = 'Only Letters and White Space Allowed';
        }
    }

    if (empty($email)) {
        $errors[] = 'Email Cannot be empty';
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'eMail is invalid';
        }
    }


    if (empty($phoneNumber)) {
        $errors[] = 'phoneNumber cannot be empty';
    }

    // If there are no errors, insert data
    if (empty($errors)) {
        insertData($Fname, $Lname, $email, $phoneNumber, $connect);
        $success = '<div class="alert alert-success">Data Saved Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }






    $output = array('success'  =>   $success);
    echo json_encode($output);
}
?>