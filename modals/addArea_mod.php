<?php


function isValidInput($areaname)
{
    return preg_match("/^[a-zA-Z0-9]+$/", $areaname) === 0;
}


function emptyInput($areaname)
{
    return empty($areaname);
}




function insertArea($areaname, $connect)
{
    try {
        $query = "INSERT INTO areas (AreaName) VALUES (:areaname)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':areaname', $areaname, PDO::PARAM_STR);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        // Handle any exceptions or errors that occur during the query execution
        // For example, log the error, display an error message, or redirect to an error page
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getData($connect)
{
    $query = "SELECT AreaID, AreaName FROM areas";

    $statement = $connect->prepare($query);

    $statement->execute();

    // Fetch all rows as an associative array
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the fetched data
    return $result;
}
