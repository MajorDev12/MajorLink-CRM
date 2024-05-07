<?php


// Call the searchData function in your existing code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["inputValue"])) {
        require_once  '../database/pdo.php';
        require_once  '../modals/addInvoice_mod.php';
        require_once  '../modals/validate_mod.php';
        $connect = connectToDatabase($host, $dbname, $username, $password);

        $searchInput = inputValidation($_POST['inputValue']);
        $results = searchServicesandPaymentsData($connect, $searchInput);


        if ($results !== false) {

            $table = '';
            $paymentStatus = '';

            foreach ($results as $result) {
                $amount = isset($result['PaymentAmount']) ? $result['PaymentAmount'] : $result['amount'];

                // Check if 'PaymentStatus' index exists in the current result
                $paymentStatus = isset($result['PaymentStatus']) ? $result['PaymentStatus'] : $result['status'];


                $table .= '
                    <tr>
                        <td>' . $result['InvoiceNumber'] . '</td>
                        <td>' . $result['PaymentDate'] . '</td>
                        <td>' . ($result['PaymentOptionName'] ?? '') . '</td>
                        <td>' . $amount . '</td>
                        <td>' . $paymentStatus . '</td>
                        <td>' . $result['type'] . '</td>
                    </tr>
                ';
            }





            // Return the results as JSON
            header('Content-Type: application/json');
            echo json_encode($table);
            exit();
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
