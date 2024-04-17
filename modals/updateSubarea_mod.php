<?php

function updateSubArea($updatedAreaName, $subareaId, $connect)
{

    // Perform the update operation in the database
    $query = "UPDATE subareas SET subAreaName = :updatedAreaName WHERE SubAreaID = :subareaId";
    $statement = $connect->prepare($query);
    $statement->bindParam(':updatedAreaName', $updatedAreaName);
    $statement->bindParam(':subareaId', $subareaId);

    $result = $statement->execute();
    return $result;
}



function getData($connect)
{
    $query = "SELECT AreaID, AreaName FROM areas";

    $statement = $connect->prepare($query);

    $statement->execute();

    // Fetch all rows as an associative array
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the fetched data
    return $result;
}
