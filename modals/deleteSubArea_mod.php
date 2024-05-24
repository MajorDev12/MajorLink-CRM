<?php

function deleteSubArea($subareaId, $connect)
{
    $query = "DELETE FROM subareas WHERE SubAreaID = :subareaId";

    $statement = $connect->prepare($query);
    $statement->bindParam(':subareaId', $subareaId, PDO::PARAM_INT);

    // Execute the query
    $result = $statement->execute();

    // Return true if deletion is successful, false otherwise
    return $result;
}
