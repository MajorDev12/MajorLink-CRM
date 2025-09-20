<?php

// PLEASE DONT CHANGE ANYTHING IN THIS SECTION, SPECIFICALLY THE ARRANGEMENT OF THE FUNCTIONS





require_once '../database/pdo.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

//test Example Data for client
$numUsers = 7; // Number of default users to create
// invoice details
$invoiceCategory = 'Invoice';
$clientCategory = 'Client';
$adminCategory = 'Admin';
$status = 'active';
$invoiceConfirmation = "We have received your Payment for Invoice ID- {{invoice_id}}";
$invoiceOverdue = "Your Invoice- {{invoice_id}} is now overdue. To view your invoice- {{invoice_url}}";
$invoiceReminder = "We have not received payment for invoice {{invoice_id}}, dated {{invoice_date}}. To view your invoice- {{invoice_url}}";
$clientPassword = "Your new password is {{Password}}

{{business_name}}.
";
$adminPassword = "Your new password is {{Password}}

{{business_name}}.
";




// Client Signup Email
$clientCategory = "Client";
$welcomeName = "Welcome Email";
$signupSubject = "Your {{business_name}} Login Info";
$signupBody = "Dear {{client_name}},

Welcome to {{business_name}}.

You can track your billing, profile, transactions from this portal.

Your login information is as follows:

---------------------------------------------------------------------------------------

Login URL: {{login_url}}
Email Address: {{client_email}}
Password: Your chosen password.

----------------------------------------------------------------------------------------

We very much appreciate you for choosing us.

{{business_name}} Team";




// Payment Overdue email template
$overdueName = "Invoice Overdue Notice";
$overdueSubject = "{{business_name}} Invoice Overdue Notice";
$overdueBody = "Greetings,

This is a notice that your invoice no. {{invoice_id}} which was generated on {{invoice_date}} is now overdue.

Invoice URL: {{invoice_url}}
Invoice ID: {{invoice_id}}
Invoice Amount: {{invoice_amount}}
Due Date: {{invoice_due_date}}

If you have any questions or need assistance, please don't hesitate to contact us.

{{business_name}} Team";





// Payment Reminder email template
$reminderName = "Invoice Payment Reminder";
$reminderSubject = "{{business_name}} Invoice Payment Reminder";
$reminderBody = "Greetings,

This is a billing reminder that your invoice no. {{invoice_id}} which was generated on {{invoice_date}} is due on {{invoice_due_date}}.

Invoice URL: {{invoice_url}}
Invoice ID: {{invoice_id}}
Invoice Amount: {{invoice_amount}}
Due Date: {{invoice_due_date}}

If you have any questions or need assistance, please don't hesitate to contact us.

{{business_name}} Team";



// Payment Confirmation email template
$confirmName = "Invoice Payment Confirmation";
$confirmSubject = "{{business_name}} Invoice Payment Confirmation";
$confirmBody = "Greetings,

This is a payment receipt for Invoice {{invoice_id}} sent on {{invoice_date}}.

Login to your client Portal to view this invoice.

Invoice URL: {{invoice_url}}
Invoice ID: {{invoice_id}}
Invoice Amount: {{invoice_amount}}
Due Date: {{invoice_due_date}}

If you have any questions or need assistance, please don't hesitate to contact us.

{{business_name}} Team";



// new password email template
$passwordName = "New Password";
$passwordSubject = "{{business_name}} New Password for Admin";
$passwordBody = "Hello {{client_name}}

Here is your new password for {{business_name}}.

Log in URL: {{login_url}}
Username: {{client_name}}
Password: {{password}}

For security reason, Please change your password after login.

{{business_name}} Team";



// new password email template
$adminPasswordName = "New Password";
$adminPasswordSubject = "{{business_name}} New Password for Client";
$adminPasswordBody = "Hello {{admin_name}}

Here is your new password for {{business_name}}.

Log in URL: {{login_url}}
Username: {{admin_name}}
Password: {{password}}

For security reason, Please change your password after login.

{{business_name}} Team";




$CompanyName = 'MajorLink';
$Email = 'majorlink@gmail.com';
$PhoneNumber = '( 254 718-317-726)';
$Country = 'Kenya';
$Address = 'Nakuru, Pipeline';
$Website = 'www.MajorLink.com';
$Zipcode = '20100';
$City = 'Nakuru City';
$TimeZone = 'Africa/Nairobi';
$currencyName = 'Kenyan Shillings';
$currencySymbol = 'Sh';
$currencyCode = 'KES';
$PhoneCode = '+254';
$LogoURL = 'company_logo.png';

$planName1 = "Basic";
$planVolume1 = "5Mbps";
$planPrice1 = "1500";

$planName2 = "Standard";
$planVolume2 = "10Mbps";
$planPrice2 = "2000";


$planName3 = "Advanced";
$planVolume3 = "25Mbps";
$planPrice3 = "4000";








