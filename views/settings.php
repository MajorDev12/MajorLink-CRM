<?php
require_once "../controllers/session_Config.php";
require_once  '../database/pdo.php';
require_once  '../modals/addPlan_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>

<style>
    .content-container {
        width: 100%;
        height: 100%;
        /* background-color: var(--grey); */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .setting {
        margin: 5%;
        width: 70%;
        min-height: 10vh;
        background-color: var(--light);
        padding: 20px;
        border-bottom: 2px solid var(--grey);
        border-left: 1px solid var(--grey);
        border-bottom-left-radius: 10px;
    }

    .actions {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .actions button {
        border: none;
        outline: none;
    }

    .actions button a {
        background-color: var(--light);
        padding: 3px 5px;
        border: 1px solid var(--light-blue);
        border-radius: 5px;
        transition: .3s;
    }

    .actions button a:hover {
        background-color: var(--blue);
        color: var(--light);
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
                <h1>Hi, <?= $_SESSION['Username']; ?></h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Settings</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom">
                    <h3>Settings</h3>
                </div>

                <div class="content-container">
                    <!-- <div class="setting shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                        <h5>Change Currency</h5>
                        <p>Choose your prefered currency from 168 different international currencies</p>
                        <div class="actions">
                            <button type="button"><a href="changeCurrency.php">Setup</a></button>
                        </div>
                    </div> -->


                    <div class="setting shadow-sm p-3 mb-2 rounded">
                        <h5>Email Templates</h5>
                        <p>Create and send your desired message here</p>
                        <div class="actions">
                            <button type="button"><a href="Emailtemplate.php">Setup</a></button>
                        </div>
                    </div>


                    <div class="setting shadow-sm p-3 mb-2 rounded">
                        <h5>Sms Templates</h5>
                        <p>Create and send your desired message here</p>
                        <div class="actions">
                            <button type="button"><a href="smsTemplate.php">Setup</a></button>
                        </div>
                    </div>


                    <div class="setting shadow-sm p-3 mb-2 rounded">
                        <h5>Export Data</h5>
                        <p>Download customer details, payments, invoices and more</p>
                        <div class="actions">
                            <button type="button"><a href="export.php">Setup</a></button>
                        </div>
                    </div>

                    <div class="setting shadow-sm p-3 mb-2 rounded">
                        <h5>System Setup</h5>
                        <p>modify the country, timezone, currencies and more</p>
                        <div class="actions">
                            <button type="button"><a href="system.php">Setup</a></button>
                        </div>
                    </div>


                    <div class="setting shadow-sm p-3 mb-2 rounded">
                        <h5>Company Settings</h5>
                        <p>modify the name, website, Email, Logo and other related company details.</p>
                        <div class="actions">
                            <button type="button"><a href="companySetting.php">Setup</a></button>
                        </div>
                    </div>

                </div>

            </div>
            <?php require_once "../views/footer.php"; ?>