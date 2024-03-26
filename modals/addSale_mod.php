<?php

function addSale($connect, $saleDate, $adminID, $clientID, $productID, $invoiceNumber, $quantity, $unitPrice, $total, $paymentMethodID, $tax, $taxType, $paymentStatus, $updatedDate)
{
    try {
        $query = "INSERT INTO sales (SaleDate, AdminID, ClientID, ProductID, InvoiceNumber, Quantity, UnitPrice, Total, PaymentOptionID, Tax, TaxSymbol, PaymentStatus, UpdatedDate)
                  VALUES (:saleDate, :adminID, :clientID, :productID, :invoiceNumber, :quantity, :unitPrice, :total, :paymentMethodID, :tax, :taxType, :paymentStatus, :updatedDate)";

        $statement = $connect->prepare($query);
        $statement->bindParam(':saleDate', $saleDate);
        $statement->bindParam(':adminID', $adminID);
        $statement->bindParam(':clientID', $clientID);
        $statement->bindParam(':productID', $productID);
        $statement->bindParam(':invoiceNumber', $invoiceNumber);
        $statement->bindParam(':quantity', $quantity);
        $statement->bindParam(':unitPrice', $unitPrice);
        $statement->bindParam(':total', $total);
        $statement->bindParam(':paymentMethodID', $paymentMethodID);
        $statement->bindParam(':tax', $tax);
        $statement->bindParam(':taxType', $taxType);
        $statement->bindParam(':paymentStatus', $paymentStatus);
        $statement->bindParam(':updatedDate', $updatedDate);

        $statement->execute();

        // Return the last inserted SaleID
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getSalesData($connect)
{
    try {
        $query = "SELECT sales.*, clients.FirstName, clients.LastName FROM sales
        JOIN clients ON sales.ClientID = clients.ClientID";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getSalesByID($connect, $SaleID)
{
    try {
        $query = "SELECT sales.*, products.ProductName 
                  FROM sales
                  JOIN products ON sales.ProductID = products.ProductID
                  WHERE sales.SaleID = :SaleID"; // Added WHERE clause to filter by SaleID
        $statement = $connect->prepare($query);
        $statement->bindParam(':SaleID', $SaleID);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