// Check if the flag file exists
$flagFilePath = __DIR__ . '/script_executed.flag';

if (!file_exists($flagFilePath)) {
    // The script hasn't been executed yet
    insertDefaultAdmin($connect);
    insertDefaultClients($connect, $numUsers);
    // Insert Stripe details
    insertPaymentMethods($connect, '', 'cash', 'the fastest way to go');
    insertPaymentMethods($connect, 'stripe.png', 'stripe', 'the best way to go');
    // Insert PayPal details
    insertPaymentMethods($connect, 'paypal.png', 'paypal', 'the only way to go');

    insertSmsTemplate($connect, $invoiceCategory, $invoiceOverdue, $status);
    insertSmsTemplate($connect, $invoiceCategory, $invoiceConfirmation, $status);
    insertSmsTemplate($connect, $invoiceCategory, $invoiceReminder, $status);
    insertSmsTemplate($connect, $clientCategory, $clientPassword, $status);
    insertSmsTemplate($connect, $adminCategory, $adminPassword, $status);

    insertEmailTemplate($connect, $clientCategory, $welcomeName, $signupSubject, $signupBody, $status);
    insertEmailTemplate($connect, $invoiceCategory, $reminderName, $reminderSubject, $reminderBody, $status);
    insertEmailTemplate($connect, $invoiceCategory, $overdueName, $overdueSubject, $overdueBody, $status);
    insertEmailTemplate($connect, $invoiceCategory, $confirmName, $confirmSubject, $confirmBody, $status);
    insertEmailTemplate($connect, $clientCategory, $passwordName, $passwordSubject, $passwordBody, $status);
    insertEmailTemplate($connect, $adminCategory, $adminPasswordName, $adminPasswordSubject, $adminPasswordBody, $status);
    insertCompanySetup($CompanyName, $Email, $PhoneNumber, $Country, $Address, $Website, $Zipcode, $City, $TimeZone, $currencyName, $currencySymbol, $currencyCode, $PhoneCode, $LogoURL, $connect);
    insertPlanData($planName1, $planVolume1, $planPrice1, $connect);
    insertPlanData($planName2, $planVolume2, $planPrice2, $connect);
    insertPlanData($planName3, $planVolume3, $planPrice3, $connect);

    insertArea("Pipeline", $connect);
    insertSubarea("Sawa", "1", $connect);
    insertSubarea("Barnabas", "1", $connect);
    insertSubarea("Pipes", "1", $connect);
    insertArea("Free Area", $connect);
    insertSubarea("Market", "2", $connect);
    insertSubarea("Oloika Country", "2", $connect);
    insertSubarea("Shinners", "2", $connect);
    insertArea("Sita", $connect);
    insertSubarea("Kabatini Road", "3", $connect);
    insertSubarea("Mawanga", "3", $connect);
    insertSubarea("Gingalili Village", "3", $connect);
    // Create the flag file to indicate that the script has been executed
    file_put_contents($flagFilePath, '');

    echo "";
} else {
    echo "";
}




















function insertDefaultAdmin($connect)
{

    // example admin
    $name = 'admin';
    $email = 'admin@gmail.com';
    $adminPassword = 'admin123';
    $ProfilePictureURL = 'default-profile-image.png';

    $options = [
        'cost' => 12
    ];

    // hash the password
    $PasswordHash = password_hash($adminPassword, PASSWORD_BCRYPT, $options);

    $query = "INSERT INTO admins (Username, Email, PasswordHash, ProfilePictureURL) VALUES (:name, :email, :PasswordHash, :ProfilePictureURL)";
    $statement = $connect->prepare($query);
    $statement->bindParam(':name', $name);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':PasswordHash', $PasswordHash);
    $statement->bindParam(':ProfilePictureURL', $ProfilePictureURL);
    $statement->execute();
}


function insertDefaultClients($connect, $numUsers)
{
    $defaultProfileImageURL = 'default-profile-image.png'; // Assuming you have a default profile image URL

    $defaultPassword = 'client123'; // Default password

    // Hash the default password using the same options
    $options = [
        'cost' => 12
    ];
    $clientPasswordHash = password_hash($defaultPassword, PASSWORD_BCRYPT, $options);

    // Loop to create and insert multiple default clients
    for ($i = 0; $i < $numUsers; $i++) {
        $fname = 'User' . ($i + 1); // Generate a unique first name for each user
        $sName = 'Doe'; // Assuming a default last name
        $pemail = 'user' . ($i + 1) . '@example.com'; // Generate a unique email for each user
        $pnumber = '0718317726'; // Generate a unique email for each user
        $PreferedPaymentMethod = 3;
        $CreatedDate = date('Y-m-d');

        // Insert user into the database
        $query = "INSERT INTO clients (FirstName, LastName, PrimaryEmail, PrimaryNumber, PasswordHash, PreferedPaymentMethod, CreatedDate, ProfilePictureURL) VALUES (:fname, :sName, :pemail, :pnumber, :clientPasswordHash, :PreferedPaymentMethod, :CreatedDate, :defaultProfileImageURL)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':fname', $fname);
        $statement->bindParam(':sName', $sName);
        $statement->bindParam(':pemail', $pemail);
        $statement->bindParam(':pnumber', $pnumber);
        $statement->bindParam(':clientPasswordHash', $clientPasswordHash);
        $statement->bindParam(':PreferedPaymentMethod', $PreferedPaymentMethod);
        $statement->bindParam(':CreatedDate', $CreatedDate);
        $statement->bindParam(':defaultProfileImageURL', $defaultProfileImageURL);
        $statement->execute();
    }
}

