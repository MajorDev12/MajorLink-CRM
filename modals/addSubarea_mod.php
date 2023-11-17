<?php

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


function insertSubarea($subArea, $areaId, $connect)
{
    $data = array(
        ':subArea' => $subArea,
        ':areaId' => $areaId
    );

    $query = "INSERT INTO subareas (SubAreaName, AreaID) VALUES (:subArea, :areaId)";

    $statement = $connect->prepare($query);

    // Execute the prepared statement with the provided data
    $statement->execute($data);
}
