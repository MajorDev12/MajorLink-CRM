<?php
$clientID = 'Ae4orbdIICDrUSBdOqXB0HbAwz41DvXZwYt9UXlCOska-hYHUEw2YkXEblL0N4VNgBmtAt9G8H7Gq1Mt';


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
                clients.PasswordHash,
                clients.Latitude,
                clients.Longitude,
                clients.CreatedDate,
                clients.ProfilePictureURL,
                clients.ActiveStatus,
                areas.AreaName AS Area,
                subareas.SubAreaName AS SubArea,
                clients.ExpireDate,
                clients.LastPayment AS LastPayment,
                clients.PreferedPaymentMethod AS PreferedPaymentMethod,
                plans.PlanID AS PlanID,
                plans.Volume AS Plan,
                plans.Name AS PlanName,
                plans.Price AS PlanPrice,
                clients.ActiveStatus AS ActiveStatus,
                payments.PaymentStatus,
                payments.PaymentDate AS paymentDate
            FROM clients
            LEFT JOIN areas ON clients.AreaID = areas.AreaID
            LEFT JOIN subareas ON clients.SubAreaID = subareas.SubAreaID
            LEFT JOIN plans ON clients.PlanID = plans.PlanID
            LEFT JOIN payments ON clients.ClientID = payments.ClientID 
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
