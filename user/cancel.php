<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['clientID']) || !isset($_SESSION['FirstName'])) {
    // Redirect to the login page
    header("location: ../views/login.php");
    exit();
}
?>
<?php
require_once  '../database/pdo.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>

<style>
    .content {
        height: 70vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .content h3 {
        color: var(--red);
        font-size: 3em;
        font-weight: 700;
        width: 90%;
    }
</style>
<!-- SIDEBAR -->
<?php require_once "side_nav.php"; ?>
<!-- SIDEBAR -->

<!-- CONTENT -->
<section id="content">
    <!-- TOP-NAVBAR -->
    <?php require_once "top_nav.php"; ?>
    <!-- TOP-NAVBAR -->

    <!-- MAIN -->
    <main>

        <!-- content-container -->
        <div class="main-content">

            <div class="content text-center">
                <h3 class="">Your payment has been cancelled!!!.</h3>

                <a href="index.php">Go back</a>
            </div>



            <?php require_once "../views/footer.php"; ?>