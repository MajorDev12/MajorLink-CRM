<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css">
	<title>Custom Toast Example</title>
	<style>
		body {
			font-family: 'Courier New', Courier, monospace;
		}

		#toast {
			position: fixed;
			bottom: 0;
			right: 0;
			margin: 2%;
			margin-bottom: 3%;
			background-color: #F9F9F9;
			height: auto;
			max-width: 350px;
			overflow: hidden;
			font-size: 0.875rem;
			border-radius: 0.25rem;
			box-shadow: 0 0.5rem 1rem #eee;
			border: 1px solid grey;
			opacity: 0;
			transition: opacity 0.5s ease-in-out;
			padding: 10px;
		}

		.toast-header {
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			align-items: center;
			border-bottom: 1px solid grey;
			padding-bottom: 10px;
		}

		.toast-body {
			width: 100%;
			padding-top: 20px;
		}

		#toast.show {
			opacity: 1;
		}
	</style>
</head>

<body>

	<button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>
	<button type="button" class="btn btn-primary" id="deadToastBtn">Show dead toast</button>

	<div id="toast">
		<div class="toast-header">
			<img src="../img/user.png" alt="" width="30px">
			<h4 id="toast-time">3 seconds Ago</h4>
		</div>
		<div class="toast-body">
			<h4>Created Successfully Custom Toast Example</h4>
		</div>
	</div>

	<script>
		document.getElementById('liveToastBtn').addEventListener('click', function() {
			// Set a flag in local storage to indicate that the toast should be displayed after reload
			localStorage.setItem('livetoast', 'true');

			// Reload the page
			location.reload();
		});

		document.getElementById('deadToastBtn').addEventListener('click', function() {
			// Set a flag in local storage to indicate that the toast should be displayed after reload
			localStorage.setItem('deadtoast', 'true');
			// Reload the page
			location.reload();
		});



		function shooo() {
			// Check if the flag is set to show the toast after reload
			if (localStorage.getItem('livetoast') === 'true') {
				showToast('Congratulations!, You have Just Added A new Costomer ', 3000); // 3000 milliseconds (3 seconds) duration

				// Reset the flag after showing the toast
				localStorage.removeItem('livetoast');
			}

			if (localStorage.getItem('deadtoast') === 'true') {
				showToast('Congratulations!', 3000); // 3000 milliseconds (3 seconds) duration

				// Reset the flag after showing the toast
				localStorage.removeItem('deadtoast');
			}
		}


		window.onload = function() {
			setTimeout(() => {
				shooo();
			}, 3000);
		};


		function showToast(message, duration) {
			var toast = document.getElementById('toast');
			toast.querySelector('.toast-body').innerText = message;

			// Show the toast
			toast.classList.add('show');

			// Hide the toast after the specified duration
			setTimeout(function() {
				hideToast();
			}, duration);
		}

		function hideToast() {
			var toast = document.getElementById('toast');
			toast.classList.remove('show');
		}
	</script>
</body>

</html>