<?php
require_once "../controllers/session_Config.php";
?>
<?php
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['nameInput'])) {
        require_once  '../database/pdo.php';
        require_once  '../modals/validate_mod.php';
        require_once  '../modals/setup_mod.php';
        sleep(1);

        $connect  = connectToDatabase($host, $dbname, $username, $password);
        $errors = array();
        // Retrieve the values from the form
        $settingID = inputValidation($_POST['id']);
        $nameInput = inputValidation($_POST['nameInput']);
        $emailInput = inputValidation($_POST['emailInput']);
        $websiteInput = inputValidation($_POST['websiteInput']);
        $addressInput = inputValidation($_POST['addressInput']);
        $cityInput = inputValidation($_POST['cityInput']);
        $zipcodeInput = inputValidation($_POST['zipcodeInput']);
        $numberInput = inputValidation($_POST['numberInput']);



        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $nameInput)) {
            $errors[] = "Only letters and numbers allowed";
        }


        if (trim($emailInput) !== "") {
            // Perform email validation only if the secondary email is not empty
            if (!filter_var($emailInput, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid company email address";
            }
        }



        if (trim($numberInput) !== "") {
            // Check if the number input has less than 10 digits
            $digitsOnly = preg_replace('/[^\d]/', '', $numberInput);
            if (strlen($digitsOnly) < 10) {
                $errors[] = "Company Number must contain at least 10 digits";
            }

            // Check if the number input contains any characters other than digits, brackets, or dashes
            if (!preg_match('/^[\d() -]+$/', $numberInput)) {
                $errors[] = "Company Number must contain only digits, brackets, or dashes";
            }
        }



        // If there are no errors, insert data
        if (empty($errors)) {
            // Insert into Clients table
            $updatedSuccess = updateCompanyDetails($settingID, $nameInput, $emailInput, $websiteInput, $addressInput, $cityInput, $zipcodeInput, $numberInput, $connect);
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
