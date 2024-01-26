
<?php

//process_data.php
$errors = array();

if (isset($_POST["Fname"])) {
    sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/addClient_mod.php';
    require_once  '../modals/updateBalance_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);

    $success = '';



    $Fname = $_POST["Fname"];
    $Lname = $_POST["Lname"];
    $primaryEmail = $_POST["primaryEmail"];
    $secondaryEmail = $_POST["secondaryEmail"];
    $primaryNumber = $_POST["primaryNumber"];
    $secondaryNumber = $_POST["secondaryNumber"];
    $longitude = $_POST["longitude"];
    $latitude = $_POST["latitude"];
    $area = $_POST["area"];
    $subArea = $_POST["subArea"];
    $Plan = $_POST["Plan"];
    $PlanAmount = $_POST["PlanAmount"];
    $CreatedDate = $_POST["JoinedDate"];
    $InstallationFees = $_POST["InstallationFees"];
    $Paymentdate = $_POST["Paymentdate"];
    $PaymentStatus = $_POST["PaymentStatus"];

    $balance = 0;




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



    if (empty($primaryEmail)) {
        $errors[] = 'Email Cannot be empty';
    } else {
        if (!filter_var($primaryEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'primaryeMail is invalid';
        }
    }


    if ($secondaryEmail) {
        if (!filter_var($secondaryEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'secondaryEmail is invalid';
        }
    }


    if (empty($primaryNumber)) {
        $errors[] = 'primaryNumber cannot be empty';
    } else {
        if (!is_numeric($primaryNumber)) {
            $errors[] = 'Only Number expected in primaryNumber';
        }
    }



    if ($secondaryNumber) {
        // Check if it's not a number
        if (!is_numeric($secondaryNumber)) {
            $errors[] = 'Only Number expected in secondaryNumber';
        }
    }

    if (!$CreatedDate) {
        $errors[] = 'Please Choose the joined Date';
    }

    // Set subArea to null if it's not a number
    $subArea = is_numeric($subArea) ? $subArea : null;

    // Set area to null if it's not a number
    $area = is_numeric($area) ? $area : null;

    // Set Plan to null if it's not a number
    $Plan = is_numeric($Plan) ? $Plan : null;





    // Check if Plan is set
    if ($Plan && $PlanAmount !== null) {
        // Check if other fields are empty when Plan is set
        if (empty($InstallationFees) || empty($Paymentdate) || empty($PaymentStatus)) {
            $errors[] = 'Related Fields are required when Plan is set.';
        }
    }


    // generate a random 6-digit password
    $randomPassword = '123456';

    $options = [
        'cost' => 12
    ];
    // hash the password
    $PasswordHash = password_hash($randomPassword, PASSWORD_BCRYPT, $options);


    $paymentDate = new DateTime($Paymentdate);
    $expireDate = clone $paymentDate;
    $expireDate->add(new DateInterval('P1M')); // Adding 1 month to the payment date
    $expireDate = $expireDate->format('Y-m-d');


    if (!$Paymentdate) {
        $expireDate = null;
    }


    if ($PaymentStatus === "Paid") {
        $activeStatus = true;
    }
    if ($PaymentStatus === "Pending") {
        $activeStatus = false;
    }

    if ($activeStatus) {
        $last_paymentDate = $Paymentdate;
    } else {
        $last_paymentDate = null;
    }


    $defaultProfileImageURL = 'default-profile-image.png';

    $ProfilePictureURL = (!empty($ProfilePictureURL)) ? $ProfilePictureURL : $defaultProfileImageURL;


    // If there are no errors, insert data
    if (empty($errors)) {
        // Insert into Clients table
        $clientId =  insertClientData($Fname, $Lname, $primaryEmail, $secondaryEmail, $primaryNumber, $secondaryNumber, $PasswordHash, $area, $subArea, $Plan, $latitude, $longitude, $CreatedDate, $ProfilePictureURL, $activeStatus, $last_paymentDate, $expireDate, $connect);
        $balanceSet = setAccount($clientId, $balance, $connect);
        // Insert into Payments table
        if ($Plan !== null) {
            insertPaymentData($clientId, $Plan, $PlanAmount, $PaymentStatus,  $Paymentdate, $InstallationFees, $connect);
        }
        $success = '<div class="alert alert-success">Client Added Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }


    $output = array('success'  =>   $success);
    echo json_encode($output);
}
?>