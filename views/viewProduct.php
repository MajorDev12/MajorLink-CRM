<?php require_once "../controllers/session_Config.php"; ?>
<?php

require_once  '../database/pdo.php';
require_once  '../modals/addSale_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/setup_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$settings = get_Settings($connect);
$Currency = $settings[0]["CurrencyCode"];
$Symbol = $settings[0]["CurrencySymbol"];
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

    .footer {
        position: absolute;
        top: 90%;
        left: 20%;
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
        color: var(--light);
        font-size: 2.5em;
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        margin-right: 50px;
    }

    .invoiceContainer .header p {
        color: var(--grey);
        font-size: 12px;
        line-height: 7px;
    }

    .invoiceContainer .header .companyInfo .first,
    .invoiceContainer .header .companyInfo .second {
        text-align: end;
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
                    $SaleID = isset($_GET['i']) ? $_GET['i'] : null;
                    $clientID = isset($_GET['c']) ? $_GET['c'] : null;
                    ?>
                    <?php
                    $sales = getSalesByID($connect, $SaleID);
                    //get clientInfo
                    $clientData = getClientDataById($connect, $clientID);

                    ?>
                    <div class="col-md-2">
                        <?php $status = $sales[0]["PaymentStatus"]; ?>
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
                    <?php if ($SaleID !== null && $clientID !== null) : ?>
                        <a href="../controllers/generateSalesInvoice_contr.php?i=<?= $SaleID; ?>&c=<?= $clientID; ?>" target="_blank" class="btn active">Download PDF</a>
                        <a href="../views/printSaleInvoice.php?i=<?= $SaleID; ?>&c=<?= $clientID; ?>" target="_blank" class="btn active">Print</a>
                    <?php endif; ?>
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

                        <div class="invoiceInfo">
                            <p>Invoice Number</p>
                            <h5><?= $sales[0]["InvoiceNumber"]; ?></h5>
                            <p class="issueDate">Date of Issue</p>
                            <h5><?= date("Y-m-d", strtotime($sales[0]["SaleDate"])); ?></h5>
                        </div>
                        <div class="invoiceTotal">
                            <p>Invoice Total</p>
                            <?php
                            $total = $sales[0]["UnitPrice"] * $sales[0]["Quantity"];
                            ?>
                            <h4 class="topTotal"><span class="currency">$</span><?= number_format($total, 2); ?></h4>

                            <?php
                            // Assuming $status contains the status value
                            $statusClass = strtolower(str_replace(' ', '-', $status)); // Convert status to lowercase and replace spaces with hyphens
                            ?>

                            <h1 class="status mt-4 <?= $statusClass; ?>"><?= $status; ?></h1>
                        </div>

                    </div>

                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $sales[0]["ProductName"]; ?></td>
                                <td><?= $sales[0]["Quantity"]; ?></td>
                                <td><?= $sales[0]["UnitPrice"]; ?></td>
                                <td><?= number_format($total, 2); ?></td>
                            </tr>


                            <tr>
                                <td colspan="2" class="border-0"></td>
                                <td colspan="" class="Subtotal">Subtotal</td>
                                <td class="subtotalAmount"><?= number_format($total, 2); ?></td>
                            </tr>

                            <tr>
                                <td colspan="2" class="border-0"></td>
                                <td colspan="" id="Tax">Tax(<?= '%'; ?>)</td>
                                <td class="taxAmount"><?= 0; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="border-0"></td>
                                <td colspan="" class="Total">Total</td>
                                <td class="totalPrice">$ <?= number_format($total, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>


            </div>



            <?php require_once "footer.php";  ?>


            <script>
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
                    var SaleID = <?php echo json_encode($SaleID); ?>;


                    var formData = new FormData();
                    formData.append("status", status);
                    formData.append("SaleID", SaleID);

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