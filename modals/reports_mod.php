<?php

function getBestSellingArea($connect)
{
    try {
        $query = "SELECT clients.AreaID, areas.AreaName, SUM(invoices.TotalAmount) AS total_income 
                  FROM invoices 
                  JOIN clients ON invoices.ClientID = clients.ClientID 
                  JOIN areas ON clients.AreaID = areas.AreaID
                  WHERE invoices.Status IN ('Paid', 'Partially Paid') 
                  GROUP BY clients.AreaID 
                  ORDER BY total_income DESC 
                  LIMIT 1";
        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getBestSellingPlan($connect)
{
    try {
        // SQL query to calculate total income for each plan
        $query = "SELECT plans.PlanID, plans.Name, plans.Volume, SUM(invoices.TotalAmount) AS total_income 
                  FROM invoices 
                  JOIN clients ON invoices.ClientID = clients.ClientID
                  JOIN plans ON clients.PlanID = plans.PlanID 
                  WHERE invoices.Status IN ('Paid', 'Partially Paid') 
                  GROUP BY clients.PlanID 
                  ORDER BY total_income DESC 
                  LIMIT 1";

        // Prepare and execute the query
        $statement = $connect->prepare($query);
        $statement->execute();

        // Fetch the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result; // Return the best-selling plan
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function getBestSellingProduct($connect)
{
    try {
        $query = "SELECT sales.ProductID, products.ProductName, SUM(sales.Total) AS total_income 
                  FROM sales 
                  JOIN products ON sales.ProductID = products.ProductID 
                  WHERE sales.PaymentStatus IN ('Paid', 'Partially Paid') 
                  GROUP BY sales.ProductID 
                  ORDER BY total_income DESC 
                  LIMIT 1";
        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getBestMonthThisYear($connect)
{
    try {
        // Get the current year
        $currentYear = date('Y');

        // SQL query to calculate total income for each month of the current year
        $query = "SELECT MONTH(paymentDate) AS month_number, 
                         MONTHNAME(paymentDate) AS month_name, 
                         SUM(TotalAmount) AS total_income 
                  FROM (
                      SELECT paymentDate, TotalAmount FROM invoices
                      UNION ALL
                      SELECT SaleDate, Total FROM sales
                  ) AS combined_data
                  WHERE YEAR(paymentDate) = :year
                --   AND Status IN ('Paid', 'Partially Paid')
                  GROUP BY MONTH(paymentDate), MONTHNAME(paymentDate)
                  ORDER BY total_income DESC 
                  LIMIT 1";

        // Prepare and execute the query
        $statement = $connect->prepare($query);
        $statement->bindValue(':year', $currentYear, PDO::PARAM_INT);
        $statement->execute();

        // Fetch the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result; // Return the best month of the current year
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}






function getBestYear($connect)
{
    try {
        // SQL query to calculate total income for each year
        $query = "SELECT YEAR(paymentDate) AS year, 
                         SUM(TotalAmount) AS total_income 
                  FROM (
                      SELECT paymentDate, TotalAmount FROM invoices
                      UNION ALL
                      SELECT SaleDate, Total FROM sales
                  ) AS combined_data
                  GROUP BY YEAR(paymentDate) 
                  ORDER BY total_income DESC 
                  LIMIT 1";

        // Prepare and execute the query
        $statement = $connect->prepare($query);
        $statement->execute();

        // Fetch the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result; // Return the best year
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalIncomeByDate($connect, $date)
{
    try {
        // SQL query to calculate total income for the given date
        $query = "SELECT SUM(total_amount) AS total_income 
                  FROM (
                      SELECT TotalAmount AS total_amount FROM invoices WHERE DATE(paymentDate) = :date
                      UNION ALL
                      SELECT Total AS total_amount FROM sales WHERE DATE(saleDate) = :date
                  ) AS combined_data";

        // Prepare and execute the query
        $statement = $connect->prepare($query);
        $statement->bindValue(':date', $date, PDO::PARAM_STR);
        $statement->execute();

        // Fetch the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result; // Return the total income for the given date
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalIncomeByYearMonth($connect, $year, $month)
{
    try {
        // SQL query to calculate total income for the given year and month
        $query = "SELECT SUM(total_amount) AS total_income 
                  FROM (
                      SELECT TotalAmount AS total_amount FROM invoices WHERE YEAR(paymentDate) = :year AND MONTH(paymentDate) = :month
                      UNION ALL
                      SELECT Total AS total_amount FROM sales WHERE YEAR(saleDate) = :year AND MONTH(saleDate) = :month
                  ) AS combined_data";

        // Prepare and execute the query
        $statement = $connect->prepare($query);
        $statement->bindValue(':year', $year, PDO::PARAM_INT);
        $statement->bindValue(':month', $month, PDO::PARAM_INT);
        $statement->execute();

        // Fetch the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result; // Return the total income for the given year and month
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalIncomeLastThreeMonths($connect)
{
    try {
        // Get the current month and year
        $currentMonth = (int)date('n');
        $currentYear = (int)date('Y');

        // Calculate the months for the last three months
        $lastThreeMonths = array();
        for ($i = 0; $i < 3; $i++) {
            $month = $currentMonth - $i;
            $year = $currentYear;
            if ($month <= 0) {
                $month += 12;
                $year--;
            }
            $lastThreeMonths[] = array('year' => $year, 'month' => $month);
        }

        // Initialize an array to store total income for each month
        $totalIncomeByMonth = array();

        // Query the database to get the total income for each of the last three months
        foreach ($lastThreeMonths as $monthData) {
            $year = $monthData['year'];
            $month = $monthData['month'];

            $query = "SELECT SUM(total_amount) AS total_income 
                      FROM (
                          SELECT TotalAmount AS total_amount FROM invoices WHERE YEAR(paymentDate) = :year AND MONTH(paymentDate) = :month
                          UNION ALL
                          SELECT Total AS total_amount FROM sales WHERE YEAR(saleDate) = :year AND MONTH(saleDate) = :month
                      ) AS combined_data";

            $statement = $connect->prepare($query);
            $statement->bindParam(':year', $year, PDO::PARAM_INT);
            $statement->bindParam(':month', $month, PDO::PARAM_INT);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            // Store the total income for the current month
            $totalIncomeByMonth[$year . '-' . $month] = $result['total_income'] ?? 0;
        }

        return $totalIncomeByMonth;
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}






function getTotalIncomeLastSixMonths($connect)
{
    try {
        // Get the current month and year
        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');

        // Calculate the months for the last six months
        $lastSixMonths = array();
        for ($i = 0; $i < 6; $i++) {
            $month = $currentMonth - $i;
            $year = $currentYear;
            if ($month <= 0) {
                $month += 12;
                $year--;
            }
            $lastSixMonths[] = array('year' => $year, 'month' => $month);
        }

        // Initialize an array to store total income for each month
        $totalIncomeByMonth = array();

        // Query the database to get the total income for each of the last six months
        foreach ($lastSixMonths as $monthData) {
            $year = $monthData['year'];
            $month = $monthData['month'];

            $query = "SELECT SUM(total_amount) AS total_income 
                      FROM (
                          SELECT TotalAmount AS total_amount FROM invoices WHERE YEAR(paymentDate) = :year AND MONTH(paymentDate) = :month
                          UNION ALL
                          SELECT Total AS total_amount FROM sales WHERE YEAR(saleDate) = :year AND MONTH(saleDate) = :month
                      ) AS combined_data";

            $statement = $connect->prepare($query);
            $statement->bindParam(':year', $year, PDO::PARAM_INT);
            $statement->bindParam(':month', $month, PDO::PARAM_INT);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            // Store the total income for the current month
            $totalIncomeByMonth[$year . '-' . $month] = $result['total_income'] ?? 0;
        }

        return $totalIncomeByMonth;
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalIncomeByYear($connect, $year)
{
    try {
        // SQL query to calculate total income for the given year
        $query = "SELECT SUM(total_amount) AS total_income 
                  FROM (
                      SELECT TotalAmount AS total_amount FROM invoices WHERE YEAR(paymentDate) = :year
                      UNION ALL
                      SELECT Total AS total_amount FROM sales WHERE YEAR(saleDate) = :year
                  ) AS combined_data";

        // Prepare and execute the query
        $statement = $connect->prepare($query);
        $statement->bindValue(':year', $year, PDO::PARAM_INT);
        $statement->execute();

        // Fetch the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result; // Return the total income for the given year
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function getBestYearInRange($connect)
{
    try {
        // Get the current year
        $currentYear = date('Y');

        // Initialize variables to store the best year and its total income
        $bestYear = '';
        $highestIncome = 0;

        // Iterate over the range of years from 2010 to the current year
        for ($year = 2010; $year <= $currentYear; $year++) {
            // Get the total income for the current year
            $totalIncome = getTotalIncomeByYear($connect, $year);

            // Check if the total income for the current year is higher than the highest income so far
            if ($totalIncome && $totalIncome['total_income'] > $highestIncome) {
                $bestYear = $year;
                $highestIncome = $totalIncome['total_income'];
            }
        }

        // Return the best year and its total income
        return array('year' => $bestYear, 'total_income' => $highestIncome);
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getWorstYearInRange($connect)
{
    try {
        // Get the current year
        $currentYear = date('Y');

        // Initialize variables to store the worst year and its total income
        $worstYear = '';
        $lowestIncome = PHP_INT_MAX;

        // Iterate over the range of years from 2010 to the current year
        for ($year = 2010; $year <= $currentYear; $year++) {
            // Get the total income for the current year
            $totalIncome = getTotalIncomeByYear($connect, $year);

            // Check if the total income for the current year is lower than the lowest income so far
            if ($totalIncome && $totalIncome['total_income'] < $lowestIncome) {
                $worstYear = $year;
                $lowestIncome = $totalIncome['total_income'];
            }
        }

        // Return the worst year and its total income
        return array('year' => $worstYear, 'total_income' => $lowestIncome);
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalIncomeByArea($connect, $areaID)
{
    try {
        $query = "SELECT areas.AreaName, SUM(total_amount) AS total_income 
                  FROM (
                      SELECT TotalAmount AS total_amount, clients.AreaID FROM invoices 
                      JOIN clients ON invoices.ClientID = clients.ClientID
                      WHERE clients.AreaID = :areaID AND invoices.Status IN ('Paid', 'Partially Paid')
                      UNION ALL
                      SELECT Total AS total_amount, clients.AreaID FROM sales 
                      JOIN clients ON sales.ClientID = clients.ClientID
                      WHERE clients.AreaID = :areaID AND sales.PaymentStatus IN ('Paid', 'Partially Paid')
                  ) AS combined_data
                  JOIN areas ON combined_data.AreaID = areas.AreaID";

        $statement = $connect->prepare($query);
        $statement->bindValue(':areaID', $areaID, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function getTotalIncomeBySubArea($connect, $subAreaID)
{
    try {
        $query = "SELECT subareas.SubAreaName, SUM(total_amount) AS total_income 
                  FROM (
                      SELECT TotalAmount AS total_amount, clients.SubAreaID FROM invoices 
                      JOIN clients ON invoices.ClientID = clients.ClientID
                      WHERE clients.SubAreaID = :subAreaID AND invoices.Status IN ('Paid', 'Partially Paid')
                      UNION ALL
                      SELECT Total AS total_amount, clients.SubAreaID FROM sales 
                      JOIN clients ON sales.ClientID = clients.ClientID
                      WHERE clients.SubAreaID = :subAreaID AND sales.PaymentStatus IN ('Paid', 'Partially Paid')
                  ) AS combined_data
                  JOIN subareas ON combined_data.SubAreaID = subareas.SubAreaID";

        $statement = $connect->prepare($query);
        $statement->bindValue(':subAreaID', $subAreaID, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}













// function getHighestTotalIncomeArea($connect)
// {
//     try {
//         $query = "SELECT areas.AreaName, MAX(total_income) AS highest_income
//                   FROM (
//                       SELECT clients.AreaID, SUM(invoices.TotalAmount) AS total_income 
//                       FROM invoices 
//                       JOIN clients ON invoices.ClientID = clients.ClientID 
//                       WHERE invoices.Status IN ('Paid', 'Partially Paid') 
//                       GROUP BY clients.AreaID
//                       UNION ALL
//                       SELECT clients.AreaID, SUM(sales.Total) AS total_income 
//                       FROM sales 
//                       JOIN clients ON sales.ClientID = clients.ClientID 
//                       WHERE sales.PaymentStatus IN ('Paid', 'Partially Paid') 
//                       GROUP BY clients.AreaID
//                   ) AS income_by_area
//                   JOIN areas ON income_by_area.AreaID = areas.AreaID
//                   GROUP BY income_by_area.AreaID 
//                   ORDER BY highest_income DESC 
//                   LIMIT 1";

//         $statement = $connect->prepare($query);
//         $statement->execute();

//         $result = $statement->fetch(PDO::FETCH_ASSOC);

//         return $result;
//     } catch (PDOException $e) {
//         echo "Error: " . $e->getMessage();
//         return false;
//     }
// }

// function getLowestTotalIncomeArea($connect)
// {
//     try {
//         $query = "SELECT areas.AreaName, MIN(total_income) AS lowest_income
//                   FROM (
//                       SELECT clients.AreaID, SUM(invoices.TotalAmount) AS total_income 
//                       FROM invoices 
//                       JOIN clients ON invoices.ClientID = clients.ClientID 
//                       WHERE invoices.Status IN ('Paid', 'Partially Paid') 
//                       GROUP BY clients.AreaID
//                       UNION ALL
//                       SELECT clients.AreaID, SUM(sales.Total) AS total_income 
//                       FROM sales 
//                       JOIN clients ON sales.ClientID = clients.ClientID 
//                       WHERE sales.PaymentStatus IN ('Paid', 'Partially Paid') 
//                       GROUP BY clients.AreaID
//                   ) AS income_by_area
//                   JOIN areas ON income_by_area.AreaID = areas.AreaID
//                   GROUP BY income_by_area.AreaID 
//                   ORDER BY lowest_income ASC 
//                   LIMIT 1";

//         $statement = $connect->prepare($query);
//         $statement->execute();

//         $result = $statement->fetch(PDO::FETCH_ASSOC);

//         return $result;
//     } catch (PDOException $e) {
//         echo "Error: " . $e->getMessage();
//         return false;
//     }
// }






function getTotalIncomeOfAllAreas($connect)
{
    try {
        $query = "SELECT areas.AreaName, COALESCE(SUM(total_income), 0) AS totalIncome
                  FROM areas
                  LEFT JOIN (
                      SELECT clients.AreaID, SUM(invoices.TotalAmount) AS total_income 
                      FROM invoices 
                      JOIN clients ON invoices.ClientID = clients.ClientID 
                      WHERE invoices.Status IN ('Paid', 'Partially Paid') 
                      GROUP BY clients.AreaID
                      UNION ALL
                      SELECT clients.AreaID, SUM(sales.Total) AS total_income 
                      FROM sales 
                      JOIN clients ON sales.ClientID = clients.ClientID 
                      WHERE sales.PaymentStatus IN ('Paid', 'Partially Paid') 
                      GROUP BY clients.AreaID
                  ) AS income_by_area ON areas.AreaID = income_by_area.AreaID
                  GROUP BY areas.AreaID";

        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalIncomeOfAllSubAreas($connect)
{
    try {
        $query = "SELECT subareas.SubAreaName, COALESCE(SUM(total_income), 0) AS totalIncome
                  FROM subareas
                  LEFT JOIN (
                      SELECT clients.SubAreaID, SUM(invoices.TotalAmount) AS total_income 
                      FROM invoices 
                      JOIN clients ON invoices.ClientID = clients.ClientID 
                      WHERE invoices.Status IN ('Paid', 'Partially Paid') 
                      GROUP BY clients.SubAreaID
                      UNION ALL
                      SELECT clients.SubAreaID, SUM(sales.Total) AS total_income 
                      FROM sales 
                      JOIN clients ON sales.ClientID = clients.ClientID 
                      WHERE sales.PaymentStatus IN ('Paid', 'Partially Paid') 
                      GROUP BY clients.SubAreaID
                  ) AS income_by_subarea ON subareas.SubAreaID = income_by_subarea.SubAreaID
                  GROUP BY subareas.SubAreaID";

        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function getTotalIncomeByProduct($connect, $productID)
{
    try {
        // Get the product name
        $productNameQuery = "SELECT ProductName FROM products WHERE ProductID = :productID";
        $productNameStatement = $connect->prepare($productNameQuery);
        $productNameStatement->bindValue(':productID', $productID, PDO::PARAM_INT);
        $productNameStatement->execute();
        $productNameResult = $productNameStatement->fetch(PDO::FETCH_ASSOC);

        // Check if there are any sales for the product
        $query = "SELECT COALESCE(SUM(sales.Total), 0) AS total_income 
                  FROM sales 
                  WHERE sales.ProductID = :productID AND sales.PaymentStatus IN ('Paid', 'Partially Paid')";

        $statement = $connect->prepare($query);
        $statement->bindValue(':productID', $productID, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // If there are no sales, return the product name and total income of 0
        if (!$result) {
            return array('ProductName' => $productNameResult['ProductName'], 'total_income' => '0.00');
        }

        // Include the product name in the result
        $result['ProductName'] = $productNameResult['ProductName'];

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalIncomeOfAllProducts($connect)
{
    try {
        $query = "SELECT products.ProductName, COALESCE(SUM(sales.Total), 0) AS totalIncome
                  FROM products
                  LEFT JOIN sales ON products.ProductID = sales.ProductID
                  GROUP BY products.ProductName";

        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function getTotalIncomeByPlan($connect, $planId)
{
    try {
        $query = "SELECT plans.Name, COALESCE(SUM(total_income), 0) AS totalIncome
                  FROM plans
                  LEFT JOIN (
                      SELECT clients.planId, SUM(invoices.TotalAmount) AS total_income 
                      FROM invoices 
                      JOIN clients ON invoices.ClientID = clients.ClientID 
                      WHERE clients.planId = :planId AND invoices.Status IN ('Paid', 'Partially Paid') 
                      GROUP BY clients.planId
                      UNION ALL
                      SELECT clients.planId, SUM(sales.Total) AS total_income 
                      FROM sales 
                      JOIN clients ON sales.ClientID = clients.ClientID 
                      WHERE clients.planId = :planId AND sales.PaymentStatus IN ('Paid', 'Partially Paid') 
                      GROUP BY clients.planId
                  ) AS income_by_plan ON plans.planId = income_by_plan.planId
                  WHERE plans.planId = :planId";

        $statement = $connect->prepare($query);
        $statement->bindValue(':planId', $planId, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}





function getTotalIncomeOfAllPlans($connect)
{
    try {
        $query = "SELECT plans.Name, plans.Volume, COALESCE(SUM(total_income), 0) AS totalIncome
                  FROM plans
                  LEFT JOIN (
                      SELECT clients.planId, SUM(invoices.TotalAmount) AS total_income 
                      FROM invoices 
                      JOIN clients ON invoices.ClientID = clients.ClientID 
                      WHERE invoices.Status IN ('Paid', 'Partially Paid') 
                      GROUP BY clients.planId
                  ) AS income_by_plan ON plans.planId = income_by_plan.planId
                  GROUP BY plans.planId";

        $statement = $connect->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getClientTotalAmount($connect, $clientId)
{
    try {
        // Query to get total sales amount for the client
        $salesQuery = "SELECT clients.FirstName, clients.LastName, COALESCE(SUM(sales.Total), 0) AS totalSales 
                       FROM sales 
                       JOIN clients ON sales.ClientID = clients.ClientID 
                       WHERE clients.ClientID = :clientId 
                       AND sales.PaymentStatus IN ('Paid', 'Partially Paid')";

        $salesStatement = $connect->prepare($salesQuery);
        $salesStatement->bindValue(':clientId', $clientId, PDO::PARAM_INT);
        $salesStatement->execute();
        $salesResult = $salesStatement->fetch(PDO::FETCH_ASSOC);

        // Query to get total invoice amount for the client
        $invoicesQuery = "SELECT clients.FirstName, clients.LastName, COALESCE(SUM(invoices.TotalAmount), 0) AS totalInvoices 
                          FROM invoices 
                          JOIN clients ON invoices.ClientID = clients.ClientID 
                          WHERE clients.ClientID = :clientId 
                          AND invoices.Status IN ('Paid', 'Partially Paid')";

        $invoicesStatement = $connect->prepare($invoicesQuery);
        $invoicesStatement->bindValue(':clientId', $clientId, PDO::PARAM_INT);
        $invoicesStatement->execute();
        $invoicesResult = $invoicesStatement->fetch(PDO::FETCH_ASSOC);

        // Combine the results into a single array
        $result = array(
            'clientName' => $salesResult['FirstName'] . ' ' . $salesResult['LastName'],
            'totalSales' => $salesResult['totalSales'],
            'totalInvoices' => $invoicesResult['totalInvoices']
        );

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}









//      expenses
function getTotalExpenseByType($connect)
{
    try {
        $sql = "SELECT et.ExpenseTypeName, COALESCE(SUM(e.ExpenseAmount), 0) AS TotalExpense
                FROM expensetypes et
                LEFT JOIN expenses e ON et.ExpenseTypeID = e.ExpenseTypeID
                GROUP BY et.ExpenseTypeName";

        $stmt = $connect->prepare($sql);
        $stmt->execute();

        $expensesByType = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $expensesByType[] = $row;
        }

        $stmt->closeCursor();

        return $expensesByType;
    } catch (PDOException $e) {
        // Handle PDO exceptions
        echo "Error: " . $e->getMessage();
        return false;
    }
}





function getTotalExpenseByDate($connect, $expenseDate)
{
    try {
        // Prepare the SQL query
        $sql = "SELECT DATE(ExpenseDate) AS ExpenseDate, COALESCE(SUM(ExpenseAmount), 0) AS TotalExpense
                FROM expenses
                WHERE DATE(ExpenseDate) = :expenseDate";

        // Prepare the statement
        $stmt = $connect->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':expenseDate', $expenseDate, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the cursor
        $stmt->closeCursor();

        return $result;
    } catch (PDOException $e) {
        // Handle PDO exceptions
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalExpenseByMonthYear($connect, $month, $year)
{
    try {
        // Prepare the SQL query
        $sql = "SELECT YEAR(ExpenseDate) AS Year, MONTH(ExpenseDate) AS Month, COALESCE(SUM(ExpenseAmount), 0) AS TotalExpense
                FROM expenses
                WHERE YEAR(ExpenseDate) = :year AND MONTH(ExpenseDate) = :month";

        // Prepare the statement
        $stmt = $connect->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the cursor
        $stmt->closeCursor();

        // Ensure that total expense is not null
        if ($result['TotalExpense'] === null) {
            $result['TotalExpense'] = 0; // Set default value to 0 if total expense is null
        }

        // Convert numeric month to month name
        $monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        if ($result['Month'] != null) {
            $result['Month'] = $monthNames[$result['Month'] - 1]; // Adjust index to match month numbering (0-indexed)
        }


        return $result;
    } catch (PDOException $e) {
        // Handle PDO exceptions
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalExpenseByYear($connect, $year)
{
    try {
        // Prepare the SQL query
        $sql = "SELECT YEAR(ExpenseDate) AS Year, COALESCE(SUM(ExpenseAmount), 0) AS TotalExpense
                FROM expenses
                WHERE YEAR(ExpenseDate) = :year";

        // Prepare the statement
        $stmt = $connect->prepare($sql);

        // Bind parameter
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the cursor
        $stmt->closeCursor();

        // Ensure that total expense is not null
        if ($result['TotalExpense'] === null) {
            $result['TotalExpense'] = 0; // Set default value to 0 if total expense is null
        }

        // Set the year name
        $result['YearName'] = date('Y', strtotime($year));

        return $result;
    } catch (PDOException $e) {
        // Handle PDO exceptions
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getTotalExpenseSummary($connect)
{
    try {
        // Prepare the SQL query
        $sql = "SELECT COALESCE(SUM(ExpenseAmount), 0) AS TotalExpense FROM expenses";

        // Prepare the statement
        $stmt = $connect->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the cursor
        $stmt->closeCursor();

        // Ensure that total expense is not null
        $totalExpense = (float) $result['TotalExpense']; // Explicitly cast to float

        return $totalExpense;
    } catch (PDOException $e) {
        // Handle PDO exceptions
        echo "Error: " . $e->getMessage();
        return false;
    }
}
