<?php
// Assuming you have a database connection established
require_once "../controllers/session_Config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["clientId"])) {
    sleep(2);

    require_once  '../database/pdo.php';
    require_once  '../modals/updateSingleUser_mod.php';

    $connect = connectToDatabase($host, $dbname, $username, $password);

    // Access data from FormData
    $clientId = $_POST["clientId"];
    $firstName = $_POST["FirstName"];
    $lastName = $_POST["LastName"];
    $primaryEmail = $_POST["PrimaryEmail"];
    $secondaryEmail = $_POST["SecondaryEmail"];
    $primaryNumber = $_POST["PrimaryNumber"];
    $secondaryNumber = $_POST["SecondaryNumber"];
    $latitude = $_POST["Latitude"];
    $longitude = $_POST["Longitude"];
    $area = $_POST["Area"];
    $subArea = $_POST["SubArea"];
    $Address = $_POST["Address"];
    $City = $_POST["City"];
    $Country = $_POST["Country"];
    $Zipcode = $_POST["zipcode"];
    $errors = array();




    // Assuming the variables are already defined

    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $firstName)) {
        $errors[] = "Only letters and numbers allowed";
    }




    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $lastName)) {
        $errors[] = "Only letters and numbers allowed";
    }


    if (trim($primaryEmail) !== "") {
        // Perform email validation only if the secondary email is not empty
        if (!filter_var($primaryEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid secondary email address";
        }
    }


    if (trim($secondaryEmail) !== "") {
        // Perform email validation only if the secondary email is not empty
        if (!filter_var($secondaryEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid secondary email address";
        }
    }


    if (trim($primaryNumber) !== "") {
        if (!ctype_digit($primaryNumber)) {
            $errors[] = "Invalid phone number (only numbers allowed)";
        } elseif (strlen($primaryNumber) !== 10) {
            $errors[] = "Primary number must contain exactly 10 digits";
        }
    }

    if (trim($secondaryNumber) !== "") {
        if (!ctype_digit($secondaryNumber)) {
            $errors[] = "Invalid secondary phone number (only numbers allowed)";
        } elseif (strlen($secondaryNumber) !== 10) {
            $errors[] = "Secondary number must contain exactly 10 digits";
        }
    }



    if (trim($latitude) !== 'null') {
        // Regular expression for validating latitude
        $latitudeRegex = '/^-?\d+(\.\d+)?$/';
        if (!preg_match($latitudeRegex, $latitude)) {
            $errors[] = "Invalid latitude format";
        }
    }

    if (trim($longitude) !== 'null') {
        // Regular expression for validating latitude
        $longitudeRegex = '/^-?\d+(\.\d+)?$/';
        if (!preg_match($longitudeRegex, $longitude)) {
            $errors[] = "Invalid Longitude format";
        }
    }

    if (!is_numeric($area) || trim($area) === "") {
        $area = null;
    }

    if (!is_numeric($subArea) || trim($subArea) === "") {
        $subArea = null;
    }


    $updatedSuccess = false;


    // If there are no errors, insert data
    if (empty($errors)) {
        // update Clients table
        $updatedSuccess = updateSingleUser($clientId, $firstName, $lastName, $primaryEmail, $secondaryEmail, $primaryNumber, $secondaryNumber, $Address, $City, $Country, $Zipcode, $area, $subArea, $latitude, $longitude, $connect);
        $success = 'Client Updated Successfuly';
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
    $output = array(
        'message'  => 'invalidId'
    );
    echo json_encode($output);
}
