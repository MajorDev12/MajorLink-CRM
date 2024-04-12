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

$connect = connectToDatabase($host, $dbname, $username, $password);
$clientID = $_SESSION['clientID'];
$clientData = getClientDataById($connect, $clientID);
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>
<style>
    .btn {
        background-color: var(--blue);
        color: var(--light);
        font-weight: 600;
    }

    .btn:hover {
        color: var(--light);
        background-color: var(--yellow);
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
                        <a class="active" href="#">RazorPay</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>

        <!-- content-container -->
        <div id="loader">Processing... Please check your phone for a popup</div>
        <div class="main-content">
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom">
                    <h3>RazorPay</h3>
                </div>

                <div id="paymentForm" class="offset-md-3 mt-5">
                    <form id="paymentDetailsForm">

                        <div class="row mt-2">

                            <div class="form-group col-md-4">
                                <label for="paymentDate">Payment Date</label>
                                <input type="date" class="form-control" id="paymentDate" readonly value="<?php echo date('Y-m-d'); ?>">
                            </div>



                            <div class="form-group col-md-4">
                                <label for="startDate">Start Date</label>
                                <?php
                                // Convert $clientData['ExpireDate'] to a DateTime object
                                $startDate = new DateTime($clientData['ExpireDate']);

                                // Add one day to the start date
                                $startDate->add(new DateInterval('P1D'));
                                ?>
                                <input type="date" class="form-control" id="startDate" name="startDate" readonly value="<?= ($startDate != null) ? $startDate->format('Y-m-d') : date('Y-m-d') ?>">
                            </div>


                        </div>

                        <div class="row mt-2">

                            <div class="form-group col-md-4">
                                <label for="Plan" class="form-label">Subscribed Plan</label>
                                <input type="text" class="form-control" readonly id="Plan" name="Plan" value="<?= $clientData['PlanName'] . ' ' . ' - ' . $clientData['Plan'] ?>">

                            </div>

                            <div class="form-group col-md-4">
                                <label for="PlanAmount" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <?php
                                        $settings = get_Settings($connect);
                                        echo $settings[0]["CurrencySymbol"];
                                        ?>
                                    </span>
                                    <input type="text" name="PlanAmount" id="PlanAmount" readonly class="form-control" value="<?= $clientData['PlanPrice'] ?>" aria-label="Amount (to the nearest dollar)">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">

                            <div class="form-group col-md-4">
                                <label for="PlanAmount" class="form-label">PhoneNumber</label>
                                <input type="tel" name="PhoneNumber" id="PhoneNumber" class="form-control" value="<?= $clientData['PrimaryNumber'] ?>">
                            </div>



                            <div class="form-group col-md-4">
                                <label for="selectedMonths" class="form-label">Months</label>
                                <select id="selectedMonths" class="form-select">
                                    <option selected value="1">1 - month</option>
                                    <option value="2">2 - months</option>
                                    <option value="3">3 - months</option>
                                    <option value="4">4 - months</option>
                                    <option value="5">5 - months</option>
                                    <option value="6">6 - months</option>
                                    <option value="7">7 - months</option>
                                    <option value="8">8 - months</option>
                                    <option value="9">9 - months</option>
                                    <option value="10">10 - months</option>
                                    <option value="11">11 - months</option>
                                    <option value="12">12 - months</option>
                                </select>
                            </div>
                        </div>


                        <div id="errorMsg"></div>

                        <div class="form-group col-md-8 mt-4 text-center">
                            <!-- <div id="paymentButton"></div> -->
                            <button type="button" id="paymentButton" class="btn p-2 border-none" style="width: 100%;" onclick="makePayment(event);">Pay</button>
                        </div>

                    </form>
                </div>
            </div>

            <?php require_once "../views/footer.php"; ?>