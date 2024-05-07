<?php require_once "../controllers/session_Config.php"; ?>
<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/reports_mod.php';
require_once  '../modals/setup_mod.php';
require_once  '../modals/addInvoice_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

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
        color: var(--light-dark);
    }

    .tab-content .page {
        display: none;
    }

    .tab-content .page .textColor {
        font-weight: 700;
        font-size: 1.5em;
        color: var(--green);
    }


    .tab-content .active {
        display: block;
    }

    .tab-content .page {
        margin-top: 10%;
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
                        <a class="active" href="#">Income Vs Expense</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- content-container -->
        <div class="main-content">


            <div class="content">
                <div class="container">
                    <div class="tabs">
                        <h3 class="active">Total</h3>
                        <h3>By Day</h3>
                        <h3>By Month</h3>
                        <h3>By Year</h3>
                    </div>

                    <div class="tab-content">

                        <div class="active page">
                            <?php
                            // get total income/revenue
                            $totalRevenueAmount = getTotalIncome($connect);

                            $totalRevenue = 0;
                            if ($totalRevenueAmount !== false) {
                                $totalRevenue = $totalRevenueAmount;
                            }
                            $TotalIncomeAmount = number_format($totalRevenue, 2);
                            // get total expenses
                            $expenseSummary = getTotalExpenseSummary($connect);
                            $TotalExpenseAmount = number_format($expenseSummary, 2);
                            if (!$expenseSummary) {
                                $expenseSummary = 0;
                            }
                            // Calculate net profit
                            $TotalnetProfit = $totalRevenue - $expenseSummary;
                            ?>
                            <h4>Income vs Expense Summary</h4>
                            <p>Total Income: <?= $code; ?> <?= $TotalIncomeAmount; ?></p>
                            <p>Total Expense: <?= $code; ?> <?= $TotalExpenseAmount; ?></p>
                            <p>Net Profit: <span class="textColor"><?= $code; ?> <?= number_format($TotalnetProfit, 2); ?></span></p>
                            <canvas id="netProfitChart" class="canvasChart"></canvas>







                            <div class="content mt-5">
                                <?php
                                $IncomeOfAllPlans = getTotalIncomeOfAllPlans($connect);
                                $allTotalPlan = 0;

                                if ($IncomeOfAllPlans) {
                                    foreach ($IncomeOfAllPlans as $IncomePlan) {
                                        $allTotalPlan += $IncomePlan['totalIncome'];
                                    }
                                }
                                $planNetWorth = $allTotalPlan - $expenseSummary;
                                ?>
                                <h4>Plans Revenue vs Expense</h4>
                                <p>Total Plans Income: <?= $code; ?> <?= number_format($allTotalPlan, 2); ?> </p>
                                <p>Total Expense: <?= $code; ?> <?= $TotalExpenseAmount; ?></p>
                                <p>Net Profit: <span class="textColor"><?= $code; ?> <?= number_format($planNetWorth, 2); ?></span></p>

                                <canvas id="PlansChart" class="canvasChart"></canvas>
                            </div>







                            <div class="content mt-5">
                                <?php
                                $IncomeOfAllProducts = getTotalIncomeOfAllProducts($connect);
                                $allTotalProducts = 0;

                                if ($IncomeOfAllProducts) {
                                    foreach ($IncomeOfAllProducts as $IncomeProduct) {
                                        $allTotalProducts += $IncomeProduct['totalIncome'];
                                    }
                                }
                                $productNetWorth = $allTotalProducts - $expenseSummary;
                                ?>
                                <h4>Products Revenue vs Expense</h4>
                                <p>Total Products Income: <?= $code; ?> <?= number_format($allTotalProducts, 2); ?> </p>
                                <p>Total Expense: <?= $code; ?> <?= $TotalExpenseAmount; ?></p>
                                <p>Net Profit: <span class="textColor"><?= $code; ?> <?= number_format($productNetWorth, 2); ?></span></p>
                                <canvas id="ProductsChart" class="canvasChart"></canvas>
                            </div>
                        </div>







                        <div class="page">
                            <h4>Net Profit By Day</h4>
                            <label for="yearInput">Select Day:</label>
                            <input type="date" value="<?= date('Y-m-d'); ?>" class="mb-5" name="" id="dateNetProfitInput">
                            <button id="getProfitByDayBtn" class="btn btn-primary">Check</button>

                            <p>Total Income This Day: <?= $code; ?> <span id="incomeDay">0.00</span></p>
                            <p>Total Expense This Day: <?= $code; ?> <span id="expenseDay">0.00</span></p>
                            <p>Net Profit This Day: <span class="textColor"><?= $code; ?> <span id="netDay">0.00</span></span></p>

                            <canvas id="dayNetChart" class="canvasChart"></canvas>
                        </div>




                        <div class="page">
                            <h4>Summary By Month</h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="yearInput">Select Year:</label>
                                    <select id="yearInput" class="mb-3" name="yearInput"></select>
                                </div>
                                <div class="col-md-6">
                                    <label for="monthInput">Select Month:</label>
                                    <select id="monthInput" class="mb-3" name="monthInput"></select>
                                </div>
                                <p id="monthneterrormsg"></p>
                                <div class="col-md-6">
                                    <button id="getProfitByMonthBtn" class="btn btn-primary">Check</button>
                                </div>
                            </div>



                            <p>Total Income This Month: <?= $code; ?> <span id="incomeMonth">0.00</span></p>
                            <p>Total Expense This Month: <?= $code; ?> <span id="expenseMonth">0.00</span></p>
                            <p>Net Profit This Month: <span class="textColor"><?= $code; ?> <span id="netMonth">0.00</span></span></p>

                            <canvas id="monthNetChart" class="canvasChart"></canvas>

                        </div>


                        <div class="page">
                            <h4>Income Summary</h4>
                            <label for="yearInput">Select a Year:</label>
                            <select id="byYear" class="mb-4" name="byYear"></select>
                            <p id="yearneterrormsg"></p>
                            <button id="getProfitByYearBtn" class="btn btn-primary">Check</button>

                            <p>Total Income This Year: <?= $code; ?> <span id="incomeYear">0.00</span></p>
                            <p>Total Expense This Year: <?= $code; ?> <span id="expenseYear">0.00</span></p>
                            <p>Net Profit This Year: <span class="textColor"><?= $code; ?> <span id="netYear">0.00</span></span></p>
                            <canvas id="yearNetChart" class="canvasChart"></canvas>
                        </div>


                    </div>
                </div>
            </div>

            <div class="content">

            </div>

            <?php require_once "footer.php"; ?>



            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    updateDayChart(0.00, "");
                    updateMonthChart(0.00, "");
                    updateYearChart(0.00, "");
                    populateDropdown("monthInput", monthOptions);
                    populateDropdown("yearInput", yearOptions);
                    populateDropdown("byYear", yearOptions);
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





                const names = ['Income', 'Expense'];
                const TotalIncomeAmount = <?= json_encode($totalRevenueAmount); ?>;
                const expenseSummary = <?= json_encode($expenseSummary); ?>;
                const CurrencyCode = <?= json_encode($code); ?>;
                const incomeLabel = numberFormatJS(TotalIncomeAmount);
                const expenseLabel = numberFormatJS(expenseSummary);


                var ctx = document.getElementById('netProfitChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [incomeLabel, expenseLabel],
                        datasets: [{
                            label: 'Total Amount',
                            data: [TotalIncomeAmount, expenseSummary], // Swap positions
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(75, 192, 192)'
                            ],
                            borderWidth: 5,
                            color: '#AAAAAA'
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount ( ' + CurrencyCode + ' )',
                                    color: '#AAAAAA'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Income Vs Expense',
                                    color: '#AAAAAA'
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

                const allTotalPlan = <?= json_encode($allTotalPlan); ?>;
                const planLabel = numberFormatJS(allTotalPlan);


                var ctx = document.getElementById('PlansChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [planLabel, expenseLabel],
                        datasets: [{
                            label: 'Total Amount',
                            data: [allTotalPlan, expenseSummary],
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(75, 192, 192)'
                            ],
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount (' + CurrencyCode + ')',
                                    color: '#AAAAAA'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Plans vs Expenses',
                                    color: '#AAAAAA'
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







                let productsLabel = ['Products', 'Expenses'];
                const allTotalProducts = <?= json_encode($allTotalProducts); ?>;
                const productLabel = numberFormatJS(allTotalProducts);

                var ctx = document.getElementById('ProductsChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [productLabel, expenseLabel],
                        datasets: [{
                            label: 'Total Amount',
                            data: [allTotalProducts, expenseSummary],
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(75, 192, 192)'
                            ],
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount (' + CurrencyCode + ')',
                                    color: '#AAAAAA'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Products vs Expenses',
                                    color: '#AAAAAA'
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



                var getProfitByDayBtn = document.querySelector("#getProfitByDayBtn");

                getProfitByDayBtn.addEventListener("click", function() {
                    var dateSelectedNetProfit = document.getElementById("dateNetProfitInput").value;

                    if (!dateSelectedNetProfit) {
                        displayMessage("productErrorMsg", "Choose a Customer First", true);
                        return;
                    }
                    var formData = new FormData();
                    formData.append("dateSelectedNetProfit", dateSelectedNetProfit);

                    fetch("../controllers/summary_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let totalIncome = data.income;
                                let totalExpense = data.expense;
                                let netProfit = data.profit;
                                document.getElementById('incomeDay').innerText = totalIncome;
                                document.getElementById('expenseDay').innerText = totalExpense;
                                document.getElementById('netDay').innerText = netProfit;
                                let chartIncome = parseFloat(totalIncome);
                                let chartExpense = parseFloat(totalExpense);
                                // Update the chart with the new data
                                updateDayChart(chartIncome, chartExpense);
                            } else {
                                console.error("Error: " + data.message);
                                updateDayChart(0.00, "");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var dayNetChart = null;


                function updateDayChart(chartIncome, chartExpense) {
                    const CurrencyCode = <?= json_encode($code); ?>;
                    var ctx = document.getElementById('dayNetChart').getContext('2d');



                    if (dayNetChart) {
                        // If a Chart instance exists, destroy it
                        dayNetChart.destroy();
                    }


                    dayNetChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income', 'Expense'],
                            datasets: [{
                                label: 'Total Amount',
                                data: [chartIncome, chartExpense],
                                backgroundColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(75, 192, 192)'
                                ],
                                borderWidth: 5
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Amount (' + CurrencyCode + ')',
                                        color: '#AAAAAA'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Net Profit By Day',
                                        color: '#AAAAAA'
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
                }






















                var getProfitByMonthBtn = document.querySelector("#getProfitByMonthBtn");

                getProfitByMonthBtn.addEventListener("click", function() {
                    var yearInput = document.getElementById("yearInput").value;
                    var monthInput = document.getElementById("monthInput").value;


                    if (!yearInput || !monthInput) {
                        displayMessage("monthneterrormsg", "Choose a month and year First", true);
                        return;
                    }
                    var formData = new FormData();
                    formData.append("monthInput", monthInput);
                    formData.append("yearInput", yearInput);

                    fetch("../controllers/summary_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let totalIncome = data.income;
                                let totalExpense = data.expense;
                                let netProfit = data.profit;
                                document.getElementById('incomeMonth').innerText = totalIncome;
                                document.getElementById('expenseMonth').innerText = totalExpense;
                                document.getElementById('netMonth').innerText = netProfit;
                                let chartIncome = parseFloat(totalIncome);
                                let chartExpense = parseFloat(totalExpense);
                                // Update the chart with the new data
                                updateMonthChart(chartIncome, chartExpense);
                            } else {
                                console.error("Error: " + data.message);
                                updateMonthChart(0.00, "");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var monthNetChart = null;


                function updateMonthChart(chartIncome, chartExpense) {
                    const CurrencyCode = <?= json_encode($code); ?>;
                    var ctx = document.getElementById('monthNetChart').getContext('2d');

                    if (monthNetChart) {
                        // If a Chart instance exists, destroy it
                        monthNetChart.destroy();
                    }


                    monthNetChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income', 'Expense'],
                            datasets: [{
                                label: 'Total Amount',
                                data: [chartIncome, chartExpense],
                                backgroundColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(75, 192, 192)'
                                ],
                                borderWidth: 5
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Amount (' + CurrencyCode + ')',
                                        color: '#AAAAAA'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Net Profit By Month',
                                        color: '#AAAAAA'
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
                }







                var getProfitByYearBtn = document.querySelector("#getProfitByYearBtn");

                getProfitByYearBtn.addEventListener("click", function() {
                    var yearNetSelected = document.getElementById("byYear").value;


                    if (!yearNetSelected) {
                        displayMessage("yearneterrormsg", "Choose year First", true);
                        return;
                    }
                    var formData = new FormData();
                    formData.append("yearNetSelected", yearNetSelected);

                    fetch("../controllers/summary_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let totalIncome = data.income;
                                let totalExpense = data.expense;
                                let netProfit = data.profit;
                                document.getElementById('incomeYear').innerText = totalIncome;
                                document.getElementById('expenseYear').innerText = totalExpense;
                                document.getElementById('netYear').innerText = netProfit;
                                let chartIncome = parseFloat(totalIncome);
                                let chartExpense = parseFloat(totalExpense);
                                // Update the chart with the new data
                                updateYearChart(chartIncome, chartExpense);
                            } else {
                                console.error("Error: " + data.message);
                                updateYearChart(0.00, "");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var yearNetChart = null;


                function updateYearChart(chartIncome, chartExpense) {
                    const CurrencyCode = <?= json_encode($code); ?>;
                    var ctx = document.getElementById('yearNetChart').getContext('2d');

                    if (yearNetChart) {
                        // If a Chart instance exists, destroy it
                        yearNetChart.destroy();
                    }


                    yearNetChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income', 'Expense'],
                            datasets: [{
                                label: 'Total Amount',
                                data: [chartIncome, chartExpense],
                                backgroundColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(75, 192, 192)'
                                ],
                                borderWidth: 5
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Amount (' + CurrencyCode + ')',
                                        color: '#AAAAAA'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Net Profit By Year',
                                        color: '#AAAAAA'
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
                }
            </script>