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
        grid-template-columns: repeat(4, 1fr);
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
<div class="content">
    <div class="container">
        <div class="tabs">
            <h3 class="active">All</h3>
            <h3>By Day</h3>
            <h3>By Month</h3>
            <h3>By Year</h3>
            <h3>By Geoghraphy</h3>
            <h3>By Product</h3>
            <h3>By Plan</h3>
            <h3>By Customer</h3>
        </div>

        <div class="tab-content">



            <div class="active page">
                <h4>Income Summary</h4>
                <p>Total Income: $ 0.00</p>
                <canvas id="myChartsum" class="canvasChart"></canvas>

                <div class="content">
                    <h4>Best Geography</h4>
                    <p>Total Income: $ 75,000.00</p>
                    <canvas id="myChartgeo" class="canvasChart"></canvas>
                </div>

                <div class="content">
                    <h4>Best Product</h4>
                    <p>Total Income: $ 45,000.00</p>
                    <canvas id="myChartproduct" class="canvasChart"></canvas>
                </div>

                <div class="content">
                    <h4>Best Plan</h4>
                    <p>Total Income: $ 40,000.00</p>
                    <canvas id="myChartplan" class="canvasChart"></canvas>
                </div>

                <div class="content">
                    <h4>Best Month this Year</h4>
                    <p>Total Income: $ 15,000.00</p>
                    <canvas id="myChartmonth" class="canvasChart"></canvas>
                </div>

                <div class="content">
                    <h4>Best Year</h4>
                    <p>Total Income: $ 155,000.00</p>
                    <canvas id="myChartyear" class="canvasChart"></canvas>
                </div>
            </div>






            <div class="page">
                <h4>Income Summary</h4>
                <label for="yearInput">Select Day:</label>
                <input type="date" class="mb-5" name="" id="">
                <button class="btn btn-primary">Check</button>
                <p>Total Income This Day: $ 2,000.00</p>
                <canvas id="myChart1" class="canvasChart"></canvas>
            </div>







            <div class="page">
                <h4>Summary By Month</h4>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="yearInput1">Select Year:</label>
                        <select id="yearInput1" class="mb-3" name="yearInput"></select>
                    </div>
                    <div class="col-md-6">
                        <label for="monthInput1">Select Month:</label>
                        <select id="monthInput1" class="mb-2" name="monthInput"></select>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary">Check</button>
                    </div>
                </div>

                <p>Total Income This Month: $ 0.00</p>
                <canvas id="revenuemonth" class="canvasChart"></canvas>
                <div class="content mt-5">
                    <h4>Last Three Months</h4>
                    <p>Total Income This Three Months: $ 0.00</p>
                    <canvas id="threemonth" class="canvasChart"></canvas>
                </div>
                <div class="content mt-5">
                    <h4>Last Six Months</h4>
                    <p>Total Income This Three Months: $ 0.00</p>
                    <canvas id="sixmonth" class="canvasChart"></canvas>
                </div>
            </div>



            <div class="page">
                <h4>Income Summary</h4>
                <label for="yearInput2">Select a Year:</label>
                <select id="yearInput2" class="mb-4" name="yearInput"></select>
                <button class="btn btn-primary">Check</button>
                <p>Total Income This Year: $ 0.00</p>
                <canvas id="revenueyear" class="canvasChart"></canvas>

                <div class="content">
                    <h4>Best Year</h4>
                    <p>Total Income This Year: $ 0.00</p>
                    <canvas id="bestyear" class="canvasChart"></canvas>

                </div>

                <div class="content">
                    <h4>Best vs Worst</h4>
                    <p>Total Income This Year: $ 0.00</p>
                    <canvas id="worstyear" class="canvasChart"></canvas>

                </div>
            </div>





            <div class="page">
                <h4>Income Summary By Geoghraphy</h4>
                <label for="areaInput">Area:</label>
                <select id="areaInput" class="mb-4" name="areaInput"></select>
                <button class="btn btn-primary">Check</button>
                <p>Total Income This Geoghraphy: $ 0.00</p>
                <canvas id="revenuearea" class="canvasChart"></canvas>

                <div class="content">
                    <h4>Income Summary By Sub Area</h4>
                    <label for="areaInput1">Sub Area:</label>
                    <select id="areaInput1" class="mb-4" name="areaInput"></select>
                    <button class="btn btn-primary">Check</button>
                    <p>Total Income This Geoghraphy: $ 0.00</p>
                    <canvas id="subarea" class="canvasChart"></canvas>
                </div>

                <div class="content">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Highest and Lowest</h4>
                            <label for="areaInput2">Area:</label>
                            <select id="areaInput2" class="mb-4" name="areaInput"></select>
                            <button class="btn btn-primary">Check</button>
                            <p>Total Income This Geoghraphy: $ 0.00</p>
                            <canvas id="HandLarea" class="canvasChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h4>Highest and Lowest</h4>
                            <label for="areaInput3">Sub Area:</label>
                            <select id="areaInput3" class="mb-4" name="areaInput"></select>
                            <button class="btn btn-primary">Check</button>
                            <p>Total Income This Geoghraphy: $ 0.00</p>
                            <canvas id="HandLsubarea" class="canvasChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <h4>Income Summary for All Areas</h4>
                    <p>Total Income This Geoghraphy: $ 0.00</p>
                    <canvas id="allarea" class="canvasChart"></canvas>
                </div>

                <div class="content">
                    <h4>Income Summary for All Sub Areas</h4>
                    <label for="areaInput4">Area:</label>
                    <select id="areaInput4" class="mb-4" name="areaInput"></select>
                    <button class="btn btn-primary">Check</button>
                    <p>Total Income This Geoghraphy: $ 0.00</p>
                    <canvas id="allsubarea" class="canvasChart"></canvas>
                </div>
            </div>





            <div class="page">
                <h4>Income Summary By Product</h4>
                <label for="productInput">Product:</label>
                <select id="productInput1" class="mb-4" name="productInput"></select>
                <p>Total Income This Product: $ 0.00</p>
                <canvas id="productchart" class="canvasChart"></canvas>

                <div class="content mt-5">
                    <h4>Income Summary for all Products</h4>
                    <p>Total Income This Product: $ 0.00</p>
                    <canvas id="productallchart" class="canvasChart"></canvas>
                </div>

                <div class="content mt-5">
                    <h4>Income Summary for Highest vs Lowest</h4>
                    <p>Total Income This Product: $ 0.00</p>
                    <canvas id="HvsLproductchart" class="canvasChart"></canvas>
                </div>
            </div>





            <div class="page">
                <h4>Income Summary By Plan</h4>
                <label for="PlanInput">Plan:</label>
                <select id="PlanInput1" class="mb-4" name="PlanInput"></select>
                <p>Total Income This Plan: $ 0.00</p>
                <canvas id="planchart" class="canvasChart"></canvas>

                <div class="content mt-5">
                    <h4>Income Summary for all Plans</h4>
                    <p>Total Income This Plan: $ 0.00</p>
                    <canvas id="allplanchart" class="canvasChart"></canvas>
                </div>

                <div class="content mt-5">
                    <h4>Income Summary for Highest vs Lowest</h4>
                    <p>Total Income This Plan: $ 0.00</p>
                    <canvas id="HvsLplanchart" class="canvasChart"></canvas>
                </div>
            </div>


            <div class="page">
                <h4>Income Summary By Customer</h4>
                <label for="PlanInput">Customer:</label>
                <input type="search" name="" id="">
                <button class="btn btn-primary">Search</button>
                <p class=" mt-5">Total Income This Plan: $ 0.00</p>
                <canvas id="customerchart" class="canvasChart"></canvas>

                <div class="content mt-5">
                    <h4>Income Summary for 5 Highest</h4>
                    <p>Total Income This Customer: $ 0.00</p>
                    <canvas id="toptencustomer" class="canvasChart"></canvas>
                </div>
            </div>

        </div>
    </div>
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

    // Populate the year dropdown
    var yearOptions = [];
    for (var year = 2010; year <= 2023; year++) {
        yearOptions.push({
            value: year,
            text: year.toString()
        });
    }
    populateDropdown("yearInput1", yearOptions);
    populateDropdown("yearInput2", yearOptions);

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
    populateDropdown("monthInput1", monthOptions);

    // Populate the plan dropdowns
    var planOptions = [{
            value: 1,
            text: "5mbps"
        },
        {
            value: 2,
            text: "10mbps"
        },
        {
            value: 3,
            text: "15mbps"
        },
        {
            value: 4,
            text: "25mbps"
        },
        {
            value: 5,
            text: "40mbps"
        }
    ];
    populateDropdown("PlanInput1", planOptions);

    // Populate the product dropdowns
    var productOptions = [{
            value: 1,
            text: "Router"
        },
        {
            value: 2,
            text: "Dish"
        },
        {
            value: 3,
            text: "Cable"
        }
    ];
    populateDropdown("productInput1", productOptions);



    // Populate the area dropdowns
    var areaOptions = [{
            value: 1,
            text: "Pipeline"
        },
        {
            value: 2,
            text: "Free Area"
        },
        {
            value: 3,
            text: "White House"
        }, {
            value: 2,
            text: "Lanet"
        }, {
            value: 2,
            text: "Mzee Wanyama"
        }, {
            value: 2,
            text: "Sita"
        }, {
            value: 2,
            text: "Naka"
        }
    ];
    populateDropdown("areaInput", areaOptions);
    populateDropdown("areaInput1", areaOptions);
    populateDropdown("areaInput2", areaOptions);
    populateDropdown("areaInput3", areaOptions);
    populateDropdown("areaInput4", areaOptions);
















    // chart.js
    // a chart for income summary total
    var ctx = document.getElementById('myChartsum').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Income'],
            datasets: [{
                label: 'Refresh',
                data: [510000],
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
                        text: ''
                    }
                }
            }
        }
    });

    var ctx = document.getElementById('myChartgeo').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mzee Wanyama'],
            datasets: [{
                label: 'Refresh',
                data: [75000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('myChartproduct').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Router'],
            datasets: [{
                label: 'Refresh',
                data: [45000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('myChartplan').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['10mbps'],
            datasets: [{
                label: 'Refresh',
                data: [45000],
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
                        text: ''
                    }
                }
            }
        }
    });




    var ctx = document.getElementById('myChartmonth').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['July'],
            datasets: [{
                label: 'Refresh',
                data: [45000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('myChartyear').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['July'],
            datasets: [{
                label: 'Refresh',
                data: [155000],
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
                        text: ''
                    }
                }
            }
        }
    });




    var ctx = document.getElementById('revenuemonth').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['July'],
            datasets: [{
                label: 'Refresh',
                data: [155000],
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
                        text: ''
                    }
                }
            }
        }
    });


    var ctx = document.getElementById('threemonth').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['July', 'August', 'September'],
            datasets: [{
                label: 'Refresh',
                data: [1000, 1200, 1500],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('sixmonth').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['June', 'July', 'August', 'September', 'October', 'November'],
            datasets: [{
                label: 'Refresh',
                data: [1000, 1200, 1500, 1300, 900, 2000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('revenueyear').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['2010'],
            datasets: [{
                label: 'Refresh',
                data: [120000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('bestyear').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['2023'],
            datasets: [{
                label: 'Refresh',
                data: [100000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('worstyear').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['2010', '2020'],
            datasets: [{
                label: 'Refresh',
                data: [120000, 23000],
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
                        text: ''
                    }
                }
            }
        }
    });


    var ctx = document.getElementById('revenuearea').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pipeline'],
            datasets: [{
                label: 'Refresh',
                data: [120000],
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
                        text: ''
                    }
                }
            }
        }
    });




    var ctx = document.getElementById('subarea').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Sawa'],
            datasets: [{
                label: 'Refresh',
                data: [120000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('HandLarea').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Sawa', 'mchangaa'],
            datasets: [{
                label: 'Refresh',
                data: [12000, 1000],
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
                        text: ''
                    }
                }
            }
        }
    });


    var ctx = document.getElementById('HandLsubarea').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Sawa', 'mchangaa'],
            datasets: [{
                label: 'Refresh',
                data: [1500, 1000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('allarea').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels: ['Pipeline', 'Free Area', 'Lanet', 'Mzee Wanyama', 'Sita', 'Naka'],
            datasets: [{
                label: 'Refresh',
                data: [1500, 1000, 2000, 3500, 2900, 3000],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(201, 203, 207)',
                    'rgb(54, 162, 235)'
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
                        text: 'Amount (USD)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('allsubarea').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels: ['Pipeline', 'Free Area', 'Lanet', 'Mzee Wanyama', 'Sita', 'Naka'],
            datasets: [{
                label: 'Refresh',
                data: [1500, 1000, 2000, 3500, 2900, 3000],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(201, 203, 207)',
                    'rgb(54, 162, 235)'
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
                        text: 'Amount (USD)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: ''
                    }
                }
            }
        }
    });


    var ctx = document.getElementById('productchart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Router'],
            datasets: [{
                label: 'Refresh',
                data: [15000],
                backgroundColor: ['rgba(75, 192, 192, 0.5)'],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('productallchart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Router', 'Cables', 'Service fee', 'Dish'],
            datasets: [{
                label: 'Refresh',
                data: [15000, 10000, 30000, 2300],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('HvsLproductchart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Router', 'Dish'],
            datasets: [{
                label: 'Refresh',
                data: [15000, 2300],
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
                        text: ''
                    }
                }
            }
        }
    });

    var ctx = document.getElementById('planchart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['10mbps'],
            datasets: [{
                label: 'Refresh',
                data: [150000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('allplanchart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['5mbps', '10mbps', '15mbps', '25mbps', '40mbps'],
            datasets: [{
                label: 'Refresh',
                data: [25000, 10000, 30000, 16000, 1000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('HvsLplanchart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['15mbps', '40mbps'],
            datasets: [{
                label: 'Refresh',
                data: [30000, 1000],
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
                        text: ''
                    }
                }
            }
        }
    });



    var ctx = document.getElementById('customerchart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Major Developer'],
            datasets: [{
                label: 'Refresh',
                data: [30000],
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
                        text: ''
                    }
                }
            }
        }
    });

    var ctx = document.getElementById('toptencustomer').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Major Developer', 'janet Wairimu', 'John Njogu', 'Traversy Media', 'Deved'],
            datasets: [{
                label: 'Refresh',
                data: [30000, 28000, 27000, 24000, 22000],
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
                        text: ''
                    }
                }
            }
        }
    });

















    const ctx1 = document.getElementById('myChart1');

    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Date'],
            datasets: [{
                label: '# of Votes',
                data: [2000],
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




    const ctx2 = document.getElementById('myChart2');

    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
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



    const ctx3 = document.getElementById('myChart3');

    new Chart(ctx3, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue'],
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




    const ctx4 = document.getElementById('myChart4');

    new Chart(ctx4, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
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



    const ctx5 = document.getElementById('myChart5');

    new Chart(ctx5, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
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



    const ctx6 = document.getElementById('myChart6');

    new Chart(ctx6, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
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


    const ctx7 = document.getElementById('myChart7');

    new Chart(ctx7, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
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