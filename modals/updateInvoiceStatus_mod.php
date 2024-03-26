<?php


function updateInvoice($connect, $invoiceID, $status)
{
    try {
        $query = "UPDATE invoices SET Status = :status WHERE InvoiceID  = :invoiceID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':status', $status, PDO::PARAM_STR);
        $statement->bindParam(':invoiceID', $invoiceID, PDO::PARAM_INT);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function updateSales($connect, $SaleID, $status)
{
    try {
        $query = "UPDATE sales SET PaymentStatus = :status WHERE SaleID = :SaleID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':status', $status, PDO::PARAM_STR);
        $statement->bindParam(':SaleID', $SaleID, PDO::PARAM_INT);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
