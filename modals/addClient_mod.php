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



function insertClientData($Fname, $Lname, $primaryEmail, $secondaryEmail, $primaryNumber, $secondaryNumber, $Address, $City, $Country, $zipCode,  $PasswordHash, $area, $subArea, $Plan, $latitude, $longitude, $CreatedDate, $PreferedPaymentMethod, $ProfilePictureURL, $activeStatus, $last_paymentDate, $expireDate, $connect)
{
  try {
    $query = "INSERT INTO clients (FirstName, LastName, PrimaryEmail, SecondaryEmail, PrimaryNumber, SecondaryNumber, Address, City, Country, ZipCode, PasswordHash, AreaID, SubAreaID, PlanID, Latitude, Longitude, CreatedDate, PreferedPaymentMethod, ProfilePictureURL, ActiveStatus, LastPayment, ExpireDate)
              VALUES (:Fname, :Lname, :primaryEmail, :secondaryEmail, :primaryNumber, :secondaryNumber, :Address, :City, :Country, :zipCode, :PasswordHash, :area, :subArea, :Plan, :latitude, :longitude, :CreatedDate, :PreferedPaymentMethod, :ProfilePictureURL, :activeStatus, :last_paymentDate, :expireDate)";
    $statement = $connect->prepare($query);
    $statement->bindParam(':Fname', $Fname, PDO::PARAM_STR);
    $statement->bindParam(':Lname', $Lname, PDO::PARAM_STR);
    $statement->bindParam(':primaryEmail', $primaryEmail, PDO::PARAM_STR);
    $statement->bindParam(':secondaryEmail', $secondaryEmail, PDO::PARAM_STR);
    $statement->bindParam(':primaryNumber', $primaryNumber, PDO::PARAM_STR);
    $statement->bindParam(':secondaryNumber', $secondaryNumber, PDO::PARAM_STR);
    $statement->bindParam(':Address', $Address, PDO::PARAM_STR);
    $statement->bindParam(':City', $City, PDO::PARAM_STR);
    $statement->bindParam(':Country', $Country, PDO::PARAM_STR);
    $statement->bindParam(':zipCode', $zipCode, PDO::PARAM_INT);
    $statement->bindParam(':PasswordHash', $PasswordHash, PDO::PARAM_STR);
    $statement->bindParam(':area', $area, PDO::PARAM_INT);
    $statement->bindParam(':subArea', $subArea, PDO::PARAM_INT);
    $statement->bindParam(':Plan', $Plan, PDO::PARAM_INT);
    $statement->bindParam(':latitude', $latitude, PDO::PARAM_STR);
    $statement->bindParam(':longitude', $longitude, PDO::PARAM_STR);
    $statement->bindParam(':CreatedDate', $CreatedDate, PDO::PARAM_STR);
    $statement->bindParam(':PreferedPaymentMethod', $PreferedPaymentMethod, PDO::PARAM_INT);
    $statement->bindParam(':ProfilePictureURL', $ProfilePictureURL, PDO::PARAM_STR);
    $statement->bindParam(':activeStatus', $activeStatus, PDO::PARAM_INT);
    $statement->bindParam(':last_paymentDate', $last_paymentDate, PDO::PARAM_STR);
    $statement->bindParam(':expireDate', $expireDate, PDO::PARAM_STR);
    $statement->execute();

    return $connect->lastInsertId(); // Return the last inserted ID (ClientID)
  } catch (PDOException $e) {
    // Handle the exception
    echo "Error: " . $e->getMessage();
    return false;
  }
}





// function insertPaymentData($clientId, $Plan, $PlanAmount, $PaymentStatus,  $Paymentdate, $paymentMethodID, $InstallationFees, $connect)
// {
//   $query = "INSERT INTO Payments (ClientID, PlanID, PaymentAmount, PaymentStatus, PaymentDate, PaymentOptionID, InstallationFees) VALUES (:clientId, :Plan, :PlanAmount, :PaymentStatus, :Paymentdate, :paymentMethodID, :InstallationFees)";
//   $statement = $connect->prepare($query);
//   $statement->bindParam(':clientId', $clientId);
//   $statement->bindParam(':Plan', $Plan);
//   $statement->bindParam(':PlanAmount', $PlanAmount);
//   $statement->bindParam(':PaymentStatus', $PaymentStatus);
//   $statement->bindParam(':Paymentdate', $Paymentdate);
//   $statement->bindParam(':paymentMethodID', $paymentMethodID);
//   $statement->bindParam(':InstallationFees', $InstallationFees);
//   $statement->execute();
// }




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




// Function to get the count of all users
function getUsersCount($connect)
{
  try {
    // Query to count all users
    $query = "SELECT COUNT(*) AS userCount FROM clients";
    $statement = $connect->query($query);

    // Fetch the count
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result['userCount'];
  } catch (PDOException $e) {
    // Handle any potential errors
    echo "Error: " . $e->getMessage();
    return false;
  }
}


function getActiveUsers($connect)
{
  try {
    // Query to count all users
    $query = "SELECT COUNT(*) AS ActiveUserCount FROM clients WHERE ActiveStatus = 1";
    $statement = $connect->query($query);

    // Fetch the count
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result['ActiveUserCount'];
  } catch (PDOException $e) {
    // Handle any potential errors
    echo "Error: " . $e->getMessage();
    return false;
  }
}




function getInActiveUsers($connect)
{
  try {
    // Query to count all users where ActiveStatus is not 1 or is NULL
    $query = "SELECT COUNT(*) AS inActiveClient FROM clients WHERE ActiveStatus <> 1 OR ActiveStatus IS NULL";
    $statement = $connect->query($query);

    // Fetch the count
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result['inActiveClient'];
  } catch (PDOException $e) {
    // Handle any potential errors
    echo "Error: " . $e->getMessage();
    return false;
  }
}






function getExpiringClients($connect)
{
  try {
    // Get today's date
    $today = date('Y-m-d');

    // Query to fetch clients whose expiration date is nearing today
    $query = "SELECT clients.*, areas.areaName, DATEDIFF(clients.ExpireDate, CURDATE()) AS RemainingDays 
                  FROM clients 
                  LEFT JOIN areas ON clients.AreaID = areas.AreaID
                  WHERE clients.ExpireDate >= CURDATE() 
                  OR clients.ExpireDate < CURDATE()  -- Check if expiration date has passed
                  ORDER BY clients.ExpireDate ASC
                  LIMIT 7"; // Limit to the first 7 clients
    $statement = $connect->prepare($query);
    $statement->execute();

    // Fetch the results
    $clients = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $clients;
  } catch (PDOException $e) {
    // Handle any potential errors
    echo "Error: " . $e->getMessage();
    return false;
  }
}




function getNewestClients($connect, $limit = 6)
{
  try {
    // Query to select the newest clients
    $query = "SELECT * FROM clients ORDER BY createdDate DESC LIMIT :limit";

    // Prepare the statement
    $statement = $connect->prepare($query);
    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);

    // Execute the statement
    $statement->execute();

    // Fetch all rows as associative arrays
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the fetched data
    return $result;
  } catch (PDOException $e) {
    // Handle any potential errors
    echo "Error: " . $e->getMessage();
    return false;
  }
}
