<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../controllers/addExpenseType_contr.php';
require_once  '../modals/addExpenseType_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>
<?php require_once "header.php"; ?>
<style>
    .icon {
        background-color: var(--blue);
        border-radius: 5px;
        padding: 4px;
        cursor: pointer;
    }

    .icon img {
        width: 30px;
    }

    #delImg {
        width: 25px;
    }

    .view {
        background-color: var(--blue);
    }

    .pdf {
        background-color: var(--yellow);
    }

    .print {
        background-color: var(--red);
    }

    .area {
        background-color: var(--light);
        color: var(--light-dark);
        border-right: none;
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
                        <a class="active" href="#">Home</a>
                    </li>
                </ul>
            </div>


        </div>

        <!-- content-container -->
        <div id="loader" class="loader">Loading...</div>
        <div id="overlay"></div>

        <div class="main-content">
            <div class="content">




                <!-- Add this to your HTML for the modal -->
                <div class="modal-container" id="delModal">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" id="closeDelModal" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="mt-3">Are you sure you want to delete this plan?</p>
                            <input type="hidden" id="hiddenExpenseTypeId" value="">
                        </div>
                        <div class="modal-footer">
                            <p id="errordelmodal"></p>
                            <button type="button" id="delButton" class="btn btn-danger ml-3" onclick="deleteExpenseTypeConfirmed()">Delete</button>
                        </div>
                    </div>
                </div>








                <div class="modal-container" id="expenseTypeModal">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit </h5>
                            <button type="button" id="closeModal" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="expenseTypeId" value="">
                            <label for="editPlanPrice">Expense:</label>
                            <input type="text" id="edit-expenseType" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <p id="modalerror"></p>
                            <button type="button" class="btn btn-info" data-plan-id="<?= $plan['PlanID'] ?>" onclick="updatePlanData(this)">Save Changes</button>
                        </div>
                    </div>

                </div>














                <form id="expenseform" class="row g-3">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Add Expense</label>
                        <input type="text" class="form-control" id="expenseType">
                        <p id="error"></p>
                        <button type="button" id="addbtn" onclick="addExpense(); return false;" class="btn btn-primary mt-4">Add</button>
                    </div>
                    <div class="col-md-6">
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action active" aria-current="true">
                                The Expenses Available
                            </button>
                            <?php
                            $expenses = getExpenseTypeData($connect);
                            foreach ($expenses as $expense) {
                                $expenseTypeID = $expense['ExpenseTypeID'];
                                $expenseTypeName = $expense['ExpenseTypeName'];

                                echo '<div class="d-flex justify-content-between align-items-center">';
                                echo '<span class="list-group-item list-group-item-action area" aria-current="true" data-area-name="' . $expenseTypeName . '" data-area-id="' . $expenseTypeID . '">' . $expenseTypeName . '</span>';

                                echo '<a href="#" class="icon view ml-3" data-area-id="' . $expenseTypeID . '" onclick="editPlan(' . $expenseTypeID . ', \'' . $expenseTypeName . '\')"><img src="../img/eyeIcon.png" alt=""></a>';

                                // echo '<a href="#" class="icon print"  data-area-id="' . $expenseTypeID . '" onclick="confirmDelete(' . $expenseTypeID . ')"><img id="delImg" src="../img/deleteIcon.png" alt=""></a>';

                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
            <?php require_once "footer.php"; ?>



            <script>
                var loader = document.getElementById("loader");
                var closeModal = document.getElementById("closeModal");
                var closeDelModal = document.getElementById("closeDelModal");

                function addExpense() {
                    var expenseType = document.getElementById("expenseType").value.trim();
                    var addbtn = document.getElementById("addbtn");
                    var isValid = true;
                    addbtn.disabled = true;


                    if (!expenseType) {
                        isValid = false;
                        displayMessage("error", "Cannot Be Empty", true);
                        addbtn.disabled = false;
                        return;
                    }
                    if (!/^[a-zA-Z0-9\s]+$/.test(expenseType)) {
                        displayMessage("error", "Invalid characters in Name", true);
                        isValid = false;
                        addbtn.disabled = false;
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
                        formData.append("expenseType", expenseType);
                        // Use Fetch API to send the data
                        fetch('../controllers/addExpenseType_contr.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    addbtn.disabled = false;
                                    loader.style.display = "none";
                                    displayMessage("error", "saved Successfuly", false);
                                    document.getElementById("expenseform").reset();
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



                function editPlan(expenseTypeID, expenseTypeName) {
                    document.getElementById('expenseTypeId').value = expenseTypeID;
                    document.getElementById('edit-expenseType').value = initialexpenseType = expenseTypeName;
                    // Show the modal (you need to implement your own showModal function)
                    showModal();
                }


                var initialexpenseType = "";


                // Function to check for changes and send data for update
                function updatePlanData(button) {
                    var expenseTypeID = document.getElementById('expenseTypeId').value;
                    var updatedexpenseType = document.getElementById('edit-expenseType').value;
                    var modalInputs = document.querySelectorAll('.modalInput');
                    var isValid = true;

                    if (!updatedexpenseType.trim()) {
                        displayMessage("modalerror", "Cannot be empty", true);
                        isValid = false;
                        return;
                    } else if (!/^[a-zA-Z0-9\s]+$/.test(updatedexpenseType.trim())) {
                        displayMessage("modalerror", "Only letters and numbers allowed", true);
                        isValid = false;
                        return;
                    }
                    if (!isValid) {
                        return; // Exit the function if validation fails
                    }

                    // Check if changes are made
                    if (updatedexpenseType !== initialexpenseType) {
                        document.getElementById('expenseTypeModal').style.display = 'none';
                        loader.style.display = "flex";

                        // Changes detected, send data for update
                        // Perform your AJAX request here
                        // You can use the Fetch API for this purpose
                        var sendData = new FormData();
                        sendData.append('expenseTypeID', expenseTypeID);
                        sendData.append('updatedexpenseType', updatedexpenseType);

                        fetch('../controllers/updateExpenseType_contr.php', {
                                method: 'POST',
                                body: sendData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                    loader.style.display = "none";
                                    // displayMessage("modalerror", "Updated Successfuly", false);
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






                function confirmDelete(ExpenseTypeId) {
                    // Set the planId to a hidden input in the delete confirmation modal
                    document.getElementById('hiddenExpenseTypeId').value = ExpenseTypeId;
                    // Show the delete confirmation modal
                    showDeleteModal();
                }






                function deleteExpenseTypeConfirmed() {
                    var ExpenseTypeId = document.getElementById('hiddenExpenseTypeId').value;
                    button = document.getElementById("delButton");
                    button.disabled = true;

                    if (ExpenseTypeId !== null) {
                        loader.style.display = "flex";
                        var sendData = new FormData();
                        sendData.append('ExpenseTypeId', ExpenseTypeId);

                        fetch('../controllers/deleteExpenseType_contr.php', {
                                method: 'POST',
                                body: sendData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                    hideDeleteModal();
                                    ExpenseTypeId = null;
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
                    }, 2000);
                }


                closeModal.addEventListener('click', function() {
                    hideModal();
                })


                closeDelModal.addEventListener('click', function() {
                    hideDeleteModal();
                })



                // Show modal and overlay
                function showModal() {
                    document.getElementById('expenseTypeModal').style.display = 'block';
                    document.getElementById('overlay').style.display = 'flex';
                    document.getElementById('overlay').style.transition = '.3s';
                }


                function hideModal() {
                    document.getElementById('expenseTypeModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }

                function showDeleteModal() {
                    document.getElementById('delModal').style.display = 'block';
                    document.getElementById('overlay').style.display = 'flex';
                }


                function hideDeleteModal() {
                    document.getElementById('delModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }
            </script>