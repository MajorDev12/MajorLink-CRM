<?php require_once "../controllers/session_Config.php"; ?>


<?php
require_once  '../database/pdo.php';
require_once  '../modals/getClientsNames_mod.php';
require_once  '../modals/addProduct_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';
require_once  '../modals/setup_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$clientData = getClientsNames($connect);
$products = getProductData($connect);
$methods = getPaymentMethods($connect);
$settings = get_Settings($connect);
?>

<?php require_once "header.php"; ?>
<?php require_once "style.config.php"; ?>

<style>
    span {
        color: var(--light-dark);
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

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>

        <!-- content-container -->
        <div class="main-content">



            <div class="content">
                <form class="row g-3 form">
                    <div class="form-group">
                        <label for="saleDate">Sale Date</label>
                        <input type="date" id="saleDate" name="saleDate" value="<?= date('Y-m-d'); ?>" required>

                    </div>


                    <div class="col-md-6">
                        <label for="customer">Customer</label>
                        <select id="user_select" name="user_id" class="clientsSelect form-select form-select-md">
                            <option value="" selected hidden>--Search--</option>
                        </select>
                    </div>




                    <div class="col-md-6">
                        <label for="Product">Product</label>
                        <select class="form-select form-select-md" id="ProductSelect" aria-label="Default select example">
                            <option value="" disabled selected>Choose...</option>
                            <?php foreach ($products as $product) : ?>
                                <option value="<?= $product["ProductID"]; ?>" data-price="<?= $product["Price"]; ?>"><?= $product["ProductName"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>



                    <div class="col-md-6">
                        <label for="Product">Quantity</label>
                        <div class="input-group">
                            <input type="number" id="Quantity" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <label for="UnitPrice">Unit Price (<?= $settings[0]["CurrencySymbol"]; ?>)</label>
                        <div class="input-group">
                            <input type="number" id="UnitPrice" class="form-control " readonly aria-label="Unit Price">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <label for="Product">Payment Method</label>
                        <select class="form-select form-select-md" id="Product" aria-label="Default select example">
                            <option value="" disabled selected>Choose...</option>
                            <?php foreach ($methods as $method) : ?>
                                <option value="<?= $method["PaymentOptionID"]; ?>"><?= $method["PaymentOptionName"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>



                    <div class="col-md-6">
                        <label for="PaymentStatus">Payment Status</label>
                        <select class="form-select form-select-md" id="PaymentStatus" aria-label="Default select example">
                            <option value="" selected>Choose</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="partially">Partially</option>
                        </select>

                    </div>




                    <div class="col-md-6">
                        <label for="paymentDate">Tax Symbol</label>
                        <span><?= $settings[0]["CurrencySymbol"]; ?></span>
                        <input type="radio" name="taxType" value="<?= $settings[0]["CurrencySymbol"]; ?>" checked id="taxCurrency">
                        <span>%</span>
                        <input type="radio" id="taxPercent" name="taxType" value="%">
                    </div>
                    <div class="col-md-6">
                        <label for="paymentDate">Tax</label>
                        <input type="number" id="taxInput" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="Product">Total (<?= $settings[0]["CurrencySymbol"]; ?>)</label>
                        <div class="input-group">
                            <input type="number" id="Total" class="form-control" readonly aria-label="Dollar amount (with dot and two decimal places)">
                        </div>
                    </div>
                    <div id="errorMsg"></div>
                    <div class="form-group">
                        <button type="submit" id="saveBtn" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            <?php require_once "footer.php"; ?>



            <script>
                // Add event listener to the Product dropdown
                document.getElementById('ProductSelect').addEventListener('change', function() {
                    // Get the selected option
                    var selectedOption = this.options[this.selectedIndex];

                    // Update the Unit Price input with the selected product's price
                    document.getElementById('UnitPrice').value = selectedOption.dataset.price || '';

                    // Trigger the input event on Unit Price to recalculate the Total
                    document.getElementById('UnitPrice').dispatchEvent(new Event('input'));
                });





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



                    // Attach change event listener
                    $("#user_select").on("change", function() {
                        selectedClientId = $(this).val();


                        // Make an AJAX request to get client data
                        $.ajax({
                            url: '../controllers/getClientInfo_contr.php',
                            type: 'GET',
                            data: {
                                clientId: selectedClientId
                            },
                            success: function(response) {
                                // Update the client information based on the response
                                $('.clientNames').text(response.FirstName + ' ' + response.LastName);
                            },
                            error: function(error) {
                                console.error('Error fetching client data:', error);
                            }
                        });
                    });


                });

                // Add event listener to both Quantity and Unit Price inputs
                document.getElementById('Quantity').addEventListener('input', updateTotal);
                document.getElementById('UnitPrice').addEventListener('input', updateTotal);
                document.getElementById('taxInput').addEventListener('input', updateTotal);


                function updateTotal() {
                    var unitPrice = parseFloat(document.querySelector('#UnitPrice').value) || 0;
                    var quantity = parseFloat(document.querySelector('#Quantity').value) || 0;
                    var taxInput = document.querySelector('#taxInput');
                    var taxAmount = parseFloat(taxInput.value) || 0;
                    var taxType = document.querySelector('input[name="taxType"]:checked').value;

                    subtotal = unitPrice * quantity;
                    var total = 0;
                    if (taxAmount <= 0) {
                        taxAmount = 0;
                    }

                    // Calculate total based on tax type
                    if (taxType !== '%') {
                        // If tax is in currency
                        total = subtotal + taxAmount;
                    } else {
                        // If tax is in percentage
                        // Calculate tax amount based on percentage of subtotal
                        var taxPercentage = (taxAmount / 100) * subtotal;
                        // Add tax amount to subtotal to get total
                        total = subtotal + taxPercentage;
                    }


                    // Update the total price element
                    var totalPriceElement = document.querySelector('#Total');
                    if (totalPriceElement) {
                        totalPriceElement.value = total.toFixed(2);
                    }
                }




                // Get references to the radio buttons
                const taxCurrencyRadio = document.getElementById('taxCurrency');
                const taxPercentRadio = document.getElementById('taxPercent');

                // Add event listener to the "percent" radio button
                taxPercentRadio.addEventListener('change', function() {
                    // Uncheck the "currency" radio button when "percent" is selected
                    taxCurrencyRadio.checked = false;
                    updateTotal();

                });

                // Add event listener to the "currency" radio button
                taxCurrencyRadio.addEventListener('change', function() {
                    taxPercentRadio.checked = false;
                    updateTotal()
                });






                var saveBtn = document.querySelector("#saveBtn");

                saveBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    var saleDate = document.querySelector("#saleDate").value;
                    var clientID = document.querySelector(".clientsSelect").value;
                    var productID = document.querySelector("#ProductSelect").value;
                    var quantity = document.querySelector("#Quantity").value;
                    var unitPrice = document.querySelector("#UnitPrice").value;
                    var paymentMethodID = document.querySelector("#Product").value;
                    var PaymentStatus = document.querySelector("#PaymentStatus").value;
                    var taxAmount = parseFloat(taxInput.value) || 0;
                    var taxType = document.querySelector('input[name="taxType"]:checked').value;
                    var Total = document.querySelector('#Total');
                    var total = parseFloat(Total.value) || 0;

                    if (!clientID) {
                        displayMessage('errorMsg', 'Choose customer first', true);
                        return;
                    }
                    if (!saleDate || !productID || !quantity || !unitPrice || !paymentMethodID || !PaymentStatus) {
                        displayMessage('errorMsg', 'FIll in all fields', true);
                        return;
                    }

                    if (quantity <= '0') {
                        displayMessage('errorMsg', 'Quantity cant be less or equal to 0', true);
                        return;
                    }

                    if (taxAmount < '0') {
                        displayMessage('errorMsg', 'Tax cant be less or equal to 0', true);
                        return;
                    }

                    loader.style.display = "flex";
                    var formData = new FormData();
                    formData.append("saleDate", saleDate);
                    formData.append("clientID", clientID);
                    formData.append("productID", productID);
                    formData.append("quantity", quantity);
                    formData.append("unitPrice", unitPrice);
                    formData.append("total", total);
                    formData.append("paymentMethodID", paymentMethodID);
                    formData.append("taxAmount", taxAmount);
                    formData.append("taxType", taxType);
                    formData.append("PaymentStatus", PaymentStatus);


                    // Perform fetch API request
                    fetch('../controllers/addSale_contr.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                loader.style.display = "none";
                                displayMessage("errorMsg", data.message, false);
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);

                            }
                            if (!data.success) {
                                loader.style.display = "none";
                                displayMessage("errorMsg", data.message, false);
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            loader.style.display = "none";
                            displayMessage("errorMsg", data.message, false);
                        });


                })







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
            </script>