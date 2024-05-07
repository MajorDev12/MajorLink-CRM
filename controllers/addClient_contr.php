
<?php

//process_data.php
$errors = array();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["Fname"]) && isset($_POST["Lname"]) && isset($_POST["primaryEmail"]) && isset($_POST["primaryNumber"])) {
        require_once  '../database/pdo.php';
        require_once  '../modals/addClient_mod.php';
        require_once  '../modals/setup_mod.php';
        require_once  '../modals/getTime_mod.php';
        require_once  '../modals/addPlan_mod.php';
        require_once  '../modals/addInvoice_mod.php';
        require_once  '../modals/viewSingleUser_mod.php';
        require_once  '../modals/addPayment_mod.php';
        require_once  '../modals/sendSms_mod.php';
        require_once  '../modals/notification_mod.php';

        $connect  = connectToDatabase($host, $dbname, $username, $password);

        $settings = get_Settings($connect);
        $timezone = $settings[0]["TimeZone"];
        $symbol = $settings[0]["CurrencySymbol"];
        $time  = getTime($timezone);
        $code = $settings[0]["PhoneCode"];

        $success = '';
        $Fname = $_POST["Fname"];
        $Lname = $_POST["Lname"];
        $primaryEmail = $_POST["primaryEmail"];
        $primaryNumber = $_POST["primaryNumber"];
        $secondaryEmail = isset($_POST["secondaryEmail"]) ? $_POST["secondaryEmail"] : null;
        $secondaryNumber = isset($_POST["secondaryNumber"]) ? $_POST["secondaryNumber"] : null;
        // Location
        $Address = isset($_POST["Address"]) ? $_POST["Address"] : null;
        $City = isset($_POST["City"]) ? $_POST["City"] : null;
        $Country = isset($_POST["Country"]) ? $_POST["Country"] : null;
        $zipCode = isset($_POST["zipCode"]) ? $_POST["zipCode"] : null;
        $area = isset($_POST["area"]) ? $_POST["area"] : null;
        $subArea = isset($_POST["subArea"]) ? $_POST["subArea"] : null;
        $longitude = isset($_POST["longitude"]) ? $_POST["longitude"] : null;
        $latitude = isset($_POST["latitude"]) ? $_POST["latitude"] : null;
        // Payment
        $Plan = isset($_POST["Plan"]) ? $_POST["Plan"] : null;
        $PlanAmount = isset($_POST["PlanAmount"]) ? $_POST["PlanAmount"] : null;
        $InstallationFees = isset($_POST["InstallationFees"]) ? $_POST["InstallationFees"] : null;
        $Paymentdate = isset($_POST["Paymentdate"]) ? $_POST["Paymentdate"] : null;
        $PaymentStatus = isset($_POST["PaymentStatus"]) ? $_POST["PaymentStatus"] : null;
        $paymentMethodID = isset($_POST["PaymentOptionID"]) ? $_POST["PaymentOptionID"] : null;
        $CreatedDate = $time;
        $invoiceNumber = generateInvoiceNumber();
        $to = $code . $primaryNumber;










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


        $last_paymentDate = $Paymentdate;

        $PreferedPaymentMethod = 3;

        $defaultProfileImageURL = 'default-profile-image.png';

        $ProfilePictureURL = (!empty($ProfilePictureURL)) ? $ProfilePictureURL : $defaultProfileImageURL;


        // If there are no errors, insert data
        if (empty($errors)) {
            // Insert into Clients table
            $clientId =  insertClientData($Fname, $Lname, $primaryEmail, $secondaryEmail, $primaryNumber, $secondaryNumber, $Address, $City, $Country, $zipCode, $PasswordHash, $area, $subArea, $Plan, $latitude, $longitude, $CreatedDate, $PreferedPaymentMethod, $ProfilePictureURL, $activeStatus, $last_paymentDate, $expireDate, $connect);
            // Insert into Payments table
            if ($Plan !== null) {
                $planData = getPlanDataByID($connect, $Plan);
                $PlanVolume = $planData['Volume'];
                $paidMonths = 1;
                $invoiceProducts[] = [
                    'product' => $planData['Name'],
                    'volume' => $planData['Volume'],
                    'qty' => $paidMonths,
                    'price' => $planData['Price']
                ];

                $paymentSuccess = insertPaymentData($clientId, $Plan, $invoiceNumber, $PlanAmount, $PaymentStatus, $Paymentdate, $paymentMethodID, $InstallationFees, $connect);
                createSaveInvoice($clientId, $invoiceNumber, $PlanAmount, $expireDate, $CreatedDate, $Paymentdate, $symbol, $connect);
                saveInvoiceProducts($connect, $invoiceNumber, $PlanAmount, $invoiceProducts);
                sendTextMessage($to, $PlanVolume, $expireDate);
            }
            sendWelcomeMessage($clientId, $CreatedDate, $connect);
            sendPaymentMessage($to);


            sendEmail($to, $name, $subject, $message);
            $success = true;
            $message = 'Client Added Successfuly';
        } else {
            // If there are errors, construct an error message
            $success = false;
            $message = implode('<br>', $errors);
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        echo json_encode($response);
        exit();
    } else {
        $success = false;
        $message = 'Fill in all required fields';

        $response = [
            'success' => $success,
            'message' => $message
        ];

        echo json_encode($response);
        exit();
    }
}



function createSaveInvoice($ClientID, $invoiceNumber, $paidAmount, $expireDate, $time, $paymentDate, $taxSymbol, $connect)
{
    $totalAmount = $paidAmount;
    $startDate = $time;
    $paymentDate = $paymentDate;
    $taxSymbol = $taxSymbol;
    $taxAmount = 0;
    $dueDate = $expireDate;
    $status = "Paid";
    addInvoice($connect, $ClientID, $invoiceNumber, $totalAmount, $paymentDate, $startDate, $dueDate, $status, $taxSymbol, $taxAmount);
}


function sendWelcomeMessage($ClientID, $createdDate, $connect)
{
    $SenderName = 'system';
    $MessageType = 'Welcome';
    $MessageContent = 'Thank you for choosing MajorLink as your ISP. Enjoy our services';
    $Status = 0;
    insertMessage($connect, $SenderName, $ClientID, $MessageType, $MessageContent, $createdDate, $Status);
}

function sendPaymentMessage($Clientnumber)
{

    $provider = 'Infobip';
    $message = 'Thank you for choosing MajorLink as your ISP. Enjoy our services';
    sendSMS($provider, $Clientnumber, $message);
}

function sendTextMessage($Clientnumber, $planVolume, $expireDate)
{
    $expireDate = new DateTime($expireDate);
    $expireDate = $expireDate->format('j F Y');

    $provider = 'Infobip';
    $message = 'You have successfully subscribed to ' . $planVolume . '. Your subscription will be renewed on ' . $expireDate . ' Thank you for choosing MajorLink';
    sendSMS($provider, $Clientnumber, $message);
}


?>