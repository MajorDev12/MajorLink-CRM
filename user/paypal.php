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

$clientID = $_SESSION["clientID"];



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

                                <input type="text" class="form-control" disabled id="Plan" name="Plan" value="<?= $clientData['PlanName'] . ' ' . ' - ' . $clientData['Plan'] ?>">
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


                        <div id="errorMsg"></div>

                        <div class="form-group col-md-8 mt-4 text-center">
                            <div id="paymentButton"></div>
                            <!-- <button type="button" id="paymentButton" class="btn p-2 border-none" style="width: 100%;">Proceed to Paypal</button> -->
                        </div>

                    </form>
                </div>
            </div>

            <script src="https://www.paypal.com/sdk/js?client-id=Ae4orbdIICDrUSBdOqXB0HbAwz41DvXZwYt9UXlCOska-hYHUEw2YkXEblL0N4VNgBmtAt9G8H7Gq1Mt&currency=USD&disable-funding=credit,card"></script>
            <script>
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        // var planId = $('#Plan').val();
                        var amount = $('#PlanAmount').val();
                        var startDate = $('#startDate').val();
                        var paymentDate = $('#paymentDate').val();

                        if (!amount) {
                            displayMessage("errorMsg", "Please enter amount to be paid before proceeding.", true);
                            return actions.reject();
                        }

                        var startDate = $('#startDate').val();
                        var expireDate = startDate;

                        <?php $settings = get_Settings($connect); ?>
                        var originalExpireDate = new Date(expireDate);
                        var timezone = <?= json_encode($settings[0]["TimeZone"]); ?>;
                        // console.log(timezone);
                        // Create a Date object with the specified timezone
                        var dateOptions = {
                            timeZone: timezone
                        };

                        var originalExpireDate = new Date(expireDate);

                        // Perform operations on the originalExpireDate
                        originalExpireDate.setMonth(originalExpireDate.getMonth() + 1);

                        // Format the updated date
                        var expireDate = originalExpireDate.toLocaleString('en-US', dateOptions);


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
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            console.log(data)
                            return;
                            // Send details to your server for verification.
                            $.ajax({
                                url: '../controllers/process_Paypalpayment_contr.php', // Replace with your server-side processing script
                                method: 'POST',
                                data: {
                                    orderID: data.orderID,
                                    payerID: data.payerID,
                                    payerEmail: data.payerID,
                                    paymentID: details.id,
                                },
                                success: function(response) {
                                    // Handle the response from the server
                                    alert(response.message);
                                    if (response.success) {
                                        // Payment was successful, redirect or perform further actions
                                        // window.location.href = 'index.php?success=payment success';
                                    } else {
                                        // Payment failed, handle accordingly
                                        // window.location.href = 'index.php?error=payment error';
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

            <?php require_once "../views/footer.php"; ?>