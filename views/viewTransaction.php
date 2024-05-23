<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addPayment_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$payments = getAllPayments($connect);
?>
<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>
<style>
    .main-content .content {
        display: flex;
        flex-wrap: wrap;
        grid-gap: 24px;
        margin-top: 24px;
        width: 100%;
        color: var(--dark);
        overflow-x: auto;
    }

    .main-content .content>div {
        border-radius: 20px;
        background: var(--light);
        padding: 24px;
        overflow-x: auto;
    }

    .main-content .content .head {
        display: flex;
        align-items: center;
        grid-gap: 16px;
        margin-bottom: 24px;
    }

    .main-content .content .tableActions {
        display: flex;
        flex-direction: row;
        justify-content: end;
        align-items: center;
    }

    .main-content .head .tableActions .bx {
        padding: 5px;
        cursor: pointer;
        font-size: 1.2em;
        margin-top: 20px;
    }

    .main-content .head .tableActions .searchBtn,
    .main-content .head .tableActions .searchInput {
        border: 1px solid var(--dark-grey);
        outline: none;
        display: none;
        transition: all 0.5s ease-in-out;
    }


    .main-content .head .tableActions .searchInput {
        width: 30%;
        height: 30%;
        padding: 5px;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        font-size: 14px;
    }

    .main-content .head .tableActions .searchBtn {
        background-color: var(--light);
        color: var(--dark);
        padding: 5px;
        margin-right: 2px;
        font-size: 14px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        height: 30%;
    }

    .main-content .head .tableActions .searchBtn:hover {
        color: var(--light-green);
        background-color: var(--blue);
    }

    .main-content .content .head h3 {
        margin-right: auto;
        font-size: 24px;
        font-weight: 600;
    }

    .main-content .content .order {
        flex-grow: 1;
        flex-basis: 500px;
    }

    .main-content .content .order table {
        width: 100%;
        border-collapse: collapse;
    }

    .main-content .content .order table th {
        padding-bottom: 12px;
        font-size: 13px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
    }

    .main-content .content .order table td {
        padding: 16px 0;
    }

    .main-content .content .order table tbody tr:hover {
        background: var(--grey);
    }



    @media screen and (max-width: 920px) {
        .main-content .content .head {
            min-width: 900px;
        }

        .main-content .content .order table {
            min-width: 900px;
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
                        <a href="viewClient.php">List Customers</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">View Transactions</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- toast -->
        <div id="toast">
            <div class="toast-header">
                <img src="../img/user.png" alt="" width="30px">
                <small id="toast-time">3 secs Ago</small>
            </div>
            <div class="toast-body">
                <h3>Created Successfully Custom Toast Example</h3>
            </div>
        </div>
        <!-- toast -->


        <!-- content-container -->
        <div class="main-content">
            <div class="content">
                <div class="order">

                    <div class="head">
                        <div class="col">
                            <div class="h4 pb-2 mt-2 mb-2 border-bottom">
                                <h3>View All Transactions</h3>
                            </div>
                            <div class="tableActions">
                                <input type="submit" value="Search" class="searchBtn" id="searchBtn1">
                                <input type="search" class="searchInput" id="searchInput1">
                                <i class='bx bx-search' id="searchIcon1" onclick=""></i>
                                <i class='bx bxs-printer' id="printIcon1"></i>
                                <i class='bx bxs-spreadsheet' id="spreadsheetIcon1"></i>
                                <!-- <i class='bx bx-filter'></i> -->

                            </div>
                        </div>
                    </div>



                    <table class="mt-5">
                        <thead id="thead1">
                            <tr>
                                <th class="pl-3">TransactID</th>
                                <th>Payment Date</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody class="searchData">
                            <?php if ($payments) : ?>
                                <?php foreach ($payments as $payment) : ?>
                                    <tr>
                                        <td class="pl-3"><?= $payment['InvoiceNumber'] ?></td>
                                        <td><?= $payment['PaymentDate'] ?></td>
                                        <td><?= isset($payment['PaymentOptionName']) ? $payment['PaymentOptionName'] : '' ?></td>
                                        <td><?= isset($payment['PaymentAmount']) ? $payment['PaymentAmount'] : $payment['Total'] ?></td>
                                        <td><?= isset($payment['PaymentStatus']) ? $payment['PaymentStatus'] : '' ?></td>
                                        <td><?= $payment['type'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td>No Data Yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>

            <?php require_once "footer.php"; ?>

            <script>
                const searchIcon1 = document.getElementById('searchIcon1');
                const searchInput1 = document.getElementById('searchInput1');
                const searchBtn1 = document.getElementById('searchBtn1');
                const printIcon1 = document.getElementById('printIcon1');
                const spreadsheetIcon1 = document.getElementById('spreadsheetIcon1');
                const thead1 = document.getElementById('thead1');
                const searchData = document.getElementsByClassName('searchData');
                const pageUrl1 = '../controllers/searchTransactionData_contr.php';
                addSearchEventListener(searchIcon1, searchInput1, searchBtn1);

                Search(searchBtn1, searchInput1, pageUrl1, 'searchData');

                printIcon1.addEventListener('click', function() {
                    // Access the first element with the class 'searchSales'
                    printTable(printIcon1, searchData[0], 'thead1');
                });

                spreadsheetIcon1.addEventListener("click", function() {
                    exportToCSV(thead1, searchData[0]);
                })
            </script>