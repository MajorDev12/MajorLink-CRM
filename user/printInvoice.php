<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['clientID']) || !isset($_SESSION['FirstName'])) {
    // Redirect to the login page
    header("location: ../views/login.php");
    exit();
}
?>
<?php

require_once  '../database/pdo.php';
require_once  '../modals/addInvoice_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

?>
<?php require_once "../views/header.php"; ?>

<style>
    .invoiceContainer {
        width: 60%;
        position: relative;
        left: 20%;
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




    @media print {
        body {
            background-color: white !important;
        }

        .invoiceContainer {
            width: 80%;
            position: relative;
            left: 10%;
            background-color: var(--light);
            font-family: 'Poppins', sans-serif;
        }

        .invoiceContainer h5 {
            color: var(--dark);
        }

        .invoiceContainer .header {
            background: var(--blue) !important;
        }

        .companyInfo {
            background-color: var(--blue) !important;
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

        .printBtn {
            display: none;
        }

    }
</style>



<?php
// Use $_GET to retrieve encoded parameters
$invoiceID = isset($_GET['i']) ? $_GET['i'] : null;
$clientID = isset($_GET['c']) ? $_GET['c'] : null;

if ($invoiceID !== null && $clientID !== null) :
    $invoice = getInvoiceData($connect, $invoiceID);
    //get clientInfo
    $clientData = getClientDataById($connect, $clientID);

?>
    <div class="invoiceContainer shadow-sm bg-body rounded">
        <h1 class="status"><?= $invoice["Status"]; ?></h1>
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
                    <h4 class="topTotal"><span class="currency"><?= $invoice["TaxSymbol"]; ?> </span><?= number_format($invoice["TotalAmount"], 2); ?></h4>
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
                    <td class="totalPrice"><?= $invoice["TaxSymbol"]; ?> <?= number_format($invoice["TotalAmount"], 2); ?></td>
                </tr>
            </tbody>
        </table>

    </div>
<?php else : ?>

    <?php echo "No Data to Show"; ?>
<?php endif; ?>

</div>



<?php require_once "../views/footer.php";  ?>


<script>
    window.onload = () => window.print();

    window.onafterprint = function() {
        // Close the current window
        window.close();
    };
</script>