<?php

// Function to fetch client data from the database
function getClientsNames($connect)
{
    // Replace this with your database query to fetch FirstName, LastName, and ID from the clients table
    $sql = "SELECT ClientID, FirstName, LastName FROM clients";

    $statement = $connect->prepare($sql);

    $statement->execute();

    // Fetch all rows as an associative array
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the fetched data
    return $result;
}
