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
