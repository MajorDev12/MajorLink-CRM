<?php


// Call the searchData function in your existing code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["inputValue"])) {
        require_once  '../database/pdo.php';
        require_once  '../modals/addInvoice_mod.php';
        require_once  '../modals/validate_mod.php';
        $connect = connectToDatabase($host, $dbname, $username, $password);

        $searchInput = inputValidation($_POST['inputValue']);
        $results = searchData($connect, $searchInput);




        if ($results !== false) {



            // Counter for indexing
            $index = 1;
            $table = '';

            foreach ($results as $result) {
                $table .= '
             <tr>
                 <td class="index pe-3">' . $index . '</td>
                 <td>' . $result['InvoiceNumber'] . '</td>
                 <td>' . $result['FirstName'] . ' ' . $result['LastName'] . '</td>
                 <td>' . $result['TotalAmount'] . '</td>
                 <td>' . $result['StartDate'] . '</td>
                 <td>' . $result['DueDate'] . '</td>
                 <td>' . $result['Status'] . '</td>
                 <td style="text-align:center">
                     <a href="viewInvoice.php?i=' . $result["InvoiceID"] . '&c=' . $result["ClientID"] . '" class="icon view"><img src="../img/eyeIcon.png" alt=""></a>
                     <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php?i=' . $result["InvoiceID"] . '&c=' . $result["ClientID"] . '" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                     <abbr title="print"><a href="../user/printInvoice.php?i=' . $result["InvoiceID"] . '&c=' . $result["ClientID"] . '" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                 </td>
             </tr>
         ';

                // Increment the counter
                $index++;
            }



            // Return the results as JSON
            header('Content-Type: application/json');
            echo json_encode($table);
        } else {
            // Return a response indicating no data
            $response = false;
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
} else {
    // Handle the case where the request method is not POST
    echo json_encode(array('error' => 'Invalid request method'));
}
