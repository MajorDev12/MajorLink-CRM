<?php require_once "../controllers/session_Config.php"; ?>
<?php require_once  '../database/pdo.php'; ?>
<?php require_once  '../modals/setup_mod.php'; ?>
<?php require_once  '../modals/addClient_mod.php'; ?>
<?php require_once  '../modals/reports_mod.php'; ?>

<?php
$connect = connectToDatabase($host, $dbname, $username, $password);

$clientCount = getUsersCount($connect);
$activeClient = getActiveUsers($connect);
$inActiveClient = getInActiveUsers($connect);
$totalSales = getTotalIncome($connect);
$expiredClients = getExpiringClients($connect);
$newestClients = getNewestClients($connect, $limit = 5);

$year = date('Y');
$month = date('m');
$thisMonthSales = getTotalIncomeByYearMonth($connect, $year, $month);
$thisMonthSales = $thisMonthSales['total_income'];



$clientCount = number_format($clientCount, 0);
$activeClient = number_format($activeClient, 0);
$inActiveClient = number_format($inActiveClient, 0);
$totalSales = number_format($totalSales, 0);
$thisMonthSales = number_format($thisMonthSales, 0);

$settings = get_Settings($connect);
$symbol = $settings[0]["CurrencySymbol"];
$CurrencyCode = $settings[0]["CurrencyCode"];
?>

<?php require_once "style.config.php"; ?>
<?php require_once "header.php"; ?>

<style>
	table tbody tr:nth-child(even) {
		background-color: var(--grey);
	}

	/* Apply background color to even rows */
	table tbody tr:nth-child(odd) {
		background-color: var(--light);
	}

	table thead th {
		padding: 20px;
	}

	table thead tr th {
		color: var(--dark);
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
				<h1>Welcome, <?php echo $_SESSION['Username']; ?></h1>

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







			<div class="content.main">
				<ul class="box-info">
					<li>
						<i class='bx bxs-calendar-check'></i>
						<span class="text">
							<h3><?= $clientCount; ?></h3>
							<p>All Customers</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-group'></i>
						<span class="text">
							<h3><?= $activeClient; ?></h3>
							<p>Active Customers</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-group'></i>
						<span class="text">
							<h3><?= $inActiveClient; ?></h3>
							<p>Offline Customers</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-dollar-circle'></i>
						<span class="text">
							<h3> <?= $symbol; ?> <?= $totalSales; ?></h3>
							<p>Total Sales</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-calendar-check'></i>
						<span class="text">
							<h3><?= $symbol; ?> <?= $thisMonthSales; ?></h3>
							<p>Sales This Month</p>
						</span>
					</li>
				</ul>
			</div>

			<div class="content.main">

				<div class="table-data">
					<div class="order">
						<div class="head">
							<h3 class="mb-2">Due Payments</h3>
							<!-- <i class='bx bx-search'></i>
							<i class='bx bx-filter'></i> -->
						</div>
						<table class="">
							<thead class="">
								<tr>
									<th>ClientID</th>
									<th>Username</th>
									<th>Area</th>
									<th>Expire</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php $counter = 1; ?>
								<?php if ($expiredClients) : ?>
									<?php foreach ($expiredClients as $index => $client) : ?>
										<tr>
											<td class="index"><?= $index + 1;  ?></td>
											<td class=""><?php echo $client['FirstName'] . ' ' . $client['LastName']; ?></td>
											<td><span class=""><?php echo $client['areaName']; ?></span></td>
											<td><span class=""><?php echo $client['RemainingDays']; ?> days</span></td>
											<td>
												<span class="">
													<?php echo $client['ActiveStatus'] == 1 ? 'Active' : 'Inactive'; ?>
												</span>
											</td>

										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<?php echo '  
                             <tr>
                                <td colspan="5" style="text-center"> No Data Yet</td>
                            </tr>'; ?>
								<?php endif; ?>


							</tbody>
						</table>
					</div>
					<!-- TODO LIST -->
					<div class="todo">
						<div class="head">
							<h3 class="mb-2">Recent Customers</h3>
							<!-- <i class='bx bx-plus'></i>
							<i class='bx bx-filter'></i> -->
						</div>

						<?php if ($newestClients) : ?>
							<ul class="todo-list">
								<?php foreach ($newestClients as $client) : ?>
									<li class="<?php echo ($client['ActiveStatus'] == 1) ? 'completed' : 'not-completed'; ?>">
										<p><?php echo $client['FirstName'] . ' ' . $client['LastName']; ?></p>
										<a href="viewSingleUser.php?id=<?php echo $client['ClientID']; ?>"><i class="bx bx-dots-vertical-rounded"></i></a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php else : ?>
							<p>No newest clients found.</p>
						<?php endif; ?>

					</div>
				</div>


				<!-- Revenue Reports -->
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
					<h4>Sales Last Three Months</h4>
					<p class="text">Total Income This Three Months: <span class="number"><?= $symbol; ?> <?= number_format($sumAllTotal, 2); ?></span></p>
					<canvas id="threemonth" class="canvasChart"></canvas>
				</div>
			</div>



			<?php require_once "footer.php"; ?>



			<script>
				var allAreas = <?php echo json_encode($allAreas); ?>;
				var allAreaIncomes = <?php echo json_encode($allAreaIncomes); ?>;
				var CurrencyCode = <?php echo json_encode($CurrencyCode); ?>;
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
			</script>