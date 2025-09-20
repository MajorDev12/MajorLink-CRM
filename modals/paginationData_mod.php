<?php

function clientDataPage($connect, $start, $limit)
{
    try {

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

            LEFT JOIN plans ON clients.PlanID = plans.PlanID LIMIT $start, $limit";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {

        echo "Error: " . $e->getMessage();

        return false;
    }
}



function getTotalClientCount($connect)
{
    $query = "SELECT COUNT(*) as total FROM clients";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}
