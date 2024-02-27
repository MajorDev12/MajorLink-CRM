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
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/setup_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/config.php';

$connect = connectToDatabase($host, $dbname, $username, $password);

$clientID = $_SESSION["clientID"];

$clientData = getClientDataById($connect, $clientID);
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>
<style>
    abbr {
        display: inline;
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
        /* background-color: var(--blue); */
    }

    .main-content .content table tbody tr:hover {
        background: var(--grey);
    }

    .tablenav {
        display: flex;
        justify-content: start;
    }



    @media screen and (max-width: 920px) {
        .main-content .content .head {
            min-width: 900px;
        }

        .main-content .content table {
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
                <h1>Hi, <?= $_SESSION['FirstName']; ?></h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Dashboard</a>
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
                <div class="h4 pb-2 mt-2 mb-4 border-bottom">
                    <h3>All Records</h3>
                </div>


                <table class="mt-5">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Start Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td>INV0001</td>
                            <td>2000</td>
                            <td>13/03/24</td>
                            <td>13/02/24</td>
                            <td>Paid</td>
                            <td class="actions">
                                <abbr title="View"><a href="viewInvoice.php" target="_blank" class="icon view"><img src="../img/eyeIcon.png" alt=""></a></abbr>
                                <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                <abbr title="print"><a href="printInvoice.php" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                            </td>
                        </tr>
                        <tr>
                            <td>INV0002</td>
                            <td>1500</td>
                            <td>12/02/23</td>
                            <td>12/01/23</td>
                            <td>Paid</td>
                            <td class="actions">
                                <abbr title="View"><a href="viewInvoice.php" target="_blank" class="icon view"><img src="../img/eyeIcon.png" alt=""></a></abbr>
                                <abbr title="download pdf"><a href="../controllers/generatepdf_contr.php" target="_blank" class="icon pdf"><img src="../img/pdfIcon.png" alt=""></a></abbr>
                                <abbr title="print"><a href="printInvoice.php" target="_blank" class="icon print"><img src="../img/printIcon.png" alt=""></a></abbr>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav class="tablenav mt-5" aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>




            </div>


            <script>
                // Get payments data from PHP and convert it to a JavaScript array
                const payments = <?php echo json_encode($payments); ?>;

                console.log(payments)


                // Function to render table rows
                function renderTableRows(data, currentPage, itemsPerPage) {
                    const tableBody = document.getElementById('tableBody');
                    tableBody.innerHTML = '';

                    const startIndex = (currentPage - 1) * itemsPerPage;

                    data.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                <td>${startIndex + index + 1}</td>
                <td>${item.PaymentDate}</td>
                <td>${item.PaymentOptionName}</td>
                <td>${item.Volume}</td>
                <td>${item.PaymentAmount}</td>
                <td>${item.PaymentStatus}</td>
            `;
                        tableBody.appendChild(row);
                    });
                }

                // Function to render pagination
                function renderPagination(totalPages, currentPage) {
                    const pagination = document.getElementById('pagination');
                    pagination.innerHTML = '';

                    for (let i = 1; i <= totalPages; i++) {
                        const li = document.createElement('li');
                        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                        li.innerHTML = `<a class="page-link" href="#" onclick="loadData(${i})">${i}</a>`;
                        pagination.appendChild(li);
                    }
                }

                // Function to load data based on page number
                function loadData(page) {
                    const itemsPerPage = 5;
                    const startIndex = (page - 1) * itemsPerPage;
                    const endIndex = startIndex + itemsPerPage;
                    const dataToShow = payments.slice(startIndex, endIndex);

                    renderTableRows(dataToShow, page, itemsPerPage);
                    renderPagination(Math.ceil(payments.length / itemsPerPage), page);
                }

                // Initial load (page 1)
                loadData(1);
            </script>












            <?php require_once "../views/footer.php"; ?>