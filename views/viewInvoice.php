<?php require_once "../controllers/session_Config.php"; ?>
<?php

require_once  '../database/pdo.php';
require_once  '../modals/addInvoice_mod.php';
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
        justify-content: center;
        margin: 2% 0;
    }

    .actions a {
        margin-left: 20px;
    }

    .invoiceContainer footer {
        text-align: center;
        position: relative;
        bottom: 0%;
        width: 90%;
        color: var(--dark-grey);
    }

    @media print {
        body {
            scrollbar-width: thin;
            /* Firefox */
            scrollbar-color: transparent transparent;
            /* Firefox */
            overflow: -moz-scrollbars-none;
            /* Firefox */
        }

        /* For Webkit based browsers (Chrome, Safari, Edge) */
        ::after {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 9999;
            /* background-color: white; */
            /* Set the background color of the overlay */
        }
    }

    @media print {

        .invoiceContainer {
            width: 80%;
            position: relative;
            left: 10%;
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

            <div class="content tab-content">

                <div class="tabs mb-2">

                    <a href="invoice.php" class="btn active">Go Back</a>
                    <?php if (isset($_POST["invoiceID"]) && isset($_POST["clientID"])) : ?>
                        <a href="../controllers/generatepdf_contr.php" target="_blank" class="btn active">Download PDF</a>

                </div>
                <?php
                        $invoiceID = $_POST["invoiceID"];
                        $clientID = $_POST["clientID"];
                        $invoice = getInvoiceData($connect, $invoiceID);
                        //get clientInfo
                        $clientData = getClientDataById($connect, $clientID);

                ?>
                <div class="invoiceContainer shadow-sm bg-body rounded">
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
                                <h4 class="topTotal"><span class="currency">$</span><?= number_format($invoice["TotalAmount"], 2); ?></h4>
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
                                <td class="totalPrice">$ <?= number_format($invoice["TotalAmount"], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            <?php else : ?>

                <?php echo "No Data to Show"; ?>
            <?php endif; ?>
            <?php
            // unset($_SESSION["invoiceID"]);
            // unset($_SESSION["clientID"]);
            ?>

            </div>



            <?php require_once "footer.php";  ?>