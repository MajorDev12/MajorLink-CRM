<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
// require_once  '../controllers/addarea_contr.php';
require_once  '../modals/getClientsNames_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';
require_once  '../modals/addPlan_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>
<?php require_once "header.php"; ?>


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

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>
        <div id="loader">Loading</div>



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


                <!-- Add this to your HTML for the modal -->
                <div class="modal" id="deleteModal">
                    <div id="modalBackground"></div>
                    <div class="modal-dialog">
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
                </div>





                <form class="row g-3 form">
                    <div class="form-group">
                        <label for="paymentDate">Payment Date:</label>
                        <input type="date" id="paymentDate" name="paymentDate" required>
                    </div>
                    <div class="col-md-6">
                        <?php $clientData = getClientsNames($connect); ?>
                        <label for="salesperson">Customer:</label>
                        <select id="user_select" name="user_id" class="form-select form-select-md pb-4">
                            <option value="" selected hidden>--Search--</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="customer">Amount Paid:</label>
                        <div class="input-group">
                            <input type="number" id="Amount" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
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


                    <div class="col-md-4 mt-4">
                        <label for="Product">Payment Reference:</label>
                        <div class="input-group">
                            <?php
                            // Generate a unique payment reference
                            $paymentReference = '#MJRLNK' . time();
                            ?>
                            <input type="text" id="paymentReference" class="form-control" aria-label="Payment Reference" value="<?php echo $paymentReference; ?>" readonly disabled>
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


                function addPayment(event) {
                    event.preventDefault();
                    var loader = document.getElementById("loader");
                    var paymentDate = document.getElementById("paymentDate").value;
                    var ClientId = document.getElementById("user_select").value;
                    var Amount = document.getElementById("Amount").value;
                    var selectedPlan = getSelectedPlan(ClientId, customerList);
                    var PlanId = document.getElementById('plans').value;
                    var planPrice = $("#plans option:selected").data("amount");
                    var PaymentMethod = document.getElementById("PaymentMethod").value;
                    var PaymentStatus = document.getElementById("PaymentStatus").value;
                    var InstallationFees = document.getElementById("InstallationFees").value;
                    var paymentReference = document.getElementById("paymentReference").value;
                    var ContinueBtn = document.getElementById("ContinueBtn");
                    var balance = 0;


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
                        !PaymentStatus ||
                        !paymentReference
                    ) {
                        displayMessage("errorMsg", "Fill In All The Required Fields", true);
                        return;
                    }



                    ContinueBtn.addEventListener("click", function() {
                        ContinuePayment();
                    });

                    //check if advance is set  - check if records of advancePayment is set
                    //                         - get all fromDate of the advancePayments and check:
                    //                         - if 
                    //if advance is set cancel
                    //redirect to advance page


                    if (Amount > planPrice) {
                        // Calculate the balance
                        balance = Amount - planPrice;
                        // Show the modal with the calculated balance
                        showModal(balance);
                        return;
                    }


                    if (Amount < planPrice) {
                        // Calculate the balance
                        balance = Amount - planPrice;
                        // Show the modal with the calculated balance
                        showlessAmountModal(balance);
                        return;
                    }


                    showAmountModal();

                    function ContinuePayment() {


                        // All fields are filled, proceed with sending data through Fetch API
                        loader.style.display = "flex";

                        var formData = new FormData();
                        formData.append("ClientId", ClientId);
                        formData.append("paymentDate", paymentDate);
                        formData.append("Amount", Amount);
                        formData.append("balance", balance);
                        formData.append("PlanId", PlanId);
                        formData.append("PaymentMethod", PaymentMethod);
                        formData.append("PaymentStatus", PaymentStatus);
                        formData.append("InstallationFees", InstallationFees);
                        formData.append("paymentReference", paymentReference);



                        // Perform fetch API request
                        fetch('../controllers/makePayment_contr.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.paymentSuccess) {
                                    // Handle the response from the server
                                    displayMessage("errorMsg", "Successfuly updated", false);
                                    localStorage.setItem('AddNewClientPaymentToast', 'true');
                                    location.reload();
                                    setTimeout(() => {
                                        loader.style.display = "none";
                                    }, 2000);
                                }
                                if (data.advancePaid) {
                                    closeModal();
                                    loader.style.display = "none";
                                    displayMessage("errorMsg", "the customer has subscribed to an advance Payment", false);
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



                }








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
                    document.getElementById("modalBackground").style.display = "block";
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
                    document.getElementById("modalBackground").style.display = "block";
                    document.getElementById("ContinueBtn").style.display = "none";


                }




                function showAmountModal() {

                    // Display the modal with the balance information
                    var modalContent = document.querySelector(".modal-body p");

                    // Update modal content with the calculated balance
                    modalContent.innerText = `Confirm payment`;

                    document.getElementById("deleteModal").style.display = "block";
                    document.getElementById("modalBackground").style.display = "block";


                }




                function closeModal() {
                    // Close the modal
                    document.getElementById("deleteModal").style.display = "none";
                    document.getElementById("modalBackground").style.display = "none";
                }




                // error message function
                function displayMessage(messageElement, message, isError, ) {
                    var targetElement = document.getElementById(messageElement);
                    targetElement.innerText = message;

                    if (isError) {
                        targetElement.style.color = 'red';
                    } else {
                        targetElement.style.color = 'green';
                    }
                    setTimeout(function() {
                        targetElement.innerText = '';
                    }, 1000);
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