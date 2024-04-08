<?php require_once "../controllers/session_Config.php"; ?>
<?php require_once "header.php"; ?>
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
                        <a class="active" href="#">Home</a>
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
            <h3 class="active">Total</h3>
            <h3>By Day</h3>
            <h3>By Month</h3>
            <h3>By Year</h3>
        </div>

        <div class="tab-content">

            <div class="active page">
                <h4>Income vs Expense Summary</h4>
                <p>Total Income: $ 510,000.00</p>
                <p>Total Expense: $ 80,000.00</p>
                <p>Net Profit: Income - Expense = $430,000</p>
                <canvas id="myChart" class="canvasChart"></canvas>

                <div class="content mt-5">
                    <h4>Plans Revenue vs Expense</h4>
                    <p>Total Plans Income: $ 510,000.00</p>
                    <p>Total Expense: $ 80,000.00</p>
                    <p>Net Profit: Income - Expense = $430,000</p>
                    <canvas id="PlansChart" class="canvasChart"></canvas>
                </div>

                <div class="content mt-5">
                    <h4>Products Revenue vs Expense</h4>
                    <p>Total Products Income: $ 510,000.00</p>
                    <p>Total Expense: $ 80,000.00</p>
                    <p>Net Profit: Income - Expense = $430,000</p>
                    <canvas id="ProductsChart" class="canvasChart"></canvas>
                </div>
            </div>







            <div class="page">
                <h4>Income Summary</h4>
                <label for="yearInput">Select Day:</label>
                <input type="date" class="mb-5" name="" id="">
                <p>Total Income This Day: $ 0.00</p>
                <p>Total Expense This Day: $ 0.00</p>
                <p>Total Net Profit This Day: $ 0.00</p>
                <canvas id="myChart1" class="canvasChart"></canvas>
            </div>



            <div class="page">
                <h4>Summary By Month</h4>

                <div class="row">
                    <div class="col-md-6">
                        <label for="yearInput">Select Year:</label>
                        <select id="yearInput" class="mb-4" name="yearInput"></select>
                    </div>
                    <div class="col-md-6">
                        <label for="monthInput">Select Month:</label>
                        <select id="monthInput" class="mb-5" name="monthInput"></select>
                    </div>
                </div>

                <p>Total Income This Month: $ 0.00</p>
                <p>Total Expense This Month: $ 0.00</p>
                <p>Total Net Profit This Month: $ 0.00</p>
                <canvas id="myChart2" class="canvasChart"></canvas>

            </div>


            <div class="page">
                <h4>Income Summary</h4>
                <label for="yearInput">Select a Year:</label>
                <select id="byYear" class="mb-4" name="byYear"></select>
                <p>Total Income This Year: $ 0.00</p>
                <p>Total Expense This Year: $ 0.00</p>
                <p>Total Net Profit This Year: $ 0.00</p>
                <canvas id="myChart3" class="canvasChart"></canvas>
            </div>



        </div>
    </div>
</div>

<div class="content">

</div>
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


    // JavaScript code to dynamically populate the year dropdown
    let yearInput = document.getElementById("yearInput");
    let minYear = 2010; // Set the minimum year
    let maxYear = 2023; // Set the maximum year

    for (let year = minYear; year <= maxYear; year++) {
        let option = document.createElement("option");
        option.value = year;
        option.innerText = year;
        yearInput.appendChild(option);
    }


    // JavaScript code to dynamically populate the year dropdown
    let byYear = document.getElementById("byYear");
    let minbyYear = 2010; // Set the minimum year
    let maxbyYear = 2023; // Set the maximum year

    for (let year = minbyYear; year <= maxbyYear; year++) {
        let option = document.createElement("option");
        option.value = year;
        option.innerText = year;
        byYear.appendChild(option);
    }

    // JavaScript code to dynamically populate the month dropdown
    let monthInput = document.getElementById("monthInput");
    let monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    for (let i = 0; i < 12; i++) {
        let option = document.createElement("option");
        option.value = i + 1; // Month values are 1-based
        option.text = monthNames[i];
        monthInput.appendChild(option);
    }






    // chart.js
    var ctx = document.getElementById('PlansChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Plans', 'Expense'],
            datasets: [{
                label: 'Refresh',
                data: [510000, 80000],
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
                        text: 'Amount (USD)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Income vs Expense'
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('ProductsChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Plans', 'Expense'],
            datasets: [{
                label: 'Refresh',
                data: [510000, 80000],
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
                        text: 'Amount (USD)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Income vs Expense'
                    }
                }
            }
        }
    });






    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Income', 'Expense'],
            datasets: [{
                label: 'Refresh',
                data: [510000, 80000],
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
                        text: 'Amount (USD)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Income vs Expense'
                    }
                }
            }
        }
    });













    //by month
    var ctx1 = document.getElementById('myChart1').getContext('2d');
    var myChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Date 12'],
            datasets: [{
                label: 'Total Income',
                data: [100],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderWidth: 1
            }, {
                label: 'Total Expense',
                data: [1],
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
                        text: 'Amount (USD)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Daily Income vs. Expense'
                    }
                }
            }
        }
    });




    //by month
    var ctx2 = document.getElementById('myChart2').getContext('2d');
    var myChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['January'],
            datasets: [{
                label: 'Total Income',
                data: [12000],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderWidth: 1
            }, {
                label: 'Total Expense',
                data: [5000],
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
                        text: 'Amount (USD)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Monthly Income vs. Expense'
                    }
                }
            }
        }
    });



    var ctx3 = document.getElementById('myChart3').getContext('2d');
    var myChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['2010'],
            datasets: [{
                label: 'Total Income',
                data: [12000],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderWidth: 1
            }, {
                label: 'Total Expense',
                data: [5000],
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
                        text: 'Amount (USD)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Monthly Income vs. Expense'
                    }
                }
            }
        }
    });
</script>