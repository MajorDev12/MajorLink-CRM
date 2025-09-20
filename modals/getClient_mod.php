<?php

function getClientData($connect)
{
    $query = "SELECT 
                clients.ClientID,
                clients.FirstName,
                clients.LastName,
                areas.AreaName AS Area,
                subareas.SubAreaName AS SubArea,
                clients.ExpireDate,
                plans.Volume AS Plan,
                clients.ActiveStatus
            FROM clients
            LEFT JOIN areas ON clients.AreaID = areas.AreaID
            LEFT JOIN subareas ON clients.SubAreaID = subareas.SubAreaID
            LEFT JOIN plans ON clients.PlanID = plans.PlanID";

    $statement = $connect->prepare($query);

    $statement->execute();

    // Fetch all rows as an associative array
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the fetched data
    return $result;
}
