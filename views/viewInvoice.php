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
<?php require_once "style.config.php"; ?>
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

    .invoiceContainer h5 {
        color: var(--dark);
    }

    .invoiceContainer .header {
        background-color: var(--blue);
        padding: 25px 15px;
        position: relative;
    }

    .invoiceContainer .header .companyInfo {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .invoiceContainer .header h2 {
        color: #F9F9F9;
        font-size: 2.5em;
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        margin-right: 50px;
    }

    .invoiceContainer .header p {
        color: #eee;
        font-size: 12px;
        line-height: 7px;
    }

    .invoiceContainer .header .companyInfo .first,
    .invoiceContainer .header .companyInfo .second {
        text-align: end;
    }

    /* second container */
    .invoiceContainer .secondContainer {
        margin: 7% 0;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
    }

    .invoiceContainer .secondContainer p {
        color: #AAAAAA;
        font-size: 14px;
    }

    .invoiceContainer .secondContainer h5 {
        color: #342E37;
        font-size: 16px;
    }

    .invoiceContainer .secondContainer .clientsSelect {
        width: 140%;
    }

    .invoiceContainer input {
        border: none;
        color: #342E37 !important;
        background-color: #eee !important;
    }

    .invoiceContainer input::placeholder {
        color: #342E37;
        font-size: 16px;
        font-weight: 500;
    }

    .invoiceContainer .secondContainer .topTotal {
        color: var(--blue);
    }

    .invoiceContainer .status {
        font-size: 18px;
        color: var(--light);
        z-index: -1;
        text-align: center;
        border-radius: 10px;
        padding: 5px;
    }

    .invoiceContainer .status.paid {
        color: var(--green);
        background-color: var(--light-green);
    }

    .invoiceContainer .status.partially-paid {
        color: var(--yellow);
        background-color: var(--light-yellow);
    }

    .invoiceContainer .status.pending {
        color: var(--orange);
        background-color: var(--light-orange);
    }

    .invoiceContainer .status.cancelled {
        color: var(--red);
        background-color: var(--light-orange);
    }





    /* table */
    .invoiceContainer table {
        margin-top: 10%;
    }

    .invoiceContainer .table thead {
        margin-bottom: 10px;
        background-color: white;
    }

    .invoiceContainer .table thead tr th {
        color: var(--blue);
        border-bottom: 2px solid var(--blue);
    }

    .invoiceContainer .table td {
        background-color: white;
        color: #342E37;
    }

    .invoiceContainer .table tbody tr {
        color: #342E37 !important;
    }

    .invoiceContainer .table tbody tr .totaltd {
        border: none !important;
    }

    .invoiceContainer .table tbody tr:hover {
        background-color: white;
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
        color: var(--dark);
    }

    .actions a {
        margin-left: 5px;
        background-color: transparent;
        color: var(--dark);
    }

    .invoiceContainer .footer {
        position: absolute;
        top: 90%;
        left: 20%;
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
                        <select name="status" class="form-select" onchange="openChangeModal(this)">
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
                        <a href="../views/printInvoice.php?i=<?= $invoiceID; ?>&c=<?= $clientID; ?>" target="_blank" class="btn active">Print</a>
                </div>

                <div class="invoiceContainer shadow-sm bg-body rounded">



                    <!-- header -->
                    <div class="header">

                        <div class="companyInfo">
                            <div class="">
                                <h2>INVOICE</h2>
                            </div>
                            <div class="first">
                                <p class="website"><?= $settings[0]["Website"]; ?></p>
                                <p class="email"><?= $settings[0]["Email"]; ?></p>
                                <p class="phonenumber"><?= $settings[0]["PhoneNumber"]; ?></p>
                            </div>
                            <div class="second">
                                <p class="Address"><?= $settings[0]["Address"]; ?></p>
                                <p class="City"><?= $settings[0]["City"]; ?> <?= $settings[0]["Country"]; ?></p>
                                <p class="zipCode"><?= $settings[0]["Zipcode"]; ?></p>
                            </div>
                        </div>
                    </div>


                    <!-- Client info -->
                    <div class="secondContainer">
                        <div class="clientInfo">
                            <p>Billed To</p>
                            <?php if ($clientData) : ?>
                                <h5 class="clientNames"><?= $clientData["FirstName"] . ' ' . $clientData["LastName"]; ?></h5>
                                <h5 class="address"><?= $clientData["Address"]; ?></h5>
                                <h5 class="City"><?= $clientData["City"]; ?></h5>
                                <h5 class="zipcode"><?= $clientData["Zipcode"] != 0 ? $clientData["Zipcode"] : ''; ?></h5>
                                <h5 class="country"><?= $clientData["Country"]; ?></h5>
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



                                <?php
                                // Assuming $status contains the status value
                                $statusClass = strtolower(str_replace(' ', '-', $status)); // Convert status to lowercase and replace spaces with hyphens
                                ?>

                                <h1 class="status mt-4 <?= $statusClass; ?>"><?= $status; ?></h1>
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
                                <td class="subtotalAmount"><?= $subtotal; ?></td>
                            </tr>

                            <tr>
                                <td colspan="3" class="border-0"></td>
                                <td colspan="" id="Tax">Tax(<?= $invoice["TaxSymbol"]; ?>)</td>
                                <td class="taxAmount"><?= $invoice["Taxamount"]; ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="totaltd"></td>
                                <td colspan="" class="Total">Total</td>
                                <td class="totalPrice"><?= $initialSymbol; ?> <?= number_format($invoice["TotalAmount"], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>


                    <p class="footer" style="font-style: italic;">
                        If you are not the intended recipient, no action is required.
                    </p>

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