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
    .main-content .content {
        display: flex;
        flex-wrap: wrap;
        grid-gap: 24px;
        margin-top: 24px;
        width: 100%;
        color: var(--dark);
        overflow-x: auto;
    }

    .main-content .content>div {
        border-radius: 20px;
        background: var(--light);
        padding: 24px;
        overflow-x: auto;
    }

    .main-content .content .head {
        display: flex;
        align-items: center;
        grid-gap: 16px;
        margin-bottom: 24px;
    }

    .main-content .content .head h3 {
        margin-right: auto;
        font-size: 24px;
        font-weight: 600;
    }

    .main-content .content .head .bx {
        cursor: pointer;
        font-size: 1.5em;
        margin-top: 20px;
    }

    .main-content .content .order {
        flex-grow: 1;
        flex-basis: 500px;
    }

    .main-content .content .order table {
        width: 100%;
        border-collapse: collapse;
    }

    .main-content .content .order table th {
        padding-bottom: 12px;
        font-size: 13px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
    }

    .main-content .content .order table td {
        padding: 16px 0;
    }

    .main-content .content .order table tbody tr:hover {
        background: var(--grey);
    }



    @media screen and (max-width: 920px) {
        .main-content .content .head {
            min-width: 900px;
        }

        .main-content .content .order table {
            min-width: 900px;
        }
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
        <div class="head-title">

            <div class="left">
                <h1>Hi, <?= $_SESSION['FirstName']; ?></h1>
                <!-- <p style="color: var(--dark);">You are running Low!!!</p> -->
                <ul class="breadcrumb">
                    <li>
                        <a href="viewClient.php">List Customers</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">View Client</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- toast -->
        <div id="toast">
            <div class="toast-header">
                <img src="../img/user.png" alt="" width="30px">
                <small id="toast-time">3 secs Ago</small>
            </div>
            <div class="toast-body">
                <h3>Created Successfully Custom Toast Example</h3>
            </div>
        </div>
        <!-- toast -->

        <div id="loader">Loading...</div>
        <!-- content-container -->
        <div class="main-content">
            <div class="content.main">
                <ul class="box-info">
                    <li>
                        <i class='bx bxs-calendar-check'></i>
                        <span class="text">
                            <h3>My Account</h3>

                        </span>
                    </li>
                    <li>
                        <i class='bx bxs-group'></i>
                        <span class="text">
                            <h3>Reports</h3>
                        </span>
                    </li>
                    <li>
                        <i class='bx bxs-group'></i>
                        <span class="text">
                            <h3>Status</h3>
                        </span>
                    </li>
                    <li>
                        <i class='bx bxs-dollar-circle'></i>
                        <span class="text">
                            <h3>Notifications</h3>
                        </span>
                    </li>
                    <li>
                        <i class='bx bxs-calendar-check'></i>
                        <span class="text">
                            <h3>Invoices</h3>
                        </span>
                    </li>
                </ul>
            </div>


            <?php require_once "../views/footer.php"; ?>