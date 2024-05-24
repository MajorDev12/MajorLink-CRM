<?php

function updateArea($updatedAreaName, $areaId, $connect)
{

    // Perform the update operation in the database
    $query = "UPDATE areas SET AreaName = :updatedAreaName WHERE AreaID = :areaId";
    $statement = $connect->prepare($query);
    $statement->bindParam(':updatedAreaName', $updatedAreaName);
    $statement->bindParam(':areaId', $areaId);

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
