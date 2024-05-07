<?php require_once "../controllers/session_Config.php"; ?>
<?php

require_once  '../database/pdo.php';
require_once  '../modals/addSale_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/setup_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$settings = get_Settings($connect);
$symbol = $settings[0]["CurrencySymbol"];
?>
<?php require_once "style.config.php"; ?>
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
        font-size: 18px;
        color: var(--light);
        z-index: -1;
        text-align: center;
        border-radius: 10px;
        padding: 5px;
    }

    .invoiceContainer h5 {
        color: var(--dark);
    }

    .invoiceContainer .header {
        background-color: var(--blue);
        padding: 20px;
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
        padding-bottom: 10px;
    }

    .invoiceContainer .header p {
        color: var(--grey);
        font-size: 12px;
        line-height: 6px;
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
            background-color: var(--blue);
            padding: 20px;
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
            padding-bottom: 10px;
        }

        .invoiceContainer .header p {
            color: var(--grey);
            font-size: 12px;
            line-height: 6px;
        }

        .invoiceContainer .header .companyInfo .first,
        .invoiceContainer .header .companyInfo .second {
            text-align: end;
        }

        .invoiceContainer .header .companyInfo .first {
            padding-bottom: 10px;
        }

        .invoiceContainer .secondContainer {
            margin: 10% 0;
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

        .printBtn {
            display: none;
        }

    }
</style>



<?php
// Use $_GET to retrieve encoded parameters
$SaleID = isset($_GET['i']) ? $_GET['i'] : null;
$clientID = isset($_GET['c']) ? $_GET['c'] : null;

if ($SaleID !== null && $clientID !== null) :
    $sales = getSalesByID($connect, $SaleID);
    //get clientInfo
    $clientData = getClientDataById($connect, $clientID);

?>
    <div class="invoiceContainer shadow-sm bg-body rounded">
        <!-- header -->
        <div class="header">
            <?php $status = $sales[0]["PaymentStatus"]; ?>
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
                <h4 class="topTotal"><span class="currency"><?= $symbol; ?> </span><?= number_format($sales[0]["Total"], 2); ?></h4>

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
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>

                <!-- Check if $products is not empty -->
                <?php if (!empty($sales)) : ?>
                    <?php
                    $subtotal = $sales[0]["Quantity"] * $sales[0]["UnitPrice"];

                    ?>
                    <tr>
                        <td><?= $sales[0]["ProductName"]; ?></td>
                        <td><?= $sales[0]["Quantity"]; ?></td>
                        <td><?= $sales[0]["UnitPrice"]; ?></td>
                        <td><?= number_format($subtotal, 2); ?></td>
                    </tr>
                <?php else : ?>
                    <!-- Handle the case where $products is empty -->
                    <tr>
                        no data yet
                    </tr>
                <?php endif; ?>

                <tr>
                    <td colspan="2" class="border-0"></td>
                    <td colspan="" class="Subtotal">Subtotal</td>
                    <td class="subtotalAmount"><?= number_format($subtotal, 2); ?></td>
                </tr>

                <tr>
                    <td colspan="2" class="border-0"></td>
                    <td colspan="" id="Tax">Tax(<?= $sales[0]["TaxSymbol"]; ?>)</td>
                    <td class="taxAmount"><?= $sales[0]["Tax"]; ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="border-0"></td>
                    <td colspan="" class="Total">Total</td>
                    <td class="totalPrice">$ <?= number_format($sales[0]["Total"], 2); ?></td>
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


    // Attach an event listener to clean up and close the window after printing
    window.onafterprint = function() {
        // Close the current window
        window.close();
    };
</script>