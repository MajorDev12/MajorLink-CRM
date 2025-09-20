<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['clientID']) || !isset($_SESSION['FirstName'])) {
    // Redirect to the login page
    header("location: ../views/login.php");
    exit();
}
?>
<?php
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['firstnameInput']) && isset($_POST['PrimaryEmailInput'])) {
        require_once  '../database/pdo.php';
        require_once  '../modals/validate_mod.php';
        require_once  '../modals/updateSingleUser_mod.php';
        sleep(1);

        $connect  = connectToDatabase($host, $dbname, $username, $password);
        $clientId = $_SESSION["clientID"];
        $errors = array();
        // Retrieve the values from the form
        $firstnameInput = inputValidation($_POST['firstnameInput']);
        $lastnameInput = inputValidation($_POST['lastnameInput']);
        $PrimaryEmailInput = inputValidation($_POST['PrimaryEmailInput']);
        $SecondaryEmailInput = inputValidation($_POST['SecondaryEmailInput']);
        $primaryNumberInput = inputValidation($_POST['primaryNumberInput']);
        $secondaryNumberInput = inputValidation($_POST['secondaryNumberInput']);
        $AddressInput = inputValidation($_POST['AddressInput']);
        $CityInput = inputValidation($_POST['CityInput']);
        $CountryInput = inputValidation($_POST['CountryInput']);
        $zipcodeInput = inputValidation($_POST['zipcodeInput']);



        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $firstnameInput)) {
            $errors[] = "Only letters and numbers allowed";
        }




        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $lastnameInput)) {
            $errors[] = "Only letters and numbers allowed";
        }


        if (trim($PrimaryEmailInput) !== "") {
            // Perform email validation only if the secondary email is not empty
            if (!filter_var($PrimaryEmailInput, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid secondary email address";
            }
        }


        if (trim($SecondaryEmailInput) !== "") {
            // Perform email validation only if the secondary email is not empty
            if (!filter_var($SecondaryEmailInput, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid secondary email address";
            }
        }


        if (trim($primaryNumberInput) !== "") {
            if (!ctype_digit($primaryNumberInput)) {
                $errors[] = "Invalid phone number (only numbers allowed)";
            }
        }


        if (trim($secondaryNumberInput) !== "") {
            if (!ctype_digit($secondaryNumberInput)) {
                $errors[] = "Invalid phone number (only numbers allowed)";
            }
        }







        // If there are no errors, insert data
        if (empty($errors)) {
            // Insert into Clients table
            $updatedSuccess = updateUserDetails($clientId, $firstnameInput, $lastnameInput, $PrimaryEmailInput, $SecondaryEmailInput, $primaryNumberInput, $secondaryNumberInput, $AddressInput, $CityInput, $CountryInput, $zipcodeInput, $connect);
            $success = '<div class="alert alert-success">Client Added Successfuly</div>';
        } else {
            // If there are errors, construct an error message
            $success = implode('<br>', $errors);
        }

        $output = array(
            'success'  =>   $updatedSuccess ? true : false,
            'message' => $success
        );
        echo json_encode($output);
    } else {
        // If the form wasn't submitted via POST, handle accordingly
        echo 'Invalid request method';
    }
}
