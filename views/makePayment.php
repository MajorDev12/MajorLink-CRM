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
                        <a class="active" href="#">Home</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>
        <div id="loader">Loading</div>
        <!-- content-container -->
        <div class="main-content">
            <div class="content">
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
                            <option value="pending">Pending</option>
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
                $(document).ready(function() {
                    var customerList = [];

                    <?php foreach ($clientData as $client) : ?>
                        customerList.push({
                            id: "<?php echo $client['ClientID']; ?>",
                            text: "<?php echo $client['FirstName'] . ' ' . $client['LastName']; ?>"
                        });
                    <?php endforeach; ?>

                    $("#user_select").select2({
                        data: customerList
                    });

                });





                function addPayment(event) {
                    event.preventDefault();

                    var loader = document.getElementById("loader");
                    var paymentDate = document.getElementById("paymentDate").value;
                    var ClientId = document.getElementById("user_select").value;
                    var Amount = document.getElementById("Amount").value;
                    var PlanId = document.getElementById('plans').value;
                    var PaymentMethod = document.getElementById("PaymentMethod").value;
                    var PaymentStatus = document.getElementById("PaymentStatus").value;
                    var InstallationFees = document.getElementById("InstallationFees").value;
                    var paymentReference = document.getElementById("paymentReference").value;

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
                        displayMessage("errorMsg", "All fields must be filled except Installation fees", true);
                        // return;
                    } else {
                        // All fields are filled, proceed with sending data through Fetch API
                        loader.style.display = "flex";
                        var formData = new FormData();
                        formData.append("ClientId", ClientId);
                        formData.append("paymentDate", paymentDate);
                        formData.append("Amount", Amount);
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
            </script>