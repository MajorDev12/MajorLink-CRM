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
        background-color: var(--light);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1001;
        display: none;
    }

    .note {
        position: absolute;
        top: 4%;
        width: 70%;
        background-color: var(--light-green);
    }

    .close {
        position: absolute;
        top: 3%;
        right: 5%;
        font-weight: 900;
        cursor: pointer;
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
                    <div id="stripeModal" class="modal shadow-sm p-3 mb-5 bg-body rounded">

                        <p class="note p-2">
                            note that changing subscription plan will remove any past payment and start afresh
                        </p>
                        <form action="">
                            <div class="row">
                                <div class="form-group">
                                    <div id="closestripeModal" class="close">X</div>
                                    <label for="Date" class="form-label">Payment Date</label>
                                    <input type="text" class="form-control" readonly id="PaymentDate" name="PaymentDate" value="<?php echo date('Y-m-d'); ?>">
                                    <label for="Date" class="form-label">Start Date</label>
                                    <input type="text" class="form-control StartDate" readonly id="StartDate" name="StartDate" value="<?php echo date('Y-m-d'); ?>">
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

                                <button type="button" id="pay" class="btn btn-success mt-4">Change Now</button>
                            </div>

                        </form>
                    </div>



                    <!-- paypal modal -->
                    <div id="paypalModal" class="modal shadow-sm p-3 mb-5 bg-body rounded">

                        <p class="note p-2">
                            note that changing subscription plan will remove any past payment
                        </p>
                        <form action="">
                            <div class="row">
                                <div class="form-group">
                                    <button type="button" id="closepaypalModal" class="close" data-dismiss="modal">&times;</button>
                                    <label for="Plan" class="form-label">Payment Date</label>
                                    <input type="text" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>">
                                    <label for="Plan" class="form-label">Start Date</label>
                                    <input type="text" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>">
                                    <label for="Plan" class="form-label">Amount</label>
                                    <input type="text" class="form-control" readonly id="paypalAmount" name="Plan">
                                    <label for="Plan" class="form-label">Months</label>
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
                                    <input type="hidden" class="">
                                </div>

                                <button type="button" id="pay" class="btn btn-success mt-4">Change Now</button>
                            </div>

                        </form>
                    </div>










                    <div class="grid text-center plans row">
                        <?php $plans = getPlanData($connect); ?>
                        <?php foreach ($plans as $plan) : ?>

                            <div class="col-md-6 shadow-sm p-3 mb-5 bg-body-tertiary rounded">
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

                        <?php endforeach; ?>


                    </div>


                </div>




            </div>

            <?php require_once "../views/footer.php"; ?>
            <script src="https://js.stripe.com/v3/"></script>
            <script>
                document.querySelector("#closestripeModal").addEventListener('click', () => {
                    console.log('eee')
                    document.querySelector(".modal").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                })

                document.querySelector("#closepaypalModal").addEventListener('click', () => {
                    console.log('eee')
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
                const payBtn = document.querySelector("#pay");


                payBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    payBtn.disabled = true;
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


                    // console.log(paymentDate)
                    // console.log(startDate)
                    // console.log(PlanName)
                    // console.log(currency)
                    // console.log(currencyCode)
                    // console.log(PlanAmount)
                    // console.log(selectedMonths)
                    // console.log(PlanID)
                    // return;
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
                    document.getElementById('pay').disabled = true;

                    // Change button content to loader
                    document.getElementById('pay').innerHTML = `
            <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
            <span role="status">Please wait...</span>
        `;
                }
            </script>