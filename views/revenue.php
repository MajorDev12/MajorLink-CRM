<?php require_once "../controllers/session_Config.php"; ?>
<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>
<?php
require_once  '../database/pdo.php';
require_once  '../modals/addInvoice_mod.php';
require_once  '../modals/reports_mod.php';
require_once  '../modals/setup_mod.php';
// require_once  '../modals/addArea_mod.php';
require_once  '../modals/addProduct_mod.php';
require_once  '../modals/addSubarea_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/getClientsNames_mod.php';
$connect = connectToDatabase($host, $dbname, $username, $password);

$settings = get_Settings($connect);
$code = $settings[0]["CurrencyCode"];
$symbol = $settings[0]["CurrencySymbol"];

?>
<style>
    #user_select {
        background-color: var(--light-dark);
    }

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

    p {
        color: var(--light-dark);
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
                        <a class="active" href="#">Revenue</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- content-container -->
        <div class="main-content">



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

                            <?php
                            $totalIncome = getTotalIncome($connect);

                            $totalAmount = 0;
                            if ($totalIncome !== false) {
                                $totalAmount = $totalIncome;
                            }

                            $formattedTotalAmount = number_format($totalAmount, 2);
                            ?>
                            <div class="content">
                                <h4>Income Summary</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= $formattedTotalAmount; ?></span></p>
                                <canvas id="myChartsum" class="canvasChart"></canvas>

                            </div>




                            <div class="content">
                                <?php
                                $bestSellingArea = getBestSellingArea($connect);
                                $invoiceTotalIncome = 0;
                                $salesTotalIncome = 0;
                                $geoTotalIncome = 0;
                                if ($bestSellingArea) {
                                    $areaName = $bestSellingArea['AreaName'];
                                    $invoiceTotalIncome = $bestSellingArea['total_invoice_income'];
                                    $salesTotalIncome = $bestSellingArea['total_sales_income'];
                                    $geoTotalIncome = $bestSellingArea['total_invoice_income'] + $bestSellingArea['total_sales_income'];
                                } else {
                                    $areaName = "Unknown";
                                    $geoTotalIncome = 0;
                                }
                                $geoTotalIncome = intval($geoTotalIncome);
                                ?>
                                <h4>Best Geography</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($geoTotalIncome, 2); ?></span></p>
                                <canvas id="myChartgeo" class="canvasChart"></canvas>
                            </div>




                            <div class="content">
                                <?php
                                $bestSellingProduct =  getBestSellingProduct($connect);

                                if ($bestSellingProduct) {
                                    $productName = $bestSellingProduct['ProductName'];
                                    $productIncome = $bestSellingProduct['total_income'];
                                } else {
                                    $productName = "Unknown";
                                    $productIncome = 0;
                                }
                                ?>
                                <h4>Best Product</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($productIncome, 2); ?></span></p>
                                <canvas id="myChartproduct" class="canvasChart"></canvas>
                            </div>





                            <div class="content">
                                <?php
                                $bestSellingPlan =  getBestSellingPlan($connect);

                                if ($bestSellingPlan) {
                                    $planName = $bestSellingPlan['Name'];
                                    $planIncome = $bestSellingPlan['total_income'];
                                    $planVolume = $bestSellingPlan['Volume'];
                                } else {
                                    $planName = "Unknown";
                                    $planIncome = 0;
                                }
                                ?>
                                <h4>Best Plan</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($planIncome, 2); ?></span></p>
                                <canvas id="myChartplan" class="canvasChart"></canvas>
                            </div>





                            <div class="content">
                                <?php
                                $bestSellingMonthThisYear =  getBestMonthThisYear($connect);

                                if ($bestSellingMonthThisYear) {
                                    $monthName = $bestSellingMonthThisYear['month_name'];
                                    $monthNumber = $bestSellingMonthThisYear['month_number'];
                                    $monthIncome = $bestSellingMonthThisYear['total_income'];
                                } else {
                                    $monthName = "Unknown";
                                    $monthNumber = "Unknown";
                                    $monthIncome = 0;
                                }
                                ?>
                                <h4>Best Month this Year</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($monthIncome, 2); ?></span></p>
                                <canvas id="myChartmonth" class="canvasChart"></canvas>
                            </div>






                            <div class="content">
                                <?php
                                $bestSellingYear =  getBestYear($connect);

                                if ($bestSellingYear) {
                                    $yearName = $bestSellingYear['year'];
                                    $yearIncome = $bestSellingYear['total_income'];
                                } else {
                                    $yearName = "Unknown";
                                    $yearIncome = 0;
                                }
                                ?>
                                <h4>Best Year</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($yearIncome, 2); ?></span></p>
                                <canvas id="myChartyear" class="canvasChart"></canvas>
                            </div>
                        </div>






                        <div class="page">
                            <h4>Income Summary</h4>
                            <label for="yearInput">Select Day:</label>
                            <input type="date" class="form-control mb-5" name="" id="IncomeDateInput">
                            <p id="errorMsg"></p>
                            <button id="IncomeDateBtn" class="btn btn-primary mb-3">Check</button>
                            <p id="totalIncomeText" class="text">Total Income This Day: <span class="number"><?= $symbol; ?> <span id="totalDay">0.00</span></span></p>
                            <canvas id="dayChart" class="canvasChart"></canvas>
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
                                    <button id="IncomeMonthBtn" class="btn btn-primary">Check</button>
                                </div>
                            </div>

                            <p id="totalIncomeMonth" class="text">Total Income This Month: <span class="number"><?= $symbol; ?> <span id="totalMonth">0.00</span></span></p>

                            <canvas id="revenuemonth" class="canvasChart"></canvas>




                            <div class="content mt-5">
                                <?php
                                $bestSellingThreeMonths =  getTotalIncomeLastThreeMonths($connect);

                                if ($bestSellingThreeMonths) {
                                    // Iterate over the array to calculate the total income for the last three months
                                    $sumAllTotal = 0;
                                    foreach ($bestSellingThreeMonths as $yearMonth => $totalIncome) {
                                        // Extract year and month from the key
                                        list($year, $month) = explode('-', $yearMonth);

                                        // Output the total income for each month
                                        //echo "<p class='text'>Total Income for " . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . ": <span class='number'>$symbol " . number_format($totalIncome, 2) . "</span></p>";
                                        $months[] = date('F Y', mktime(0, 0, 0, $month, 1, $year));
                                        // Store the total income for each month
                                        $totalIncomeLastThreeMonths[] = $totalIncome;
                                        $sumAllTotal += $totalIncome;
                                    }
                                } else {
                                    $totalIncomeLastThreeMonths = array_fill(0, 3, 0); // Fill the array with 0s if no data is available
                                }
                                ?>

                                <h4>Last Three Months</h4>
                                <p class="text">Total Income This Three Months: <span class="number"><?= $symbol; ?> <?= number_format($sumAllTotal, 2); ?></span></p>
                                <canvas id="threemonth" class="canvasChart"></canvas>
                            </div>


                            <div class="content mt-5">
                                <?php
                                $bestSellingSixMonths =  getTotalIncomeLastSixMonths($connect);


                                if ($bestSellingSixMonths) {
                                    // Iterate over the array to calculate the total income for the last three months
                                    $sumAllsixTotal = 0;
                                    foreach ($bestSellingSixMonths as $yearMonth => $totalIncome) {
                                        // Extract year and month from the key
                                        list($year, $month) = explode('-', $yearMonth);

                                        // Output the total income for each month
                                        $sixmonths[] = date('F Y', mktime(0, 0, 0, $month, 1, $year));
                                        // Store the total income for each month
                                        $totalIncomeLastSixMonths[] = $totalIncome;
                                        $sumAllsixTotal += $totalIncome;
                                    }
                                } else {
                                    $totalIncomeLastSixMonths = array_fill(0, 3, 0, 0, 0, 0); // Fill the array with 0s if no data is available
                                }
                                ?>
                                <h4>Last Six Months</h4>
                                <p class="text">Total Income This Six Months: <span class="number"><?= $symbol; ?> <?= number_format($sumAllsixTotal, 2); ?></span></p>
                                <canvas id="sixmonth" class="canvasChart"></canvas>
                            </div>
                        </div>



                        <div class="page">
                            <h4>Income Summary</h4>


                            <label for="yearInput2">Select a Year:</label>
                            <select id="yearInput2" class="mb-4" name="yearInput"></select>
                            <button id="getYearIncomeBtn" class="btn btn-primary">Check</button>
                            <p class="text">Total Income This Year: <span class="number"><?= $symbol; ?><span id="totalYear">0.00</span></span></p>

                            <canvas id="revenueyear" class="canvasChart"></canvas>









                            <div class="content">
                                <?php
                                $bestSellingYearInRange =  getBestYearInRange($connect);

                                if ($bestSellingYearInRange) {
                                    $yearRange = $bestSellingYearInRange['year'];
                                    $yearRangeIncome = $bestSellingYearInRange['total_income'];
                                } else {
                                    $yearRange = "Unknown";
                                    $yearRangeIncome = 0;
                                }
                                ?>
                                <h4>Best Year</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($yearRangeIncome, 2); ?></span></p>
                                <canvas id="bestyear" class="canvasChart"></canvas>
                            </div>




                            <div class="content">
                                <?php
                                $worstSellingYearInRange =  getWorstYearInRange($connect);

                                if ($worstSellingYearInRange) {
                                    $worstyearRange = $worstSellingYearInRange['year'];
                                    $worstyearRangeIncome = $worstSellingYearInRange['total_income'];
                                } else {
                                    $worstyearRange = "Unknown";
                                    $worstyearRangeIncome = 0;
                                }
                                ?>
                                <h4>Best vs Worst</h4>
                                <canvas id="worstyear" class="canvasChart"></canvas>

                            </div>
                        </div>





                        <div class="page">
                            <h4>Income Summary By Geoghraphy</h4>


                            <label for="areaSelect">Area:</label>
                            <select name="areaSelect" id="areaSelect" class="form-select mb-4">
                                <option value="" selected disabled>Choose...</option>
                                <?php
                                $areas = getData($connect); // Replace with the actual function to get area data
                                foreach ($areas as $area) {
                                    echo '<option value="' . $area['AreaID'] . '">' . $area['AreaName'] . '</option>';
                                }
                                ?>
                            </select>
                            <button id="getAreaByIdBtn" class="btn btn-primary">Check</button>
                            <p class="text">Total Income : <span class="number"><?= $symbol; ?> <span id="totalAreaIncome">0.00</span></span></p>
                            <canvas id="revenuearea" class="canvasChart"></canvas>



                            <div class="content">
                                <h4>Income Summary By Sub Area</h4>
                                <label for="subareaSelect">Sub Area:</label>
                                <select name="subareaSelect" id="subareaSelect" class="form-select mb-4">
                                    <option value="" selected disabled>Choose...</option>
                                    <?php
                                    $subareas = getSubAreaData($connect); // Replace with the actual function to get area data
                                    foreach ($subareas as $subarea) {
                                        echo '<option value="' . $subarea['SubAreaID'] . '">' . $subarea['SubAreaName'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <button id="getSubAreaByIdBtn" class="btn btn-primary">Check</button>
                                <p class="text">Total Income : <span class="number"><?= $symbol; ?><span id="totalSubAreaIncome">0.00</span></span></p>
                                <canvas id="subarea" class="canvasChart"></canvas>
                            </div>



                            <!-- 
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Highest and Lowest</h4>
                                        <h4>Area</h4>
                                        <p>Lowest Income</p>
                                        <p>Highest Income</p>
                                        <canvas id="HandLarea" class="canvasChart"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>Highest and Lowest</h4>
                                        <h4>Sub Area</h4>
                                        <p>Total Income This Geoghraphy: $ 0.00</p>
                                        <canvas id="HandLsubarea" class="canvasChart"></canvas>
                                    </div>
                                </div>
                            </div> -->

                            <div class="content">
                                <?php
                                $IncomeOfAllAreas = getTotalIncomeOfAllAreas($connect);
                                $allAreaIncomes = [];
                                $allAreas = [];
                                $allTotalAreas = 0;

                                if ($IncomeOfAllAreas) {
                                    foreach ($IncomeOfAllAreas as $IncomeArea) {
                                        $allAreas[] = $IncomeArea['AreaName'];
                                        $allAreaIncomes[] = $IncomeArea['totalIncome'];
                                        $allTotalAreas += $IncomeArea['totalIncome'];
                                    }
                                }
                                ?>
                                <h4>Income Summary for All Areas</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($allTotalAreas, 2); ?></span></p>
                                <canvas id="allareaCan" class="canvasChart"></canvas>
                            </div>

                            <div class="content">
                                <?php
                                $IncomeOfAllSubAreas = getTotalIncomeOfAllSubAreas($connect);
                                $allSubAreaIncomes = [];
                                $allSubAreas = [];
                                $allTotalSubAreas = 0;

                                if ($IncomeOfAllSubAreas) {
                                    foreach ($IncomeOfAllSubAreas as $IncomeSubArea) {
                                        $allSubAreas[] = $IncomeSubArea['SubAreaName'];
                                        $allSubAreaIncomes[] = $IncomeSubArea['totalIncome'];
                                        $allTotalSubAreas += $IncomeSubArea['totalIncome'];
                                    }
                                }
                                ?>
                                <h4>Income Summary for All Sub Areas</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($allTotalSubAreas, 2); ?></span></p>
                                <canvas id="allsubarea" class="canvasChart"></canvas>
                            </div>
                        </div>





                        <div class="page">

                            <h4>Income Summary By Product</h4>
                            <label for="productInput">Product:</label>
                            <?php $products = getProductData($connect); ?>
                            <select class="form-select mb-3" id="ProductSelect" aria-label="Default select example">
                                <option value="" disabled selected>Choose...</option>
                                <?php foreach ($products as $product) : ?>
                                    <option value="<?= $product["ProductID"]; ?>" data-price="<?= $product["Price"]; ?>"><?= $product["ProductName"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p id="producterrorMsg"></p>
                            <button id="getProductByIdBtn" class="btn btn-primary">Check</button>
                            <p class="text">Total Income: <span class="number"><?= $symbol; ?> <span id="totalProductIncome">0.00</span></span></p>
                            <canvas id="productchart" class="canvasChart"></canvas>




                            <div class="content mt-5">
                                <?php
                                $IncomeOfAllProducts = getTotalIncomeOfAllProducts($connect);
                                $allProductsIncomes = [];
                                $allProducts = [];
                                $allTotalProducts = 0;

                                if ($IncomeOfAllProducts) {
                                    foreach ($IncomeOfAllProducts as $IncomeProduct) {
                                        $allProducts[] = $IncomeProduct['ProductName'];
                                        $allProductsIncomes[] = $IncomeProduct['totalIncome'];
                                        $allTotalProducts += $IncomeProduct['totalIncome'];
                                    }
                                }

                                ?>
                                <h4>Income Summary for All Products</h4>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($allTotalProducts, 2); ?></span></p>
                                <canvas id="productallchart" class="canvasChart"></canvas>
                            </div>

                            <!-- <div class="content mt-5">
                                <h4>Income Summary for Highest vs Lowest</h4>
                                <p>Total Income This Product: $ 0.00</p>
                                <canvas id="HvsLproductchart" class="canvasChart"></canvas>
                            </div> -->
                        </div>





                        <div class="page">
                            <h4>Income Summary By Plan</h4>
                            <label for="PlanInput">Plan:</label>
                            <select class="form-select form-select-md mb-3" id="planSelected" aria-label="Default select example">
                                <option value="" selected disabled>Choose...</option>
                                <?php
                                $plans = getPlanData($connect);

                                foreach ($plans as $plan) {
                                    $selected = ($plan['PlanID'] == $clientData['PlanID']) ? 'selected' : '';
                                    echo "<option value=\"{$plan['PlanID']}\" {$selected} data-amount=\"{$plan['Price']}\">{$plan['Volume']}</option>";
                                }
                                ?>
                            </select>
                            <p id="planerrorMsg"></p>
                            <button id="getPlanByIdBtn" class="btn btn-primary">Check</button>
                            <p class="text">Total Income: <span class="number"><?= $symbol; ?><span id="totalPlanIncome">0.00</span></span></p>
                            <canvas id="planchart" class="canvasChart"></canvas>






                            <div class="content mt-5">
                                <h4>Income Summary for all Plans</h4>
                                <?php
                                $IncomeOfAllPlans = getTotalIncomeOfAllPlans($connect);
                                $allPlanIncomes = [];
                                $allPlans = [];
                                $allPlansVolume = [];
                                $allTotalPlan = 0;

                                if ($IncomeOfAllPlans) {
                                    foreach ($IncomeOfAllPlans as $IncomePlan) {
                                        $allPlans[] = $IncomePlan['Name'];
                                        $allPlansVolume[] = $IncomePlan['Volume'];
                                        $allPlanIncomes[] = $IncomePlan['totalIncome'];
                                        $allTotalPlan += $IncomePlan['totalIncome'];
                                    }
                                }

                                ?>
                                <p class="text">Total Income: <span class="number"><?= $symbol; ?> <?= number_format($allTotalPlan, 2); ?></span></p>
                                <canvas id="allplanchart" class="canvasChart"></canvas>
                            </div>

                            <!-- <div class="content mt-5">
                                <h4>Income Summary for Highest vs Lowest</h4>
                                <p>Total Income This Plan: $ 0.00</p>
                                <canvas id="HvsLplanchart" class="canvasChart"></canvas>
                            </div> -->
                        </div>


                        <div class="page">
                            <h4>Income Summary By Customer</h4>


                            <div class="col">
                                <div class="row-md-6">
                                    <label for="PlanInput" class="form-label">Customer:</label>
                                    <?php $clientData = getClientsNames($connect); ?>
                                    <select id="user_select" name="user_id" class="form-select pb-4" style="width: 100%;">
                                        <option value="" selected hidden>--Search--</option>
                                    </select>
                                </div>
                                <p id="customererrorMsg"></p>
                                <div class="row-md-1">
                                    <div class="col-md-6 mt-4">
                                        <button id="getCustomerBtn" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                            <p class="text">Total Income: <span class="number"><?= $symbol; ?> <span id="totalCustomerIncome">0.00</span></span></p>
                            <canvas id="customerchart" class="canvasChart"></canvas>

                            <?php require_once "footer.php"; ?>




                            <!-- <div class="content mt-5">
                                <h4>Income Summary for 5 Highest</h4>
                                <p>Total Income This Customer: $ 0.00</p>
                                <canvas id="toptencustomer" class="canvasChart"></canvas>
                            </div> -->
                        </div>

                    </div>
                </div>
            </div>


            <?php require_once "footer.php"; ?>





            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    populateDropdown("yearInput1", yearOptions);
                    populateDropdown("yearInput2", yearOptions);
                    populateDropdown("monthInput1", monthOptions);
                    updateChart("0");
                    updatemonthChart(0);
                    updateyearChart(0);
                    updateAreaChart(0, "");
                    updateSubAreaChart(0, "");
                    updateProductChart(0, "");
                    updatePlanChart(0, "", "");
                    updateCustomerChart(0, 0, 0, "");
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




                var customerList = [];

                $(document).ready(function() {


                    <?php foreach ($clientData as $client) : ?>
                        customerList.push({
                            id: "<?php echo $client['ClientID']; ?>",
                            text: "<?php echo $client['FirstName'] . ' ' . $client['LastName']; ?>",
                            plan: "<?php echo $client['PlanID']; ?>"
                        });
                    <?php endforeach; ?>


                    $("#user_select").select2({
                        data: customerList
                    });




                });

















                document.getElementById('IncomeDateBtn').addEventListener('click', function() {
                    // Get the selected date
                    var selectedDate = document.getElementById('IncomeDateInput').value;

                    if (!selectedDate) {
                        displayMessage("errorMsg", "Choose Date First", true);
                        return;
                    }

                    // Fetch the total income for the selected date
                    fetchTotalIncome(selectedDate);
                });










                // chart.js
                // a chart for income summary total
                const formattedTotalAmount = <?= json_encode($totalAmount); ?>;
                const CurrencyCode = <?= json_encode($code); ?>;

                var ctx = document.getElementById('myChartsum').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Total Income'],
                        datasets: [{
                            label: 'Total Income',
                            data: [formattedTotalAmount],
                            backgroundColor: 'rgb(75, 192, 192)',
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
                        }
                    }
                });



                const area = <?= json_encode($areaName); ?>;
                const total = <?= json_encode($geoTotalIncome); ?>;

                var ctx = document.getElementById('myChartgeo').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [area],
                        datasets: [{
                            label: 'Refresh',
                            data: [total],
                            backgroundColor: 'rgb(75, 192, 192)',
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
                        }
                    }
                });


                const productName = <?= json_encode($productName); ?>;
                const productIncome = <?= json_encode($productIncome); ?>;
                var ctx = document.getElementById('myChartproduct').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [productName],
                        datasets: [{
                            label: 'Refresh',
                            data: [productIncome],
                            backgroundColor: 'rgb(75, 192, 192)',
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
                        }
                    }
                });



                const planName = <?= json_encode($planName); ?>;
                const planIncome = <?= json_encode($planIncome); ?>;
                const planVolume = <?= json_encode($planVolume); ?>;
                var ctx = document.getElementById('myChartplan').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [planName + ' ( ' + planVolume + ' )'],
                        datasets: [{
                            label: 'Refresh',
                            data: [planIncome],
                            backgroundColor: 'rgb(75, 192, 192)',
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
                        }
                    }
                });



                const monthName = <?= json_encode($monthName); ?>;
                const monthIncome = <?= json_encode($monthIncome); ?>;
                var ctx = document.getElementById('myChartmonth').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [monthName],
                        datasets: [{
                            label: 'Refresh',
                            data: [monthIncome],
                            backgroundColor: 'rgb(75, 192, 192)',
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
                        }
                    }
                });




                const yearName = <?= json_encode($yearName); ?>;
                const yearIncome = <?= json_encode($yearIncome); ?>;
                var ctx = document.getElementById('myChartyear').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [yearName],
                        datasets: [{
                            label: 'Refresh',
                            data: [yearIncome],
                            backgroundColor: 'rgb(75, 192, 192)',
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
                        }
                    }
                });



                function fetchTotalIncome(date) {
                    fetch('../controllers/getTotalIncomeByDate_contr.php?d=' + date)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // console.log(data.results.total_income)
                                // Update the total income text
                                let total = data.results.total_income;
                                if (total === null) {
                                    total = "0.00";
                                }
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalDay').innerText = formattedtotal;


                                // Update the chart
                                updateChart(total);
                            } else {
                                document.getElementById('totalDay').textContent = "No data available";
                                updateChart("0");
                            }

                            // Optionally, you can update the chart here if needed
                        })
                        .catch(error => console.error('Error:', error));
                }




                var dayChart = null; // variable to store the Chart instance


                function updateChart(totalIncome) {
                    var ctx = document.getElementById('dayChart').getContext('2d');

                    if (dayChart) {
                        // If a Chart instance exists, destroy it
                        dayChart.destroy();
                    }


                    dayChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income By Day'],
                            datasets: [{
                                label: 'Total Income',
                                data: [totalIncome],
                                backgroundColor: 'rgb(75, 192, 192)',
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




                var IncomeMonthBtn = document.querySelector("#IncomeMonthBtn");

                IncomeMonthBtn.addEventListener("click", function() {
                    var yearSelected = document.getElementById("yearInput1").value;
                    var monthSelected = document.getElementById("monthInput1").value;

                    // Make a fetch request to your PHP script to get the total income for the selected year and month
                    var formData = new FormData();
                    formData.append("yearSelected", yearSelected);
                    formData.append("monthSelected", monthSelected);

                    fetch("../controllers/Income_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let total = data.results.total_income;
                                if (total === null) {
                                    total = "0.00";
                                }
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalMonth').innerText = formattedtotal;

                                // Update the chart with the new data
                                updatemonthChart(total);
                            } else {
                                console.error("Error: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var monthChart = null;

                function updatemonthChart(totalIncome) {
                    var ctx = document.getElementById('revenuemonth').getContext('2d');

                    if (monthChart) {
                        // If a Chart instance exists, destroy it
                        monthChart.destroy();
                    }


                    monthChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income By Month'],
                            datasets: [{
                                label: 'Total Income',
                                data: [totalIncome],
                                backgroundColor: 'rgb(75, 192, 192)',
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


                var ctx = document.getElementById('threemonth').getContext('2d');
                var threemonths = <?php echo json_encode($months); ?>;
                var totalIcomePerMonth = <?php echo json_encode($totalIncomeLastThreeMonths); ?>;
                var existingChart = Chart.getChart(ctx); // Get the existing chart instance associated with the canvas
                if (existingChart) {
                    existingChart.destroy(); // Destroy the existing chart instance if it exists
                }

                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [threemonths[2], threemonths[1], threemonths[0]], // Use the month names as labels
                        datasets: [{
                            label: 'Total Income',
                            data: [{
                                    x: threemonths[2],
                                    y: totalIcomePerMonth[2]
                                },
                                {
                                    x: threemonths[1],
                                    y: totalIcomePerMonth[1]
                                },
                                {
                                    x: threemonths[0],
                                    y: totalIcomePerMonth[0]
                                }
                            ],
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
                                    display: true,
                                    text: ''
                                }
                            }
                        }
                    }
                });



                var sixmonths = <?php echo json_encode($sixmonths); ?>;
                var sixtotalIcomePerMonth = <?php echo json_encode($totalIncomeLastSixMonths); ?>;

                var ctx = document.getElementById('sixmonth').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [sixmonths[5], sixmonths[4], sixmonths[3], sixmonths[2], sixmonths[1], sixmonths[0]],
                        datasets: [{
                            label: 'Refresh',
                            data: [sixtotalIcomePerMonth[5], sixtotalIcomePerMonth[4], sixtotalIcomePerMonth[3], sixtotalIcomePerMonth[2], sixtotalIcomePerMonth[1], sixtotalIcomePerMonth[0]],
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
                                    display: true,
                                    text: ''
                                }
                            }
                        }
                    }
                });






                var IncomeYearBtn = document.querySelector("#getYearIncomeBtn");

                IncomeYearBtn.addEventListener("click", function() {
                    var yearInput2 = document.getElementById("yearInput2").value;
                    // console.log(yearSelected)
                    // Make a fetch request to your PHP script to get the total income for the selected year
                    var formData = new FormData();
                    formData.append("yearSelected2", yearInput2);

                    fetch("../controllers/Income_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let total = data.results.total_income;
                                if (total === null) {
                                    total = "0.00";
                                }
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalYear').innerText = formattedtotal;

                                // Update the chart with the new data
                                updateyearChart(total);
                            } else {
                                console.error("Error: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });



                var yearChart = null;

                function updateyearChart(totalIncome) {
                    var ctx = document.getElementById('revenueyear').getContext('2d');

                    if (yearChart) {
                        // If a Chart instance exists, destroy it
                        yearChart.destroy();
                    }


                    yearChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income By Year'],
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




                var yearRange = <?php echo json_encode($yearRange); ?>;
                var yearRangeIncome = <?php echo json_encode($yearRangeIncome); ?>;

                var ctx = document.getElementById('bestyear').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [yearRange],
                        datasets: [{
                            label: 'Refresh',
                            data: [yearRangeIncome],
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
                                    display: true,
                                    text: ''
                                }
                            }
                        }
                    }
                });





                var worstyearRange = <?php echo json_encode($worstyearRange); ?>;
                var worstyearRangeIncome = <?php echo json_encode($worstyearRangeIncome); ?>;


                var ctx = document.getElementById('worstyear').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [yearRange, worstyearRange],
                        datasets: [{
                            label: 'Refresh',
                            data: [yearRangeIncome, worstyearRangeIncome],
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
                                    display: true,
                                    text: ''
                                }
                            }
                        }
                    }
                });





                var getAreaBtn = document.querySelector("#getAreaByIdBtn");

                getAreaBtn.addEventListener("click", function() {
                    var areaSelected = document.getElementById("areaSelect").value;

                    // Make a fetch request to your PHP script to get the total income for the selected year and month
                    var formData = new FormData();
                    formData.append("areaSelected", areaSelected);

                    fetch("../controllers/Income_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let total = data.results.total_income;
                                let areaName = data.results.AreaName;
                                if (total === null) {
                                    total = "0.00";
                                }
                                if (areaName === null) {
                                    areaName = "No Data";
                                }
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalAreaIncome').innerText = formattedtotal;

                                // Update the chart with the new data
                                updateAreaChart(total, areaName);
                            } else {
                                console.error("Error: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var areaChart = null;

                function updateAreaChart(totalIncome, areaName) {
                    var ctx = document.getElementById('revenuearea').getContext('2d');

                    if (areaChart) {
                        // If a Chart instance exists, destroy it
                        areaChart.destroy();
                    }


                    areaChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income For ' + areaName],
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







                var getSubAreaBtn = document.querySelector("#getSubAreaByIdBtn");

                getSubAreaBtn.addEventListener("click", function() {
                    var subareaSelect = document.getElementById("subareaSelect").value;

                    // Make a fetch request to your PHP script to get the total income for the selected year and month
                    var formData = new FormData();
                    formData.append("subareaSelect", subareaSelect);

                    fetch("../controllers/Income_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let total = data.results.total_income;
                                let subAreaName = data.results.SubAreaName;
                                if (total === null) {
                                    total = "0.00";
                                }
                                if (subAreaName === null) {
                                    subAreaName = "No Data";
                                }
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalSubAreaIncome').innerText = formattedtotal;

                                // Update the chart with the new data
                                updateSubAreaChart(total, subAreaName);
                            } else {
                                console.error("Error: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var subareaChart = null;

                function updateSubAreaChart(totalIncome, subAreaName) {
                    var ctx = document.getElementById('subarea').getContext('2d');

                    if (subareaChart) {
                        // If a Chart instance exists, destroy it
                        subareaChart.destroy();
                    }


                    subareaChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income For ' + subAreaName],
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










                /*
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

                                */
                var allAreas = <?php echo json_encode($allAreas); ?>;
                var allAreaIncomes = <?php echo json_encode($allAreaIncomes); ?>;
                var combinedLabels = [];
                for (var i = 0; i < allAreas.length; i++) {
                    combinedLabels.push([allAreas[i], numberFormatJS(allAreaIncomes[i])]); // Push an array containing area name and income
                }


                var ctx = document.getElementById('allareaCan').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: combinedLabels,
                        datasets: [{
                            label: 'Refresh',
                            data: allAreaIncomes,
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



                var allSubAreas = <?php echo json_encode($allSubAreas); ?>;
                var allSubAreaIncomes = <?php echo json_encode($allSubAreaIncomes); ?>;
                var combinedLabels = [];
                for (var i = 0; i < allAreas.length; i++) {
                    combinedLabels.push([allSubAreas[i], numberFormatJS(allSubAreaIncomes[i])]); // Push an array containing area name and income
                }



                var ctx = document.getElementById('allsubarea').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: combinedLabels,
                        datasets: [{
                            label: 'Refresh',
                            data: allSubAreaIncomes,
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







                var getProductByIdBtn = document.querySelector("#getProductByIdBtn");

                getProductByIdBtn.addEventListener("click", function() {
                    var ProductSelect = document.getElementById("ProductSelect").value;

                    if (!ProductSelect) {
                        displayMessage("producterrorMsg", "Choose a product First", true);
                        return;
                    }
                    var formData = new FormData();
                    formData.append("ProductSelect", ProductSelect);

                    fetch("../controllers/Income_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let total = data.results.total_income;
                                let productName = data.results.ProductName;
                                if (total === null) {
                                    total = "0.00";
                                }
                                if (productName === null) {
                                    productName = "No Data";
                                }
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalProductIncome').innerText = formattedtotal;

                                // Update the chart with the new data
                                updateProductChart(total, productName);
                            } else {
                                console.error("Error: " + data.message);
                                updateProductChart(0, "");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var productChart = null;

                function updateProductChart(totalIncome, productName) {
                    var ctx = document.getElementById('productchart').getContext('2d');

                    if (productChart) {
                        // If a Chart instance exists, destroy it
                        productChart.destroy();
                    }


                    productChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income For ' + productName],
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






                var allProducts = <?php echo json_encode($allProducts); ?>;
                var allProductsIncomes = <?php echo json_encode($allProductsIncomes); ?>;
                var combinedLabel = [];
                for (var i = 0; i < allProducts.length; i++) {
                    combinedLabel.push([allProducts[i], allProductsIncomes[i]]); // Push an array containing area name and income
                }

                var ctx = document.getElementById('productallchart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: combinedLabel,
                        datasets: [{
                            label: 'Refresh',
                            data: allProductsIncomes,
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




                var getPlanByIdBtn = document.querySelector("#getPlanByIdBtn");

                getPlanByIdBtn.addEventListener("click", function() {
                    var planSelected = document.getElementById("planSelected").value;

                    if (!planSelected) {
                        displayMessage("planerrorMsg", "Choose a plan First", true);
                        return;
                    }
                    var formData = new FormData();
                    formData.append("planSelected", planSelected);

                    fetch("../controllers/Income_contr.php", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let total = data.results.totalIncome;
                                let planName = data.results.Name;
                                let planVolume = data.results.Volume;
                                if (total === null) {
                                    total = "0.00";
                                }
                                if (planName === null) {
                                    planName = "No Data";
                                    planVolume = "";
                                }
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalPlanIncome').innerText = formattedtotal;

                                // Update the chart with the new data
                                updatePlanChart(total, planName, planVolume);
                            } else {
                                console.error("Error: " + data.message);
                                updatePlanChart(0, "", "");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var planChart = null;

                function updatePlanChart(totalIncome, planName, planVolume) {
                    var ctx = document.getElementById('planchart').getContext('2d');

                    if (planChart) {
                        // If a Chart instance exists, destroy it
                        planChart.destroy();
                    }


                    planChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [planName + ' ( ' + planVolume + ' )'],
                            datasets: [{
                                label: 'Total Income',
                                data: [totalIncome],
                                backgroundColor: 'rgb(75, 192, 192)',
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




                //    var ctx = document.getElementById('HvsLproductchart').getContext('2d');
                //    var myChart = new Chart(ctx, {
                //        type: 'doughnut',
                //        data: {
                //            labels: ['Router', 'Dish'],
                //            datasets: [{
                //                label: 'Refresh',
                //                data: [15000, 2300],
                //                backgroundColor: ['rgba(75, 192, 192, 0.5)', 'rgba(255, 99, 132, 0.5)'],
                //                borderWidth: 1
                //            }]
                //        },
                //        options: {
                //            scales: {
                //                y: {
                //                    beginAtZero: true,
                //                    title: {
                //                        display: true,
                //                        text: 'Amount (USD)'
                //                    }
                //                },
                //                x: {
                //                    title: {
                //                        display: true,
                //                        text: ''
                //                    }
                //                }
                //            }
                //        }
                //    });






                var allPlans = <?php echo json_encode($allPlans); ?>;
                var allPlanIncomes = <?php echo json_encode($allPlanIncomes); ?>;
                var allPlansVolume = <?php echo json_encode($allPlansVolume); ?>;
                var combinedLabel = [];
                for (var i = 0; i < allPlans.length; i++) {
                    combinedLabel.push([allPlans[i] + ' ' + allPlansVolume[i], numberFormatJS(allPlanIncomes[i])]);
                }


                var ctx = document.getElementById('allplanchart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: combinedLabel,
                        datasets: [{
                            label: 'Refresh',
                            data: allPlanIncomes,
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
                                formattedtotal = numberFormatJS(total);
                                document.getElementById('totalCustomerIncome').innerText = formattedtotal;

                                // Update the chart with the new data
                                updateCustomerChart(totalInvoices, totalSales, total, customerName);
                            } else {
                                console.error("Error: " + data.message);
                                updateCustomerChart(0, 0, 0, "");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });


                var customerChart = null;

                function updateCustomerChart(totalInvoices, totalSales, total, customerName) {
                    var ctx = document.getElementById('customerchart').getContext('2d');

                    if (customerChart) {
                        // If a Chart instance exists, destroy it
                        customerChart.destroy();
                    }


                    customerChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Total Invoices', 'Total Sales'],
                            datasets: [{
                                label: 'Total Income',
                                data: [totalInvoices, totalSales],
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



















                /*
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
                                                });*/
            </script>