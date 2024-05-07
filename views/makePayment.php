<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
// require_once  '../controllers/addarea_contr.php';
require_once  '../modals/getClientsNames_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/setup_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

$settings = get_Settings($connect);
$symbol = $settings[0]["CurrencySymbol"];
?>
<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>

<style>
    select {
        background-color: var(--light);
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
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Make Payment</a>
                    </li>
                </ul>
            </div>

        </div>
        <div id="loader">Processing...</div>



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


        <!-- content-container -->
        <div class="main-content">
            <div class="content">

                <div id="overlay"></div>
                <!-- Add this to your HTML for the modal -->
                <div class="modal-container" id="deleteModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Payment</h5>
                            <button type="button" id="closeDelModal" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <p class="mt-3 pb-1"></p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="ContinueBtn" class="btn btn-success ml-3">Continue</button>
                            <button type="button" id="CancelBtn" class="btn btn-danger ml-3">Cancel</button>
                        </div>
                    </div>
                </div>





                <form class="row g-3 form">
                    <div class="form-group">
                        <label for="paymentDate">Payment Date:</label>
                        <input type="date" id="paymentDate" value="<?= date('Y-m-d'); ?>" name="paymentDate" required>
                    </div>
                    <div class="col-md-6">
                        <?php $clientData = getClientsNames($connect); ?>
                        <label for="salesperson">Customer:</label>
                        <select id="user_select" name="user_id" class="form-select form-select-md pb-4">
                            <option value="" selected hidden>--Search--</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="customer">Amount Paid (<?= $symbol; ?>)</label>
                        <div class="input-group">
                            <input type="number" id="Amount" readonly class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>



                    <div class="col-md-6">
                        <label for="Product">Plan:</label>
                        <select class="form-select form-select-md" id="plans" aria-label="Default select example">
                            <option value="" selected disabled>Choose...</option>
                            <?php
                            $plans = getPlanData($connect);

                            foreach ($plans as $plan) {
                                $selected = ($plan['PlanID'] == $clientData['PlanID']) ? 'selected' : '';
                                echo "<option value=\"{$plan['PlanID']}\" {$selected} data-amount=\"{$plan['Price']}\">{$plan['Volume']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="Product">Payment Method:</label>
                        <select class="form-select form-select-md" id="PaymentMethod" aria-label="Default select example">
                            <option value="" selected disabled>Choose...</option>
                            <?php
                            $methods = getPaymentMethods($connect);

                            foreach ($methods as $method) {
                                $selected = ''; // Adjust this based on your logic for selecting a default method
                                echo '<option value="' . $method['PaymentOptionID'] . '" ' . $selected . ' data-method-id="' . $method['PaymentOptionName'] . '">' . $method['PaymentOptionName'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="Product">Payment Status:</label>
                        <select class="form-select form-select-md" id="PaymentStatus" aria-label="Default select example">
                            <option value="" selected disabled>Choose...</option>
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="customer">Installation Fees:</label>
                        <div class="input-group">
                            <input type="number" id="InstallationFees" name="InstallationFees" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>




                    <p id="errorMsg"></p>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="addPayment(event)">Add Payment</button>
                    </div>
                </form>
            </div>


            <?php require_once "footer.php";  ?>

            <script>
                var customerList = [];

                $(document).ready(function() {


                    <?php foreach ($clientData as $client) : ?>
                        customerList.push({
                            id: "<?php echo $client['ClientID']; ?>",
                            text: "<?php echo $client['FirstName'] . ' ' . $client['LastName']; ?>",
                            plan: "<?php echo $client['PlanID']; ?>"
                        });
                    <?php endforeach; ?>


                    $("#user_select").select2({
                        data: customerList
                    });




                });


                // Function to get the plan based on the selected client
                function getSelectedPlan(selectedClientId, customerList) {
                    var selectedClient = customerList.find(client => client.id === selectedClientId);
                    return selectedClient ? selectedClient.plan : null;
                }




                var CancelBtn = document.getElementById("CancelBtn");
                var closeDelModal = document.getElementById("closeDelModal");

                CancelBtn.addEventListener("click", function() {

                    closeModal();
                });

                closeDelModal.addEventListener("click", function() {

                    closeModal();
                });

                var ClientId;
                var loader;
                var paymentDate;
                var Amount;
                var selectedPlan;
                var PlanId;
                var planPrice;
                var PaymentMethod;
                var PaymentStatus;
                var InstallationFees;
                var ContinueBtn = document.getElementById("ContinueBtn");

                function addPayment(event) {
                    event.preventDefault();
                    loader = document.getElementById("loader");
                    paymentDate = document.getElementById("paymentDate").value;
                    ClientId = document.getElementById("user_select").value;
                    Amount = document.getElementById("Amount").value;
                    selectedPlan = getSelectedPlan(ClientId, customerList);
                    PlanId = document.getElementById('plans').value;
                    planPrice = $("#plans option:selected").data("amount");
                    PaymentMethod = document.getElementById("PaymentMethod").value;
                    PaymentStatus = document.getElementById("PaymentStatus").value;
                    InstallationFees = document.getElementById("InstallationFees").value;



                    if (!selectedPlan) {
                        if (!InstallationFees) {
                            displayMessage("errorMsg", "Installation fees Must be Filled For New Clients", true);
                            return;
                        }
                    }


                    if (!InstallationFees) {
                        InstallationFees = null;
                    }



                    if (!ClientId ||
                        !paymentDate ||
                        !Amount ||
                        !PlanId ||
                        !PaymentMethod ||
                        !PaymentStatus
                    ) {
                        displayMessage("errorMsg", "Fill In All The Required Fields", true);
                        return;
                    }

                    //check if advance is set  - check if records of advancePayment is set
                    //                         - get all fromDate of the advancePayments and check:
                    //                         - if 
                    //if advance is set cancel
                    //redirect to advance page
                    showAmountModal();
                }


                ContinueBtn.addEventListener("click", function() {
                    ContinuePayment();
                });



                function ContinuePayment() {


                    closeModal();
                    loader.style.display = "flex";

                    var formData = new FormData();
                    formData.append("ClientId", ClientId);
                    formData.append("paymentDate", paymentDate);
                    formData.append("Amount", Amount);
                    formData.append("PlanId", PlanId);
                    formData.append("PaymentMethod", PaymentMethod);
                    formData.append("PaymentStatus", PaymentStatus);
                    formData.append("InstallationFees", InstallationFees);


                    // Perform fetch API request
                    fetch('../controllers/makePayment_contr.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.paymentSuccess) {
                                showAmountModal();
                                loader.style.display = "none";
                                // Handle the response from the server
                                displayMessage("errorMsg", "Successfuly updated", false);
                                localStorage.setItem('AddNewClientPaymentToast', 'true');
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            }
                            if (data.advancePaid) {
                                closeModal();
                                loader.style.display = "none";
                                displayMessage("errorMsg", "the customer has subscribed to an advance Payment", true);
                                localStorage.setItem('ClientHasAdvancePaymentToast', 'true');
                                setTimeout(() => {
                                    location.reload();
                                }, 3000);

                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });


                }









                $(document).ready(function() {
                    $('#plans').change(function() {
                        var selectedPlan = $(this).find(':selected');
                        var planAmountInput = $('#Amount');

                        if (!isNaN(selectedPlan.val())) {
                            var planPrice = selectedPlan.data('amount');
                            planAmountInput.val(planPrice);
                        } else {
                            planAmountInput.val(''); // Clear the input if "Choose..." is selected
                        }
                    });
                });



                function showModal(balance) {

                    // Display the modal with the balance information
                    var modalContent = document.querySelector(".modal-body p");
                    var shouldFormat = balance.toLocaleString(undefined, {
                        style: "currency",
                        currency: "KSH"

                    });

                    // Update modal content with the calculated balance
                    modalContent.innerText = `You Exceeded The Amount For The Plan Selected By ${shouldFormat} Would You Want To Make Payment and Send Balance To Your Account?`;

                    document.getElementById("deleteModal").style.display = "block";
                    document.getElementById("overlay").style.display = "block";
                    document.getElementById("ContinueBtn").style.display = "block";

                }


                function showlessAmountModal(balance) {

                    // Display the modal with the balance information
                    var modalContent = document.querySelector(".modal-body p");
                    var shouldFormat = balance.toLocaleString(undefined, {
                        style: "currency",
                        currency: "KSH"

                    });

                    // Update modal content with the calculated balance
                    modalContent.innerText = `The Amount is less ${shouldFormat} to subscribe to the selected plan`;

                    document.getElementById("deleteModal").style.display = "block";
                    document.getElementById("overlay").style.display = "block";
                    document.getElementById("ContinueBtn").style.display = "none";


                }




                function showAmountModal() {

                    // Display the modal with the balance information
                    var modalContent = document.querySelector(".modal-body p");

                    // Update modal content with the calculated balance
                    modalContent.innerText = `Confirm payment`;

                    document.getElementById("deleteModal").style.display = "block";
                    document.getElementById("overlay").style.display = "block";


                }




                function closeModal() {
                    // Close the modal
                    document.getElementById("deleteModal").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                }



                // Call the function after the page loads
                window.onload = function() {
                    setTimeout(() => {
                        checkAndShowToastAfterReload();
                    }, 3000);
                };



                //toast function
                function checkAndShowToastAfterReload() {

                    if (localStorage.getItem('AddNewClientPaymentToast') === 'true') {
                        showToast('Congratulations! you\'ve Just Added a New Payment, Business I Booming', 9000);

                        // Reset the flag after showing the toast
                        localStorage.removeItem('AddNewClientPaymentToast');
                    }
                    if (localStorage.getItem('ClientHasAdvancePaymentToast') === 'true') {
                        var counter = 9; // Set the initial counter value

                        // Show the toast with the initial counter value
                        showToast('It seems the customer has made an advance payment. Please use our Advance Payment portal for further transactions. Redirecting in:' + counter + ' seconds.', 9000);

                        // Update the counter every second
                        var counterInterval = setInterval(function() {
                            counter--;

                            // Update the toast with the updated counter value
                            showToast('It seems the customer has made an advance payment. Please use the Advance Payment portal for further transactions. I Am Redirecting in:' + counter + ' seconds.', 9000);

                            if (counter <= 0) {
                                // Reset the flag after showing the toast
                                localStorage.removeItem('ClientHasAdvancePaymentToast');

                                // Stop the counter interval
                                clearInterval(counterInterval);

                                // Redirect the user after the specified time (10 seconds)
                                setTimeout(function() {
                                    // Replace 'your_redirect_url' with the actual URL you want to redirect to
                                    window.location.href = 'advancePayment.php';
                                }, 0); // Use 0 milliseconds to execute the redirect as soon as possible
                            }
                        }, 1000); // Update the counter every second
                    }




                }
            </script>