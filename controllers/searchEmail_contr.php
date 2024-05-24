<?php
session_start();

// Call the searchData function in your existing code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["searchEmail"])) {
        require_once  '../database/pdo.php';
        sleep(2);
        $connect = connectToDatabase($host, $dbname, $username, $password);

        $searchEmail = $_POST["searchEmail"];

        $clientEmailFound = checkEmailInClients($connect, $searchEmail);
        $adminEmailFound = checkEmailInAdmins($connect, $searchEmail);


        if (isset($clientEmailFound) && $clientEmailFound !== false) {
            $id = $clientEmailFound["ClientID"];
            $_SESSION["clientEmailFound"] = $id;

            $output = array(
                'success' => $id
            );
            echo json_encode($output);
            exit();
        } elseif (isset($adminEmailFound) && $adminEmailFound !== false) {
            $id = $adminEmailFound["AdminID"];
            $_SESSION["adminEmailFound"] = $id;

            $output = array(
                'success' => $id
            );
            echo json_encode($output);
            exit();
        } else {
            $output = array(
                'success' => false
            );
            echo json_encode($output);
            exit();
        }
    }
}







function checkEmailInClients($connect, $email)
{
    try {
        // Query to check if the email exists in the clients table
        $query = "SELECT * FROM clients WHERE PrimaryEmail = :email";
        $statement = $connect->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();

        // Fetch the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // Check if the email exists in either PrimaryEmail or SecondaryEmail
        if ($result) {
            return $result; // Return the matched email
        } else {
            return false; // Return false if no email found
        }
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}





function checkEmailInAdmins($connect, $email)
{
    try {
        // Query to check if the email exists in the clients table
        $query = "SELECT * FROM admins WHERE Email = :email";
        $statement = $connect->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();

        // Fetch the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // Check if the email exists in either PrimaryEmail or SecondaryEmail
        if ($result) {
            return $result; // Return the matched email
        } else {
            return false; // Return false if no email found
        }
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}
