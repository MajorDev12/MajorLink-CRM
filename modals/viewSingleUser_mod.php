<?php

function getClientDataById($connect, $clientID)
{
    $query = "SELECT 
                clients.ClientID,
                clients.FirstName,
                clients.LastName,
                clients.PrimaryEmail,
                clients.SecondaryEmail,
                clients.PrimaryNumber,
                clients.SecondaryNumber,
                clients.Latitude,
                clients.Longitude,
                clients.CreatedDate,
                clients.ProfilePictureURL,
                clients.ActiveStatus,
                areas.AreaName AS Area,
                subareas.SubAreaName AS SubArea,
                clients.ExpireDate,
                plans.Volume AS Plan,
                clients.ActiveStatus
            FROM clients
            LEFT JOIN areas ON clients.AreaID = areas.AreaID
            LEFT JOIN subareas ON clients.SubAreaID = subareas.SubAreaID
            LEFT JOIN plans ON clients.PlanID = plans.PlanID
            WHERE clients.ClientID = :clientID";

    $statement = $connect->prepare($query);
    $statement->bindParam(':clientID', $clientID, PDO::PARAM_INT);
    $statement->execute();

    // Fetch the client data
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if data is found
    if ($result) {
        return $result;
    } else {
        // Handle the case where no client data is found for the given clientID
        return false;
    }
}
