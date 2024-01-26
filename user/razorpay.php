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

$connect = connectToDatabase($host, $dbname, $username, $password);
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

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="paymentDate">Start Date</label>
                                <input type="date" class="form-control" id="paymentDate" disabled value="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="Plan" class="form-label">Select Plan</label>
                                <select id="Plan" name="Plan" class="form-select">
                                    <option value="" selected>Choose...</option>
                                    <?php
                                    $plans = getPlanData($connect);
                                    foreach ($plans as $plan) {
                                        echo '<option value="' . $plan['PlanID'] . '" data-price="' . $plan['Price'] . '">' . $plan['Name'] . ' ' . ' - ' . $plan['Volume'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="PlanAmount" class="form-label">PhoneNumber</label>
                                <input type="tel" name="PhoneNumber" id="PhoneNumber" class="form-control" value="">
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
                                    <input type="text" name="PlanAmount" id="PlanAmount" class="form-control" value="" disabled aria-label="Amount (to the nearest dollar)">
                                </div>
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

            <script>
                $(document).ready(function() {
                    $('#Plan').change(function() {
                        var selectedPlan = $(this).find(':selected');
                        var planAmountInput = $('#PlanAmount');

                        if (!isNaN(selectedPlan.val())) {
                            var planPrice = selectedPlan.data('price');
                            planAmountInput.val(planPrice);
                        } else {
                            planAmountInput.val(''); // Clear the input if "Choose..." is selected
                        }
                    });
                });
            </script>
            <?php require_once "../views/footer.php"; ?>