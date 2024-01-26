<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../controllers/addPaymentMethod_contr.php';
require_once  '../modals/addPaymentMethod_mod.php';

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

        <!-- content-container -->
        <div id="loader">Loading...</div>
        <div class="main-content">
            <div class="content">




                <!-- Add this to your HTML for the modal -->
                <div class="modal-plan" id="delModal">
                    <div id="modalBackground"></div>
                    <div class="modal-dialog-plan">
                        <div class="modal-content-plan">
                            <div class="modal-header-plan">
                                <h5 class="modal-title-plan">Confirm Delete</h5>
                                <button type="button" id="closeDelModal" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body-plan">
                                <p class="mt-3">Are you sure you want to delete this plan?</p>
                                <input type="hidden" id="delmodalPaymentMethodID" value="">
                            </div>
                            <div class="modal-footer-plan">
                                <p id="errordelmodal"></p>
                                <button type="button" id="delButton" class="btn btn-danger ml-3" onclick="deletePaymentMethodConfirmed()">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>













                <div class="modal-plan" id="PaymentMethodModal">
                    <div id="modalBackground"></div>
                    <div class="modal-dialog-plan">
                        <div class="modal-content-plan">
                            <div class="modal-header-plan">
                                <h5 class="modal-title-plan">Edit </h5>
                                <button type="button" id="closeModal" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body-plan">
                                <input type="hidden" id="hiddenPaymentMethodID" value="">
                                <label for="editPlanPrice">Expense:</label>
                                <input type="text" id="edit-PaymentMethod" class="form-control">
                            </div>
                            <div class="modal-footer-plan">
                                <p id="modalerror"></p>
                                <button type="button" class="btn btn-info" data-plan-id="<?= $plan['PlanID'] ?>" onclick="updatePaymentMethodData(this)">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>












                <form id="PaymentMethodForm" class="row g-3">
                    <div class="col-md-6">
                        <label for="PaymentMethod" class="form-label">Add Payment Method</label>
                        <input type="text" class="form-control" id="PaymentMethodInput">
                        <p id="error"></p>
                        <button type="button" id="addbtn" onclick="addPaymentMethod(); return false;" class="btn btn-primary mt-4">Add</button>
                    </div>
                    <div class="col-md-6">
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action active" aria-current="true">
                                Payment Methods Available
                            </button>
                            <?php
                            $PaymentMethods = getPaymentMethodData($connect);
                            foreach ($PaymentMethods as $PaymentMethod) {
                                $PaymentMethodID = $PaymentMethod['PaymentOptionID'];
                                $PaymentMethod = $PaymentMethod['PaymentOptionName'];

                                echo '<div class="d-flex justify-content-between align-items-center">';
                                echo '<span class="list-group-item list-group-item-action area" aria-current="true" data-area-name="' . $PaymentMethod . '" data-area-id="' . $PaymentMethodID . '">' . $PaymentMethod . '</span>';
                                echo '<button type="button" class="btn btn-info me-3" data-area-id="' . $PaymentMethodID . '" onclick="editPaymentMethod(' . $PaymentMethodID . ', \'' . $PaymentMethod . '\')">Edit</button>';
                                echo '<button type="button" class="btn btn-danger" data-area-id="' . $PaymentMethodID . '" onclick="confirmDelete(' . $PaymentMethodID . ')">Del</button>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>

            <script>
                var loader = document.getElementById("loader");
                var closeModal = document.getElementById("closeModal");
                var addbtn = document.getElementById("addbtn");

                function addPaymentMethod() {
                    console.log("it reads");
                    var PaymentMethodInput = document.getElementById("PaymentMethodInput").value.trim();
                    var isValid = true;
                    addbtn.disabled = true;

                    if (!PaymentMethodInput) {
                        displayMessage("error", "Cannot Be Empty", true);
                        isValid = false;
                        return;
                    }
                    if (!/^[a-zA-Z0-9\s]+$/.test(PaymentMethodInput)) {
                        displayMessage("error", "Invalid characters in Name", true);
                        isValid = false;
                        return;
                    }

                    if (!isValid) {
                        addbtn.disabled = false;
                        return;
                    }


                    if (isValid) {
                        loader.style.display = "flex";
                        // Create a FormData object to send data via Fetch API
                        var formData = new FormData();
                        formData.append("PaymentMethodInput", PaymentMethodInput);
                        // Use Fetch API to send the data
                        fetch('../controllers/addPaymentMethod_contr.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    addbtn.disabled = false;
                                    loader.style.display = "none";
                                    displayMessage("error", "saved Successfuly", false);
                                    document.getElementById("PaymentMethodForm").reset();
                                    location.reload();
                                } else {
                                    displayMessage("error", "Error fetching Data", true);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                displayMessage("error", "something went wrong", true);
                                addbtn.disabled = false;
                                loader.style.display = "none";
                            });
                    }
                }




                function editPaymentMethod(PaymentMethodID, PaymentMethod) {
                    document.getElementById('hiddenPaymentMethodID').value = PaymentMethodID;
                    document.getElementById('edit-PaymentMethod').value = initialPaymentMethod = PaymentMethod;
                    // Show the modal (you need to implement your own showModal function)
                    showModal();
                }



                var initialPaymentMethod = "";



                function updatePaymentMethodData(button) {
                    var PaymentMethodID = document.getElementById('hiddenPaymentMethodID').value;
                    var updatedPaymentMethod = document.getElementById('edit-PaymentMethod').value;
                    var modalInputs = document.querySelectorAll('.modalInput');
                    var isValid = true;


                    if (!updatedPaymentMethod.trim()) {
                        displayMessage("modalerror", "Cannot be empty", true);
                        isValid = false;
                        return;
                    } else if (!/^[a-zA-Z0-9\s]+$/.test(updatedPaymentMethod.trim())) {
                        displayMessage("modalerror", "Only letters and numbers allowed", true);
                        isValid = false;
                        return;
                    }
                    if (!isValid) {
                        return; // Exit the function if validation fails
                    }


                    // Check if changes are made
                    if (updatedPaymentMethod !== initialPaymentMethod) {
                        loader.style.display = "flex";
                        console.log("it loads");
                        // Changes detected, send data for update
                        // Perform your AJAX request here
                        // You can use the Fetch API for this purpose
                        var sendData = new FormData();
                        sendData.append('PaymentMethodID', PaymentMethodID);
                        sendData.append('updatedPaymentMethod', updatedPaymentMethod);

                        fetch('../controllers/updatePaymentMethod_contr.php', {
                                method: 'POST',
                                body: sendData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                    hideModal();
                                    loader.style.display = "none";
                                    displayMessage("modalerror", "Updated Successfuly", false);
                                } else {
                                    // Handle failure (e.g., display an error message)
                                    console.error("Update failed: " + data.error);
                                    displayMessage("modalerror", "Update failed: " + data.error, true);
                                }
                            })
                            .catch(error => {
                                // Handle network or request errors
                                console.error("An error occurred: " + error);
                                displayMessage("modalerror", "An error occurred", true);
                                loader.style.display = "none";
                                setTimeout(() => {
                                    hideModal();
                                }, 2000);
                            });
                    } else {
                        // No changes made
                        displayMessage("modalerror", "No changes made", true);
                    }



                }





                function confirmDelete(PaymentMethodID) {
                    // Set the planId to a hidden input in the delete confirmation modal
                    document.getElementById('delmodalPaymentMethodID').value = PaymentMethodID;
                    // Show the delete confirmation modal
                    showDeleteModal();
                }






                function deletePaymentMethodConfirmed() {
                    var PaymentMethodID = document.getElementById('delmodalPaymentMethodID').value;
                    button = document.getElementById("delButton");
                    button.disabled = true;

                    if (PaymentMethodID !== null) {
                        loader.style.display = "flex";
                        var sendData = new FormData();
                        sendData.append('PaymentMethodID', PaymentMethodID);

                        fetch('../controllers/deletePaymentMethod_contr.php', {
                                method: 'POST',
                                body: sendData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                    hideDeleteModal();
                                    PaymentMethodID = null;
                                    button.disabled = false;
                                    loader.style.display = "none";
                                    displayMessage("errordelmodal", "Deleted Successfuly", false);

                                } else {
                                    // Handle failure (e.g., display an error message)
                                    button.disabled = false;
                                    console.error("Update failed: " + data.error);
                                    displayMessage("errordelmodal", "Delete failed: " + data.error, true);
                                }
                            })
                            .catch(error => {
                                // Handle network or request errors
                                console.error("An error occurred: " + error);
                                displayMessage("errordelmodal", "An error occurred: " + error, true);
                                loader.style.display = "none";
                                button.disabled = false;
                            });
                    } else {
                        displayMessage("errordelmodal", "NULL", false);
                        button.disabled = false;
                    }
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
                        addbtn.disabled = false;
                    }, 2000);
                }


                closeModal.addEventListener('click', function() {
                    hideModal();
                })

                // Show modal and overlay
                function showModal() {
                    document.getElementById('PaymentMethodModal').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                    document.getElementById('overlay').style.transition = '.3s';
                }


                function hideModal() {
                    document.getElementById('PaymentMethodModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }

                function showDeleteModal() {
                    document.getElementById('delModal').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                }


                function hideDeleteModal() {
                    document.getElementById('delModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }
            </script>