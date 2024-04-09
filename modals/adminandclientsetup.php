<?php
require_once '../database/pdo.php';

//test Example Data for admin
$name = 'admin';
$email = 'admin@gmail.com';
$password = '12345678';
$ProfilePictureURL = 'default-profile-image.png';


//test Example Data for client
$clientID = 1;
$PlanID = 2;
$fname = 'client';
$sName = 'account';
$pemail = 'client@gmail.com';
$password = '123456';
$defaultProfileImageURL = 'default-profile-image.png';

//clients payment examples
$clientID = 1;
$PlanID = 2;
$PaymentAmount = 2000.00;
$PaymentStatus = 'Paid';
$PaymentDate = 'Paid';
$PaymentOptionID  = 2;
$InstallationFees  = 1500;





$connect = connectToDatabase($host, $dbname, $username, $password);

$options = [
    'cost' => 12
];

// hash the password
$PasswordHash = password_hash($password, PASSWORD_BCRYPT, $options);

$clientPasswordHash = password_hash($password, PASSWORD_BCRYPT, $options);

function insertDefaultAdmin($connect, $name, $email, $PasswordHash, $ProfilePictureURL)
{
    $query = "INSERT INTO admins (Username, Email, PasswordHash, ProfilePictureURL) VALUES (:name, :email, :PasswordHash, :ProfilePictureURL)";
    $statement = $connect->prepare($query);
    $statement->bindParam(':name', $name);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':PasswordHash', $PasswordHash);
    $statement->bindParam(':ProfilePictureURL', $ProfilePictureURL);
    $statement->execute();
}

function insertDefaultClient($connect, $fname, $sName, $pemail, $clientPasswordHash, $defaultProfileImageURL)
{
    $query = "INSERT INTO clients (FirstName, LastName, PrimaryEmail, PasswordHash, ProfilePictureURL) VALUES (:fname, :sName, :pemail, :clientPasswordHash, :defaultProfileImageURL)";
    $statement = $connect->prepare($query);
    $statement->bindParam(':fname', $fname);
    $statement->bindParam(':sName', $sName);
    $statement->bindParam(':pemail', $pemail);
    $statement->bindParam(':clientPasswordHash', $clientPasswordHash);
    $statement->bindParam(':defaultProfileImageURL', $defaultProfileImageURL);
    $statement->execute();
}



function insertDefaultClientPayments($connect, $ClientID, $PlanID, $PaymentAmount, $PaymentStatus, $PaymentDate, $PaymentOptionID, $InstallationFees)
{
    $query = "INSERT INTO clients (FirstName, LastName, PrimaryEmail, PasswordHash, ProfilePictureURL) VALUES (:fname, :sName, :pemail, :clientPasswordHash, :defaultProfileImageURL)";
    $statement = $connect->prepare($query);
    $statement->bindParam(':fname', $fname);
    $statement->bindParam(':sName', $sName);
    $statement->bindParam(':pemail', $pemail);
    $statement->bindParam(':clientPasswordHash', $clientPasswordHash);
    $statement->bindParam(':defaultProfileImageURL', $defaultProfileImageURL);
    $statement->execute();
}







// Check if the flag file exists
$flagFilePath = __DIR__ . '/script_executed.flag';

if (!file_exists($flagFilePath)) {
    // The script hasn't been executed yet

    // Execute the script
    insertDefaultAdmin($connect, $name, $email, $PasswordHash, $ProfilePictureURL);
    insertDefaultClient($connect, $fname, $sName, $pemail, $clientPasswordHash, $defaultProfileImageURL);
    // Create the flag file to indicate that the script has been executed
    file_put_contents($flagFilePath, '');

    echo "Script executed successfully.";
} else {
    echo "";
}
