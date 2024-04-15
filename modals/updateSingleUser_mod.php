<?php

function updateSingleUser($clientId, $firstName, $lastName, $primaryEmail, $secondaryEmail, $primaryNumber, $secondaryNumber, $area, $subArea, $latitude, $longitude, $connect)
{
    // Perform your update logic here, for example, updating a database
    try {

        // Use prepared statement to prevent SQL injection
        $query = "UPDATE clients SET 
            FirstName = :FirstName,
            LastName = :LastName,
            PrimaryEmail = :PrimaryEmail,
            SecondaryEmail = :SecondaryEmail,
            PrimaryNumber = :PrimaryNumber,
            SecondaryNumber = :SecondaryNumber,
            Latitude = :Latitude,
            Longitude = :Longitude,
            AreaID = :Area,
            SubAreaID = :SubArea
            WHERE ClientID = :clientId";

        $stmt = $connect->prepare($query);

        // Bind parameters
        $stmt->bindParam(':clientId', $clientId);
        $stmt->bindParam(':FirstName', $firstName);
        $stmt->bindParam(':LastName', $lastName);
        $stmt->bindParam(':PrimaryEmail', $primaryEmail);
        $stmt->bindParam(':SecondaryEmail', $secondaryEmail);
        $stmt->bindParam(':PrimaryNumber', $primaryNumber);
        $stmt->bindParam(':SecondaryNumber', $secondaryNumber);
        $stmt->bindParam(':Latitude', $latitude);
        $stmt->bindParam(':Longitude', $longitude);
        $stmt->bindParam(':Area', $area);
        $stmt->bindParam(':SubArea', $subArea);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    } finally {
        $connect = null; // Close the database connection
    }
}












function updateUserDetails($clientId, $firstName, $lastName, $primaryEmail, $secondaryEmail, $primaryNumber, $secondaryNumber, $address, $city, $country, $zipcode, $connect)
{
    // Perform your update logic here, for example, updating a database
    try {

        // Use prepared statement to prevent SQL injection
        $query = "UPDATE clients SET 
            FirstName = :FirstName,
            LastName = :LastName,
            PrimaryEmail = :PrimaryEmail,
            SecondaryEmail = :SecondaryEmail,
            PrimaryNumber = :PrimaryNumber,
            SecondaryNumber = :SecondaryNumber,
            Address = :Address,
            City = :City,
            Country = :Country,
            Zipcode = :Zipcode
            WHERE ClientID = :clientId";

        $stmt = $connect->prepare($query);

        // Bind parameters
        $stmt->bindParam(':clientId', $clientId);
        $stmt->bindParam(':FirstName', $firstName);
        $stmt->bindParam(':LastName', $lastName);
        $stmt->bindParam(':PrimaryEmail', $primaryEmail);
        $stmt->bindParam(':SecondaryEmail', $secondaryEmail);
        $stmt->bindParam(':PrimaryNumber', $primaryNumber);
        $stmt->bindParam(':SecondaryNumber', $secondaryNumber);
        $stmt->bindParam(':Address', $address);
        $stmt->bindParam(':City', $city);
        $stmt->bindParam(':Country', $country);
        $stmt->bindParam(':Zipcode', $zipcode);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    } finally {
        $connect = null; // Close the database connection
    }
}
