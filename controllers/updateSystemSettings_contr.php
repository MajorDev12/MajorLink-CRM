<?php
require_once "../controllers/session_Config.php";
?>
<?php
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['SettingID']) && isset($_POST['countrySelect'])) {
        require_once  '../database/pdo.php';
        require_once  '../modals/validate_mod.php';
        require_once  '../modals/setup_mod.php';
        sleep(1);

        $connect  = connectToDatabase($host, $dbname, $username, $password);
        $errors = array();
        // Retrieve the values from the form
        $settingID = inputValidation($_POST['SettingID']);
        $countrySelect = inputValidation($_POST['countrySelect']);
        $timezoneSelect = inputValidation($_POST['timezoneSelect']);
        $phoneCodeSelect = inputValidation($_POST['phoneCodeSelect']);
        $currencySelect = inputValidation($_POST['currencySelect']);
        $currencyCode = inputValidation($_POST['currencyCode']);
        $currencySymbol = inputValidation($_POST['currencySymbol']);



        // If there are no errors, insert data
        if (empty($errors)) {
            // Insert into Clients table
            $updatedSuccess = updateSystemSettings($settingID, $countrySelect, $timezoneSelect, $phoneCodeSelect, $currencySelect, $currencyCode, $currencySymbol, $connect);
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
