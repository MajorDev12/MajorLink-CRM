<?php

function getAdminDataById($connect, $adminID)
{
    try {
        $query = "SELECT * FROM admins WHERE AdminID = :adminID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':adminID', $adminID, PDO::PARAM_INT); // Assuming AdminID is an integer
        $statement->execute();

        // Fetch the row as an associative array
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // Return the fetched data
        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function  updateAdminDetails($adminID, $firstnameInput, $lastnameInput, $PrimaryEmailInput, $primaryNumberInput, $connect)
{
    // Perform your update logic here, for example, updating a database
    try {

        // Use prepared statement to prevent SQL injection
        $query = "UPDATE admins SET 
            Username = :firstnameInput,
            FullName = :lastnameInput,
            Email = :PrimaryEmailInput,
            Phone = :primaryNumberInput
            WHERE AdminID = :adminID";

        $stmt = $connect->prepare($query);

        // Bind parameters
        $stmt->bindParam(':adminID', $adminID);
        $stmt->bindParam(':firstnameInput', $firstnameInput);
        $stmt->bindParam(':lastnameInput', $lastnameInput);
        $stmt->bindParam(':PrimaryEmailInput', $PrimaryEmailInput);
        $stmt->bindParam(':primaryNumberInput', $primaryNumberInput);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    } finally {
        $connect = null; // Close the database connection
    }
}
