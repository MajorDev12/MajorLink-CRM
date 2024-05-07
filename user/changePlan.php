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
require_once  '../modals/getPaymentMethods_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

$clientID = $_SESSION["clientID"];
$clientData = getClientDataById($connect, $clientID);

$settings = get_Settings($connect);
$initialCurrency = $settings[0]["CurrencyCode"];

$currentPlan = $clientData["PlanID"];

$preferedmethodid = $clientData["PreferedPaymentMethod"];

$PaymentMethods = getPaymentMethods($connect);

?>

<?php require_once "../views/header.php"; ?>

<?php require_once "../views/style.config.php"; ?>
<style>
    .planPrice .month {
        color: var(--blue);
        font-size: 18px;
    }

    .overlay {
        display: none;
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: var(--light);
        z-index: 1000;
    }

    .modal {
        position: absolute;
        top: 15%;
        left: 30%;
        width: 40%;
        height: 70vh;
        background-color: var(--light);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1001;
        display: none;
        padding: 10px;
    }

    .note {
        width: 50%;
        padding: 10px;
        margin: 10px;
        background-color: var(--light-green);
    }

    .close {
        position: absolute;
        top: 3%;
        right: 5%;
        font-weight: 900;
        cursor: pointer;
    }

    #plandiv {
        background-color: var(--light);
    }

    .radiodiv {
        display: flex;
        flex-direction: row;
    }
