<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Invoice Slip</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>

	<div class="container mt-5">
		<div class="card">
			<div class="card-header bg-primary text-white">
				<h5 class="mb-0">Invoice Slip</h5>
			</div>
			<div class="card-body">

				<!-- Add your receipt details here -->
				<form>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="paymentDate">Payment Date:</label>
							<input type="date" id="paymentDate" name="saleDate" class="form-control" disabled required>
						</div>
						<div class="form-group col-md-6">
							<label for="amount">Payment Amount:</label>
							<input type="number" id="amount" name="saleDate" class="form-control" disabled required>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="paymentMethod">Payment Method:</label>
							<select class="form-control" name="" id="paymentMethod">
								<option selected value="">Choose</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="paymentStatus">Payment Status:</label>
							<select class="form-control" name="" id="paymentStatus">
								<option selected value="">Choose</option>
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="paymentReference">Payment Reference:</label>
							<input type="text" id="paymentReference" name="paymentReference" class="form-control" disabled required>
						</div>
						<div class="form-group col-md-6">
							<label for="total">Total:</label>
							<input type="text" id="total" name="total" class="form-control" disabled required>
						</div>
					</div>

					<!-- Save Changes Button -->
					<div class="form-row">
						<button type="submit" class="btn btn-success col-md-4">Save Changes</button>
					</div>
				</form>

			</div>
		</div>
	</div>

	<!-- Bootstrap JS and Popper.js -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>