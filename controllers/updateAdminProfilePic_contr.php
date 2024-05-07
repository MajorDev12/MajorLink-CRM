<?php
require_once "../controllers/session_Config.php";
require_once  '../database/pdo.php';

$connect  = connectToDatabase($host, $dbname, $username, $password);


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_FILES["fileImage"]["name"]) && isset($_POST["id"])) {
        $adminID = $_POST["id"];

        $src = $_FILES["fileImage"]["tmp_name"];
        $imageName = uniqid() . $_FILES["fileImage"]["name"];

        $target = "../img/" . $imageName;

        move_uploaded_file($src, $target);

        try {
            // Assuming $connect is your PDO connection
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Use prepared statement to prevent SQL injection
            $query = "UPDATE admins SET ProfilePictureURL = :imageName WHERE AdminID = :adminID";
            $stmt = $connect->prepare($query);
            $stmt->bindParam(':imageName', $imageName);
            $stmt->bindParam(':adminID', $adminID);
            $stmt->execute();

            // Respond with a success message
            echo json_encode(["success" => true, "message" => "Profile picture updated successfully"]);
        } catch (PDOException $e) {
            // Respond with an error message
            echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
        } finally {
            // Close the database connection
            $connect = null;
        }
    }
}
