<?php
function inputValidation($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function insertPlanData($planName, $planVolume, $planPrice, $connect)
{
    try {
        $query = "INSERT INTO plans (Name, Volume, Price) VALUES (:planName, :planVolume, :planPrice)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':planName', $planName, PDO::PARAM_STR);
        $statement->bindParam(':planVolume', $planVolume, PDO::PARAM_STR);
        $statement->bindParam(':planPrice', $planPrice, PDO::PARAM_INT);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getPlanData($connect)
{
    try {
        $query = "SELECT PlanID, Name, Volume, Price FROM plans";
        $statement = $connect->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
