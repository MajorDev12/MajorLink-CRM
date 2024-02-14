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
require_once  '../modals/config.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

$clientID = $_SESSION["clientID"];

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
                        <a class="active" href="#">Stripe</a>
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
                    <h3>Stripe</h3>
                </div>

                <div id="paymentForm" class="offset-md-3 mt-5">

                    <form id="paymentDetailsForm">

                        <div class="row mb-4">
                            <div class="form-group col-md-4">
                                <label for="paymentDate">Payment Date</label>
                                <input type="date" class="form-control" readonly id="paymentDate" name="paymentDate" value="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="startDate">Start Date</label>
                                <?php
                                // Convert ExpireDate to a DateTime object
                                $expireDate = new DateTime($clientData['ExpireDate']);

                                // Check if expireDate has passed
                                if ($expireDate < new DateTime()) {
                                    // If expired, set start date to today
                                    $startDate = new DateTime();
                                } else {
                                    // If not expired, add one day to the expireDate
                                    $expireDate->add(new DateInterval('P1D'));
                                    $startDate = $expireDate;
                                }
                                ?>
                                <input type="date" class="form-control" id="startDate" name="startDate" readonly value="<?= ($startDate != null) ? $startDate->format('Y-m-d') : date('Y-m-d') ?>">
                            </div>




                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="Plan" class="form-label">Subscribed Plan</label>
                                <input type="hidden" id="PlanName" name="PlanName" value="<?= $clientData['PlanName']; ?>">
                                <input type="text" class="form-control" readonly id="Plan" name="Plan" value="<?= $clientData['PlanName'] . ' ' . ' - ' . $clientData['Plan'] ?>">
                                <input type="hidden" name="planVolume" value="<?= $clientData['Plan']; ?>">
                                <input type="hidden" id="PlanID" name="PlanID" value="<?= $clientData['PlanID']; ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="PlanAmount" class="form-label">Amount</label>
                                <div class="input-group">
                                    <?php $settings = get_Settings($connect); ?>
                                    <span class="input-group-text" id="currency" value="<?= $settings[0]["CurrencySymbol"]; ?>">
                                        <?php echo $settings[0]["CurrencySymbol"]; ?>
                                    </span>
                                    <input type="hidden" name="currencyCode" id="currencyCode" value="<?= $settings[0]["CurrencyCode"]; ?>">

                                    <input type="number" id="PlanAmount" class="form-control" readonly value="<?= $clientData['PlanPrice']; ?>">
                                </div>
                            </div>

                        </div>

                        <div class="row">
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

                        <script>
                            // Get elements
                            var planAmountInput = document.getElementById("PlanAmount");
                            var selectedMonthsDropdown = document.getElementById("selectedMonths");

                            // Initial plan price value
                            var initialPlanPrice = <?= $clientData['PlanPrice']; ?>;

                            // Update the amount on dropdown change
                            selectedMonthsDropdown.addEventListener("change", function() {
                                var selectedMonths = parseInt(selectedMonthsDropdown.value);
                                var calculatedAmount = initialPlanPrice * selectedMonths;

                                // Update the PlanAmount input value
                                planAmountInput.value = calculatedAmount.toFixed(2); // Round to 2 decimal places


                            });
                        </script>



                        <div id="errorMsg" class="mt-3" style="color: red;">
                            <?php
                            if (isset($_GET["error"])) {
                                if ($_GET["error"] == "emptyinput") {
                                    echo "Amount Cannot be empty";
                                }
                            }

                            ?>
                        </div>

                        <div class="form-group col-md-8 mt-4 text-center">
                            <button type="button" id="paymentButton" class="btn p-2 border-none" style="width: 100%;">Pay</button>
                        </div>

                    </form>
                </div>
            </div>


            <?php require_once "../views/footer.php"; ?>
            <!-- Stripe JavaScript library -->
            <script src="https://js.stripe.com/v3/"></script>

            <script>
                let paymentDate, startDate, PlanName, PlanID, currency, PlanAmount;

                // Set Stripe publishable key to initialize Stripe.js
                const stripe = Stripe('<?= STRIPE_PUBLISHABLE_KEY; ?>');

                // Select payment button
                const payBtn = document.querySelector("#paymentButton");

                // Payment request handler
                payBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    payBtn.disabled = true;
                    months = document.querySelector("#selectedMonths").value;
                    // Assign values to the global variables
                    paymentDate = document.querySelector("#paymentDate").value;
                    startDate = document.querySelector("#startDate").value;
                    PlanName = document.querySelector("#PlanName").value;
                    PlanID = document.querySelector("#PlanID").value;
                    currency = document.querySelector("#currency").textContent;
                    PlanAmount = document.getElementById("PlanAmount").value;
                    currencyCode = document.getElementById("currencyCode").value;
                    selectedMonths = document.getElementById("selectedMonths").value;


                    if (PlanAmount === '') {
                        displayMessage("errorMsg", "Amount Cannot Be Empty", true);
                        payBtn.disabled = false;
                        return;
                    }

                    showLoader();

                    createCheckoutSession().then(function(data) {
                        if (data.sessionId) {
                            stripe.redirectToCheckout({
                                sessionId: data.sessionId,
                            }).then(handleResult);
                        } else {
                            handleResult(data);
                        }
                    });
                });



                // Create a Checkout Session with the selected product
                const createCheckoutSession = function(stripe) {
                    return fetch("../controllers/stripe_contr.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            createCheckoutSession: 1,
                            paymentDate: paymentDate,
                            startDate: startDate,
                            PlanName: PlanName,
                            PlanID: PlanID,
                            currency: currency,
                            currencyCode: currencyCode,
                            PlanAmount: PlanAmount,
                            selectedMonths: selectedMonths,
                        }),
                    }).then(function(result) {
                        return result.json();
                    }).catch(function(error) {
                        // Handle network or other errors
                        console.error("Error:", error);
                    });
                };





                // Handle any errors returned from Checkout
                const handleResult = function(result) {
                    if (result.error) {
                        console.error(result.error.message)
                        displayMessage('errorMsg', 'something went wrong', true);
                        // window.location.href = 'index.php';
                    }
                };






                // Function to toggle button content and show loader
                function showLoader() {
                    // Disable the button
                    document.getElementById('paymentButton').disabled = true;

                    // Change button content to loader
                    document.getElementById('paymentButton').innerHTML = `
            <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
            <span role="status">Please wait...</span>
        `;
                }



                function displayMessage(messageElement, message, isError) {
                    // Get the HTML element where the message should be displayed
                    var targetElement = document.getElementById(messageElement);

                    // Set the message text
                    targetElement.innerText = message;

                    // Add styling based on whether it's an error or success
                    if (isError) {
                        targetElement.style.color = 'red';
                    } else {
                        targetElement.style.color = 'green';
                    }

                    // Set a timeout to hide the message with the fade-out effect
                    setTimeout(function() {
                        targetElement.innerText = '';
                    }, 2000);
                }
            </script>