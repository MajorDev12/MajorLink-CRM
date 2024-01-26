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


function isTest($email, $PasswordHash, $connect)
{
    try {
        $emailQuery = "SELECT * FROM clients WHERE PrimaryEmail = :email";
        $emailStatement = $connect->prepare($emailQuery);
        $emailStatement->bindParam(':email', $email);
        $emailStatement->execute();

        $clientData = $emailStatement->fetch(PDO::FETCH_ASSOC);

        if ($clientData && password_verify($PasswordHash, $clientData['PasswordHash'])) {
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
