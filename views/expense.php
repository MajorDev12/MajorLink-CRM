<?php require_once "../controllers/session_Config.php"; ?>
<?php require_once "header.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addProduct_mod.php';
require_once  '../modals/setup_mod.php';

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
                            <select class="form-select form-select-md mb-3" id="ProductSelect" aria-label="Default select example">
                                <option value="" disabled selected>Choose...</option>
                                <?php foreach ($products as $product) : ?>
                                    <option value="<?= $product["ProductID"]; ?>" data-price="<?= $product["Price"]; ?>"><?= $product["ProductName"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p id="productErrorMsg"></p>
                            <button id="getProduct" class="btn btn-primary">Check</button>

                            <p id="totalIncomeByProduct" class="text">Total Income This Month: <span class="number"><?= $symbol; ?> <span id="totalProduct">0.00</span></span></p>

                            <canvas id="expensecategoryChart" class="canvasChart"></canvas>

                            <div class="content">
                                <h4>All Expense Summary</h4>
                                <p>Total Expense for Salaries: $ 0.00</p>

                                <canvas id="allexpensecategoryChart" class="canvasChart"></canvas>

                            </div>
                        </div>




                        <script>
                            var getCustomerBtn = document.querySelector("#getCustomerBtn");

                            getCustomerBtn.addEventListener("click", function() {
                                var clientSelected = document.getElementById("user_select").value;

                                if (!clientSelected) {
                                    displayMessage("customererrorMsg", "Choose a Customer First", true);
                                    return;
                                }
                                var formData = new FormData();
                                formData.append("clientSelected", clientSelected);

                                fetch("../controllers/Income_contr.php", {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            let totalInvoices = parseFloat(data.results.totalInvoices);
                                            let totalSales = parseFloat(data.results.totalSales);
                                            let customerName = data.results.clientName;
                                            let total = totalInvoices + totalSales;

                                            if (totalInvoices === null) {
                                                totalInvoices = "0.00";
                                            }
                                            if (totalSales === null) {
                                                totalSales = "0.00";
                                            }
                                            if (total === null) {
                                                total = "0.00";
                                            }
                                            if (customerName === null) {
                                                customerName = "No Data";
                                            }
                                            document.getElementById('totalCustomerIncome').innerText = total;

                                            // Update the chart with the new data
                                            updateCustomerChart(totalInvoices, total, customerName);
                                        } else {
                                            console.error("Error: " + data.message);
                                            updateCustomerChart(0, 0, "");
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                            });


                            var customerChart = null;

                            function updateCustomerChart(totalInvoices, total, customerName) {
                                var ctx = document.getElementById('customerchart').getContext('2d');

                                if (customerChart) {
                                    // If a Chart instance exists, destroy it
                                    customerChart.destroy();
                                }


                                customerChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Income Summary For ' + customerName],
                                        datasets: [{
                                            label: 'Total Income',
                                            data: [total],
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
                        </script>






                        <div class="page">
                            <h4>Expense Summary By Day</h4>
                            <label for="form-data">Choose Day</label>
                            <input type="date" name="" id="">
                            <p>Total Income This Day: $ 0.00</p>
                            <canvas id="expenseday" class="canvasChart"></canvas>



                        </div>









                        <div class="page">
                            <h4>Expense Summary By Month</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="expensemonth">Choose Month</label>
                                    <select name="expensemonth" id="expensemonth"></select>
                                </div>
                                <div class="col-md-6">
                                    <label for="expenseyear">Choose Year</label>
                                    <select name="expenseyear" id="expenseyear"></select>
                                </div>
                            </div>
                            <button class="btn btn-primary mb-5">Check</button>
                            <p>Total Expense This Month: $ 0.00</p>
                            <canvas id="expensemonthChart" class="canvasChart"></canvas>
                        </div>





                        <div class="page">
                            <h4>Income Summary By Year</h4>
                            <label for="expenseyear1">Choose Year</label>
                            <select name="expenseyear1" id="expenseyear1"></select>
                            <button class="btn btn-primary">Check</button>
                            <p class=" mt-5">Total Income This Day: $ 0.00</p>
                            <canvas id="expenseyear1Chart" class="canvasChart"></canvas>


                        </div>








                    </div>
                </div>
            </div>



            <?php require_once "footer.php"; ?>



            <script>
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



                // JavaScript function to dynamically populate a dropdown
                function populateDropdown(selectElement, options) {
                    var dropdown = document.getElementById(selectElement);
                    options.forEach(function(option) {
                        var optionElement = document.createElement("option");
                        optionElement.value = option.value;
                        optionElement.text = option.text;
                        dropdown.appendChild(optionElement);
                    });
                }



                // Populate the expense dropdowns
                var expenseOptions = [{
                        value: 1,
                        text: "Salaries"
                    },
                    {
                        value: 2,
                        text: "Rent"
                    },
                    {
                        value: 3,
                        text: "Utilities"
                    }
                ];

                populateDropdown("expensecategory", expenseOptions);


                // Populate the month dropdowns
                var monthOptions = [{
                        value: 1,
                        text: "January"
                    },
                    {
                        value: 2,
                        text: "February"
                    },
                    {
                        value: 3,
                        text: "March"
                    },
                    {
                        value: 4,
                        text: "April"
                    },
                    {
                        value: 5,
                        text: "May"
                    },
                    {
                        value: 6,
                        text: "June"
                    },
                    {
                        value: 7,
                        text: "July"
                    },
                    {
                        value: 8,
                        text: "August"
                    },
                    {
                        value: 9,
                        text: "September"
                    },
                    {
                        value: 10,
                        text: "October"
                    },
                    {
                        value: 11,
                        text: "November"
                    },
                    {
                        value: 12,
                        text: "December"
                    }
                ];
                populateDropdown("expensemonth", monthOptions);


                // Populate the year dropdown
                var yearOptions = [];
                for (var year = 2010; year <= 2023; year++) {
                    yearOptions.push({
                        value: year,
                        text: year.toString()
                    });
                }
                populateDropdown("expenseyear", yearOptions);
                populateDropdown("expenseyear1", yearOptions);







                var ctx = document.getElementById('allexpensecategoryChart');

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Salary', 'Rent', 'Utilities'],
                        datasets: [{
                            label: '# of Votes',
                            data: [12, 19, 3, 5, 2, 3],
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });






                // chart.js
                var ctx = document.getElementById('expensecategoryChart');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Salaries'],
                        datasets: [{
                            label: '# of Votes',
                            data: [9600],
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });


                var ctx = document.getElementById('expenseday');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['12-july-2023'],
                        datasets: [{
                            label: '# of Votes',
                            data: [1200],
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });




                var ctx = document.getElementById('expensemonthChart');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['January'],
                        datasets: [{
                            label: '# of Votes',
                            data: [132444],
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });



                var ctx = document.getElementById('expenseyear1Chart');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['2010'],
                        datasets: [{
                            label: '# of Votes',
                            data: [12, 19, 3, 5, 2, 3],
                            borderWidth: 5
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>