<?php
require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["expenseDate"], $_POST["expenseSelected"], $_POST["amountSpent"], $_POST["methodSelected"])) {
        sleep(1);
        require_once  '../database/pdo.php';
        require_once  '../modals/addExpense_mod.php';


        $connect  = connectToDatabase($host, $dbname, $username, $password);

        $error = '';
        $expenseDate = $_POST["expenseDate"];
        $expenseSelected = $_POST["expenseSelected"];
        $amountSpent = $_POST["amountSpent"];
        $methodSelected = $_POST["methodSelected"];
        $expenseDescription = $_FILES["expenseDescription"] ?? '';
        $uploadedFilePath = '';

        // Check if the file was uploaded without errors
        if (isset($_FILES["PaymentRecieptFile"]) && $_FILES["PaymentRecieptFile"]["error"] == UPLOAD_ERR_OK) {
            // File information
            $fileName = $_FILES["PaymentRecieptFile"]["name"];
            $fileSize = $_FILES["PaymentRecieptFile"]["size"];
            $fileTmpName = $_FILES["PaymentRecieptFile"]["tmp_name"];
            $fileType = $_FILES["PaymentRecieptFile"]["type"];

            // Valid file types
            $allowedTypes = array("image/jpeg", "image/png", "application/pdf"); // Example: Allow only JPEG, PNG, and PDF files

            // Maximum file size (in bytes)
            $maxFileSize = 5 * 1024 * 1024; // Example: 5 MB

            // Check if the file type is allowed
            if (!in_array($fileType, $allowedTypes)) {
                // File type is not allowed
                $error = "Error: Only JPEG, PNG, and PDF files are allowed.";
            } elseif ($fileSize > $maxFileSize) {
                // Check if the file size exceeds the maximum allowed size
                $error = "Error: File size exceeds the maximum allowed size.";
            } else {
                // Perform additional security checks (e.g., check file contents, file name)
                // Move the uploaded file to the desired location
                $uploadDirectory = "../img/uploads/";
                $uploadedFilePath = $uploadDirectory . basename($fileName);
                if (move_uploaded_file($fileTmpName, $uploadedFilePath)) {
                    $error = "File uploaded successfully.";
                    // Proceed with further processing if needed
                } else {
                    $error = "Error: Failed to upload file.";
                }
            }
        }

        $updated = addExpense($connect, $expenseDate, $expenseSelected, $amountSpent, $methodSelected, $expenseDescription, $uploadedFilePath);


        $response = [
            'success' => $updated ? true : false,
            'message' => $updated ? 'Added Successfully' : 'Something went wrong'
        ];

        echo json_encode($response);
        exit();
    } else {
        $response = [
            'success' => false,
            'message' => 'Some data is missing'
        ];

        echo json_encode($response);
        exit();
    }
}
