<?php
function hasMadeAdvancePayment($clientId, $connect)
{
    try {
        $todayDate = date('Y-m-d');

        // Fetch advance payment data for the specific ClientId
        $query = "SELECT ExpireDate FROM clients WHERE ClientId = :clientId";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are any rows
        if ($result) {
            // Compare FromDate with the current date
            foreach ($result as $row) {
                $ExpireDate = $row['ExpireDate'];
                if ($ExpireDate > $todayDate) {
                    return true;
                }
            }
        }
        return false;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
