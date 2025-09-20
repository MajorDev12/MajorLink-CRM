<?php

require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST["selectedValue"])) {
        // Include necessary files
        require_once '../database/pdo.php';
        require_once '../modals/sendSms_mod.php';
        require "../includes/phpmailer/vendor/autoload.php";
        require_once  '../modals/setup_mod.php';

        //get phone code
        $connect = connectToDatabase($host, $dbname, $username, $password);
        $settings = get_Settings($connect);
        $code = $settings[0]["PhoneCode"];

        // Retrieve data 
        $selectedValue = $_POST["selectedValue"];
        $checkedValues[] = $_POST["checkedValues"];
        $body = $_POST["massmessage"];
        $provider = $_POST["smsMode"];
        $connect = connectToDatabase($host, $dbname, $username, $password);


        // Check if the selected value is "Area"
        if ($selectedValue === "Area") {
            try {
                // Convert the array to a comma-separated string for the SQL query
                $areaIDs = implode(',', $checkedValues);

                // Query to retrieve clients with area IDs in the checkedValues array
                $query = "SELECT * FROM clients WHERE AreaID IN ($areaIDs)";
                $statement = $connect->prepare($query);
                $statement->execute();

                // Fetch the clients data
                $clients = $statement->fetchAll(PDO::FETCH_ASSOC);

                if ($clients) {
                    // Loop through each client and send the message
                    foreach ($clients as $client) {
                        // Get the client's email and other necessary details
                        $number = $client['PrimaryNumber'];
                        $to = $code . $number;
                        $from = "MajorLink"; // Assuming you have a FirstName field in your clients table
                        $name = $client['FirstName'] . ' ' . $client['LastName']; // Assuming you have a FirstName field in your clients table

                        // Replacements for template words
                        $replacements = array(
                            'client_name' => $name,
                            'business_name' => 'MajorLink ISP', // Replace with your actual business name
                            'client_email' => $to
                        );


                        // Replace template words in the message
                        $message = replaceTemplateWords($body, $replacements);


                        // $sent = sendSms($to, $from, $message);
                        $sent = sendSMS($provider, $to, $message);

                        $output = array(
                            'success' => $sent ? true : false,
                            'message' => $sent ? 'Sent Successfully' : 'Something went wrong while sending'

                        );
                        echo json_encode($output);
                        exit();
                    }
                } else {
                    $output = array(
                        'success'  =>  false,
                        'message'  =>  'No Recipient found'
                    );
                    echo json_encode($output);
                    exit();
                }
            } catch (PDOException $e) {
                // Handle any database errors
                echo json_encode(array("error" => "Database error: " . $e->getMessage()));
            }
        }





        if ($selectedValue === "SubArea") {
            try {
                // Convert the array to a comma-separated string for the SQL query
                $subareaIDs = implode(',', $checkedValues);

                // Query to retrieve clients with area IDs in the checkedValues array
                $query = "SELECT * FROM clients WHERE SubAreaID IN ($subareaIDs)";
                $statement = $connect->prepare($query);
                $statement->execute();

                // Fetch the clients data
                $clients = $statement->fetchAll(PDO::FETCH_ASSOC);

                if ($clients) {
                    // Loop through each client and send the message
                    foreach ($clients as $client) {
                        // Get the client's email and other necessary details
                        $number = $client['PrimaryNumber'];
                        $to = $code . $number;
                        $from = "MajorLink"; // Assuming you have a FirstName field in your clients table
                        $name = $client['FirstName'] . ' ' . $client['LastName']; // Assuming you have a FirstName field in your clients table

                        // Replacements for template words
                        $replacements = array(
                            'client_name' => $name,
                            'business_name' => 'MajorLink ISP', // Replace with your actual business name
                            'client_email' => $to
                        );


                        // Replace template words in the message
                        $message = replaceTemplateWords($body, $replacements);


                        $sent = sendSMS($provider, $to, $message);

                        $output = array(
                            'success' => $sent ? true : false,
                            'message' => $sent ? 'Sent Successfully' : 'Something went wrong while sending'

                        );
                        echo json_encode($output);
                        exit();
                    }
                } else {
                    $output = array(
                        'success'  =>  false,
                        'message'  =>  'No Recipient found'
                    );
                    echo json_encode($output);
                    exit();
                }
            } catch (PDOException $e) {
                // Handle any database errors
                echo json_encode(array("error" => "Database error: " . $e->getMessage()));
            }
        }







        if ($selectedValue === "Active") {
            try {

                // Query to retrieve clients with area IDs in the checkedValues array
                $query = "SELECT * FROM clients WHERE ActiveStatus = 1";
                $statement = $connect->prepare($query);
                $statement->execute();

                // Fetch the clients data
                $clients = $statement->fetchAll(PDO::FETCH_ASSOC);

                if ($clients) {
                    // Loop through each client and send the message
                    foreach ($clients as $client) {
                        // Get the client's email and other necessary details
                        $number = $client['PrimaryNumber'];
                        $to = $code . $number;
                        $from = "MajorLink"; // Assuming you have a FirstName field in your clients table
                        $name = $client['FirstName'] . ' ' . $client['LastName']; // Assuming you have a FirstName field in your clients table

                        // Replacements for template words
                        $replacements = array(
                            'client_name' => $name,
                            'business_name' => 'MajorLink ISP', // Replace with your actual business name
                            'client_email' => $to
                        );

                        // Replace template words in the message
                        $message = replaceTemplateWords($body, $replacements);


                        $sent = sendSMS($provider, $to, $message);

                        $output = array(
                            'success' => $sent ? true : false,
                            'message' => $sent ? 'Sent Successfully' : 'Something went wrong while sending'

                        );
                        echo json_encode($output);
                        exit();
                    }
                } else {
                    $output = array(
                        'success'  =>  false,
                        'message'  =>  'No Recipient found'
                    );
                    echo json_encode($output);
                    exit();
                }
            } catch (PDOException $e) {
                // Handle any database errors
                echo json_encode(array("error" => "Database error: " . $e->getMessage()));
            }
        }





        if ($selectedValue === "Inactive") {
            try {

                // Query to retrieve clients with area IDs in the checkedValues array
                $query = "SELECT * FROM clients WHERE ActiveStatus = 0";
                $statement = $connect->prepare($query);
                $statement->execute();

                // Fetch the clients data
                $clients = $statement->fetchAll(PDO::FETCH_ASSOC);

                if ($clients) {
                    // Loop through each client and send the message
                    foreach ($clients as $client) {
                        // Get the client's email and other necessary details
                        $number = $client['PrimaryNumber'];
                        $to = $code . $number;
                        $from = "MajorLink"; // Assuming you have a FirstName field in your clients table
                        $name = $client['FirstName'] . ' ' . $client['LastName']; // Assuming you have a FirstName field in your clients table

                        // Replacements for template words
                        $replacements = array(
                            'client_name' => $name,
                            'business_name' => 'MajorLink ISP', // Replace with your actual business name
                            'client_email' => $to
                        );

                        // Replace template words in the message
                        $message = replaceTemplateWords($body, $replacements);

                        $sent = sendSMS($provider, $to, $message);


                        $output = array(
                            'success' => $sent ? true : false,
                            'message' => $sent ? 'Sent Successfully' : 'Something went wrong while sending'

                        );
                        echo json_encode($output);
                        exit();
                    }
                } else {
                    $output = array(
                        'success'  =>  false,
                        'message'  =>  'No Recipient found'
                    );
                    echo json_encode($output);
                    exit();
                }
            } catch (PDOException $e) {
                // Handle any database errors
                echo json_encode(array("error" => "Database error: " . $e->getMessage()));
                exit();
            }
        }




        if ($selectedValue === "All") {
            try {

                // Query to retrieve clients with area IDs in the checkedValues array
                $query = "SELECT * FROM clients";
                $statement = $connect->prepare($query);
                $statement->execute();

                // Fetch the clients data
                $clients = $statement->fetchAll(PDO::FETCH_ASSOC);
                if ($clients) {
                    // Loop through each client and send the message
                    foreach ($clients as $client) {
                        // Get the client's email and other necessary details
                        $number = $client['PrimaryNumber'];
                        $to = $code . $number;
                        $from = "MajorLink"; // Assuming you have a FirstName field in your clients table
                        $name = $client['FirstName'] . ' ' . $client['LastName']; // Assuming you have a FirstName field in your clients table

                        // Replacements for template words
                        $replacements = array(
                            'client_name' => $name,
                            'business_name' => 'MajorLink ISP', // Replace with your actual business name
                            'client_email' => $to
                        );


                        // Replace template words in the message
                        $message = replaceTemplateWords($body, $replacements);


                        $sent = sendSMS($provider, $to, $message);

                        $output = array(
                            'success' => $sent ? true : false,
                            'message' => $sent ? 'Sent Successfully' : 'Something went wrong while sending'

                        );
                        echo json_encode($output);
                        exit();
                    }
                } else {
                    $output = array(
                        'success'  =>  false,
                        'message'  =>  'No Recipient found'
                    );
                    echo json_encode($output);
                    exit();
                }
            } catch (PDOException $e) {
                // Handle any database errors
                echo json_encode(array("error" => "Database error: " . $e->getMessage()));
            }
        } else {
            // Handle other cases if needed
            echo json_encode(array("error" => "Invalid selected value"));
        }
    }
}




function replaceTemplateWords($message, $replacements)
{
    // Iterate through each replacement key-value pair
    foreach ($replacements as $key => $value) {
        // Replace the template word (e.g., {{client_name}}) with its corresponding value
        $message = str_replace('{{' . $key . '}}', $value, $message);
    }

    // Return the message with replaced template words
    return $message;
}