</style>
<div id="overlay"></div>

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
                        <a class="active" href="settings.php">Settings</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Change Plan</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">


                <!-- modifySubscription page -->
                <div class="page modifySubscription">
                    <h4 class="border-bottom pb-3 mb-5">Change Subscription Plan</h4>




                    <!-- stripe modal -->
                    <div id="stripeModal" class="modal-container">

                        <p class="note p-2">
                            note that changing subscription plan now will remove any past payment
                        </p>
                        <form action="">
                            <div class="row">
                                <div class="form-group">
                                    <div id="closestripeModal" class="close">X</div>
                                    <label for="Date" class="form-label">Payment Date</label>
                                    <input type="text" class="form-control" readonly id="PaymentDate" name="PaymentDate" value="<?php echo date('Y-m-d'); ?>">
                                    <input type="hidden" class="form-control StartDate" readonly id="StartDate" name="StartDate" value="<?php echo date('Y-m-d'); ?>">
                                    <label for="Amount" class="form-label">Amount</label>
                                    <input type="text" class="form-control Amount" readonly id="Amount" name="Amount">
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
                                    <input type="hidden" id="currencyCode" value="">
                                    <input type="hidden" id="currencySymbol" value="">
                                    <input type="hidden" id="PlanName" value="">
                                    <input type="hidden" id="PlanID" value="">
                                </div>

                                <button type="button" id="payNow" class="btn btn-success mt-4">Change Now</button>
                                <button type="button" id="payLater" class="btn btn-success mt-2">Change Later</button>
                            </div>

                        </form>
                    </div>



                    <!-- paypal modal -->
                    <div id="paypalModal" class="modal-container">

                        <p class="note p-2">
                            note that changing subscription plan Now will remove any past payment
                        </p>

                        <div class="row">
                            <div class="form-group">
                                <button type="button" id="closepaypalModal" class="close" data-dismiss="modal">&times;</button>
                                <label for="Plan" class="form-label">Payment Date</label>
                                <input type="text" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>">
                                <label for="Plan" class="form-label">Amount</label>
                                <input type="text" class="form-control" data-planID="" readonly id="paypalAmount" name="Plan">
                                <label for="Plan" class="form-label">Months</label>
                                <select id="paypalSelectedMonths" class="form-select">
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
                                <input type="hidden" id="currencyCode" value="">
                                <input type="hidden" id="currencySymbol" value="">
                                <input type="hidden" id="PlanName" value="">
                                <input type="hidden" id="PlanID" value="">
                                <input type="hidden" class="">
                            </div>


                            <div class="radiodiv mt-3">
                                <input type="radio" value="" id="nowRadio" name="fav_language">
                                <label class="form-check-label" for="nowRadio">
                                    Change Now
                                </label>
                            </div>

                            <div class="radiodiv">
                                <input type="radio" value="" id="laterRadio" name="fav_language">
                                <label class="form-check-label" for="laterRadio">
                                    Change Later
                                </label>
                            </div>


                            <p id="errorMsg"></p>
                            <div id="paypalButtonNow"></div>

                        </div>


                    </div>








                    <?php
                    $currentPlan = $clientData["PlanID"];
                    ?>

                    <div class="grid text-center plans row" id="plandiv">
                        <?php $plans = getPlanData($connect); ?>
                        <?php foreach ($plans as $plan) : ?>
                            <?php if ($plan["PlanID"] !== $currentPlan) : ?> <!-- Check if the plan ID is not equal to the current plan ID -->
                                <div class="col-md-5 shadow p-3 m-3 rounded">
                                    <form action="">
                                        <div class="planVolume mt-5 bg-primary">
                                            <h3 class="p-4 text-light"><?php echo $plan["Volume"]; ?></h3>
                                        </div>
                                        <div class="planName mt-5">
                                            <h4 id="planName" data-planid="<?php echo $plan["PlanID"]; ?>"><?php echo $plan["Name"]; ?></h4>
                                            <p><?php //echo $plan["Notes"]; 
                                                ?></p>
                                        </div>
                                        <div class="planPrice mb-3">
                                            <h3 class=""> <sup class="currency" data-currencycode="<?= $settings[0]["CurrencyCode"]; ?>"> <?php
                                                                                                                                            $settings = get_Settings($connect);
                                                                                                                                            echo $settings[0]["CurrencySymbol"];
                                                                                                                                            ?></sup><span class="number"><?php echo $plan["Price"]; ?></span><span class="month" name="duration">/month</span></h3>
                                        </div>
                                        <button type="button" class="btn btn-primary apply">Apply Now</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>



                </div>




            </div>

            <?php require_once "../views/footer.php"; ?>
            <script src="https://js.stripe.com/v3/"></script>
            <script src="https://www.paypal.com/sdk/js?client-id=<?= CLIENT_ID; ?>&currency=USD&disable-funding=credit,card"></script>
            <script>
                var changing = true;
                var changingNow = false;
                var changingLater = false;
                var amount = '';
                var selectedMonths = '';
                var paidPlanID = '';



                paypal.Buttons({
                    createOrder: function(data, actions) {
                        changingNow = document.getElementById("nowRadio");
                        changingLater = document.getElementById("laterRadio");
                        amount = document.getElementById("paypalAmount").value;
                        selectedMonths = document.getElementById("paypalSelectedMonths").value;
                        paidPlanID = $('#paypalAmount').data('planid');
                        amount = amount * selectedMonths;


                        if (!changingNow.checked && !changingLater.checked) {
                            displayMessage("errorMsg", "Check the now or later button to continue", true);
                            return actions.reject();
                        }



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
                                    paidPlanID: paidPlanID,
                                    paidMonths: selectedMonths,
                                    changing: changing,
                                    changingNow: changingNow.checked,
                                    amount: amount
                                },
                                success: function(response) {
                                    // Handle the response from the server
                                    console.log(response);
                                    if (response.paymentInserted && response.planUpdated && response.statusChanged && response.transactionSet && response.invoiceAdded && response.productsAdded) {
                                        // Payment was successful, redirect or perform further actions
                                        displayMessage("errorMsg", "Successfully paid", false);
                                        setTimeout(() => {
                                            window.location.href = 'paypal.php?success=paymentsuccess';
                                        }, 1000);

                                    } else {
                                        // Payment failed, handle accordingly
                                        displayMessage("errorMsg", "Failed", true);
                                        console.log(response);
                                        // window.location.href = 'paypal.php?error=payment error';
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
                }).render('#paypalButtonNow');






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




























                document.querySelector("#closestripeModal").addEventListener('click', () => {
                    document.querySelector(".modal").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                })

                document.querySelector("#closepaypalModal").addEventListener('click', () => {
                    document.querySelector("#paypalModal").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                })






                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('apply')) {
                        var form = event.target.closest('form');
                        if (form) {
                            var price = form.querySelector('.planPrice .number').textContent;
                            var currency = form.querySelector('.planPrice .currency').textContent;
                            var PlanName = form.querySelector('.planName #planName').textContent;
                            var planID = form.querySelector('.planName #planName').dataset.planid;
                            var currencyCode = form.querySelector('.planPrice .currency').dataset.currencycode;
                            // console.log(planID)
                            // return
                            if (price) {

                                // Get the preferred payment method ID from PHP
                                var preferredMethodId = <?php echo json_encode($preferedmethodid); ?>;
                                var paymentMethod = <?php echo json_encode($PaymentMethods); ?>;
                                paymentMethod.forEach(method => {
                                    if (preferredMethodId === method["PaymentOptionID"]) {

                                        // Check the preferred method and show the corresponding modal
                                        switch (method["PaymentOptionName"]) {
                                            case 'paypal':
                                                // Set the display property of the PayPal modal to block
                                                document.getElementById('paypalModal').style.display = 'flex';
                                                document.getElementById('overlay').style.display = 'flex';
                                                document.getElementById('paypalAmount').value = price;
                                                $('#paypalAmount').data('planid', planID);
                                                document.getElementById('currencyCode').value = currencyCode;
                                                document.getElementById('PlanName').value = PlanName;
                                                break;
                                            case 'mpesa':
                                                // Set the display property of the Mpesa modal to block
                                                document.getElementById('mpesaModal').style.display = 'flex';
                                                document.getElementById('overlay').style.display = 'flex';
                                                document.getElementById('currencyCode').value = currencyCode;
                                                break;
                                            case 'razorpay':
                                                // Set the display property of the Razorpay modal to block
                                                document.getElementById('razorpayModal').style.display = 'flex';
                                                document.getElementById('overlay').style.display = 'flex';
                                                document.getElementById('currencyCode').value = currencyCode;
                                                break;
                                            case 'stripe':

                                                // Set the display property of the Stripe modal to block
                                                document.getElementById('stripeModal').style.display = 'flex';
                                                document.getElementById('overlay').style.display = 'flex';
                                                document.getElementById('Amount').value = price;
                                                document.getElementById('currencySymbol').value = currency;
                                                document.getElementById('currencyCode').value = currencyCode;
                                                document.getElementById('PlanName').value = PlanName;
                                                document.getElementById('PlanID').value = planID;
                                                break;
                                            default:
                                                // Handle other cases or set a default modal
                                                document.getElementById('stripeModal').style.display = 'flex';
                                                document.getElementById('overlay').style.display = 'flex';
                                                document.getElementById('Amount').value = price;
                                                document.getElementById('currencyCode').value = currencyCode;
                                                document.getElementById('PlanName').value = planName;
                                                break;
                                        }

                                    }
                                });

                            }
                        }
                    }
                });



                // let paymentDate, startDate, PlanName, PlanID, currency, PlanAmount;

                // Set Stripe publishable key to initialize Stripe.js
                const stripe = Stripe('<?= STRIPE_PUBLISHABLE_KEY; ?>');
                // Select payment button
                const payNow = document.getElementById("payNow");
                const payLater = document.getElementById("payLater");


                payNow.addEventListener("click", (e) => {
                    e.preventDefault();
                    months = document.querySelector("#selectedMonths").value;
                    // Assign values to the global variables
                    paymentDate = document.querySelector("#PaymentDate").value;
                    startDate = document.querySelector("#StartDate").value;
                    PlanName = document.querySelector("#PlanName").value;
                    PlanID = document.querySelector("#PlanID").value;
                    currencyCode = document.querySelector("#currencyCode").value;
                    currency = document.querySelector("#currencySymbol").value;
                    PlanAmount = document.getElementById("Amount").value;
                    selectedMonths = document.getElementById("selectedMonths").value;
                    changing = true;
                    changingNow = true;


                    if (PlanAmount === '') {
                        displayMessage("errorMsg", "Amount Cannot Be Empty", true);
                        payNow.disabled = false;
                        payLater.disabled = false;
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

                })








                payLater.addEventListener("click", (e) => {
                    e.preventDefault();
                    months = document.querySelector("#selectedMonths").value;
                    // Assign values to the global variables
                    paymentDate = document.querySelector("#PaymentDate").value;
                    startDate = document.querySelector("#StartDate").value;
                    PlanName = document.querySelector("#PlanName").value;
                    PlanID = document.querySelector("#PlanID").value;
                    currencyCode = document.querySelector("#currencyCode").value;
                    currency = document.querySelector("#currencySymbol").value;
                    PlanAmount = document.getElementById("Amount").value;
                    selectedMonths = document.getElementById("selectedMonths").value;
                    changing = true;
                    changingNow = false;



                    if (PlanAmount === '') {
                        displayMessage("errorMsg", "Amount Cannot Be Empty", true);
                        payNow.disabled = false;
                        payLater.disabled = false;
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

                })








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
                            changing: changing,
                            changingNow: changingNow
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
                    document.getElementById('stripeModal').style.display = 'none';
                    document.getElementById('paypalModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                    document.getElementById('loader').style.display = 'flex';
                }
            </script>