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
require_once  '../modals/addPayment_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$clientID = $_SESSION['clientID'];

$payments = getPayments($connect, $clientID);
?>

<?php require_once "../views/header.php"; ?>
<?php require_once "../views/style.config.php"; ?>
<style>
    .main-content .content table {
        width: 100%;
        border-collapse: collapse;
    }

    .main-content .content table th {
        padding-bottom: 12px;
        font-size: 13px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
        color: var(--dark);
    }

    .main-content .content table td {
        padding: 16px 0;
        color: var(--light-dark);
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
                        <a class="active" href="#">Transactions</a>
                    </li>
                </ul>
            </div>

            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>

        <!-- content-container -->
        <div class="main-content">
            <div class="content">
                <div class="h4 pb-2 mt-2 mb-4 border-bottom">
                    <h3>View Transactions</h3>
                </div>


                <table class="mt-5">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Payment Date</th>
                            <th>Payment Method</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">

                    </tbody>
                </table>

                <!-- Pagination -->
                <nav class="tablenav mt-5" aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>




            </div>
            <?php require_once "../views/footer.php"; ?>

            <script>
                const TableNavigation = (function() {
                    // Private variables
                    let data = [];
                    let renderRowFunction = null;

                    // Private functions
                    function renderTableRows(currentPage, itemsPerPage) {
                        const tableBody = document.getElementById('tableBody');
                        tableBody.innerHTML = '';

                        const startIndex = (currentPage - 1) * itemsPerPage;

                        data.forEach((item, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = renderRowFunction(startIndex + index, item);
                            tableBody.appendChild(row);
                        });
                    }

                    function renderPagination(totalPages, currentPage) {
                        const pagination = document.getElementById('pagination');
                        pagination.innerHTML = '';

                        for (let i = 1; i <= totalPages; i++) {
                            const li = document.createElement('li');
                            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                            li.innerHTML = `<a class="page-link" href="#" onclick="TableNavigation.loadData(${i})">${i}</a>`;
                            pagination.appendChild(li);
                        }
                    }

                    function loadData(page) {
                        const itemsPerPage = 5;
                        const startIndex = (page - 1) * itemsPerPage;
                        const endIndex = startIndex + itemsPerPage;
                        const dataToShow = data.slice(startIndex, endIndex);

                        renderTableRows(page, itemsPerPage);
                        renderPagination(Math.ceil(data.length / itemsPerPage), page);
                    }

                    // Public API
                    return {
                        initialize: function(config) {
                            data = config.data || [];
                            renderRowFunction = config.renderRowFunction || ((index, item) => '');
                            loadData(1); // Initial load
                        },
                        loadData: loadData,
                    };
                })();




                // Get payments data from PHP and convert it to a JavaScript array
                const payments = <?php echo json_encode($payments); ?>;


                const renderRowFunction = function(index, item) {
                    // Customize this function based on the type of data
                    return `
        <td>${index}</td>
        <td>${item.PaymentDate}</td>
        <td>${item.PaymentOptionName}</td>
        <td>${item.Volume}</td>
        <td>${item.PaymentAmount}</td>
        <td>${item.PaymentStatus}</td>
    `;
                };


                TableNavigation.initialize({
                    data: payments,
                    renderRowFunction: renderRowFunction,
                });
            </script>