<?php


// function generateRandomPassword($length = 6)
// {
//   $characters = '0123456789';
//   $password = '';

//   for ($i = 0; $i < $length; $i++) {
//     $password .= $characters[rand(0, strlen($characters) - 1)];
//   }

//   return $password;
// }



function insertClientData($Fname, $Lname, $primaryEmail, $secondaryEmail, $primaryNumber, $secondaryNumber, $PasswordHash, $area, $subArea, $Plan, $latitude, $longitude, $CreatedDate, $ProfilePictureURL, $activeStatus, $last_paymentDate, $paymentMethodID, $expireDate, $connect)
{
  $query = "INSERT INTO clients (FirstName, LastName, PrimaryEmail, SecondaryEmail, PrimaryNumber, SecondaryNumber, PasswordHash, AreaID, SubAreaID, PlanID, Latitude, Longitude, CreatedDate, ProfilePictureURL, ActiveStatus, LastPayment, PreferedPaymentMethod, ExpireDate)
       VALUES (:Fname, :Lname, :primaryEmail, :secondaryEmail, :primaryNumber, :secondaryNumber, :PasswordHash, :area, :subArea, :Plan, :latitude, :longitude, :CreatedDate, :ProfilePictureURL, :activeStatus, :last_paymentDate, :paymentMethodID, :expireDate)";
  $statement = $connect->prepare($query);
  $statement->bindParam(':Fname', $Fname);
  $statement->bindParam(':Lname', $Lname);
  $statement->bindParam(':primaryEmail', $primaryEmail);
  $statement->bindParam(':secondaryEmail', $secondaryEmail);
  $statement->bindParam(':primaryNumber', $primaryNumber);
  $statement->bindParam(':secondaryNumber', $secondaryNumber);
  $statement->bindParam(':PasswordHash', $PasswordHash);
  $statement->bindParam(':area', $area, PDO::PARAM_INT);
  $statement->bindParam(':subArea', $subArea, PDO::PARAM_INT);
  $statement->bindParam(':Plan', $Plan);
  $statement->bindParam(':latitude', $latitude);
  $statement->bindParam(':longitude', $longitude);
  $statement->bindParam(':CreatedDate', $CreatedDate);
  $statement->bindParam(':ProfilePictureURL', $ProfilePictureURL);
  $statement->bindParam(':activeStatus', $activeStatus);
  $statement->bindParam(':last_paymentDate', $last_paymentDate);
  $statement->bindParam(':paymentMethodID', $paymentMethodID);
  $statement->bindParam(':expireDate', $expireDate);
  $statement->execute();

  return $connect->lastInsertId(); // Return the last inserted ID (ClientID)
}



function insertPaymentData($clientId, $Plan, $PlanAmount, $PaymentStatus,  $Paymentdate, $paymentMethodID, $InstallationFees, $connect)
{
  $query = "INSERT INTO Payments (ClientID, PlanID, PaymentAmount, PaymentStatus, PaymentDate, PaymentOptionID, InstallationFees) VALUES (:clientId, :Plan, :PlanAmount, :PaymentStatus, :Paymentdate, :paymentMethodID, :InstallationFees)";
  $statement = $connect->prepare($query);
  $statement->bindParam(':clientId', $clientId);
  $statement->bindParam(':Plan', $Plan);
  $statement->bindParam(':PlanAmount', $PlanAmount);
  $statement->bindParam(':PaymentStatus', $PaymentStatus);
  $statement->bindParam(':Paymentdate', $Paymentdate);
  $statement->bindParam(':paymentMethodID', $paymentMethodID);
  $statement->bindParam(':InstallationFees', $InstallationFees);
  $statement->execute();
}




function searchClientsData($connect, $searchInput)
{
  try {
    // Use a prepared statement to prevent SQL injection
    $query = "SELECT clients.*, 
                         areas.AreaName, 
                         subareas.SubAreaName, 
                         plans.Volume
                  FROM clients
                  LEFT JOIN areas ON clients.AreaID = areas.AreaID
                  LEFT JOIN subareas ON clients.SubAreaID = subareas.SubAreaID
                  LEFT JOIN plans ON clients.PlanID = plans.PlanID
                  WHERE clients.FirstName LIKE :searchInput 
                     OR clients.LastName LIKE :searchInput 
                     OR areas.AreaName LIKE :searchInput 
                     OR subareas.SubAreaName LIKE :searchInput 
                     OR plans.Volume LIKE :searchInput 
                     OR clients.ActiveStatus LIKE :searchInput";
    $statement = $connect->prepare($query);
    $statement->bindValue(':searchInput', '%' . $searchInput . '%', PDO::PARAM_STR);
    $statement->execute();

    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $results;
  } catch (PDOException $e) {
    // Handle the exception as needed
    echo "Error: " . $e->getMessage();
    return false;
  }
}





function deleteClient($clientId, $connect)
{
  try {
    // Prepare the DELETE statement
    $query = "DELETE FROM clients WHERE ClientID = :clientId";
    $statement = $connect->prepare($query);

    // Bind the parameter
    $statement->bindParam(':clientId', $clientId, PDO::PARAM_INT);

    // Execute the statement
    $statement->execute();

    // Check if any row was affected
    if ($statement->rowCount() > 0) {
      // Client deleted successfully
      return true;
    } else {
      // No client was deleted (maybe client with given ID doesn't exist)
      return false;
    }
  } catch (PDOException $e) {
    // Handle the exception as needed
    echo "Error deleting client: " . $e->getMessage();
    return false;
  }
}
