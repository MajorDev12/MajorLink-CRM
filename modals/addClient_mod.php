<?php

function insertData($Fname, $Lname, $email, $phoneNumber, $connect)
{
  //put insert data code here 
  $data = array(
    ':Fname'    =>    $Fname,
    ':Lname'    =>    $Lname,
    ':email'    =>    $email,
    ':phoneNumber'    =>    $phoneNumber
  );

  $query = "
		INSERT INTO clients 
		(FirstName, LastName, Email, Phone) 
		VALUES (:Fname, :Lname, :email, :phoneNumber)
		";

  $statement = $connect->prepare($query);

  $statement->execute($data);
}
