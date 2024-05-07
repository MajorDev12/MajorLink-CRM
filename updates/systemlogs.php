<?php
require_once "../controllers/session_Config.php";
require_once  '../database/pdo.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/getSms_mod.php';




$connect = connectToDatabase($host, $dbname, $username, $password);
$texts = getSmsTemplate($connect);
// $template = getEmailTemplateById($connect, $templateID);
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>
<style>
    .content .header {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .main-content .content table {
        width: 100%;
        border-collapse: collapse;
    }

    .main-content .content table thead {
        background-color: var(--blue);
        color: var(--light);
    }

    .main-content .content table th {
        padding: 12px;
        font-size: 13px;
        text-align: center;
        border-bottom: 1px solid var(--grey);
        color: #F9F9F9;
    }

    .main-content .content table td {
        padding: 10px 0;
        text-align: center;
    }

    .main-content .content table td .icon {
        background-color: var(--blue);
        border-radius: 5px;
        padding: 4px;
        cursor: pointer;
    }

    .main-content .content table tbody tr:hover {
        background: var(--grey);
    }

    .main-content .content .tablenav {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .main-content .content .tablenav p {
        display: flex;
        justify-content: start;
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
                        <a class="active" href="#">System Logs</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">


            <div class="content">

                <table class="mt-5">
                    <thead id="thead">
                        <tr>
                            <th>Timestamp</th>
                            <th>Category</th>
                            <!-- <th style="text-align:center">Name</th> -->
                            <th style="text-align:center">Message</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="tableBody">

                        <?php $counter = 1; ?>
                        <?php if ($texts) : ?>
                            <?php foreach ($texts as $index => $text) : ?>
                                <tr>
                                    <td class="index pe-3"><?= $index + 1; ?></td>
                                    <td class="pe-5"><?= $text['Category']; ?></td>

                                    <td style="text-align:left; width: 70%;" class="pe-3"><?= $text['Body']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <?php echo '<tr><td colspan="8" style="text-center"> No Data Yet</td></tr>'; ?>
                        <?php endif; ?>



                    </tbody>
                </table>



            </div>

            <?php require_once "footer.php"; ?>