function insertPaymentMethods($connect, $PaymentOptionImg, $PaymentOptionName, $PaymentOptionQuote)
{
    $query = "INSERT INTO paymentoptions (PaymentOptionImg, PaymentOptionName, PaymentOptionQuote) VALUES (:PaymentOptionImg, :PaymentOptionName, :PaymentOptionQuote)";
    $statement = $connect->prepare($query);
    $statement->bindParam(':PaymentOptionImg', $PaymentOptionImg);
    $statement->bindParam(':PaymentOptionName', $PaymentOptionName);
    $statement->bindParam(':PaymentOptionQuote', $PaymentOptionQuote);
    $statement->execute();
}



function insertSmsTemplate($connect, $category, $body, $status)
{
    try {
        $query = "INSERT INTO smstemplate (Category, Body, Status) VALUES (:category, :body, :status)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':category', $category);
        $statement->bindParam(':body', $body);
        $statement->bindParam(':status', $status);
        $statement->execute();

        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function insertEmailTemplate($connect, $category, $name, $subject, $body, $status)
{
    try {
        $query = "INSERT INTO emailtemplate (Category, Name, Subject, Body, Status) VALUES (:category, :name, :subject, :body, :status)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':category', $category);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':subject', $subject);
        $statement->bindParam(':body', $body);
        $statement->bindParam(':status', $status);
        $statement->execute();

        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function insertCompanySetup($CompanyName, $Email, $PhoneNumber, $Country, $Address, $Website, $Zipcode, $City, $TimeZone, $currencyName, $currencySymbol, $currencyCode, $PhoneCode, $LogoURL, $connect)
{
    try {
        $query = "INSERT INTO companysettings (
            CompanyName,
            Email,
            PhoneNumber,
            Country,
            Address,
            Website,
            Zipcode,
            City,
            TimeZone,
            currencyName,
            currencySymbol,
            currencyCode,
            PhoneCode,
            LogoURL
        ) VALUES (
            :CompanyName,
            :Email,
            :PhoneNumber,
            :Country,
            :Address,
            :Website,
            :Zipcode,
            :City,
            :TimeZone,
            :currencyName,
            :currencySymbol,
            :currencyCode,
            :PhoneCode,
            :LogoURL
        )";

        $statement = $connect->prepare($query);
        $statement->bindParam(':CompanyName', $CompanyName);
        $statement->bindParam(':Email', $Email);
        $statement->bindParam(':PhoneNumber', $PhoneNumber);
        $statement->bindParam(':Country', $Country);
        $statement->bindParam(':Address', $Address);
        $statement->bindParam(':Website', $Website);
        $statement->bindParam(':Zipcode', $Zipcode);
        $statement->bindParam(':City', $City);
        $statement->bindParam(':TimeZone', $TimeZone);
        $statement->bindParam(':currencyName', $currencyName);
        $statement->bindParam(':currencySymbol', $currencySymbol);
        $statement->bindParam(':currencyCode', $currencyCode);
        $statement->bindParam(':PhoneCode', $PhoneCode);
        $statement->bindParam(':LogoURL', $LogoURL);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function insertPlanData($planName, $planVolume, $planPrice, $connect)
{
    try {
        $query = "INSERT INTO plans (Name, Volume, Price) VALUES (:planName, :planVolume, :planPrice)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':planName', $planName, PDO::PARAM_STR);
        $statement->bindParam(':planVolume', $planVolume, PDO::PARAM_STR);
        $statement->bindParam(':planPrice', $planPrice, PDO::PARAM_INT);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function insertArea($areaname, $connect)
{
    try {
        $query = "INSERT INTO areas (AreaName) VALUES (:areaname)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':areaname', $areaname, PDO::PARAM_STR);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        // Handle any exceptions or errors that occur during the query execution
        // For example, log the error, display an error message, or redirect to an error page
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function insertSubarea($subArea, $areaId, $connect)
{
    $data = array(
        ':subArea' => $subArea,
        ':areaId' => $areaId
    );

    $query = "INSERT INTO subareas (SubAreaName, AreaID) VALUES (:subArea, :areaId)";

    $statement = $connect->prepare($query);

    // Execute the prepared statement with the provided data
    $statement->execute($data);
}
