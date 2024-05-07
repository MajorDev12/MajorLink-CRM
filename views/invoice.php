<?php require_once "../controllers/session_Config.php"; ?>

<?php

require_once  '../database/pdo.php';
require_once  '../modals/getClientsNames_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/addInvoice_mod.php';
require_once  '../modals/addSale_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/setup_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);
$clientData = getClientsNames($connect);
$invoicesData = getAllInvoices($connect);

$settings = get_Settings($connect);
$code = $settings[0]["CurrencyCode"];
$symbol = $settings[0]["CurrencySymbol"];
?>
<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>


<style>
    .newInvoice {
        width: 80%;
        min-height: 130vh;
        position: relative;
        left: 10%;
        background-color: var(--light);
        font-family: 'Poppins', sans-serif;
        padding: 5%;
    }

    .newInvoice h5 {
        color: #342E37;
    }

    .newInvoice .header {
        background-color: var(--blue);
        padding: 25px 15px;
        position: relative;
    }

    .newInvoice .header .companyInfo {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .newInvoice .header h2 {
        color: #F9F9F9;
        font-size: 2.5em;
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        margin-right: 50px;
    }

    .newInvoice .header p {
        color: #eee;
        font-size: 12px;
        line-height: 7px;
    }





    /* second container */
    .newInvoice .secondContainer {
        margin: 7% 0;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
    }

    .newInvoice .secondContainer p {
        color: #222831;
        font-size: 14px;
    }

    .newInvoice .secondContainer h5 {
        color: #342E37;
        font-size: 16px;
    }

    .newInvoice .secondContainer .clientsSelect {
        width: 140%;
    }

    .newInvoice input,
    select {
        border: none;
        color: #342E37 !important;
        background-color: #eee !important;
    }

    .newInvoice input::placeholder {
        color: #342E37;
        font-size: 16px;
        font-weight: 500;
    }

    .newInvoice .secondContainer .toptotal,
    .newInvoice .totalPrice {
        color: var(--blue);
    }

    /* table invoice */
    .newInvoice table {
        margin-top: 10%;
        background-color: white;
    }

    .newInvoice table tbody tr:hover {
        background: white !important;
    }

    .newInvoice .table thead {
        margin-bottom: 10px;
    }

    .newInvoice .table thead tr th {
        color: var(--blue);
        border-bottom: 2px solid var(--blue);

    }

    .newInvoice .table tr .Subtotal,
    .newInvoice .table tr #Tax {
        color: var(--dark-grey);
    }

    .newInvoice .table input {
        width: 80%;
    }

    .newInvoice .secondContainer .status {
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

    .newInvoice footer {
        text-align: center;
        position: relative;
        bottom: 0%;
        width: 90%;
        color: var(--dark-grey);
    }

    .main-content .content .page .head {
        position: relative;
    }

    .main-content .content .page .tableActions {
        display: flex;
        flex-direction: row;
        justify-content: end;
    }

    .main-content .content .page .tableActions .bx {
        padding: 5px;
        cursor: pointer;
        color: var(--dark);
    }

    .main-content .content .page .tableActions .searchBtn,
    .main-content .content .page .tableActions .searchInput {
        border: 1px solid var(--dark-grey);
        outline: none;
        display: none;
        transition: all 0.5s ease-in-out;
    }

    .show {
        display: inline-block !important;
    }

    .main-content .content .page .tableActions .searchInput {
        width: 20%;
        padding-left: 10px;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        font-size: 14px;
    }

    .main-content .content .page .tableActions .searchBtn {
        background-color: var(--light);
        color: var(--dark);
        padding: 2px;
        margin-right: 2px;
        font-size: 14px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }

    .main-content .content .page .tableActions .searchBtn:hover {
        color: var(--light-green);
        background-color: var(--blue);
    }

    .main-content .content .page .tableActions .filterdiv {
        position: absolute;
        top: 100%;
        display: none;
        flex-direction: column;
        width: 15%;
        background-color: var(--light);
        box-shadow: 2px 2px 5px var(--light-green);
    }

    .main-content .content .page .tableActions .filterdiv p {
        padding-bottom: 5px;
        border-bottom: 1px solid var(--grey);
        font-weight: 500;
    }

    .main-content .content .page .tableActions .filterdiv span {
        font-size: 12px;
    }

    .main-content .content table {
        width: 100%;
        border-collapse: collapse;
    }


    .main-content .content table th {
        padding-bottom: 12px;
        font-size: 13px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
    }

    .main-content .content table td {
        padding: 16px 0;
        text-align: left;
    }

    .main-content .content table td .icon {
        background-color: var(--blue);
        border-radius: 5px;
        padding: 4px;
        cursor: pointer;
    }

    .main-content .content table td .view {
        background-color: var(--blue);
    }

    .main-content .content table td .pdf {
        background-color: var(--yellow);
    }

    .main-content .content table td .print {
        background-color: var(--orange);
    }

    .main-content .content table td .icon img {
        width: 20px;
    }

    .main-content .content table tbody tr:hover {
        background: var(--grey);
    }

    .main-content .content .tablenav {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .main-content .content .tablenav p {
        display: flex;
        justify-content: start;
    }

    .main-content .content .tablenav .pagination {
        display: flex;
        justify-content: end;
    }

    .main-content .content .printTable {
        width: 70%;
        background: var(--grey);
    }

    .main-content .content #analytics li {
        background-color: var(--grey);
    }







    @media screen and (max-width: 920px) {
        .main-content .content {
            min-width: 700px;
        }

        .main-content .content .head {
            min-width: 900px;
        }

    }



    @media print {
        #tableBody {
            width: 80%;
        }

        .tdIcons {
            display: none;
        }

        #printTable {
            background-color: var(--dark);
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
        <div class="head-title">

            <div class="left">
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Invoices</a>
                    </li>
                </ul>
            </div>


        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-2 border-bottom">
                    <div class="tabs">
                        <button type="button" class="btn active">Services</button>
                        <button type="button" class="btn active">Products</button>
                        <button type="button" class="btn active">New Invoice</button>
                        <button type="button" class="btn active">Analytics</button>
                    </div>
                </div>
            </div>


            <div class="content tab-content">


                <!-- services page -->
                <div class="page active" id="services">
                    <div class="h4 pb-2 mt-2 mb-5 border-bottom">
                        <h3>Service Records</h3>
                    </div>
                    <div class="tableActions">
                        <input type="submit" value="Search" class="searchBtn" id="searchBtn1">
                        <input type="search" class="searchInput" id="searchInput1">
                        <i class='bx bx-search' id="searchIcon1" onclick=""></i>
                        <i class='bx bxs-printer' id="printIcon1"></i>
                        <i class='bx bxs-spreadsheet' id="spreadsheetIcon1"></i>
                        <!-- <i class='bx bx-filter'></i> -->
                        <div class="filterdiv shadow-sm p-3 mb-5 bg-white rounded row">
                            <p>Filter</p>
                            <div class="col">
                                <input type="radio" name="" id="">
                                <span>None</span>
                            </div>
                            <div class="col">
                                <input type="radio" name="" id="">
                                <span>Start Date</span>
                            </div>
                            <div class="col">
                                <input type="radio" name="" id="">
                                <span>Due Date</span>
                            </div>
                            <div class="col">
                                <input type="radio" name="" id="">
                                <span>Status</span>
                            </div>
                        </div>
                    </div>
                    <table class="mt-5">
                        <thead id="thead1">
                            <tr>
                                <th>#</th>
                                <th>Number</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody1" class="tableBody searchData">
                            <?php $counter = 1; ?>
                            <?php if ($invoicesData) : ?>
                                <?php foreach ($invoicesData as $index => $invoice) : ?>
                                    <tr>
                                        <td class="index pe-3"><?= $index + 1;  ?></td>
                                        <td class=""><?php echo $invoice['InvoiceNumber']; ?></td>
                                        <td class=""><?php echo $invoice['FirstName'] . ' ' . $invoice['LastName']; ?></td>
                                        <td><span class=""><?php echo $invoice['TotalAmount']; ?></span></td>
                                        <td><span class=""><?php echo $invoice['StartDate']; ?></span></td>
                                        <td><span class=""><?php echo $invoice['DueDate']; ?></span></td>
                                        <td><span class=""><?php echo $invoice['Status']; ?></span></td>
                                        <td style="text-align:center">
                                            <a href="viewInvoice.php?i=<?= $invoice["InvoiceID"]; ?>&c=<?= $invoice["ClientID"]; ?>" class="icon view"><img src="../img/eyeIcon.png" alt=""></a>
                                            <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php?i=<?= $invoice["InvoiceID"]; ?>&c=<?= $invoice["ClientID"]; ?>" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                            <abbr title="print"><a href="../views/printInvoice.php?i=<?= $invoice["InvoiceID"]; ?>&c=<?= $invoice["ClientID"]; ?>" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?php echo '  
                             <tr>
                                <td colspan="8" style="text-center"> No Data Yet</td>
                            </tr>'; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>






                <!-- products page -->
                <div class="page" id="products">
                    <div class="h4 pb-2 mt-2 mb-5 border-bottom">
                        <h3>Product Records</h3>
                    </div>
                    <div class="tableActions">
                        <input type="submit" value="Search" class="searchBtn" id="searchBtn2">
                        <input type="search" class="searchInput" id="searchInput2">
                        <i class='bx bx-search' id="searchIcon2"></i>
                        <i class='bx bxs-printer' id="printIcon2"></i>
                        <i class='bx bxs-spreadsheet' id="spreadsheetIcon2"></i>
                        <!-- <i class='bx bx-filter'></i> -->
                        <div class="filterdiv shadow-sm p-3 mb-5 bg-white rounded row">
                            <p>Filter</p>
                            <div class="col">
                                <input type="radio" name="" id="">
                                <span>None</span>
                            </div>
                            <div class="col">
                                <input type="radio" name="" id="">
                                <span>Start Date</span>
                            </div>
                            <div class="col">
                                <input type="radio" name="" id="">
                                <span>Due Date</span>
                            </div>
                            <div class="col">
                                <input type="radio" name="" id="">
                                <span>Status</span>
                            </div>
                        </div>
                    </div>
                    <table class="mt-5">
                        <thead id="thead2">
                            <tr>
                                <th>#</th>
                                <th>Invoice Number</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Issue Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody2" class="tableBody searchSales">
                            <?php $counter = 1; ?>
                            <?php $sales = getSalesData($connect); ?>
                            <?php if ($sales) : ?>
                                <?php foreach ($sales as $index => $sale) : ?>
                                    <tr>
                                        <td class="index pe-3"><?= $index + 1;  ?></td>
                                        <td class=""><?php echo $sale['InvoiceNumber']; ?></td>
                                        <td class=""><?php echo $sale['FirstName'] . ' ' . $sale['LastName']; ?></td>
                                        <td><span class=""><?php echo $sale['Quantity'] * $sale['UnitPrice']; ?></span></td>
                                        <td><span class=""><?php echo $sale['SaleDate']; ?></span></td>
                                        <td><span class=""><?php echo $sale['PaymentStatus']; ?></span></td>
                                        <td style="text-align:center">
                                            <a href="viewProduct.php?i=<?= $sale["SaleID"]; ?>&c=<?= $sale["ClientID"]; ?>" class="icon view"><img src="../img/eyeIcon.png" alt=""></a>
                                            <abbr title="download pdf"><a href="../controllers/generateSalesInvoice_contr.php?i=<?= $sale["SaleID"]; ?>&c=<?= $sale["ClientID"]; ?>" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                            <abbr title="print"><a href="../views/printSaleInvoice.php?i=<?= $sale["SaleID"]; ?>&c=<?= $sale["ClientID"]; ?>" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?php echo '  
                             <tr>
                                <td colspan="8" style="text-center"> No Data Yet</td>
                            </tr>'; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>





                <!-- Create new invoice -->
                <div class="page" id="newInvoice">

                    <!-- the service modal -->
                    <div id="overlay"></div>
                    <div class="modal-container" id="changeModal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Choose Service</h5>
                                <button type="button" id="closeDelModal" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <table class="mt-3" id="serviceTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Volume</th>
                                            <th>Price</th>
                                            <th>Select</th> <!-- Add a new column for radio buttons -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php $plans = getPlanData($connect); ?>
                                        <?php if ($plans) : ?>
                                            <?php foreach ($plans as $index => $plan) : ?>
                                                <tr>
                                                    <td class=""><?php echo $plan['Name']; ?></td>
                                                    <td><span class=""><?php echo $plan['Volume']; ?></span></td>
                                                    <td><span class=""><?php echo $plan['Price']; ?></span></td>
                                                    <td style="text-align: center;"><input type="radio" name="selectedRow"></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <?php echo '  
                             <tr>
                                <td colspan="8" style="text-center"> No Data Yet</td>
                            </tr>'; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" id="saveBtn" class="btn btn-primary ml-3">Yes</button>
                                <button type="button" id="cancel" class="btn btn-danger ml-3">Cancel</button>
                            </div>
                        </div>
                    </div>





















                    <div class="newInvoice shadow-sm bg-body rounded">
                        <!-- header -->
                        <div class="header">

                            <div class="companyInfo">
                                <div class="">
                                    <h2>INVOICE</h2>
                                </div>
                                <div class="first">
                                    <p id="website"><?= $settings[0]["Website"]; ?></p>
                                    <p id="email"><?= $settings[0]["Email"]; ?></p>
                                    <p id="phonenumber"><?= $settings[0]["PhoneNumber"]; ?></p>
                                </div>
                                <div class="second">
                                    <p id="Address"><?= $settings[0]["Address"]; ?></p>
                                    <p id="City"><?= $settings[0]["City"]; ?> <?= $settings[0]["Country"]; ?></p>
                                    <p id="ZipCode"><?= $settings[0]["Zipcode"]; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Client info -->
                        <div class="secondContainer">
                            <div class="clientInfo">
                                <select id="user_select" name="user_id" class="clientsSelect">
                                    <option value="" selected hidden>--Search--</option>
                                </select>
                                <p>Billed To</p>
                                <h5 class="clientNames"></h5>
                                <h5 class="address"></h5>
                                <h5 class="city"></h5>
                                <h5 class="zipcode"></h5>
                                <h5 class="country"></h5>

                            </div>

                            <div class="invoiceInfo">
                                <p>Invoice Number</p>
                                <abbr title="leave blank for automatic generation">
                                    <h5><input type="text" style="border: none;" placeholder="INV00001" class="invoiceNumber"></h5>
                                </abbr>

                                <p class="issueDate">Date of Issue</p>
                                <h5><input type="date" class="paymentDate"></h5>
                                <p class="issueDate">Start Date</p>
                                <h5><input type="date" class="startDate"></h5>
                            </div>
                            <div class="invoiceTotal">
                                <p class="issueDate">Expire Date</p>
                                <h5><input type="date" class="expireDate"></h5>
                                <p>Invoice Total</p>
                                <h4 class="toptotal"><span class="currency"><?= $symbol; ?> </span> <span class="topTotal">00.00</span></h4>
                                <select name="" id="status">
                                    <option value="" disabled selected>Status</option>
                                    <option value="Paid">Paid</option>
                                    <option value="Partially Paid">Partial Paid</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="addbuttons">
                            <button type="button" class="btn btn-primary" onclick="addRow()">Add Blank Line</button>
                            <button type="button" class="btn btn-primary" id="addService">Add Service</button>
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

                                <tr>
                                    <td colspan="3" class="border-0"></td>
                                    <td colspan="" class="Subtotal">Subtotal</td>
                                    <td class="subtotalAmount">00.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-0"></td>
                                    <td colspan="" id="Tax">
                                        Tax<br />
                                        <span><?= $symbol; ?></span>
                                        <input type="radio" name="taxType" value="<?= $symbol; ?>" checked id="dollarRadio">
                                        <br />
                                        <span>%</span>
                                        <input type="radio" name="taxType" value="%" id="percentRadio">
                                    </td>
                                    <td class="taxAmount"><input type="text" placeholder="" class="tax"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-0"></td>
                                    <td colspan="" class="Total">Total</td>
                                    <td class="totalPrice"><span class="currency"><?= $symbol; ?> </span>0</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>


                    <div class="addbuttons">
                        <!-- <button type="button" class="btn btn-primary">Save</button> -->
                        <button type="button" class="btn btn-primary" onclick="saveInvoice()">Save and close</button>

                        <div id="errorMsg"></div>
                    </div>
                </div>



                <?php

                $totalIncomeByStatus = getTotalIncomeByInvoiceStatus($connect);
                if ($totalIncomeByStatus) {
                    $totalPaid = $totalIncomeByStatus['total_paid'];
                    $totalPending = $totalIncomeByStatus['total_pending'];
                    $totalCancelled = $totalIncomeByStatus['total_cancelled'];
                    $totalPartiallyPaid = $totalIncomeByStatus['total_partially_paid'];

                    // Use the total income values as needed
                } else {
                    $totalPaid = '';
                    $totalPending = '';
                    $totalCancelled = '';
                    $totalPartiallyPaid = '';
                }
                ?>





                <!-- Transaction Report page -->
                <div class="page" id="analytics">
                    <h3 class="pb-2 mt-2 mb-2 border-bottom">Reports</h3>

                    <div class="sales mt-5">
                        <h5> Services Analytics</h5>
                        <div class="content.main">
                            <ul class="box-info">
                                <li>
                                    <span class="text">
                                        <p><?= $symbol . ' ' . number_format($totalPaid, 2); ?></p>
                                        <h3 style="color: #2cce89;">Paid</h3>
                                    </span>
                                </li>
                                <li>
                                    <span class="text">
                                        <p><?= $symbol . ' ' . number_format($totalPartiallyPaid, 2); ?></p>
                                        <h3 style="color: #3C91E6;">Partially Paid</h3>
                                    </span>
                                </li>
                                <li>
                                    <span class="text">
                                        <p><?= $symbol . ' ' . number_format($totalPending, 2); ?></p>
                                        <h3 style="color: #FFCE26;">Pending</h3>
                                    </span>
                                </li>
                                <li>
                                    <span class="text">
                                        <p><?= $symbol . ' ' . number_format($totalCancelled, 2); ?></p>
                                        <h3 style="color: #FD7238;">Cancelled</h3>
                                    </span>
                                </li>
                            </ul>

                        </div>


                    </div>


                    <?php

                    $totalIncomeByStatus = getTotalIncomeBySalesStatus($connect);
                    if ($totalIncomeByStatus) {
                        $total_paid = $totalIncomeByStatus['total_paid'];
                        $total_pending = $totalIncomeByStatus['total_pending'];
                        $total_cancelled = $totalIncomeByStatus['total_cancelled'];
                        $total_partially_paid = $totalIncomeByStatus['total_partially_paid'];

                        // Use the total income values as needed
                    } else {
                        $total_paid = '';
                        $total_pending = '';
                        $total_cancelled = '';
                        $total_partially_paid = '';
                    }
                    ?>
                    <div class="Plan mt-5">
                        <h5>Sales Analytics</h5>

                        <div class="content.main">
                            <ul class="box-info">
                                <li>
                                    <span class="text">
                                        <p><?= $symbol . ' ' . number_format($total_paid, 2); ?></p>
                                        <h3 style="color: #2cce89;">Paid</h3>
                                    </span>
                                </li>
                                <li>
                                    <span class="text">
                                        <p><?= $symbol . ' ' . number_format($total_partially_paid, 2); ?></p>
                                        <h3 style="color: #3C91E6;">Partially Paid</h3>
                                    </span>
                                </li>
                                <li>
                                    <span class="text">
                                        <p><?= $symbol . ' ' . number_format($total_pending, 2); ?></p>
                                        <h3 style="color: #FFCE26;">Pending</h3>
                                    </span>
                                </li>
                                <li>
                                    <span class="text">
                                        <p><?= $symbol . ' ' . number_format($total_cancelled, 2); ?></p>
                                        <h3 style="color: #FD7238;">Cancelled</h3>
                                    </span>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>


            </div>

            <?php require_once "footer.php"; ?>

            <script>
                document.getElementById('saveBtn').addEventListener('click', function() {
                    var selectedRadio = document.querySelector('input[name="selectedRow"]:checked');
                    if (!selectedRadio) {
                        // Handle case where no radio button is selected
                        return;
                    }

                    var selectedRow = selectedRadio.closest('tr');

                    // Extract data from the selected row
                    var rowData = Array.from(selectedRow.querySelectorAll('td')).map(cell => cell.textContent);

                    // Create a new row in the main table
                    var table = document.getElementById('dataTable').getElementsByTagName('tbody')[0];
                    var newRow = table.insertRow(table.rows.length - 3);

                    // Add cells to the new row
                    for (var i = 0; i < 5; i++) {
                        var cell = newRow.insertCell(i);

                        if (i === 0 || i === 1) {
                            // For the first, second, and fourth columns
                            var input = document.createElement('input');
                            input.type = 'text';
                            input.value = rowData[i]; // Populate input with row data
                            cell.appendChild(input);
                        } else if (i === 2) {
                            // For the third cell (Price)
                            var input = document.createElement('input');
                            input.type = 'text';
                            input.classList.add('months');
                            input.addEventListener('input', updateAmount);
                            cell.appendChild(input);
                        } else if (i === 3) {
                            // For the third cell (Price)
                            var input = document.createElement('input');
                            input.type = 'text';
                            input.classList.add('price');
                            input.value = rowData[2]; // Populate input with price data from modal row
                            input.addEventListener('input', updateAmount);
                            cell.appendChild(input);
                        } else if (i === 4) {
                            // For the last cell (Amount)
                            cell.classList.add('amount');
                            cell.textContent = '0';


                            // Create the delete icon
                            var deleteIcon = document.createElement('i');
                            deleteIcon.className = 'fas fa-trash-alt delete-icon';
                            deleteIcon.style.position = 'absolute';
                            deleteIcon.style.left = '2%';
                            deleteIcon.style.color = '#AAAAAA';
                            deleteIcon.style.cursor = 'pointer';
                            cell.appendChild(deleteIcon);

                            // Add event listener to delete icon
                            deleteIcon.addEventListener('click', function() {
                                var rowToDelete = this.closest('tr');
                                rowToDelete.parentNode.removeChild(rowToDelete);
                            });

                        }






                    }


                    hideModal();


                });

















                // tab navigation
                initializeTabs(".tabs button", ".tab-content .page");


                document.addEventListener('DOMContentLoaded', function() {

                    const searchIcon1 = document.getElementById('searchIcon1');
                    const searchInput1 = document.getElementById('searchInput1');
                    const searchBtn1 = document.getElementById('searchBtn1');
                    const printIcon1 = document.getElementById('printIcon1');
                    const spreadsheetIcon1 = document.getElementById('spreadsheetIcon1');
                    const thead1 = document.getElementById('thead1');
                    const searchData = document.getElementsByClassName('searchData');
                    const pageUrl1 = '../controllers/searchData_contr.php';
                    addSearchEventListener(searchIcon1, searchInput1, searchBtn1);


                    const searchIcon2 = document.getElementById('searchIcon2');
                    const searchInput2 = document.getElementById('searchInput2');
                    const searchBtn2 = document.getElementById('searchBtn2');
                    const printIcon2 = document.getElementById('printIcon2');
                    const spreadsheetIcon2 = document.getElementById('spreadsheetIcon2');
                    const thead2 = document.getElementById('thead2');
                    const searchSales = document.getElementsByClassName('searchSales');
                    const pageUrl2 = '../controllers/searchSales_contr.php';
                    addSearchEventListener(searchIcon2, searchInput2, searchBtn2);




                    Search(searchBtn1, searchInput1, pageUrl1, 'searchData');
                    Search(searchBtn2, searchInput2, pageUrl2, 'searchSales');


                    printIcon1.addEventListener('click', function() {
                        // Access the first element with the class 'searchSales'
                        printTable(printIcon1, searchData[0], 'thead1');
                    });


                    printIcon2.addEventListener('click', function() {
                        // Access the first element with the class 'searchSales'
                        printTable(printIcon2, searchSales[0], 'thead2');
                    });



                    // const searchData = document.getElementsByClassName('searchData');
                    // const searchSales = document.getElementsByClassName('searchSales');

                    spreadsheetIcon1.addEventListener("click", function() {
                        exportToCSV(thead1, searchData[0]);
                    })

                    spreadsheetIcon2.addEventListener("click", function() {
                        exportToCSV(thead2, searchSales[0]);
                    })




                });












                // new invoice code
                var selectedClientId;
                var taxSymbol = <?= json_encode($symbol); ?>;
                var dollarRadio = document.getElementById('dollarRadio');
                var percentRadio = document.getElementById('percentRadio');

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
                                $('.address').text(response.Address);
                                $('.city').text(response.City);
                                $('.zipcode').text(response.Zipcode);
                                $('.country').text(response.Country);
                            },
                            error: function(error) {
                                console.error('Error fetching client data:', error);
                            }
                        });
                    });


                });








                document.addEventListener('DOMContentLoaded', function() {
                    var clientsSelect = document.querySelector(".clientsSelect").value;

                    // Add event listeners to radio buttons
                    dollarRadio.addEventListener('change', function() {
                        if (dollarRadio.checked) {
                            percentRadio.checked = false;
                            taxSymbol = <?= json_encode($symbol); ?>;
                        }
                        updateTotal();
                    });

                    percentRadio.addEventListener('change', function() {
                        if (percentRadio.checked) {
                            dollarRadio.checked = false;
                            taxSymbol = "%";
                        }
                        updateTotal();
                    });

                    document.getElementById("dataTable").addEventListener('input', function(event) {
                        updateAmount();
                        updateSubtotal();
                        updateTotal();
                    });


                    // Add event listener to the tax input field
                    var taxInput = document.querySelector('.tax');
                    taxInput.addEventListener('input', updateTotal);

                    // Function to update the total
                    function updateTotal() {
                        var subtotalAmount = parseFloat(document.querySelector('.subtotalAmount').textContent) || 0;
                        var taxAmount = parseFloat(taxInput.value) || 0;
                        var taxType = document.querySelector('input[name="taxType"]:checked').value;

                        var total = 0;

                        if (taxType !== '%') {
                            total = subtotalAmount + taxAmount;
                        } else {
                            var taxPercentage = (taxAmount / 100) * subtotalAmount;
                            total = subtotalAmount + taxPercentage;
                        }

                        // Update the total price element
                        var totalPriceElement = document.querySelector('.totalPrice');
                        var topTotalElement = document.querySelector('.topTotal');
                        var currencyElement = document.querySelector('.currency');
                        if (totalPriceElement && topTotalElement) {
                            totalPriceElement.textContent = total.toFixed(2);
                            topTotalElement.textContent = total.toFixed(2);
                            currencyElement.textContent = <?= json_encode($symbol); ?>;
                        }
                    }


                    // Initial calculation
                    updateTotal();

                });



                // Function to create a new row
                function addRow() {
                    var table = document.getElementById("dataTable").getElementsByTagName('tbody')[0];
                    var newRow = table.insertRow(table.rows.length - 3); // Insert before the subtotal row

                    // Add cells to the new row
                    for (var i = 0; i < 5; i++) {
                        var cell = newRow.insertCell(i);

                        if (i < 4) {
                            // For cells other than the last one
                            var input = document.createElement("input");
                            input.type = "text";

                            // Add classes and event listeners based on conditions
                            if (i === 2 || i === 3) {
                                input.classList.add(i === 2 ? 'months' : 'price');
                                input.addEventListener('input', updateAmount);
                            }

                            cell.appendChild(input);
                        } else {
                            // For the last cell
                            cell.classList.add('amount');
                            cell.textContent = '0';

                            // Add the delete icon directly to the last cell's HTML
                            cell.innerHTML += '<i class="fas fa-trash-alt delete-icon" style="position: absolute; left: 2%; color: #AAAAAA; cursor: pointer;"></i>';
                        }



                    }



                }

                // Event delegation to handle delete icon clicks
                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('delete-icon')) {
                        var row = event.target.closest('tr');
                        deleteRow(row);
                    }
                });

                function deleteRow(row) {
                    var table = row.closest('tbody'); // Reference the parent table body
                    table.removeChild(row);
                }








                var addService = document.querySelector("#addService");
                var closeDelModal = document.querySelector("#closeDelModal");
                var cancel = document.querySelector("#cancel");
                var saveBtn = document.querySelector("#saveBtn");

                addService.addEventListener("click", function() {
                    showModal();
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
                }




                // Function to update amount based on input values
                function updateAmount() {
                    // Get the input element that triggered the event
                    var input = event.target;

                    // Find the closest ancestor tr element
                    var row = input;
                    while (row && row.tagName !== 'TR') {
                        row = row.parentNode;
                    }

                    if (!row) {
                        console.error('Could not find the closest tr element.');
                        return;
                    }

                    // Rest of the code remains the same...
                    var monthsValue = parseFloat(row.querySelector('.months').value) || 0;
                    var priceValue = parseFloat(row.querySelector('.price').value) || 0;
                    var amount = monthsValue * priceValue;

                    // Update the "amount" cell
                    row.querySelector('.amount').textContent = isNaN(amount) ? 'NaN' : amount.toFixed(2);

                    // Create the delete icon
                    var deleteIcon = document.createElement('i');
                    deleteIcon.className = 'fas fa-trash-alt delete-icon';
                    deleteIcon.style.position = 'absolute';
                    deleteIcon.style.left = '1%';
                    deleteIcon.style.color = '#AAAAAA';
                    deleteIcon.style.cursor = 'pointer';
                    row.appendChild(deleteIcon);

                    // Add event listener to delete icon
                    deleteIcon.addEventListener('click', function() {
                        var rowToDelete = this.closest('tr');
                        rowToDelete.parentNode.removeChild(rowToDelete);
                    });


                    // Recalculate subtotal
                    updateSubtotal();
                }


                // Function to calculate and update subtotal
                function updateSubtotal() {
                    var table = document.getElementById("dataTable").getElementsByTagName('tbody')[0];
                    var rows = table.getElementsByTagName('tr');
                    var subtotal = 0;

                    // Iterate through rows and calculate subtotal
                    for (var i = 0; i < rows.length; i++) {
                        var amountCell = rows[i].querySelector('.amount');
                        if (amountCell) {
                            var amountValue = parseFloat(amountCell.textContent) || 0;
                            subtotal += amountValue;
                        }
                    }

                    // Update the subtotalAmount element
                    var subtotalAmountElement = document.querySelector('.subtotalAmount');
                    if (subtotalAmountElement) {
                        subtotalAmountElement.textContent = subtotal.toFixed(2);
                    }

                }

                // Attach event listener to recalculate on any input change
                document.getElementById("dataTable").addEventListener('input', function(event) {
                    updateAmount();
                });

                // Initial calculation
                updateSubtotal();


                function saveInvoice() {

                    //invoice info
                    var invoiceNumber = document.querySelector(".invoiceNumber");
                    var paymentDate = document.querySelector(".paymentDate");
                    var startDate = document.querySelector(".startDate");
                    var expireDate = document.querySelector(".expireDate");
                    var status = document.querySelector("#status").value;




                    if (!selectedClientId) {
                        displayMessage("errorMsg", "Choose the recipient first", true);
                        return;
                    }

                    if (!status) {
                        displayMessage("errorMsg", "Choose Payment Status", true);
                        return;
                    }

                    // Collect invoice products data from the table
                    var table = document.getElementById("dataTable").getElementsByTagName('tbody')[0];
                    var rows = table.getElementsByTagName('tr');
                    var tax = document.querySelector(".tax").value;
                    var invoiceProducts = [];
                    var subtotalAmount = parseFloat(document.querySelector('.subtotalAmount').textContent) || 0; // Get the initial subtotal
                    var totalPrice = parseFloat(document.querySelector('.totalPrice').textContent) || 0; // Get the initial subtotal


                    for (var i = 0; i < rows.length - 3; i++) { // Exclude the subtotal and tax rows
                        var cells = rows[i].getElementsByTagName('td');
                        var product = cells[0].querySelector('input').value;
                        var volume = cells[1].querySelector('input').value;
                        var qty = cells[2].querySelector('input').value;
                        var price = cells[3].querySelector('input').value;

                        // Get the amount from the last td
                        var amount = cells[4].textContent;

                        if (!qty || !price) {
                            displayMessage("errorMsg", "Quantity or Price Cannot be Empty", true);
                            return;
                        }


                        // Add the product data to the array
                        invoiceProducts.push({
                            product: product,
                            volume: volume,
                            qty: qty,
                            price: price
                        });
                    }


                    // console.log('subtotal' + subtotalAmount)
                    // console.log('total' + totalPrice)
                    // return


                    var formData = new FormData();
                    formData.append("selectedClientId", selectedClientId);
                    formData.append("invoiceNumber", invoiceNumber.value);
                    formData.append("paymentDate", paymentDate.value);
                    formData.append("startDate", startDate.value);
                    formData.append("expireDate", expireDate.value);
                    formData.append("tax", tax);
                    formData.append("taxSymbol", taxSymbol);
                    formData.append("subtotalAmount", subtotalAmount);
                    formData.append("totalPrice", totalPrice);
                    formData.append("status", status);
                    formData.append("invoiceProducts", JSON.stringify(invoiceProducts));

                    // Perform fetch API request
                    fetch('../controllers/addInvoice_contr.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.addInvoice && data.saveProducts) {
                                if (data.invoiceid && data.clientid) {
                                    window.location.href = `viewInvoice.php?i=${data.invoiceid}&c=${data.clientid}`;
                                }

                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });









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