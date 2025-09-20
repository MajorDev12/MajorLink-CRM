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
        return true;
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
        echo "No such Invoice";
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





function getAllPaidInvoices($connect)
{
    try {
        // Modify the query to include a WHERE clause for the "Paid" status
        $query = "SELECT invoices.*, clients.FirstName, clients.LastName 
                  FROM invoices
                  JOIN clients ON invoices.ClientID = clients.ClientID
                  WHERE invoices.Status = 'Paid'";

        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}





function getAllUnpaidInvoices($connect)
{
    try {
        // Modify the query to include a WHERE clause for the "Paid" status
        $query = "SELECT invoices.*, clients.FirstName, clients.LastName 
                  FROM invoices
                  JOIN clients ON invoices.ClientID = clients.ClientID
                  WHERE invoices.Status = 'Unaid'";

        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}









function searchServicesData($connect, $searchInput)
{
    try {
        // Use a prepared statement to prevent SQL injection
        $query = "SELECT invoices.*, clients.FirstName, clients.LastName FROM invoices
                  JOIN clients ON invoices.ClientID = clients.ClientID
                  WHERE FirstName LIKE :searchInput 
                     OR LastName LIKE :searchInput 
                     OR InvoiceNumber LIKE :searchInput";
        $statement = $connect->prepare($query);
        $statement->bindValue(':searchInput', '%' . $searchInput . '%', PDO::PARAM_STR);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {
        // Handle the exception as needed
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function searchProductsData($connect, $searchInput)
{
    try {
        // Use a prepared statement to prevent SQL injection
        $query = "SELECT sales.*, clients.FirstName, clients.LastName FROM sales
                  JOIN clients ON sales.ClientID = clients.ClientID
                  WHERE FirstName LIKE :searchInput 
                     OR LastName LIKE :searchInput 
                     OR PaymentStatus LIKE :searchInput";
        $statement = $connect->prepare($query);
        $statement->bindValue(':searchInput', '%' . $searchInput . '%', PDO::PARAM_STR);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {
        // Handle the exception as needed
        echo "Error: " . $e->getMessage();
        return false;
    }
}






function searchServicesandPaymentsData($connect, $searchInput)
{
    try {
        // Use a prepared statement to prevent SQL injection
        $query = "(SELECT 
        'Product' AS type,
        sales.InvoiceNumber,
        sales.Total AS amount,
        sales.PaymentStatus AS status,
        sales.SaleDate AS PaymentDate,
        paymentoptions.PaymentOptionName
    FROM sales
    LEFT JOIN paymentoptions ON sales.PaymentOptionID = paymentoptions.PaymentOptionID
    WHERE sales.PaymentStatus LIKE :searchInput 
        OR sales.InvoiceNumber LIKE :searchInput 
        OR sales.Total LIKE :searchInput)
    UNION
    (SELECT 
        'Plan' AS type,
        payments.InvoiceNumber,
        payments.PaymentAmount,
        payments.PaymentStatus,
        payments.PaymentDate,
        paymentoptions.PaymentOptionName
    FROM payments
    LEFT JOIN paymentoptions ON payments.PaymentOptionID = paymentoptions.PaymentOptionID
    WHERE payments.PaymentStatus LIKE :searchInput 
        OR payments.InvoiceNumber LIKE :searchInput 
        OR payments.PaymentAmount LIKE :searchInput 
        OR paymentoptions.PaymentOptionName LIKE :searchInput)
    ORDER BY PaymentDate DESC
    ";

        $statement = $connect->prepare($query);
        $statement->bindValue(':searchInput', '%' . $searchInput . '%', PDO::PARAM_STR);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {
        // Handle the exception as needed
        echo "Error: " . $e->getMessage();
        return false;
    }
}











function getInvoicesByClientID($connect, $clientID)
{
    try {
        $query = "SELECT invoices.*, clients.FirstName, clients.LastName 
                  FROM invoices 
                  INNER JOIN clients ON invoices.ClientID = clients.ClientID 
                  WHERE invoices.ClientID = :clientID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientID', $clientID);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}





function deleteClientInvoices($clientId, $connect)
{
    try {
        // Prepare SQL statement to delete invoices associated with the client
        $query = "DELETE FROM invoices WHERE ClientID = :clientId";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);

        // Execute the query
        $statement->execute();

        // Return true if the deletion is successful
        return true;
    } catch (PDOException $e) {
        // Handle the exception
        echo "Error deleting client invoices: " . $e->getMessage();
        return false;
    }
}



function getTotalIncomeByInvoiceStatus($connect)
{
    try {
        $query = "SELECT
                    SUM(CASE WHEN Status = 'Paid' THEN TotalAmount ELSE 0 END) AS total_paid,
                    SUM(CASE WHEN Status = 'Pending' THEN TotalAmount ELSE 0 END) AS total_pending,
                    SUM(CASE WHEN Status = 'Cancelled' THEN TotalAmount ELSE 0 END) AS total_cancelled,
                    SUM(CASE WHEN Status = 'Partially Paid' THEN TotalAmount ELSE 0 END) AS total_partially_paid
                  FROM invoices";

        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getTotalIncomeBySalesStatus($connect)
{
    try {
        $query = "SELECT
                    SUM(CASE WHEN PaymentStatus = 'Paid' THEN Total ELSE 0 END) AS total_paid,
                    SUM(CASE WHEN PaymentStatus = 'Pending' THEN Total ELSE 0 END) AS total_pending,
                    SUM(CASE WHEN PaymentStatus = 'Cancelled' THEN Total ELSE 0 END) AS total_cancelled,
                    SUM(CASE WHEN PaymentStatus = 'Partially Paid' THEN Total ELSE 0 END) AS total_partially_paid
                  FROM sales";

        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
