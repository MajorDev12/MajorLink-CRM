<?php

function getSubareasByAreaId($connect, $areaId)
{
    $query = "SELECT SubAreaID, SubAreaName FROM subareas WHERE AreaID = :areaId";

    $statement = $connect->prepare($query);

    // Bind the parameter
    $statement->bindParam(':areaId', $areaId, PDO::PARAM_INT);

    $statement->execute();

    // Fetch all rows as an associative array
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the fetched data
    return $result;
}
