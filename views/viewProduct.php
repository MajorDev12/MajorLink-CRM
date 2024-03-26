<?php require_once "../controllers/session_Config.php"; ?>
<?php

require_once  '../database/pdo.php';
require_once  '../modals/addSale_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

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
        top: 57%;
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
        /* background-color: var(--yellow); */
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

    .invoiceContainer .secondContainer .status {
        background-color: var(--green);
        color: var(--light-green);
        padding: 0px 5px;
        border-radius: 10px;
        text-align: center;
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
                        <a href="../controllers/generatepdf_contr.php?i=<?= $SaleID; ?>&c=<?= $clientID; ?>" target="_blank" class="btn active">Download PDF</a>
                        <a href="../user/printInvoice.php?i=<?= $SaleID; ?>&c=<?= $clientID; ?>" target="_blank" class="btn active">Print</a>
                    <?php endif; ?>
                </div>

                <div class="invoiceContainer shadow-sm bg-body rounded">
                    <h1 class="status"><?= $sales[0]["PaymentStatus"]; ?></h1>
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
                                <h5 class="clientNames"><?= $clientData["FirstName"]; ?></h5>
                                <h5 class="address">Nakuru, Pipeline</h5>
                                <h5 class="City">Nakuru City</h5>
                                <h5 class="zipcode">20100</h5>
                                <h5 class="country">Kenya</h5>
                            <?php endif; ?>
                        </div>

                        <div class="invoiceInfo">
                            <p>Invoice Number</p>
                            <h5>INV097654</h5>
                            <p class="issueDate">Date of Issue</p>
                            <h5><?= date("Y-m-d", strtotime($sales[0]["SaleDate"])); ?></h5>
                        </div>
                        <div class="invoiceTotal">
                            <p>Invoice Total</p>
                            <?php
                            $total = $sales[0]["UnitPrice"] * $sales[0]["Quantity"];
                            ?>
                            <h4 class="topTotal"><span class="currency">$</span><?= number_format($total, 2); ?></h4>
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