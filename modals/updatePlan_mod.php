<?php
function inputValidation($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



function updatePlanData($planId, $planName, $planVolume, $planPrice, $connect)
{
    try {

        // Assuming your table is named "plans" and has columns Name, Volume, and Price
        $query = "UPDATE plans SET Name = :planName, Volume = :planVolume, Price = :planPrice WHERE PlanID = :planId";
        $statement = $connect->prepare($query);
        $statement->bindParam(':planName', $planName, PDO::PARAM_STR);
        $statement->bindParam(':planVolume', $planVolume, PDO::PARAM_STR);
        $statement->bindParam(':planPrice', $planPrice, PDO::PARAM_STR);
        $statement->bindParam(':planId', $planId, PDO::PARAM_INT);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        // Handle any exceptions or errors that occur during the query execution
        // For example, log the error, display an error message, or redirect to an error page
        echo "Error: " . $e->getMessage();
        return false;
    }
}
