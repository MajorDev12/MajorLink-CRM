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
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/setup_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$clientID = $_SESSION['clientID'];
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

    .changePlan,
    .changePaymentMethod,
    .changePassword,
    .acceptEmail {
        margin: 5%;
        width: 70%;
        min-height: 20vh;
        background-color: var(--light);
        padding: 20px;
        border-radius: 10px;
    }

    .changePlan .planDetails {
        width: 100%;
        /* background-color: grey; */
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .changePlan .planDetails .planPrice .month {
        color: var(--blue);
        font-size: 18px;
    }

    .changePlan .timelinebar .days {
        margin-top: 20px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;

    }

    .changePlan .timelinebar .days .day {
        font-weight: 900;
    }

    .actions {
        margin-top: 40px;
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

    .changePaymentMethod .Choosenoption {
        display: flex;
        flex-direction: column;
    }

    .changePaymentMethod .Choosenoption .div {
        position: relative;
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-bottom: 3%;
        border: 1px solid var(--light-blue);
        border-radius: 5px;
        padding: 1% 4%;
    }

    .changePaymentMethod .Choosenoption .div .form-check {
        position: absolute;
        right: 0%;
        padding: 10px;
    }

    .changePaymentMethod .Choosenoption .div .form-check .checkbox {
        padding: 10px;
    }

    .changePassword button a {
        float: right;
        background-color: var(--light);
        padding: 3px 5px;
        border: 1px solid var(--light-blue);
        border-radius: 5px;
        transition: .3s;
    }

    .changePassword button a:hover {
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
                <h1>Hi, <?= $_SESSION['FirstName']; ?></h1>
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

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom">
                    <h3>Settings</h3>
                </div>

                <div class="content-container">
                    <div class="changePlan shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                        <div class="planDetails">
                            <?php $clientData = getClientDataById($connect, $clientID); ?>
                            <div class="planName">
                                <?php if ($clientData) : ?>
                                    <h5><?php echo !empty($clientData["PlanName"]) ? $clientData["PlanName"] : "Not Subscribed Yet"; ?></h5>
                                    <p>The perfect way to get started.</p>
                            </div>
                            <div class="planPrice">
                                <h3 class=""> <sup class="currency"> <?php
                                                                        $settings = get_Settings($connect);
                                                                        echo $settings[0]["CurrencySymbol"];
                                                                        ?></sup><span class="number"><?php echo $clientData["PlanPrice"]; ?>/</span><span class="month">month</span></h3>
                            </div>
                        </div>
                        <div class="timelinebar">
                            <div class="days">
                                <?php
                                    // get total days
                                    $lastPaymentTimestamp = strtotime($clientData["LastPayment"]);
                                    $expireDateTimestamp = strtotime($clientData["ExpireDate"]);
                                    $totalDays = round(($expireDateTimestamp - $lastPaymentTimestamp) / (60 * 60 * 24));

                                    // get left days
                                    $expireDate = new DateTime($clientData["ExpireDate"]);
                                    $today = new DateTime();
                                    $daysRemaining = $today->diff($expireDate)->days;

                                    // calculate percentage of time left
                                    $percentageLeft = ($daysRemaining / $totalDays) * 100;
                                ?>
                                <p class="day"><?php echo $totalDays; ?> days</p>
                                <p><?php echo $daysRemaining; ?> days Left</p>
                            </div>
                            <div class="progress" role="progressbar" aria-label="Time Left" aria-valuenow="<?php echo $percentageLeft; ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar text-bg-warning" style="width: <?php echo $percentageLeft; ?>%"></div>
                            </div>
                        </div>

                        <div class="actions">
                            <a href="#">Cancel Plan</a>
                            <button type="button"><a href="changePlan.php">Change Plan</a></button>
                        </div>
                    </div>


                    <div class="changePaymentMethod shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                        <h5>Payment Method</h5>
                        <p class="mb-3">Your Prefered Payment Option</p>
                        <?php $PaymentMethods = getPaymentMethods($connect); ?>
                        <?php
                                    $preferedMethodId = $clientData["PreferedPaymentMethod"];
                                    foreach ($PaymentMethods as $PaymentMethod) :
                                        if ($preferedMethodId === $PaymentMethod["PaymentOptionID"]) :
                        ?>
                                <div class="Choosenoption">
                                    <div class="div">
                                        <img src="../img/<?= $PaymentMethod["PaymentOptionImg"] ?>" class="rounded float-start me-3" alt="..." style="width: 140px; height: 70px;">
                                        <p class="quote"><?= $PaymentMethod["PaymentOptionQuote"] ?></p>
                                    </div>
                                    <!-- <p class="text-center">No Choosen option</p> -->
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="actions">
                            <!-- <a href="#">Remove</a> -->
                            <button type="button"><a href="changePaymentMethod.php">Change</a></button>
                        </div>
                    </div>

                    <div class="changePassword shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                        <h5>Change Password</h5>
                        <p>Make sure your password is strong</p>
                        <div class="actions">
                            <button type="button"><a href="changePassword.php">Setup</a></button>
                        </div>
                    </div>

                    <div class="acceptEmail shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                        <h5>Send my Email</h5>
                        <p>This means you will get instant notifications in your email</p>
                        <div class="form-check">
                            <input class="form-check-input checkbox p-2" type="checkbox" value="" id="defaultCheck1">
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            </div>
            <?php require_once "../views/footer.php"; ?>