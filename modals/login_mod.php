<?php


function isAdmin($email, $hashedPassword, $connect)
{
    try {
        // Check in the admin table
        $emailQuery = "SELECT * FROM admins WHERE Email = :email";
        $emailStatement = $connect->prepare($emailQuery);
        $emailStatement->bindParam(':email', $email);
        // $emailStatement->bindParam(':hashedPassword', $hashedPassword);
        $emailStatement->execute();

        // Fetch the result
        $adminData = $emailStatement->fetch(PDO::FETCH_ASSOC);
        if ($adminData && password_verify($hashedPassword, $adminData['PasswordHash'])) {
            // Password is correct, return true
            return $adminData;
        } else {
            // No matching record or incorrect password, return false
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function isClient($email, $hashedPassword, $connect)
{
    try {
        $emailQuery = "SELECT * FROM clients WHERE PrimaryEmail = :email";
        $emailStatement = $connect->prepare($emailQuery);
        $emailStatement->bindParam(':email', $email);
        $emailStatement->execute();

        $clientData = $emailStatement->fetch(PDO::FETCH_ASSOC);

        if ($clientData && password_verify($hashedPassword, $clientData['PasswordHash'])) {
            // Password is correct, return true
            return $clientData;
        } else {
            // No matching record or incorrect password, return false
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function maskEmail($email)
{
    // Separate the username and domain parts of the email
    $parts = explode('@', $email);
    $username = $parts[0];
    $domain = $parts[1];

    // Mask the username, leaving the first, second, and last characters visible
    $maskedUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 3) . substr($username, -1);

    // Concatenate the masked username and the domain to form the masked email
    $maskedEmail = $maskedUsername . '@' . $domain;

    return $maskedEmail;
}






// Function to mask phone number
function maskPhoneNumber($number)
{
    // Check if phone number has at least 7 digits
    if (strlen($number) >= 7) {
        // Get the first three and last two characters of the phone number
        $maskedNumber = substr($number, 0, 3) . str_repeat('*', strlen($number) - 5) . substr($number, -2);
    } else {
        // If phone number has less than 7 digits, mask all but the last two digits
        $maskedNumber = str_repeat('*', strlen($number) - 2) . substr($number, -2);
    }

    // Return masked phone number
    return $maskedNumber;
}
