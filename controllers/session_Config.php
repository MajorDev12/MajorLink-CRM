<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['adminID']) || !isset($_SESSION['Username'])) {
    // Redirect to the login page
    header("location: login.php");
    exit();
}
