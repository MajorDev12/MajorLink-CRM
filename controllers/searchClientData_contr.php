<?php
require_once "session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["inputValue"])) {
        require_once '../database/pdo.php';
        require_once '../modals/addClient_mod.php';
        require_once '../modals/validate_mod.php';
        $connect = connectToDatabase($host, $dbname, $username, $password);

        $searchInput = inputValidation($_POST['inputValue']);
        $results = searchClientsData($connect, $searchInput);

        if ($results !== false) {

            // Counter for indexing
            $index = 1;
            $table = '';

            foreach ($results as $result) {
                $table .= '
                    <tr>
                        <td class="index pe-3">' . $index . '</td>
                        <td>' . $result['FirstName'] . ' ' . $result['LastName'] . '</td>
                        <td>' . $result['AreaName'] . '</td>
                        <td>' . $result['SubAreaName'] . '</td>
                        <td>' . date("d F Y", strtotime($result['ExpireDate'])) . '</td>
                        <td>' . $result['Volume'] . '</td>
                        <td><span>' . ($result['ActiveStatus'] == 1 ? 'Active' : 'Inactive') . '</span></td>
                        <td>
                            <a href="viewSingleUser.php?id=' . $result['ClientID'] . '" class="btn btn-primary me-2">View</a>
                            <a href="#" data-client-id="' . $result['ClientID'] . '" onclick="deleteClient()" class="btn btn-secondary">Del</a>
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
