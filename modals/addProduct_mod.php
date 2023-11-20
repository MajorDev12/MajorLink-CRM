<?php

// function sanitizeImage(&$errorMsg)
// {


//     if ($_FILES['ProductImage']['error'] !== UPLOAD_ERR_OK) {
//         // Handle the error (e.g., display an error message or log it)
//         $errors[] = 'Image upload failed.';
//     }
//     $allowedTypes = ['image/jpeg', 'image/png'];
//     $maxFileSize = 5 * 1024 * 1024; // 5 MB

//     if (!in_array($_FILES['ProductImage']['type'], $allowedTypes) || $_FILES['ProductImage']['size'] > $maxFileSize) {
//         // Handle invalid file type or size
//         $errors[] = 'Invalid image type or size.';
//     }

//     $originalFilename = $_FILES['ProductImage']['name'];
//     $uniqueFilename = uniqid() . '_' . $originalFilename;

//     $uploadDirectory = '/path/to/upload/directory/';
//     $destination = $uploadDirectory . $uniqueFilename;

//     if (move_uploaded_file($_FILES['ProductImage']['tmp_name'], $destination)) {
//         // Image upload successful
//     } else {
//         // Handle the move_uploaded_file error
//         $errors[] = 'Image upload failed.';
//     }
// }


function  insertProductData($name, $price, $notes, $connect)
{
    try {
        $query = "INSERT INTO products (ProductName, Price, Description) VALUES (:name, :price, :notes)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':name', $name, PDO::PARAM_STR);
        $statement->bindParam(':price', $price, PDO::PARAM_INT);
        $statement->bindParam(':notes', $notes, PDO::PARAM_STR);
        // $statement->bindParam(':image', $image, PDO::PARAM_STR);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getProductData($connect)
{
    try {
        $query = "SELECT ProductID, ProductName, Price, Description FROM products";
        $statement = $connect->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
