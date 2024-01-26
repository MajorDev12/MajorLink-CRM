<?php require_once "../controllers/session_Config.php"; ?>
<?php require_once  '../database/pdo.php'; ?>
<?php require_once  '../modals/setup_mod.php'; ?>


<?php
$connect = connectToDatabase($host, $dbname, $username, $password);
?>


<?php require_once "header.php"; ?>


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
							<h3>1020</h3>
							<p>All Customers</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-group'></i>
						<span class="text">
							<h3>934</h3>
							<p>Active Customers</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-group'></i>
						<span class="text">
							<h3>934</h3>
							<p>Offline Customers</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-dollar-circle'></i>
						<span class="text">
							<h3> <?php
									$settings = get_Settings($connect);
									echo $settings[0]["CurrencySymbol"];
									?> 2543</h3>
							<p>Total Sales</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-calendar-check'></i>
						<span class="text">
							<h3><?php
								$settings = get_Settings($connect);
								echo $settings[0]["CurrencySymbol"];
								?> 1020</h3>
							<p>Sales This Month</p>
						</span>
					</li>
				</ul>
			</div>

			<div class="content.main">

				<div class="table-data">
					<div class="order">
						<div class="head">
							<h3>Due Payments</h3>
							<i class='bx bx-search'></i>
							<i class='bx bx-filter'></i>
						</div>
						<table class="table table-striped">
							<thead class="table-primary">
								<tr>
									<th>ClientID</th>
									<th>Username</th>
									<th>Area</th>
									<th>Expire</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="">00001</td>
									<td class="">Major Nganga</td>
									<td><span class="">Pipeline</span></td>
									<td><span class="">03 Days</span></td>
									<td><span class="">Offline</span></td>
								</tr>

								<tr>
									<td class="">00002</td>
									<td class="">Major Nganga</td>
									<td><span class="">Pipeline</span></td>
									<td><span class="">03 Days</span></td>
									<td><span class="">Offline</span></td>
								</tr>

								<tr>
									<td class="">00003</td>
									<td class="">Major Nganga</td>
									<td><span class="">Pipeline</span></td>
									<td><span class="">03 Days</span></td>
									<td><span class="">Offline</span></td>
								</tr>

								<tr>
									<td class="">00004</td>
									<td class="">Major Nganga</td>
									<td><span class="">Pipeline</span></td>
									<td><span class="">03 Days</span></td>
									<td><span class="">Offline</span></td>
								</tr>

								<tr>
									<td class="">00013</td>
									<td class="">Major Nganga</td>
									<td><span class="">Pipeline</span></td>
									<td><span class="">03 Days</span></td>
									<td><span class="">Offline</span></td>
								</tr>

							</tbody>
						</table>
					</div>
					<!-- TODO LIST -->
					<div class="todo">
						<div class="head">
							<h3>Recent Customers</h3>
							<i class='bx bx-plus'></i>
							<i class='bx bx-filter'></i>
						</div>
						<ul class="todo-list">
							<li class="completed">
								<p>John Doe</p>
								<i class='bx bx-dots-vertical-rounded'></i>
							</li>
							<li class="completed">
								<p>Traversy</p>
								<i class='bx bx-dots-vertical-rounded'></i>
							</li>
							<li class="not-completed">
								<p>Deved</p>
								<i class='bx bx-dots-vertical-rounded'></i>
							</li>
							<li class="completed">
								<p>Elon</p>
								<i class='bx bx-dots-vertical-rounded'></i>
							</li>
							<li class="not-completed">
								<p>Kevin</p>
								<i class='bx bx-dots-vertical-rounded'></i>
							</li>
						</ul>
					</div>
				</div>
				<!-- Revenue Reports -->
				<div class="content">
					<h4>Income Summary for All Areas</h4>
					<p>Total Income This Geoghraphy: <?php
														$settings = get_Settings($connect);
														echo $settings[0]["CurrencySymbol"];
														?> 0.00</p>
					<canvas id="allarea" class="canvasChart"></canvas>
				</div>

				<div class="content mt-5">
					<h4>Sales Last Three Months</h4>
					<p>Total Income This Three Months: <?php
														$settings = get_Settings($connect);
														echo $settings[0]["CurrencySymbol"];
														?> 0.00</p>
					<canvas id="threemonth" class="canvasChart"></canvas>
				</div>
			</div>





			<?php require_once "footer.php"; ?>