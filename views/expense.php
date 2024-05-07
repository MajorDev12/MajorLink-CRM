<?php require_once "../controllers/session_Config.php"; ?>
<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addProduct_mod.php';
require_once  '../modals/setup_mod.php';
require_once  '../modals/reports_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$products = getProductData($connect);
// get currency details
$settings = get_Settings($connect);
$code = $settings[0]["CurrencyCode"];
$symbol = $settings[0]["CurrencySymbol"];

?>
<style>
    .container {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .tabs {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
    }

    h3 {
        background-color: var(--grey);
        text-align: center;
        padding: 15px 0;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
    }

    .tab-content {
        width: 100%;
        padding: 50px 40px;
    }

    .tab-content h4 {
        font-size: 24px;
        margin-bottom: 20px;
        color: var(--blue);
        font-weight: 600;
    }

    .tab-content p {
        font-size: 14px;
        text-align: justify;
        line-height: 1.9;
        letter-spacing: 0.4px;
        color: #202238;
    }

    .tab-content .page {
        display: none;
    }

    .tab-content .active {
        display: block;
    }

    .tab-content .page {
        margin-top: 10%;
    }

    .tab-content .page .number {
        font-weight: 500;
        font-size: 1.5em;
        color: var(--dark);
    }

    .tab-content .page .text {
        display: flex;
        flex-direction: column;
        align-items: end;
        justify-content: flex-end;
        color: var(--blue);
    }

    .tab-content .canvasChart {
        margin-top: 10%;
    }

    .tabs .active {
        background-color: #ffffff;
        color: #4d5bf9;
    }

    /* chart */
    .date-range {
        margin-bottom: 20px;
    }

    .chart-container {
        margin-top: 20px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15%;
    }

    .data-table th,
    .data-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .data-table th {
        background-color: #f2f2f2;
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
                        <a class="active" href="#">Expense</a>
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
                <div class="container">
                    <div class="tabs">
                        <h3 class="active">By Category</h3>
                        <h3>By Day</h3>
                        <h3>By Month</h3>
                        <h3>By Year</h3>
                    </div>

                    <div class="tab-content">





                        <div class="active page">
                            <h4>Expense Summary By Category</h4>

                            <label for="expensecategory">Choose Category</label>
                            <select class="form-select form-select-md mb-3" id="ProductSelected" aria-label="Default select example">
                                <option value="" disabled selected>Choose...</option>
                                <?php foreach ($products as $product) : ?>
                                    <option value="<?= $product["ProductID"]; ?>" data-price="<?= $product["Price"]; ?>"><?= $product["ProductName"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p id="productErrorMsg"></p>
                            <button id="getProductBtn" class="btn btn-primary">Check</button>
                            <p id="totalIncomeByProduct" class="text">Total Income This Month: <span class="number"><?= $symbol; ?> <span id="totalProduct">0.00</span></span></p>
                            <canvas id="expensecategoryChart" class="canvasChart"></canvas>



                            <div class="content">
                                <?php
                                $expenseOfAllProducts = getTotalExpenseByType($connect);
                                $allTypeExpenses = [];
                                $allExpenses = [];
                                $allTotalExpenses = 0;

                                foreach ($expenseOfAllProducts as $expenseType) {
                                    $allExpenses[] = $expenseType['ExpenseTypeName'];
                                    $allTypeExpenses[] = $expenseType['TotalExpense'];
                                    $allTotalExpenses += $expenseType['TotalExpense'];
                                }
                                ?>

                                <h4>All Expense Summary</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($allTotalExpenses, 2); ?></span></p>
                                <canvas id="allexpensecategoryChart" class="canvasChart"></canvas>

                            </div>


                        </div>


                        <div class="page">
                            <h4>Expense Summary By Day</h4>
                            <label for="form-data">Choose Day</label>
                            <input type="date" name="" id="expenseDateInput">
                            <p id="errorMsg"></p>
                            <button id="getExpenseByDateBtn" class="btn btn-primary">Check</button>
                            <p id="totalIncomeByProduct" class="text">Total Income: <span class="number"><?= $symbol; ?> <span id="totalExpenseByDate">0.00</span></span></p>
                            <canvas id="expenseday" class="canvasChart"></canvas>
                        </div>




                        <div class="page">
                            <h4>Expense Summary By Month</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="expensemonth">Choose Month</label>
                                    <select id="expensemonth"></select>
                                </div>
                                <div class="col-md-6">
                                    <label for="expenseYear">Choose Year</label>
                                    <select id="expenseYear"></select>
                                </div>
                            </div>
                            <p id="expenseYearErrorMsg"></p>
                            <button id="getmonthYearBtn" class="btn btn-primary mb-5">Check</button>
                            <p id="totalIncomeByProduct" class="text">Total Income: <span class="number"><?= $symbol; ?> <span id="totalExpenseByMonthYear">0.00</span></span></p>
                            <canvas id="expensemonthChart" class="canvasChart"></canvas>
                        </div>








                        <div class="page">
                            <h4>Income Summary By Year</h4>
                            <label for="expenseyearOne">Choose Year</label>
                            <select id="expenseyearOne"></select>
                            <p id="expenseyearErrorMsg"></p>
                            <button id="getexpenseyearBtn" class="btn btn-primary">Check</button>
                            <p id="totalIncomeByProduct" class="text">Total Income: <span class="number"><?= $symbol; ?> <span id="totalExpenseByYear">0.00</span></span></p>
                            <canvas id="expenseyear1Chart" class="canvasChart"></canvas>


                        </div>

                    </div>
                </div>
            </div>



            <?php require_once "footer.php"; ?>



            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    updateProductChart(0.00, "");
                    updateExpenseDateChart(0.00, "");
                    updatemonthyearChart(0.00, "", "");
                    updateYearExpenseChart(0.00, "")
                    populateDropdown("expenseYear", yearOptions);
                    populateDropdown("expenseyearOne", yearOptions);
                    populateDropdown("expensemonth", monthOptions);
                })






                // tabs functionality
                let tabs = document.querySelectorAll(".tabs h3");
                let tabContents = document.querySelectorAll(".tab-content .page");

                tabs.forEach((tab, index) => {
                    tab.addEventListener("click", () => {
                        tabContents.forEach((content) => {
                            content.classList.remove("active");
                        });
                        tabs.forEach((tab) => {
                            tab.classList.remove("active");
                        });
                        tabContents[index].classList.add("active");
                        tabs[index].classList.add("active");
                    });
                });










                var getProductBtn = document.querySelector("#getProductBtn");

                getProductBtn.addEventListener("click", function() {
                    var ProductSelected = document.getElementById("ProductSelected").value;

                    if (!ProductSelected) {
                        displayMessage("productErrorMsg", "Choose a Customer First", true);
                        return;
                    }
                    var formData = new FormData();
                    formData.append("ProductSelected", ProductSelected);

                    fetch("../controllers/expense_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let totalIncome = parseFloat(data.results.total_income);
                                let productName = data.results.ProductName;

                                if (totalIncome === null) {
                                    totalIncome = "0.00";
                                }

                                if (productName === null) {
                                    productName = "No Data";
                                }
                                formattedtotal = numberFormatJS(totalIncome);
                                document.getElementById('totalProduct').innerText = formattedtotal;

                                // Update the chart with the new data
                                updateProductChart(totalIncome, productName);
                            } else {
                                console.error("Error: " + data.message);
                                updateProductChart(0.00, "");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var customerChart = null;


                function updateProductChart(totalIncome, productName) {
                    const CurrencyCode = <?= json_encode($code); ?>;
                    var ctx = document.getElementById('expensecategoryChart').getContext('2d');

                    if (customerChart) {
                        // If a Chart instance exists, destroy it
                        customerChart.destroy();
                    }


                    customerChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [productName],
                            datasets: [{
                                label: 'Total Income',
                                data: [totalIncome],
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Amount (' + CurrencyCode + ')'
                                    }
                                },
                                x: {
                                    title: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
















                const allExpenses = <?= json_encode($allExpenses); ?>;
                const allTypeExpenses = <?= json_encode($allTypeExpenses); ?>;
                const CurrencyCode = <?= json_encode($code); ?>;
                var combinedLabels = [];
                for (var i = 0; i < allExpenses.length; i++) {
                    combinedLabels.push([allExpenses[i], numberFormatJS(allTypeExpenses[i])]); // Push an array containing area name and income
                }

                var ctx = document.getElementById('allexpensecategoryChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: combinedLabels,
                        datasets: [{
                            label: 'Refresh',
                            data: allTypeExpenses,
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(75, 192, 192)',
                                'rgb(255, 205, 86)',
                                'rgb(201, 203, 207)',
                                'rgb(54, 162, 235)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount ( ' + CurrencyCode + ' )'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: ''
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        if (label.length > 1) {
                                            return [label[0] + ':', label[1]];
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });





                // chart.js
                var getExpenseByDateBtn = document.querySelector("#getExpenseByDateBtn");

                getExpenseByDateBtn.addEventListener("click", function() {
                    var expenseDateInput = document.getElementById("expenseDateInput").value;

                    if (!expenseDateInput) {
                        displayMessage("errorMsg", "Choose a Date First", true);
                        return;
                    }
                    var formData = new FormData();
                    formData.append("expenseDateInput", expenseDateInput);

                    fetch("../controllers/expense_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let total = data.results.TotalExpense;
                                let expenseDate = data.results.ExpenseDate;
                                if (total === null) {
                                    total = "0.00";
                                }
                                if (expenseDate === null) {
                                    expenseDate = "No Data";
                                }
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalExpenseByDate').innerText = formattedtotal;

                                // Update the chart with the new data
                                updateExpenseDateChart(total, expenseDate);
                            } else {
                                console.error("Error: " + data.message);
                                updateExpenseDateChart(0.00, "");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var expenseChart = null;

                function updateExpenseDateChart(totalIncome, expenseDate) {
                    const CurrencyCode = <?= json_encode($code); ?>;
                    var ctx = document.getElementById('expenseday').getContext('2d');

                    if (expenseChart) {
                        // If a Chart instance exists, destroy it
                        expenseChart.destroy();
                    }


                    expenseChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [expenseDate],
                            datasets: [{
                                label: 'Total Income',
                                data: [totalIncome],
                                backgroundColor: '#3C91E6',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Amount ( ' + CurrencyCode + ' )'
                                    }
                                },
                                x: {
                                    title: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }









                var getmonthYearBtn = document.getElementById("getmonthYearBtn");


                getmonthYearBtn.addEventListener("click", function() {
                    var month = document.getElementById("expensemonth").value;
                    var year = document.getElementById("expenseYear").value;

                    if (!month || !year) {
                        displayMessage("expenseYearErrorMsg", "Choose a month and year First", true);
                        return;
                    }

                    var formData = new FormData();
                    formData.append("month", month);
                    formData.append("year", year);

                    fetch("../controllers/expense_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let total = data.results.TotalExpense;
                                let month = data.results.Month;
                                let year = data.results.Year;
                                if (total === null) {
                                    total = "0.00";
                                }

                                if (month === null) {
                                    month = "";
                                }
                                if (year === null) {
                                    year = "No Record";
                                }
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalExpenseByMonthYear').innerText = formattedtotal;

                                // Update the chart with the new data
                                updatemonthyearChart(total, month, year);
                            } else {
                                console.error("Error: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

                })





                var expensemonthyearChart = null;

                function updatemonthyearChart(totalIncome, month, year) {
                    var ctx = document.getElementById('expensemonthChart').getContext('2d');

                    if (expensemonthyearChart) {
                        // If a Chart instance exists, destroy it
                        expensemonthyearChart.destroy();
                    }


                    expensemonthyearChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [month + ' ' + year],
                            datasets: [{
                                label: 'Total Income',
                                data: [totalIncome],
                                backgroundColor: '#3C91E6',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Amount ( ' + CurrencyCode + ' )'
                                    }
                                },
                                x: {
                                    title: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }




                var getexpenseyearBtn = document.querySelector("#getexpenseyearBtn");

                getexpenseyearBtn.addEventListener("click", function() {
                    var expenseyearSelected = document.getElementById("expenseyearOne").value;

                    if (!expenseyearSelected) {
                        displayMessage("expenseyearErrorMsg", "Choose a Year First", true);
                        return;
                    }
                    var formData = new FormData();
                    formData.append("expenseyearSelected", expenseyearSelected);

                    fetch("../controllers/expense_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let TotalExpense = parseFloat(data.results.TotalExpense);
                                let Year = data.results.Year;

                                if (TotalExpense === null) {
                                    TotalExpense = "0.00";
                                }

                                if (Year === null) {
                                    Year = "No Data";
                                }
                                formattedtotal = numberFormatJS(TotalExpense);
                                document.getElementById('totalExpenseByYear').innerText = formattedtotal;

                                // Update the chart with the new data
                                updateYearExpenseChart(TotalExpense, Year);
                            } else {
                                console.error("Error: " + data.message);
                                updateYearExpenseChart(0.00, "");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var yearChart = null;


                function updateYearExpenseChart(TotalExpense, Year) {
                    const CurrencyCode = <?= json_encode($code); ?>;
                    var ctx = document.getElementById('expenseyear1Chart').getContext('2d');

                    if (yearChart) {
                        // If a Chart instance exists, destroy it
                        yearChart.destroy();
                    }


                    yearChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [Year],
                            datasets: [{
                                label: 'Total Income',
                                data: [TotalExpense],
                                backgroundColor: ['rgba(75, 192, 192, 0.5)', 'rgba(255, 99, 132, 0.5)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Amount ( ' + CurrencyCode + ' )'
                                    }
                                },
                                x: {
                                    title: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            </script>