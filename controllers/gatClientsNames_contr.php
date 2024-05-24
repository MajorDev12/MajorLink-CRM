<?php
require_once  '../database/pdo.php';

$connect  = connectToDatabase($host, $dbname, $username, $password);

if (!empty($_GET["type"]) && $_GET["type"] == "user_search") {
    $search_term = !empty($_GET["search"]) ? $_GET["search"] : '';

    $sql = "SELECT * FROM clients WHERE FirstName LIKE :search_term OR LastName LIKE :search_term AND status=1 ORDER BY FirstName ASC";
    $stmt = $connect->prepare($sql);
    $stmt->bindValue(':search_term', '%' . $search_term . '%', PDO::PARAM_STR);
    $stmt->execute();

    $userData = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data["ClientID"] = $row["ClientID"];
        $data["FirstName"] = $row["FirstName"];
        $data["LastName"] = $row["LastName"];

        array_push($userData, $data);
    }

    echo json_encode($userData);
}
