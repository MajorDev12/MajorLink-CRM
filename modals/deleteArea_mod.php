<?php

function deleteArea($areaId, $connect)
{
    $query = "DELETE FROM areas WHERE AreaID = :areaId";

    $statement = $connect->prepare($query);
    $statement->bindParam(':areaId', $areaId, PDO::PARAM_INT);

    // Execute the query
    $result = $statement->execute();

    // Return true if deletion is successful, false otherwise
    return $result;
}
