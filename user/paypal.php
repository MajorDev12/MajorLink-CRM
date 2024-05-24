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
$settings = get_Settings($connect);
$initialCurrency = $settings[0]["CurrencyCode"];
$initialSymbol = $settings[0]["CurrencySymbol"];


$clientData = getClientDataById($connect, $clientID);
?>
<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>
<style>
    .btn {
        background-color: var(--yellow);
        color: var(--blue);
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
                        <a class="active" href="#">Paypal</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div id="loader">Processing...</div>
        <div class="main-content">
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom">
                    <h3>Paypal</h3>
                </div>

                <div id="paymentForm" class="offset-md-3 mt-5">
                    <form id="paymentDetailsForm">

                        <div class="row mb-4">
                            <div class="form-group col-md-4">
                                <label for="paymentDate">Payment Date</label>
                                <input type="date" class="form-control" id="paymentDate" disabled value="<?php echo date('Y-m-d'); ?>">
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

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="Plan" class="form-label">Subscribed Plan</label>

                                <input type="text" class="form-control" data-planId="<?= $clientData['PlanID'] ?>" disabled id="Plan" name="Plan" value="<?= $clientData['PlanName'] . ' - ' . $clientData['Plan'] ?>">

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
                                    <input type="text" name="PlanAmount" id="PlanAmount" class="form-control" value="<?= $clientData['PlanPrice'] ?>" aria-label="Amount (to the nearest dollar)">
                                </div>
                            </div>

                        </div>


                        <div class="row mt-3">
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

                        <div id="errorMsg"></div>

                        <div class="form-group col-md-8 mt-4 text-center">
                            <div id="paymentButton"></div>
                            <!-- <button type="button" id="paymentButton" class="btn p-2 border-none" style="width: 100%;">Proceed to Paypal</button> -->
                        </div>

                    </form>
                </div>
            </div>

            <?php require_once "../views/footer.php"; ?>


            <script src="https://www.paypal.com/sdk/js?client-id=<?= CLIENT_ID; ?>&currency=USD&disable-funding=credit,card"></script>
            <script>
                let amount, startDate, paymentDate, selectedMonths, planId, changing, changingNow;

                paypal.Buttons({
                    createOrder: function(data, actions) {
                        amount = $('#PlanAmount').val();
                        startDate = $('#startDate').val();
                        paymentDate = $('#paymentDate').val();
                        selectedMonths = $('#selectedMonths').val();
                        planId = $('#Plan').data('planid');
                        changing = false;
                        changingNow = false;
                        if (!amount) {
                            displayMessage("errorMsg", "Please enter amount to be paid before proceeding.", true);
                            return actions.reject();
                        }

                        // Parsing the input value to a floating-point number
                        const initialCurrency = '<?= $initialCurrency; ?>';

                        if (initialCurrency !== "USD") {
                            // Convert the amount to USD
                            return convertToUSD(amount, initialCurrency)
                                .then(convertedAmount => {

                                    // Create the order with the converted amount
                                    return actions.order.create({
                                        purchase_units: [{
                                            amount: {
                                                value: convertedAmount, // Use the converted amount here
                                                currency_code: 'USD',
                                            },
                                        }],
                                        application_context: {
                                            shipping_preference: 'NO_SHIPPING',
                                        },
                                    });
                                })
                                .catch(error => {
                                    // Handle the error
                                    displayMessage("errorMsg", "Failed!!! Please try again later or contact customer support.", true);
                                    console.error('Error converting to USD:', error.message);
                                    // Display an error message to the user or take appropriate action
                                });
                        } else {
                            // If the initial currency is already USD, create the order with the original amount
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: amount,
                                        currency_code: 'USD',
                                    },
                                }],
                                application_context: {
                                    shipping_preference: 'NO_SHIPPING',
                                },
                            });
                        }

                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {


                            $.ajax({
                                url: '../controllers/process_Paypalpayment_contr.php', // Replace with your server-side processing script
                                method: 'POST',
                                data: {
                                    details: details,
                                    paymentData: data,
                                    planID: planId,
                                    paidMonths: selectedMonths,
                                    changing: changing,
                                    changingNow: changingNow,
                                    amount: amount,
                                    startDate: startDate
                                },
                                success: function(response) {
                                    // Handle the response from the server
                                    console.log(response ? true : false);

                                    // Check if all properties are true
                                    if (response) {
                                        // Payment was successful
                                        window.location.href = 'paymentSuccess.php?error=payment success';
                                    } else {
                                        // Payment failed, handle accordingly
                                        displayMessage("errorMsg", "Something went wrong", true);
                                        window.location.href = 'paypal.php?error=payment error';
                                    }
                                },

                                error: function() {
                                    // Handle errors or server-side issues
                                    // alert('Error processing payment.');
                                    console.log("Error processing payment.")
                                },
                            });
                        });
                    },
                    onCancel: function(data) {
                        console.log("payment cancelled" + data);
                        // window.location.replace('index.php');
                    }
                }).render('#paymentButton');





                function convertToUSD(amount, initialCurrency) {
                    // API endpoint for ExchangeRatesAPI
                    const apiEndpoint = '<?= ENDPOINT; ?>';

                    // API request to get the latest exchange rates
                    const apiUrl = `${apiEndpoint}?base=${initialCurrency}&symbols=USD`;

                    // Fetch API to get exchange rates
                    return fetch(apiUrl)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Decode the API response
                            const exchangeRates = data;

                            // Check if the exchange rates were successfully retrieved
                            if (exchangeRates && exchangeRates.rates.USD) {
                                // Convert amount to USD using the exchange rate and round off
                                const exchangeRate = exchangeRates.rates.USD;
                                return Math.round(amount * exchangeRate * 100) / 100; // Round to two decimal places
                            } else {
                                throw new Error('Failed to retrieve exchange rates');
                            }
                        })
                        .catch(error => {
                            console.error('Error converting to USD:', error);
                            throw error; // Rethrow the error to propagate it to the caller
                        });
                }
            </script>