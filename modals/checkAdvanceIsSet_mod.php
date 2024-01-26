<?php
function hasMadeAdvancePayment($clientId, $connect)
{
    try {
        $todayDate = date('Y-m-d');

        // Fetch advance payment data for the specific ClientId
        $query = "SELECT FromDate FROM advancepayments WHERE ClientId = :clientId";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are any rows
        if ($result) {
            // Compare FromDate with the current date
            foreach ($result as $row) {
                $fromDate = $row['FromDate'];
                if ($fromDate > $todayDate) {
                    // Client has made an advance payment for a date in the future
                    return true;
                }
            }
        }

        // Client has not made an advance payment or all payments are in the past
        return false;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
