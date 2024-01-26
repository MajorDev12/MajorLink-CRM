<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/getClientsNames_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';

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
                        <a class="active" href="#">Home</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>


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
        <div id="loader">Loading...</div>
        <div class="main-content">
            <div class="content">
                <form class="row g-3 form">
                    <div class="form-group">
                        <label for="paymentDate">Payment Date:</label>
                        <input type="date" id="paymentDate" name="paymentDate" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fromDate">From Date:</label>
                        <input type="date" id="fromDate" name="fromDate" required disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="toDate">To Date:</label>
                        <input type="date" id="toDate" name="toDate" required disabled>
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
                            <input type="number" id="AmountPaid" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>



                    <div class="col-md-6">
                        <label for="plan">Plan:</label>
                        <select class="form-select form-select-md" id="plan" aria-label="Default select example">
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
                        <label for="PaymentMethod">Payment Method:</label>
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
                    <p id="errorMsg"></p>
                    <!-- Rest of the form fields remain the same -->
                    <!-- ... -->
                    <div class="form-group">
                        <button type="button" id="addBtn" class="btn btn-primary" onclick="updateDateFields(event)">Add Payment</button>
                    </div>
                </form>
            </div>

            <?php require_once "footer.php"; ?>


            <script>
                var customerList = [];


                $(document).ready(function() {


                    <?php foreach ($clientData as $client) : ?>
                        customerList.push({
                            id: "<?php echo $client['ClientID']; ?>",
                            text: "<?php echo $client['FirstName'] . ' ' . $client['LastName']; ?>",
                            plan: "<?php echo $client['PlanID']; ?>",
                            ClientexpireDate: "<?php echo $client['ExpireDate']; ?>"
                        });
                    <?php endforeach; ?>


                    $("#user_select").select2({
                        data: customerList
                    });


                });




                CancelBtn.addEventListener("click", function() {

                    closeModal();
                });

                closeDelModal.addEventListener("click", function() {

                    closeModal();
                });












                function updateDateFields(event) {
                    event.preventDefault();
                    var loader = document.getElementById("loader");
                    var selectedClientId = $("#user_select").val();
                    var selectedInfo = getSelectedClientInfo(selectedClientId, customerList);
                    var currentPlan = selectedInfo ? selectedInfo.plan : null;
                    var expireDate = selectedInfo ? selectedInfo.expireDate : null;
                    var paymentDate = document.getElementById("paymentDate").value;
                    var selectedPlan = document.getElementById("plan").value;
                    var PaymentMethod = document.getElementById("PaymentMethod").value;
                    var SetfromDate = document.getElementById("fromDate").value;
                    var toDateInput = document.getElementById("toDate").value;
                    var errorMsg = document.getElementById("errorMsg");
                    var planPrice = $("#plan option:selected").data("amount");
                    var amountPaid = $("#AmountPaid").val();
                    var fromDate;


                    if (!selectedClientId, !amountPaid, !selectedPlan, !PaymentMethod, !paymentDate) {
                        displayMessage("errorMsg", "fill in all fields", true);
                        return;
                    }

                    fromDate = expireDate ? String(expireDate) : String(paymentDate);

                    // Check if fromDate is equal to expireDate
                    if (fromDate === expireDate) {
                        // If they are equal, set fromDate to one day ahead from expireDate
                        var nextDay = new Date(expireDate);
                        nextDay.setDate(nextDay.getDate() + 1);
                        fromDate = nextDay.toISOString().split('T')[0];
                    }

                    SetfromDate = fromDate;
                    document.getElementById("fromDate").value = SetfromDate;

                    // Calculate the expiration date based on plan price and amount paid
                    var durationInMonths = Math.floor(amountPaid / planPrice);
                    var balance = amountPaid - (durationInMonths * planPrice);
                    var amountSet = (durationInMonths * planPrice);


                    var calculatedExpireDate = addMonthsToDate(fromDate, durationInMonths);
                    document.getElementById("toDate").value = calculatedExpireDate;

                    if (durationInMonths > 0) {
                        showModal(durationInMonths, SetfromDate, calculatedExpireDate, balance)
                    } else {
                        showEmptyModal();
                    }




                    ContinueBtn.addEventListener("click", function() {
                        closeModal();
                        ContinuePayment();
                    });



                    function ContinuePayment() {
                        // All fields are filled, proceed with sending data through Fetch API
                        loader.style.display = "flex";

                        var formData = new FormData();
                        formData.append("selectedClientId", selectedClientId);
                        formData.append("paymentDate", paymentDate);
                        formData.append("SetfromDate", SetfromDate);
                        formData.append("balance", balance);
                        formData.append("amountSet", amountSet);
                        formData.append("calculatedExpireDate", calculatedExpireDate);
                        formData.append("selectedPlan", selectedPlan);
                        formData.append("PaymentMethod", PaymentMethod);


                        // Perform fetch API request
                        fetch('../controllers/advancePayment_contr.php', {
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
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });


                    }




                }





                function addMonthsToDate(dateString, months) {
                    var date = new Date(dateString);

                    // Check if the date is valid
                    if (isNaN(date.getTime())) {
                        throw new Error("Invalid date");
                    }

                    date.setMonth(date.getMonth() + months);

                    // Check again after adding months
                    if (isNaN(date.getTime())) {
                        throw new Error("Invalid date");
                    }

                    return date.toISOString().split('T')[0];
                }









                function getSelectedClientInfo(selectedClientId, customerList) {
                    var selectedClient = customerList.find(client => client.id === selectedClientId);
                    return selectedClient ? {
                        plan: selectedClient.plan,
                        expireDate: selectedClient.ClientexpireDate
                    } : null;
                }



                function showModal(durationInMonths, SetfromDate, calculatedExpireDate, balance) {

                    // Display the modal with the balance information
                    var modalContent = document.querySelector(".modal-body p");
                    var shouldFormat = balance.toLocaleString(undefined, {
                        style: "currency",
                        currency: "KSH"

                    });

                    // Update modal content with the calculated balance
                    modalContent.innerText = `The Advance Payment Will Run For ${durationInMonths} Months and Start on ${SetfromDate} to ${calculatedExpireDate}.The Remaining Balance of ${shouldFormat} Will Be Sent To The Clients Account`;

                    document.getElementById("deleteModal").style.display = "block";
                    document.getElementById("modalBackground").style.display = "block";
                    document.getElementById("ContinueBtn").style.display = "block";

                }

                showEmptyModal

                function showEmptyModal() {

                    // Display the modal with the balance information
                    var modalContent = document.querySelector(".modal-body p");


                    // Update modal content with the calculated balance
                    modalContent.innerText = `Amount Entered is Less than the Selected Plan`;

                    document.getElementById("deleteModal").style.display = "block";
                    document.getElementById("modalBackground").style.display = "block";
                    document.getElementById("ContinueBtn").style.display = "none";

                }



                function displayMessage(messageElement, message, isError, ) {
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
                    }, 1000);
                }



                function closeModal() {
                    // Close the modal
                    document.getElementById("deleteModal").style.display = "none";
                    document.getElementById("modalBackground").style.display = "none";
                }




                //toast function
                function checkAndShowToastAfterReload() {

                    if (localStorage.getItem('AddNewClientPaymentToast') === 'true') {
                        showToast('Congratulations! you\'ve Just Added a New Payment', 9000);

                        // Reset the flag after showing the toast
                        localStorage.removeItem('AddNewClientPaymentToast');
                    }


                }


                // Call the function after the page loads
                window.onload = function() {
                    setTimeout(() => {
                        checkAndShowToastAfterReload();
                    }, 3000);
                };
            </script>