<?php


function addInvoice($connect, $clientID, $invoiceNumber, $totalAmount, $paymentDate, $startDate, $dueDate, $status, $taxSymbol, $taxAmount)
{
    try {
        $query = "INSERT INTO invoices (ClientID, InvoiceNumber, TotalAmount, paymentDate, StartDate, DueDate, Status, taxSymbol, Taxamount)
VALUES (:clientID, :invoiceNumber, :totalAmount, :paymentDate, :startDate, :dueDate, :status, :taxSymbol, :taxAmount)";

        $statement = $connect->prepare($query);
        $statement->bindParam(':clientID', $clientID);
        $statement->bindParam(':invoiceNumber', $invoiceNumber);
        $statement->bindParam(':totalAmount', $totalAmount);
        $statement->bindParam(':paymentDate', $paymentDate);
        $statement->bindParam(':startDate', $startDate);
        $statement->bindParam(':dueDate', $dueDate);
        $statement->bindParam(':status', $status);
        $statement->bindParam(':taxSymbol', $taxSymbol);
        $statement->bindParam(':taxAmount', $taxAmount);

        $statement->execute();

        // Return the last inserted InvoiceID
        return $connect->lastInsertId();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}









function getInvoiceIDByNumber($connect, $invoiceNumber)
{
    $query = "SELECT InvoiceID FROM invoices WHERE InvoiceNumber = :invoiceNumber";
    $statement = $connect->prepare($query);
    $statement->bindParam(':invoiceNumber', $invoiceNumber);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result['InvoiceID'];
    } else {
        return null; // No matching InvoiceNumber found
    }
}








function saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts)
{
    // Get the InvoiceID for the given InvoiceNumber
    $invoiceID = getInvoiceIDByNumber($connect, $invoiceNumber);

    if ($invoiceID !== null) {
        // Insert each product into the database
        foreach ($invoiceProducts as $product) {
            $name = $product['product'];
            $volume = $product['volume'];
            $qty = $product['qty'];
            $price = $product['price'];

            // Calculate the amount
            $amount = $qty * $price;

            // Perform your database insert operation with the obtained InvoiceID
            try {
                $query = "INSERT INTO invoiceproducts (InvoiceID, Name, Volume, Qty, Price, Amount, subTotal)
                          VALUES (:invoiceID, :name, :volume, :qty, :price, :amount, :subtotal)";

                $statement = $connect->prepare($query);
                $statement->bindParam(':invoiceID', $invoiceID);
                $statement->bindParam(':name', $name);
                $statement->bindParam(':volume', $volume);
                $statement->bindParam(':qty', $qty);
                $statement->bindParam(':price', $price);
                $statement->bindParam(':amount', $amount);
                $statement->bindParam(':subtotal', $subtotal);

                $statement->execute();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        }

        return true;
    } else {
        return false; // No matching InvoiceNumber found
    }
}







function getInvoiceData($connect, $invoiceID)
{
    try {
        $query = "SELECT * FROM invoices WHERE InvoiceID = :invoiceID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':invoiceID', $invoiceID);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getInvoiceProducts($connect, $invoiceID)
{
    try {
        $query = "SELECT * FROM invoiceproducts WHERE InvoiceID = :invoiceID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':invoiceID', $invoiceID);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}






function getAllInvoices($connect)
{
    try {
        $query = "SELECT invoices.*, clients.FirstName, clients.LastName FROM invoices
                  JOIN clients ON invoices.ClientID = clients.ClientID";
        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
