<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/getClient_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>
<?php require_once "header.php"; ?>


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
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="main.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">List Customers</a>
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
            <div class="clienttable-view">
                <div class="order">

                    <div class="head">
                        <h3>Customers List</h3>
                        <i class='bx bx-search'></i>
                        <i class='bx bxs-printer'></i>
                        <i class='bx bxs-spreadsheet'></i>
                        <i class='bx bx-filter'></i>
                    </div>


                    <table>
                        <thead>
                            <tr>
                                <th>ClientID</th>
                                <th>Username</th>
                                <th>Area</th>
                                <th>SubArea</th>
                                <th>Expire</th>
                                <th>Current Plan</th>
                                <th>Status</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $clientData = getClientData($connect); ?>
                            <?php foreach ($clientData as $client) : ?>
                                <tr>
                                    <td class=""><?php echo $client['ClientID']; ?></td>
                                    <td class=""><?php echo $client['FirstName'] . ' ' . $client['LastName']; ?></td>
                                    <td><span class=""><?php echo $client['Area']; ?></span></td>
                                    <td><span class=""><?php echo $client['SubArea']; ?></span></td>
                                    <td><span class=""><?php echo $client['ExpireDate']; ?></span></td>
                                    <td><span class=""><?php echo $client['Plan']; ?></span></td>
                                    <?php echo "<td><span class=''>" . ($client['ActiveStatus'] == 1 ? 'Active' : 'Inactive') . "</span></td>"; ?>
                                    <td>


                                        <!-- Update the onclick event to trigger the form submission -->
                                        <a href="viewSingleUser.php?id=<?= $client['ClientID'] ?>" class="btn btn-primary me-2">View</a>
                                        <a href="#" data-client-id="<?= $client['ClientID'] ?>" onclick="deleteClient()" class="btn btn-secondary">Del</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php require_once "footer.php"; ?>