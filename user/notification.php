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
require_once  '../modals/notification_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
$clientID = $_SESSION['clientID'];
$messages = getMessagesByClientId($connect, $clientID);


?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>

<style>
    .messageTitle {
        width: 70vw;
        border: none;
        padding: 2px;
        border-top: 5px solid var(--orange);
        border-left: 5px solid var(--orange);
        border-top-left-radius: 10px;
        text-align: left;
    }

    .trans {
        width: 70vw;
        border: none;
        padding: 2px;
        border-top: 5px solid var(--green);
        border-left: 5px solid var(--green);
        border-top-left-radius: 10px;
        text-align: left;
    }

    .main-content .content table {
        width: 100%;
        border-collapse: collapse;
    }


    .main-content .content table th {
        padding-bottom: 12px;
        font-size: 13px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
    }

    .main-content .content table td {
        padding: 16px 0;
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
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Notifications</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-4 border-bottom">
                    <h3>All Notifications</h3>
                </div>

                <table class="mt-5">
                    <thead class="tablehead">
                        <tr>
                            <th>#</th>
                            <th>Message Type</th>
                            <th style="text-align: center;">Message</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; ?>
                        <?php if ($messages) : ?>
                            <?php foreach ($messages as $index => $message) : ?>
                                <tr>
                                    <td class="index pe-3"><?= $index + 1;  ?></td>
                                    <td class=""><?php echo $message['MessageType']; ?></td>
                                    <td><span class=""><?php echo $message['MessageContent']; ?></span></td>
                                    <td><span class=""><?php echo $message['Timestamp']; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <?php echo '  
                             <tr>
                                <td colspan="8" style="text-center"> No Data Yet</td>
                            </tr>'; ?>
                        <?php endif; ?>
                    </tbody>
                </table>


            </div>








            <?php require_once "../views/footer.php"; ?>