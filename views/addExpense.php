<?php require_once "../controllers/session_Config.php"; ?>
<?php require_once "header.php"; ?>
<?php require_once "style.config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addExpenseType_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
$expenseTypes = getExpenseTypeData($connect);
$methods = getPaymentMethods($connect);

?>

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
        <div class="main-content">
            <div class="content">
                <div class="row g-3 form">
                    <div class="form-group">
                        <label for="expenseDate">Expense Date:</label>
                        <input type="date" value="<?= date('Y-m-d'); ?>" id="expenseDate">
                    </div>

                    <div class="col-md-6">
                        <label for="CategoryType">Category Type:</label>
                        <select class="form-select form-select-md mb-3" id="expenseSelected" aria-label="Default select example">
                            <option value="" disabled selected>Choose...</option>
                            <?php foreach ($expenseTypes as $expenseType) : ?>
                                <option value="<?= $expenseType["ExpenseTypeID"]; ?>"><?= $expenseType["ExpenseTypeName"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="amount">Amount Spent:</label>
                        <div class="input-group">
                            <input type="number" id="amountSpent" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="PaymentAmount">Payment Option:</label>
                        <select class="form-select form-select-md" id="methodSelected" aria-label="Default select example">
                            <option value="" disabled selected>Choose...</option>
                            <?php foreach ($methods as $method) : ?>
                                <option value="<?= $method["PaymentOptionID"]; ?>"><?= $method["PaymentOptionName"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="PaymentReciept">Payment Receipt:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="PaymentReciept" aria-describedby="inputGroupFileAddon04" aria-label="Upload" onchange="sanitizeFileInput(event)">
                        </div>
                    </div>



                    <div class="col-md-12">
                        <label for="">Expense Description</label>
                        <textarea class="p-3" name="expenseDescription" id="expenseDescription" cols="100" rows="10"></textarea>
                    </div>
                    <!-- Rest of the form fields remain the same -->
                    <!-- ... -->
                    <p id="errorMsg"></p>
                    <div class="form-group">
                        <button type="button" id="addExpenseBtn" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>


            <?php require_once "footer.php"; ?>

            <script>
                var addBtn = document.getElementById("addExpenseBtn");
                var expenseDate = document.getElementById("expenseDate");
                var expenseSelected = document.getElementById("expenseSelected");
                var amountSpent = document.getElementById("amountSpent");
                var methodSelected = document.getElementById("methodSelected");
                var PaymentRecieptInput = document.getElementById("PaymentReciept");
                // var PaymentRecieptFile = PaymentRecieptInput.files[0];
                var expenseDescription = document.getElementById("expenseDescription");



                addBtn.addEventListener("click", function(event) {
                    event.preventDefault();
                    if (!expenseDate.value || !expenseSelected.value || !amountSpent.value || !methodSelected.value || !expenseDescription.value) {
                        displayMessage("errorMsg", "Please Fill in all Fields", true);
                        return;
                    }

                    loader.style.display = "flex";

                    var formData = new FormData();
                    formData.append("expenseDate", expenseDate.value);
                    formData.append("expenseSelected", expenseSelected.value);
                    formData.append("amountSpent", amountSpent.value);
                    formData.append("methodSelected", methodSelected.value);
                    formData.append("PaymentRecieptFile", PaymentRecieptInput.files[0]);
                    formData.append("expenseDescription", expenseDescription.value);


                    // Perform fetch API request
                    fetch('../controllers/addExpense_contr.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                displayMessage("errorMsg", data.message, false);

                                setTimeout(() => {
                                    loader.style.display = "none";
                                }, 2000);
                                location.reload();
                            }
                            if (!data.success) {
                                loader.style.display = "none";
                                // Handle the response from the server
                                displayMessage("errorMsg", data.message, false);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            loader.style.display = "none";
                            displayMessage("errorMsg", "Network Error", false);
                        });



                })





                function sanitizeFileInput(event) {
                    const fileInput = event.target;
                    const file = fileInput.files[0];

                    // Check if a file is selected
                    if (file) {
                        // Perform any necessary sanitization here
                        // For example, you can check the file type or size

                        // Example: Check file type
                        const allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                        if (!allowedFileTypes.includes(file.type)) {
                            displayMessage("errorMsg", "Please select a valid file type (JPEG, PNG, or PDF).", true);
                            fileInput.value = ''; // Clear the file input
                        }

                        // Example: Check file size
                        const maxSizeInBytes = 10 * 1024 * 1024; // 10 MB
                        if (file.size > maxSizeInBytes) {
                            displayMessage("errorMsg", "File size exceeds the maximum allowed size (10 MB).", true);
                            fileInput.value = ''; // Clear the file input
                        }
                    }
                }
            </script>