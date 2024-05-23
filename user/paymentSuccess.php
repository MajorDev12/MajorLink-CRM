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
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/addPayment_mod.php';
require_once  '../modals/addInvoice_mod.php';
require_once  '../modals/setup_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$settings = get_Settings($connect);
// get invoiceid of lastinserted data in clients.payments
$clientID = $_SESSION['clientID'];
$clientData = getClientDataById($connect, $clientID);
$expireDate = $clientData['ExpireDate'];
$initialSymbol = $settings[0]["CurrencySymbol"];

$paymentsData = getLastPayment($connect, $clientID);
$invoiceNumber = $paymentsData["InvoiceNumber"];
$invoiceId = getInvoiceIDByNumber($connect, $invoiceNumber);
$invoiceData = getInvoiceData($connect, $invoiceId);


$Taxamount = $invoiceData['Taxamount'];
$taxSymbol = $invoiceData['TaxSymbol'];

$paidAmount = $paymentsData["PaymentAmount"];
$paymentStatus = $paymentsData["PaymentStatus"];
$paymentDate = $paymentsData["PaymentDate"];

$planName = $paymentsData['Name'];
$planVolume = $paymentsData['Volume'];
$planPrice = $paymentsData['Price'];
$selectedMonths = floor($paidAmount / $planPrice);


?>
<?php require_once "../views/style.config.php"; ?>
<?php require_once "../views/header.php"; ?>

<style>
    .content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .content h3 {
        color: var(--blue);
        font-size: 2em;
        font-weight: 700;
    }

    .invoice {
        width: 100%;
        height: 100%;
        background-color: var(--light);
        padding: 20px;
    }

    .invoice .row {
        display: flex;
        justify-content: space-between;
        text-align: start;
    }

    .invoice .row div {
        margin-left: 10%;
    }

    .invoice .table thead th,
    .invoice .table tbody tr,
    .invoice .table tbody td {
        background-color: var(--light);
    }

    .invoice .table tbody .Subtotal,
    .invoice .table tbody .Tax,
    .invoice .table tbody .Total {
        font-weight: 700;
        color: var(--dark-grey);
    }

    .invoice .table tbody .Total {
        color: var(--dark);
    }

    .invoice .table tbody .totalPrice {
        font-weight: 700;
        font-size: 1.3em;
    }

    .space {
        border: none;
    }

    .status {
        background-color: var(--green);
        color: var(--light-green);
        padding: 2px 20px;
        border-radius: 10px;
    }

    p {
        color: var(--dark);
    }

    .bold {
        color: var(--dark);
        font-weight: 800;
    }

    .invoice .companyName {
        color: var(--blue);
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

            <div class="content text-center">

                <?php if (isset($clientData)) : ?>

                    <div class="container invoice">
                        <div class="row">
                            <div class="col mb-5">
                                <h3 class="companyName"><?= $settings[0]["CompanyName"]; ?></h3>
                                <p><?= $settings[0]["Address"]; ?></p>
                                <p><?= $settings[0]["City"]; ?></p>
                                <p><?= $settings[0]["Zipcode"]; ?></p>
                                <p><?= $settings[0]["Country"]; ?></p>
                            </div>
                            <div class="col">
                                <h3><?php echo $clientData['FirstName'] . '  ' . $clientData['LastName']; ?></h3>
                                <p><span class="bold">Payment Date:</span> <?= $paymentDate; ?></p>
                                <p><span class="bold">Expire Date:</span> <?php echo $expireDate; ?></p>
                                <p><span class="bold">Status:</span> <span class="status"> <?php echo $paymentStatus; ?></span></p>
                            </div>

                        </div>


                        <table class="table mt-5">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Volume</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <tr>
                                    <td><?= $planName; ?></td>
                                    <td><?= $planVolume; ?></td>
                                    <td><?= $selectedMonths; ?></td>
                                    <td><?= $planPrice; ?></td>
                                    <td><?php
                                        // Calculate the amount
                                        $planAmount = $planPrice * $selectedMonths;
                                        echo $planAmount . ' ' . $initialSymbol;
                                        ?></td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="border-none space"></td>
                                    <td colspan="" class="text-start Subtotal">Subtotal</td>
                                    <td class="">
                                        <?= $planAmount; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="border-none space"></td>
                                    <td colspan="" class="text-start Tax">Tax(<?= $taxSymbol; ?>)</td>
                                    <td class="">
                                        <?= $Taxamount; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="border-none space"></td>
                                    <td colspan="" class="text-start Total">Total</td>
                                    <td class="text-primary totalPrice">
                                        <?= $initialSymbol . ' ' . $planAmount; ?>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="../user/printInvoice.php?i=<?= $invoiceId; ?>&c=<?= $clientID; ?>" target="_blank" class="btn btn-warning Print">Print</a>
                        <a href="../controllers/generatepdf_contr.php?i=<?= $invoiceId; ?>&c=<?= $clientID; ?>" class="btn btn-success Download" target="_blank">Download pdf</a>
                        <a href="transaction.php" class="btn btn-secondary">View Transactions</a>
                        <a href="index.php" class="btn btn-primary">Done</a>
                    </div>

                <?php else : ?>
                    <div class="alert alert-warning" role="alert">
                        No data found
                        <?php header("Location: viewClient.php"); ?>
                    </div>
                <?php endif; ?>
            </div>






            <?php require_once "../views/footer.php"; ?>