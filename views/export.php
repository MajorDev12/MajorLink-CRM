<?php
require_once "../controllers/session_Config.php";
require_once  '../database/pdo.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/addClient_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>

<style>
    .importbody {
        background-color: var(--grey);
        padding: 20px;
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
                        <a class="active" href="settings.php">Settings</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Export data</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">

            <div class="content">
                <div class="mt-4">
                    <div id="importdiv" class="row">
                        <div class="col-md-6">
                            <?php $count = getUsersCount($connect); ?>
                            <div class="importbody">
                                <h5 class="title">Clients Data</h5>
                                <div class="mb-3">
                                    <h3><?= $count; ?></h3>
                                </div>
                                <button type="submit" id="backupClientsButton" class="btn btn-primary">Export</button>
                            </div>
                        </div>




                        <div class="col-md-6">
                            <div class="importbody">
                                <h5 class="title">Backup Data</h5>

                                <div class="mb-3">
                                    <h3></h3>

                                </div>
                                <button type="submit" id="backupButton" class="btn btn-primary">Export</button>

                            </div>

                        </div>





                    </div>
                </div>







            </div>

            <?php require_once "../views/footer.php"; ?>


            <script>
                document.getElementById('backupButton').addEventListener('click', function() {
                    // Make an AJAX call to trigger the backup process
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '../controllers/backup_contr.php', true);
                    xhr.responseType = 'blob'; // Set the response type to blob
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            // Create a link element to initiate the download
                            var a = document.createElement('a');
                            a.href = window.URL.createObjectURL(xhr.response);
                            a.download = 'backup.csv'; // Set the filename for the downloaded file
                            document.body.appendChild(a);
                            a.click(); // Click the link to initiate the download
                            document.body.removeChild(a); // Remove the link element
                            alert('Database backup successful!');
                        } else {
                            alert('Error: Unable to backup database.');
                        }
                    };
                    xhr.send();
                });







                document.getElementById('backupClientsButton').addEventListener('click', function() {
                    // Make an AJAX call to trigger the backup process
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '../controllers/exportClients_contr.php', true);
                    xhr.responseType = 'blob'; // Set the response type to blob
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            // Create a link element to initiate the download
                            var a = document.createElement('a');
                            a.href = window.URL.createObjectURL(xhr.response);
                            a.download = 'majorlink_Customers.csv'; // Set the filename for the downloaded file
                            document.body.appendChild(a);
                            a.click(); // Click the link to initiate the download
                            document.body.removeChild(a); // Remove the link element
                            alert('Database backup successful!');
                        } else {
                            alert('Error: Unable to backup database.');
                        }
                    };
                    xhr.send();
                });
            </script>