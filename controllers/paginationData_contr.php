<?php

require_once "session_Config.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['page']) && $_GET['page']) {
        require_once '../database/pdo.php';
        require_once '../modals/paginationData_mod.php';

        $connect = connectToDatabase($host, $dbname, $username, $password);

        $limit = 10;
        $start = 0;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $limit;

        $clientData = clientDataPage($connect, $start, $limit);
        $totalRecords = getTotalClientCount($connect); // Make sure this function exists

        echo json_encode([
            'success' => true,
            'clientData' => $clientData,
            'totalRecords' => $totalRecords,
            'totalPages' => ceil($totalRecords / $limit)
        ]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'No page found']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Something went wrong']);
    exit();
}
