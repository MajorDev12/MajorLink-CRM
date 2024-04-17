<?php require_once "../controllers/session_Config.php"; ?>
<?php

require_once  '../database/pdo.php';
require_once  '../modals/addInvoice_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/setup_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
$settings = get_Settings($connect);
$initialCurrency = $settings[0]["CurrencyCode"];
$initialSymbol = $settings[0]["CurrencySymbol"];
?>
<?php require_once "header.php"; ?>

<style>
    .invoiceContainer {
        width: 80%;
        min-height: 130vh;
        position: relative;
        left: 10%;
        background-color: var(--light);
        font-family: 'Poppins', sans-serif;
        padding: 5%;
        z-index: 1;
    }

    .invoiceContainer .status {
        position: absolute;
        left: 60%;
        top: 60%;
        font-size: 3em;
        rotate: -10deg;
        color: #ecfaf6;
        z-index: -1;
    }

    .invoiceContainer h5 {
        color: var(--dark);
    }

    .invoiceContainer .header {
        background-color: var(--blue);
        padding: 20px;
        position: relative;
    }

    .invoiceContainer .header h1 {
        color: var(--light);
        font-size: 3.5em;
        padding-bottom: 10px;
    }

    .invoiceContainer .header p {
        color: var(--grey);
        font-size: 14px;
        line-height: 10px;
    }

    .invoiceContainer .header .companyInfo .first {
        padding-bottom: 10px;
    }

    .invoiceContainer .secondContainer {
        margin: 7% 0;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
    }

    .invoiceContainer .secondContainer p {
        color: var(--dark-grey);
        font-size: 14px;
    }

    .invoiceContainer .secondContainer h5 {
        color: var(--dark);
        font-size: 16px;
    }

    .invoiceContainer .secondContainer .clientsSelect {
        width: 140%;
    }

    .invoiceContainer input {
        border: none;
        color: var(--dark);
    }

    .invoiceContainer input::placeholder {
        color: var(--dark);
        font-size: 16px;
        font-weight: 500;
    }

    .invoiceContainer .secondContainer .topTotal {
        color: var(--blue);
    }

    .invoiceContainer table {
        margin-top: 10%;
    }

    .invoiceContainer .table thead {
        margin-bottom: 10px;
        background-color: transparent;
    }

    .invoiceContainer .table thead tr th {
        color: var(--blue);
        border-bottom: 2px solid var(--blue);

    }

    .invoiceContainer .table tr .Subtotal,
    .invoiceContainer .table tr #Tax {
        color: var(--dark-grey);
    }

    .invoiceContainer .table input {
        width: 80%;
    }

    .invoiceContainer .table .totalPrice {
        color: var(--blue);
        font-weight: 600;
    }

    .actions {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: end;
    }

    .actions a {
        margin-left: 5px;
        background-color: transparent;
    }

    .invoiceContainer footer {
        text-align: center;
        position: relative;
        bottom: 0%;
        width: 90%;
        color: var(--dark-grey);
    }



    @media print {
        body * {
            visibility: hidden;
        }

        .invoiceContainer,
        .invoiceContainer * {
            visibility: visible;
            display: block;
        }


        .invoiceContainer {
            display: block;
            position: absolute;
            left: 0%;
            top: 0%;
            background-color: #3C91E6;
            font-family: 'Poppins', sans-serif;
        }

        .invoiceContainer .header p {
            font-size: 12px;
        }

        .actions {
            display: none;
        }

        h5 {
            font-size: 18px;
        }

        footer {
            display: none;
        }
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


        <!-- content-container -->
        <div class="main-content">

            <div id="responseContainer" class="content tab-content">
                <div id="overlay"></div>
                <div class="modal-container" id="changeModal">
                    <div id="modalBackground"></div>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Change Payment Status</h5>
                            <button type="button" id="closeDelModal" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="mt-3">Are You Sure?</p>
                            <input type="hidden" id="status" value="">
                        </div>
                        <div class="modal-footer">
                            <p id="errormodal"></p>
                            <button type="button" id="saveBtn" class="btn btn-primary ml-3" onclick="changeConfirmed()">Yes</button>
                            <button type="button" id="cancel" class="btn btn-danger ml-3">Cancel</button>
                        </div>
                    </div>

                </div>



                <div class="actions mb-2">
                    <?php
                    // Use $_GET to retrieve encoded parameters
                    $invoiceID = isset($_GET['i']) ? $_GET['i'] : null;
                    $clientID = isset($_GET['c']) ? $_GET['c'] : null;
                    ?>
                    <?php
                    $invoice = getInvoiceData($connect, $invoiceID);
                    //get clientInfo
                    $clientData = getClientDataById($connect, $clientID);

                    ?>

                    <div class="col-md-2">
                        <?php $status = $invoice["Status"]; ?>
                        <select name="status" class="form-select" style="border: 1px solid black;" onchange="openChangeModal(this)">
                            <option value="" disabled selected>Mark as</option>
                            <?php if ($status !== "Paid") : ?>
                                <option value="Paid">Paid</option>
                            <?php endif; ?>
                            <?php if ($status !== "Partially Paid") : ?>
                                <option value="Partially Paid">Partially Paid</option>
                            <?php endif; ?>
                            <?php if ($status !== "Pending") : ?>
                                <option value="Pending">Pending</option>
                            <?php endif; ?>
                            <?php if ($status !== "Cancelled") : ?>
                                <option value="Cancelled">Cancelled</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <a href="invoice.php" class="btn active">Go Back</a>
                    <?php if ($invoiceID !== null && $clientID !== null) : ?>
                        <a href="../controllers/generatepdf_contr.php?i=<?= $invoiceID; ?>&c=<?= $clientID; ?>" target="_blank" class="btn active">Download PDF</a>
                        <a href="../user/printInvoice.php?i=<?= $invoiceID; ?>&c=<?= $clientID; ?>" target="_blank" class="btn active">Print</a>
                </div>

                <div class="invoiceContainer shadow-sm bg-body rounded">

                    <h1 class="status"><?= $status; ?></h1>

                    <!-- header -->
                    <div class="header">

                        <div class="companyInfo">
                            <div class="">
                                <h1>INVOICE</h1>
                            </div>
                            <div class="first">
                                <p class="website">www.majorlink.com</p>
                                <p class="email">majorlink@gmail.com</p>
                                <p class="phonenumber">(254) 718 317 726</p>
                            </div>
                            <div class="second">
                                <p class="Address">Pipeline, Nakuru</p>
                                <p class="City">Nakuru City</p>
                                <p class="country">Kenya</p>
                                <p class="zipCode">20100</p>
                            </div>
                        </div>
                    </div>


                    <!-- Client info -->
                    <div class="secondContainer">
                        <div class="clientInfo">
                            <p>Billed To</p>
                            <?php if ($clientData) : ?>
                                <h5 class="clientNames"><?= $clientData["FirstName"] . ' ' . $clientData["LastName"]; ?></h5>
                                <h5 class="address">Nakuru, Pipeline</h5>
                                <h5 class="City">Nakuru City</h5>
                                <h5 class="zipcode">20100</h5>
                                <h5 class="country">Kenya</h5>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($invoice)) : ?>

                            <div class="invoiceInfo">
                                <p>Invoice Number</p>
                                <h5><?= $invoice["InvoiceNumber"]; ?></h5>
                                <p class="issueDate">Date of Issue</p>
                                <h5><?= date("Y-m-d", strtotime($invoice["paymentDate"])); ?></h5>
                                <p class="issueDate">Start Date</p>
                                <h5><?= date("Y-m-d", strtotime($invoice["StartDate"])); ?></h5>
                            </div>
                            <div class="invoiceTotal">
                                <p class="issueDate">Expire Date</p>
                                <h5><?= date("Y-m-d", strtotime($invoice["DueDate"])); ?></h5>
                                <p>Invoice Total</p>
                                <h4 class="topTotal"><span class="currency"><?= $initialSymbol; ?> </span><?= number_format($invoice["TotalAmount"], 2); ?></h4>
                            </div>
                        <?php endif; ?>
                    </div>

                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Volume</th>
                                <th>Qty/Months</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $products = getInvoiceProducts($connect, $invoiceID);
                            $subtotal = 0; // Initialize subtotal

                            ?>

                            <!-- Check if $products is not empty -->
                            <?php if (!empty($products)) : ?>
                                <!-- Loop through each product -->
                                <?php foreach ($products as $product) : ?>
                                    <tr>
                                        <td><?= $product["Name"]; ?></td>
                                        <td><?= $product["Volume"]; ?></td>
                                        <td><?= $product["Qty"]; ?></td>
                                        <td><?= $product["Price"]; ?></td>
                                        <td><?= $product["Amount"]; ?></td>
                                    </tr>
                                    <?php
                                    // Update subtotal for each product
                                    $subtotal += $product["Amount"];
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <!-- Handle the case where $products is empty -->
                                <tr>
                                </tr>
                            <?php endif; ?>

                            <tr>
                                <td colspan="3" class="border-0"></td>
                                <td colspan="" class="Subtotal">Subtotal</td>
                                <td class="subtotalAmount"><?= number_format($subtotal, 2); ?></td>
                            </tr>

                            <tr>
                                <td colspan="3" class="border-0"></td>
                                <td colspan="" id="Tax">Tax(<?= $invoice["TaxSymbol"]; ?>)</td>
                                <td class="taxAmount"><?= $invoice["Taxamount"]; ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="border-0"></td>
                                <td colspan="" class="Total">Total</td>
                                <td class="totalPrice"><?= $initialSymbol; ?> <?= number_format($invoice["TotalAmount"], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            <?php else : ?>

                <?php echo "No Data to Show"; ?>
            <?php endif; ?>

            </div>



            <?php require_once "footer.php";  ?>


            <script>
                function printInvoice() {
                    var printContent = document.querySelector('.invoiceContainer').outerHTML;
                    var printWindow = window.open('', '_blank');
                    printWindow.document.write(printContent);
                    printWindow.document.close();
                    printWindow.print();
                }


                function openChangeModal(selectElement) {
                    // Get the selected option value
                    var selectedStatus = selectElement.value;

                    // Set the value to the hidden input field in the modal
                    document.getElementById('status').value = selectedStatus;

                    // Show the modal
                    showModal();
                }




                var closeDelModal = document.querySelector("#closeDelModal");
                var cancel = document.querySelector("#cancel");
                var saveBtn = document.querySelector("#saveBtn");

                saveBtn.addEventListener("click", function() {
                    var status = document.getElementById('status').value;
                    var invoiceID = <?php echo json_encode($invoiceID); ?>;


                    var formData = new FormData();
                    formData.append("status", status);
                    formData.append("invoiceID", invoiceID);

                    // Use Fetch API to send the data
                    fetch('../controllers/updateInvoiceStatus_contr.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                displayMessage("error", data.message, true);
                            }
                        })
                        .catch(error => {
                            displayMessage("error", "Network Error", true);
                            document.getElementById("addbtn").disabled = false;
                        })

                })



                cancel.addEventListener('click', function() {
                    hideModal();
                })
                closeDelModal.addEventListener('click', function() {
                    hideModal();
                })

                function showModal() {
                    document.getElementById('changeModal').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                }


                function hideModal() {
                    document.getElementById('changeModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                    location.reload();
                }
            </script>