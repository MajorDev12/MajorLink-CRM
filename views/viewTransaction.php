<?php require_once "../controllers/session_Config.php"; ?>
<?php
require_once  '../database/pdo.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
?>
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

    .main-content .content .head h3 {
        margin-right: auto;
        font-size: 24px;
        font-weight: 600;
    }

    .main-content .content .head .bx {
        cursor: pointer;
        font-size: 1.5em;
        margin-top: 20px;
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
                        <a class="active" href="#">View Client</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
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

        <div id="loader">Loading...</div>
        <!-- content-container -->
        <div class="main-content">
            <div class="content">
                <div class="order">

                    <div class="head">
                        <div class="col-md-6">
                            <label for="salesperson">Search Customer:</label>
                            <select class="form-select form-select-md " id="salesperson" aria-label="Default select example">
                                <option selected>Customer 1</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <div class="output-icons">
                                <i class='bx bxs-file-pdf'></i>
                                <i class='bx bxs-printer'></i>
                                <i class='bx bxs-download'></i>
                                <i class='bx bx-filter'></i>
                            </div>
                        </div>
                    </div>



                    <table class="mt-5">
                        <thead>
                            <tr>
                                <th>TransactID</th>
                                <th>Payment Date</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Plan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="">00001</td>
                                <td class="">1 / 01 / 2023 </td>
                                <td><span class="">M-Pesa</span></td>
                                <td><span class="">2000</span></td>
                                <td><span class="">5mbps</span></td>
                                <td>
                                    <a href="#" data-target="main" class="btn btn-primary me-2">View</a>
                                    <a href="#" data-target="addClient" class="btn btn-secondary">Edit</a>
                                </td>
                            </tr>

                            <tr>
                                <td class="">00001</td>
                                <td class="">1 / 01 / 2023 </td>
                                <td><span class="">M-Pesa</span></td>
                                <td><span class="">2000</span></td>
                                <td><span class="">5mbps</span></td>
                                <td>
                                    <a href="#" data-target="main" class="btn btn-primary me-2">View</a>
                                    <a href="#" data-target="addClient" class="btn btn-secondary">Edit</a>
                                </td>
                            </tr>

                            <tr>
                                <td class="">00001</td>
                                <td class="">1 / 01 / 2023 </td>
                                <td><span class="">M-Pesa</span></td>
                                <td><span class="">2000</span></td>
                                <td><span class="">5mbps</span></td>
                                <td>
                                    <a href="#" data-target="main" class="btn btn-primary me-2">View</a>
                                    <a href="#" data-target="addClient" class="btn btn-secondary">Edit</a>
                                </td>
                            </tr>

                            <tr>
                                <td class="">00001</td>
                                <td class="">1 / 01 / 2023 </td>
                                <td><span class="">M-Pesa</span></td>
                                <td><span class="">2000</span></td>
                                <td><span class="">5mbps</span></td>
                                <td>
                                    <a href="#" data-target="main" class="btn btn-primary me-2">View</a>
                                    <a href="#" data-target="addClient" class="btn btn-secondary">Edit</a>
                                </td>
                            </tr>

                            <tr>
                                <td class="">00001</td>
                                <td class="">1 / 01 / 2023 </td>
                                <td><span class="">M-Pesa</span></td>
                                <td><span class="">2000</span></td>
                                <td><span class="">5mbps</span></td>
                                <td>
                                    <a href="#" data-target="main" class="btn btn-primary me-2">View</a>
                                    <a href="#" data-target="addClient" class="btn btn-secondary">Edit</a>
                                </td>
                            </tr>

                            <tr>
                                <td class="">00001</td>
                                <td class="">1 / 01 / 2023 </td>
                                <td><span class="">M-Pesa</span></td>
                                <td><span class="">2000</span></td>
                                <td><span class="">5mbps</span></td>
                                <td>
                                    <a href="#" data-target="main" class="btn btn-primary me-2">View</a>
                                    <a href="#" data-target="addClient" class="btn btn-secondary">Edit</a>
                                </td>
                            </tr>\
                            <tr>
                                <td class="">00001</td>
                                <td class="">1 / 01 / 2023 </td>
                                <td><span class="">M-Pesa</span></td>
                                <td><span class="">2000</span></td>
                                <td><span class="">5mbps</span></td>
                                <td>
                                    <a href="#" data-target="main" class="btn btn-primary me-2">View</a>
                                    <a href="#" data-target="addClient" class="btn btn-secondary">Edit</a>
                                </td>
                            </tr>

                            <tr>
                                <td class="">00001</td>
                                <td class="">1 / 01 / 2023 </td>
                                <td><span class="">M-Pesa</span></td>
                                <td><span class="">2000</span></td>
                                <td><span class="">5mbps</span></td>
                                <td>
                                    <a href="#" data-target="main" class="btn btn-primary me-2">View</a>
                                    <a href="#" data-target="addClient" class="btn btn-secondary">Edit</a>
                                </td>
                            </tr>

                            <tr>
                                <td class="">00001</td>
                                <td class="">1 / 01 / 2023 </td>
                                <td><span class="">M-Pesa</span></td>
                                <td><span class="">2000</span></td>
                                <td><span class="">5mbps</span></td>
                                <td>
                                    <a href="#" data-target="main" class="btn btn-primary me-2">View</a>
                                    <a href="#" data-target="addClient" class="btn btn-secondary">Edit</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>

            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>