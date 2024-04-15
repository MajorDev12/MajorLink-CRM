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
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/getTime_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$clientID = $_SESSION['clientID'];
$clientData = getClientDataById($connect, $clientID);
$greeting = getGreeting();
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

    #profileCard {
        background-color: var(--light);
        padding: 20px;
        border-radius: 10px;
    }

    #profileCard #bioCard {
        box-shadow: 4px 4px 4px var(--grey) inset;
    }

    #profileCard #cardpic {
        box-shadow: 4px 4px 4px var(--grey);
    }

    #profileCard div {
        color: var(--dark-grey);
    }

    #profileCard span,
    #profileCard h5 {
        color: var(--dark);
    }



    .planDetails {
        width: 100%;
        /* background-color: grey; */
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .timelinebar .days {
        margin-top: 20px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;

    }

    .timelinebar .days .day {
        font-weight: 900;
        color: var(--green);
    }

    .timelinebar .days .daysLeft {
        font-weight: 900;
        color: var(--orange);
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
                <h1><?= $greeting ?>, <?= $_SESSION['FirstName']; ?></h1>
                <!-- <p style="color: var(--dark);">You are running Low!!!</p> -->
                <ul class="breadcrumb">
                    <li>
                        <a href="viewClient.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>

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



                <div id="profileCard" class="mb-3">
                    <div class="row g-0">
                        <div id="cardpic" class="col-md-4 pb-5">
                            <div class="col text-center">
                                <img src="../img/<?= $clientData['ProfilePictureURL']; ?>" class="img-fluid rounded-start" alt="..." width="200px" height="200px">
                                <h5 class="mt-4">Edit Profile</h5>
                                <a href="profile.php" class="">Click Here</a>
                            </div>



                            <?php
                            $status = '';
                            if ($clientData["ActiveStatus"] === 0) {
                                $status = "Inactive";
                            } elseif ($clientData["ActiveStatus"] === 1) {
                                $status = "Active";
                            }
                            $datetime = $clientData['ExpireDate'];
                            $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
                            // Format the date to day, month, year
                            $DateTime = $dateObj->format('d F Y H:i:s');
                            ?>
                            <div class="col mt-5">
                                <div class="col mb-3">Current Plan: <span style="font-weight: 900;"><?= $clientData["Plan"]; ?></span> </div>
                                <div class="col mb-3">Status: <span style="font-weight: 900;"><?= $status; ?></span> </div>

                                <div class="col mb-3">Expire Date: <span style="font-weight: 900;"><?= $DateTime; ?></span></div>
                            </div>

                        </div>



                        <div id="bioCard" class="col-md-8">
                            <div class="p-3">
                                <h5 class="mb-5">Bio Graph</h5>
                                <div class="row mb-4">
                                    <div class="col-md-5">First Name: <span style="font-weight: 900;"><?= $clientData['FirstName']; ?></span> </div>
                                    <div class="col-md-5">Last Name: <span style="font-weight: 900;"><?= $clientData['LastName']; ?></span></div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-5">Phone Number: <span style="font-weight: 900;"><?= $clientData['PrimaryNumber']; ?></span> </div>
                                    <div class="col-md-7">Email Address: <span style="font-weight: 900;"><?= $clientData['PrimaryEmail']; ?></span></div>
                                </div>






                                <div class="planDetails">
                                    <?php $clientData = getClientDataById($connect, $clientID); ?>
                                    <div class="planName">
                                        <?php if ($clientData) : ?>
                                            <?php
                                            // Convert expireDate to a DateTime object
                                            $expireDate = new DateTime($clientData["ExpireDate"]);

                                            // Check if expireDate has passed today
                                            if ($expireDate < new DateTime()) {
                                                // Set everything to 0
                                                $totalDays = 0;
                                                $daysRemaining = 0;
                                                $percentageLeft = 0;
                                            } else {
                                                // Calculate total days
                                                $expireDate = new DateTime($clientData["ExpireDate"]);
                                                $lastPaymentDate = new DateTime($clientData["LastPayment"]);
                                                // Calculate the difference between the two dates
                                                $dateInterval = $expireDate->diff($lastPaymentDate);

                                                // Get the total number of days from the DateInterval object
                                                $totalDays = $dateInterval->days;

                                                // // Calculate left days
                                                $today = new DateTime();
                                                $daysRemaining = max(0, $today->diff($expireDate)->days); // Ensure daysRemaining is not negative

                                                // Calculate percentage of time left
                                                $percentageLeft = ($totalDays > 0) ? max(0, ($daysRemaining / $totalDays) * 100) : 0; // Check if $totalDays is greater than 0
                                            }
                                            ?>
                                            <h5><?php echo ($expireDate < new DateTime()) ? "Pay to continue Browsing" : (!empty($clientData["PlanName"]) ? "" : "Not Subscribed Yet"); ?></h5>
                                            <p><?php echo ($expireDate < new DateTime()) ? "Without Internet Is Boring😪😪" : ""; ?></p>
                                    </div>



                                </div>
                                <div class="timelinebar mb-5">
                                    <div class="timelinebar">
                                        <div class="days">
                                            <?php
                                            if ($totalDays > 30) {
                                                // Calculate the number of months
                                                $totalMonths = floor($totalDays / 30);
                                                echo "<p class='day'>$totalMonths months</p>";
                                            } else {
                                                echo "<p class='day'>$totalDays days</p>";
                                            }
                                            ?>
                                            <p class="daysLeft"><?php echo $daysRemaining; ?> days Left</p>
                                        </div>
                                        <!-- ... Other HTML code ... -->
                                    </div>
                                    <div class="progress" role="progressbar" aria-label="Animated striped Time Left" aria-valuenow="<?php echo $percentageLeft; ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar text-bg-warning progress-bar-striped progress-bar-animated" style="width: <?php echo $percentageLeft; ?>%"></div>
                                    </div>
                                </div>
                                <a href="../controllers/choosepaymentPage_contr.php" class="mt-3" style="font-weight: 900;">PAY HERE</a>

                            <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>












                <!-- <ul class="box-info">
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
                </ul> -->
            </div>


            <?php require_once "../views/footer.php"; ?>