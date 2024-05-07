<?php


// Call the searchData function in your existing code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once  '../database/pdo.php';
    require_once  '../modals/addInvoice_mod.php';
    require_once  '../modals/getEmail_mod.php';
    require_once  '../modals/getSms_mod.php';
    require_once  '../modals/sendSms_mod.php';
    require "../includes/phpmailer/vendor/autoload.php";
    require_once  '../modals/updateSingleUser_mod.php';
    require_once  '../modals/viewSingleUser_mod.php';
    require_once  '../modals/setup_mod.php';
    require_once  '../modals/addAdmin_mod.php';


    $connect = connectToDatabase($host, $dbname, $username, $password);
    $settings = get_Settings($connect);
    $code = $settings[0]["PhoneCode"];

    // generate password and hash it
    $randomPassword = generateRandomPassword();
    $options = ['cost' => 12];
    $PasswordHash = password_hash($randomPassword, PASSWORD_BCRYPT, $options);


    if (isset($_POST["clientID"]) && !empty($_POST["clientID"])) {

        $clientID = $_POST['clientID'];
        $client = getClientDataById($connect, $clientID);


        // update password to the hashedPassword
        $passwordUpdated = updateUserPassword($clientID, $PasswordHash, $connect);



        if ($passwordUpdated) {

            if (isset($_POST["primaryEmail"]) || isset($_POST["secondaryEmail"])) {
                // Send email function call
                if (isset($_POST["primaryEmail"]) && !empty($_POST["primaryEmail"])) {
                    $to = $_POST["primaryEmail"];
                } elseif (isset($_POST["secondaryEmail"]) && !empty($_POST["secondaryEmail"])) {
                    $to = $_POST["secondaryEmail"];
                }

                $from = "MajorLink";
                $name = $client['FirstName'] . ' ' . $client['LastName'];

                $templateID = 4;
                $emails = getEmailTemplateById($connect, $templateID);
                $Subject = $emails["Subject"];
                $body = $emails["Body"];


                $replacements = array(
                    'Client_name' => $name,
                    'Business_name' => 'MajorLink ISP',
                    'Client_email' => $to,
                    'Password' => $randomPassword,
                    'Login_url' => 'http://localhost/majorlink/views/login.php'
                );

                // Replace template words in the message
                $message = replaceTemplateWords($body, $replacements);
                $subject = replaceTemplateWords($Subject, $replacements);


                $sent = sendEmail($to, $from, $subject, $message);

                $output = array(
                    'success' => $sent ? true : false,
                    'message' => $sent ? 'Sent Email Successfully' : 'Something went wrong while sending'

                );
                echo json_encode($output);
                exit();
            } elseif (isset($_POST["primaryNumber"]) || isset($_POST["secondaryNumber"])) {

                if (isset($_POST["primaryNumber"]) && !empty($_POST["primaryNumber"])) {
                    $to = $_POST["primaryNumber"];
                } elseif (isset($_POST["secondaryNumber"]) && !empty($_POST["secondaryNumber"])) {
                    $to = $_POST["secondaryNumber"];
                }

                $to = $code . $number;
                $templateID = 10;
                $sms = getSmsTemplateById($connect, $templateID);
                $body = $sms["Body"];

                $replacements = array(
                    'Business_name' => 'MajorLink ISP',
                    'Password' => $randomPassword
                );

                // Replace template words in the message
                $message = replaceTemplateWords($body, $replacements);
                $provider = 'Infobip';

                $sent = sendSMS($provider, $to, $message);

                $output = array(
                    'success' => $sent ? true : false,
                    'message' => $sent ? 'Sent message Successfully' : 'Something went wrong while sending the message'

                );
                echo json_encode($output);
                exit();
            }
        } else {
            $output = array(
                'success' => false,
                'message' => 'Something went wrong. Please try again later'

            );
            echo json_encode($output);
            exit();
        }
    } elseif (isset($_POST["adminID"]) && !empty($_POST["adminID"])) {

        $adminID = $_POST['adminID'];
        $admin = getAdminDataById($connect, $adminID);

        // update password to the hashedPassword
        $passwordUpdated = updateAdminPassword($adminID, $PasswordHash, $connect);

        if ($passwordUpdated) {
            if (isset($_POST["Email"]) && !empty($_POST["Email"])) {

                $to = $_POST["Email"];
                $from = "MajorLink";
                $name = $admin['Fullname'];

                $templateID = 4;
                $emails = getEmailTemplateById($connect, $templateID);
                $Subject = $emails["Subject"];
                $body = $emails["Body"];


                $replacements = array(
                    'Client_name' => $name,
                    'Business_name' => 'MajorLink ISP',
                    'Client_email' => $to,
                    'Password' => $randomPassword,
                    'Login_url' => 'http://localhost/majorlink/views/login.php'
                );

                // Replace template words in the message
                $message = replaceTemplateWords($body, $replacements);
                $subject = replaceTemplateWords($Subject, $replacements);


                $sent = sendEmail($to, $from, $subject, $message);

                $output = array(
                    'success' => $sent ? true : false,
                    'message' => $sent ? 'Sent Email Successfully' : 'Something went wrong while sending'

                );
                echo json_encode($output);
                exit();
            } elseif (isset($_POST["Number"]) && !empty($_POST["Number"])) {

                $number = $_POST["Number"];
                $to = $code . $number;
                $templateID = 10;
                $sms = getSmsTemplateById($connect, $templateID);
                $body = $sms["Body"];

                $replacements = array(
                    'Business_name' => 'MajorLink ISP',
                    'Password' => $randomPassword
                );

                // Replace template words in the message
                $message = replaceTemplateWords($body, $replacements);
                $provider = 'Infobip';

                $sent = sendSMS($provider, $to, $message);

                $output = array(
                    'success' => $sent ? true : false,
                    'message' => $sent ? 'Sent message Successfully' : 'Something went wrong while sending the message'

                );
                echo json_encode($output);
                exit();
            }
        }
    }
} else {
    // Handle the case where the request method is not POST
    echo json_encode(array('error' => 'Invalid request method'));
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




function generateRandomPassword($length = 8)
{
    // Define the character set for the password
    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    // Get the length of the character set
    $charsetLength = strlen($charset);
    $randomPassword = '';

    // Generate random password
    for ($i = 0; $i < $length; $i++) {
        // Choose a random character from the character set
        $randomChar = $charset[rand(0, $charsetLength - 1)];

        // Append the random character to the password
        $randomPassword .= $randomChar;
    }

    return $randomPassword;
}
