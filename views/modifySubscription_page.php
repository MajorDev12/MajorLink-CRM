<div class="page modifySubscription">
    <h4 class="border-bottom pb-3 mb-5">Change Subscription Plan</h4>
    <form id="modifySubscriptionForm">
        <!-- Existing form elements -->

        <!-- Choose Plan Section -->
        <div class="row mt-5">
            <div class="col-md-6">
                <label for="firstname">Choose Plan</label>
                <select name="" id="Plans" class="form-select">
                    <option value="" selected disabled>Choose...</option>
                    <?php
                    $plans = getPlanData($connect);

                    foreach ($plans as $plan) {
                        // Check if the plan is not equal to the client's current plan
                        if ($plan['PlanID'] != $clientData['PlanID']) {
                            echo "<option value=\"{$plan['PlanID']}\" data-amount=\"{$plan['Price']}\">{$plan['Volume']}</option>";
                        }
                    }
                    ?>
                </select>
                <p class="currentPlan"><?php echo empty($clientData['Plan']) ? 'Not subscribed yet' : 'Current Plan: ' . $clientData['Plan']; ?></p>
            </div>

        </div>



        <!-- Subscription Options Section -->

        <div class="form-check form-switch col-md-6">
            <input class="form-check-input" type="radio" name="subscriptionOption" id="applyNowSwitch">
            <label class="form-check-label" for="applyNowSwitch">Apply Now</label>
        </div>

        <!-- Receipt Section -->
        <div id="receiptSection" class="mt-5" style="display: none;">
            <!-- Add your receipt details here -->
            <div class="row mt-5">
                <div class="form-group col-md-6">
                    <label for="paymentDate">Payment Date</label>
                    <input type="date" class="form-control" id="paymentDate" name="paymentDate" value="<?php echo date('Y-m-d'); ?>" disabled required>
                </div>

                <div class="form-group col-md-6">
                    <label for="saleDate">Payment Amount (<?= $symbol; ?>)</label>
                    <input type="number" class="form-control" id="amountInput" name="amountInput" disabled required>
                </div>
            </div>
            <div class="row mt-4">
                <div class="form-group col-md-6">
                    <label for="PaymentOption">Payment Method</label>
                    <select class="form-select" name="PaymentOption" id="PaymentOption">
                        <option value="" selected disabled>Choose</option>
                        <?php
                        $methods = getPaymentMethods($connect);

                        foreach ($methods as $method) {
                            $selected = ''; // Adjust this based on your logic for selecting a default method
                            echo '<option value="' . $method['PaymentOptionID'] . '" ' . $selected . ' data-method-id="' . $method['PaymentOptionName'] . '">' . $method['PaymentOptionName'] . '</option>';
                        }

                        ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="paymentStatus">Payment Status:</label>
                    <select class="form-select" name="" id="paymentStatus">
                        <option selected disabled value="">Choose</option>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="row mt-5">
                <div class="form-group col-md-6">
                    <label for="Total">Total (<?= $symbol; ?>)</label>
                    <input type="text" id="Total" class="form-control" name="Total" disabled value="">
                </div>
            </div>

            <p id="suberror"></p>

            <div class="row mt-4">
                <button type="submit" id="updateSubscriptionBtn" class="btn btn-success col-md-4" onclick="updateSubscriptionPlan(event)">Save Changes</button>
            </div>



        </div>

    </form>
</div>

<!-- Existing HTML code -->

<script>
    const applyNowSwitch = document.getElementById('applyNowSwitch');
    const receiptSection = document.getElementById('receiptSection');
    const loader = document.getElementById('loader');


    applyNowSwitch.addEventListener('change', function() {
        // Show or hide the receipt section based on the radio button state
        receiptSection.style.display = applyNowSwitch.checked ? 'block' : 'none';
        applynextBtn.style.display = applyNowSwitch.checked ? 'none' : 'block';

    });



    $(document).ready(function() {
        $('#Plans').change(function() {
            var selectedPlan = $(this).find(':selected');
            var planAmountInput = $('#amountInput');
            var totalAmount = $('#Total');

            if (!isNaN(selectedPlan.val())) {
                var planPrice = selectedPlan.data('amount');
                planAmountInput.val(planPrice);
                totalAmount.val(planPrice);
            } else {
                planAmountInput.val(''); // Clear the input if the selected option is not a number
            }


        });
    });





    function updateSubscriptionPlan(event) {
        event.preventDefault();

        const clientId = <?= json_encode($clientData["ClientID"]); ?>;

        // Get selected plan details
        var selectedPlan = document.getElementById('Plans');
        var selectedPlanID = selectedPlan.value;
        // var selectedPlanAmount = selectedPlan.options[selectedPlan.selectedIndex].getAttribute('data-amount');
        var selectedPlanAmount = document.getElementById('amountInput').value;
        var paymentDate = document.getElementById('paymentDate').value;
        var paymentMethodID = document.getElementById('PaymentOption').value;
        var paymentStatus = document.getElementById('paymentStatus').value;
        var InstallationFees = null;

        if (
            !clientId ||
            !selectedPlanID ||
            !selectedPlanAmount ||
            !paymentDate ||
            !paymentMethodID ||
            !paymentStatus
        ) {
            // Display an error message or handle the empty fields as needed
            displayMessage("suberror", "Error: All fields must be filled", true);
            return;
        } else {


            loader.style.display = "flex";
            // All fields are filled, proceed with sending data through Fetch API
            var formData = new FormData();
            formData.append("clientId", clientId);
            formData.append("selectedPlanID", selectedPlanID);
            formData.append("selectedPlanAmount", selectedPlanAmount);
            formData.append("paymentDate", paymentDate);
            formData.append("paymentMethodID", paymentMethodID);
            formData.append("paymentStatus", paymentStatus);
            formData.append("InstallationFees", InstallationFees);

            // Perform fetch API request
            fetch('../controllers/addPayment_contr.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.paymentSuccess) {
                        // Handle the response from the server
                        displayMessage("suberror", "Successfuly updated", false);
                        // Set a flag in local storage to indicate that the toast should be displayed after reload
                        localStorage.setItem('UpdateClientPlanToast', 'true');
                        setTimeout(() => {
                            loader.style.display = "none";
                        }, 2000);
                        location.reload();

                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });


        }
    }
</script>