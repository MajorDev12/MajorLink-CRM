<?php
require_once "../controllers/session_Config.php";
?>
<?php
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Username']) && isset($_POST['email'])) {
        require_once  '../database/pdo.php';
        require_once  '../modals/validate_mod.php';
        require_once  '../modals/addAdmin_mod.php';
        sleep(1);

        $connect  = connectToDatabase($host, $dbname, $username, $password);
        $adminID = $_SESSION["adminID"];
        $errors = array();
        // Retrieve the values from the form
        $firstnameInput = inputValidation($_POST['Username']);
        $lastnameInput = inputValidation($_POST['fullName']);
        $PrimaryEmailInput = inputValidation($_POST['email']);
        $primaryNumberInput = inputValidation($_POST['phone']);



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



        if (trim($primaryNumberInput) !== "") {
            if (!ctype_digit($primaryNumberInput)) {
                $errors[] = "Invalid phone number (only numbers allowed)";
            }
        }




        // If there are no errors, insert data
        if (empty($errors)) {
            // Insert into Clients table
            $updatedSuccess = updateAdminDetails($adminID, $firstnameInput, $lastnameInput, $PrimaryEmailInput, $primaryNumberInput, $connect);
            $success = 'Client Added Successfuly';
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
