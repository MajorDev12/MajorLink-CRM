<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Simple Dashboard</title>
	<style>
		body {
			margin: 0;
			font-family: Arial, sans-serif;
			background-color: #f4f4f4;
		}

		#sidebar {
			width: 250px;
			height: 100%;
			background-color: #333;
			color: white;
			position: fixed;
			overflow-x: hidden;
			padding-top: 20px;
		}

		#main-container {
			margin-left: 250px;
			padding: 20px;
		}

		a {
			text-decoration: none;
			color: white;
			display: block;
			padding: 10px;
			margin: 5px 0;
			transition: background-color 0.3s;
		}

		a:hover {
			background-color: #555;
		}
	</style>
</head>

<body>

	<div id="sidebar">
		<a href="#" onclick="loadPage('home')">Home</a>
		<a href="#" onclick="loadPage('dashboard')">Dashboard</a>
		<a href="#" onclick="loadPage('settings')">Settings</a>
		<!-- Add more links as needed -->
	</div>

	<div id="main-container">
		<h2>Welcome to the Dashboard</h2>
		<p>This is the default content. Click on the side navigation links to load different pages.</p>
	</div>

	<script>
		function loadPage(page) {
			// You can perform additional actions before loading the page, if needed
			// For simplicity, this example only updates the main container content
			document.getElementById('main-container').innerHTML = `<h2>${page.charAt(0).toUpperCase() + page.slice(1)}</h2><p>This is the ${page} page content.</p>`;
		}
	</script>

</body>

</html>