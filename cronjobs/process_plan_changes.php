<?php
// Include database connection
require_once  '../database/pdo.php';
$connect  = connectToDatabase($host, $dbname, $username, $password);

// Query database for scheduled plan changes
$query = "SELECT * FROM plan_change_schedule WHERE ScheduledDate <= NOW()";
$stmt = $connect->query($query);

// Execute plan changes
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $ClientID = $row['ClientID'];
    $newPlanID = $row['NewPlanID'];
    // Execute function to update plan ID for the client
    updateClientPlan($ClientID, $newPlanID, $connect);
    // Remove the processed entry from the schedule table
    $deleteQuery = "DELETE FROM plan_change_schedule WHERE ClientID = :ClientID";
    $deleteStmt = $connect->prepare($deleteQuery);
    $deleteStmt->bindParam(':ClientID', $ClientID);
    $deleteStmt->execute();
}





function updateClientPlan($ClientID, $newPlanID, $connect)
{
    try {
        // Prepare SQL query to update client plan
        $query = "UPDATE clients SET PlanID = :newPlanID WHERE ClientID = :ClientID";
        $stmt = $connect->prepare($query);

        // Bind parameters
        $stmt->bindParam(':ClientID', $ClientID);
        $stmt->bindParam(':newPlanID', $newPlanID);

        // Execute query
        $stmt->execute();

        // Success message or logging
        return true;
    } catch (PDOException $e) {
        // Error handling
        echo "Error updating client plan: " . $e->getMessage();
        return false;
    }
}
