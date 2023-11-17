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
                <select name="expensecategory" id="expensecategory" class="mb-5"></select>
                <p>Total Expense for Salaries: $ 0.00</p>

                <canvas id="expensecategoryChart" class="canvasChart"></canvas>

                <div class="content">
                    <h4>All Expense Summary</h4>
                    <p>Total Expense for Salaries: $ 0.00</p>

                    <canvas id="allexpensecategoryChart" class="canvasChart"></canvas>

                </div>
            </div>











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