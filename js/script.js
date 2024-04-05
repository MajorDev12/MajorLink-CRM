
const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});



// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})


//loader
$(document).ready(function() {
	$('#loading').addClass("removeLoader");
});



// const searchButton = document.querySelector('#content nav form .form-input button');
// const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
// const searchForm = document.querySelector('#content nav form');

// searchButton.addEventListener('click', function (e) {
// 	if(window.innerWidth < 576) {
// 		e.preventDefault();
// 		searchForm.classList.toggle('show');
// 		if(searchForm.classList.contains('show')) {
// 			searchButtonIcon.classList.replace('bx-search', 'bx-x');
// 		} else {
// 			searchButtonIcon.classList.replace('bx-x', 'bx-search');
// 		}
// 	}
// })




// if(window.innerWidth < 768) {
// 	sidebar.classList.add('hide');
// } else if(window.innerWidth > 576) {
// 	// searchButtonIcon.classList.replace('bx-x', 'bx-search');
// 	// searchForm.classList.remove('show');
// }


// window.addEventListener('resize', function () {
// 	if(this.innerWidth > 576) {
// 		searchButtonIcon.classList.replace('bx-x', 'bx-search');
// 		searchForm.classList.remove('show');
// 	}
// })



const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
})








var ctx = document.getElementById('allarea').getContext('2d');
var myChart = new Chart(ctx, {
	type: 'bar',
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






															// tab changer
// Get all tabs and tab content
function initializeTabs(tabSelector, contentSelector) {
    let tabs = document.querySelectorAll(tabSelector);
    let tabContents = document.querySelectorAll(contentSelector);

    tabs.forEach((tab, index) => {
        tab.addEventListener("click", () => {
            // Hide all tab contents
            tabContents.forEach((content) => {
                content.classList.remove("active");
            });
            // Deactivate all tabs
            tabs.forEach((tab) => {
                tab.classList.remove("active");
            });
            // Show the clicked tab content and activate the tab
            tabContents[index].classList.add("active");
            tabs[index].classList.add("active");
        });
    });
}





// table search function
function Search(searchBtn, searchInput, dataUrl, source) {
	searchBtn.addEventListener("click", function() {
		const inputValue = searchInput.value;
		if (!inputValue) {
			displayMessage("errorMsg", "Cannot be empty");
			return;
		}

		var formData = new FormData();
		formData.append("inputValue", inputValue);

		fetch(dataUrl, {
				method: 'POST',
				body: formData,
			})
			.then(response => response.json())
			.then(data => {
				if (!data) {
					// Handle case where no data was found
					var tableBody = document.querySelector(`.${source}`);

					// Set the message directly to the table body
					tableBody.innerHTML = `
	<tr>
		<td colspan="8" style="text-center"> no such data</td>
	</tr>
`;
				} else {
					// Get the target table body based on the source
					var targetTableBody = document.querySelector(`.${source}`);

					if (targetTableBody) {
						// Manipulate the target table body element
						targetTableBody.innerHTML = data;
					} else {
						console.error('Table body element not found.');
					}
				}

			})
			.catch(error => {
				console.error('Error:', error);
			});
	});
}



// print table

function printTable(printIcon, tableBody, theadId) {
	// Open a new window or tab
	var printWindow = window.open('', '_blank');

	// Create a blank page with the Bootstrap table
	printWindow.document.write('<html><head><title>Print Table</title>');
	printWindow.document.write('<link rel="stylesheet" href="../styles/table.css">');
	printWindow.document.write('</head><body>');

	// Create a table and append it to the new window
	var printTable = document.createElement('table');
	printTable.className = 'table printTable';
	printTable.style.border = '1px solid black';
	printTable.style.width = "80%";
	printTable.style.position = "relative";
	printTable.style.left = "10%";

	// Copy the table headers, excluding the last td
	var originalTableHeaders = document.getElementById(theadId);
	var TableHeaderRows = document.querySelector('#' + theadId + ' th');
	if (originalTableHeaders) {
		var clonedHeaders = originalTableHeaders.cloneNode(true);
		var lastHeaderCell = clonedHeaders.querySelector('th:last-child');
		if (lastHeaderCell) {
			lastHeaderCell.remove();
		}
		clonedHeaders.style.background = "#342E37";
		clonedHeaders.style.color = "#F9F9F9";
		TableHeaderRows.style.padding = "8px";
		TableHeaderRows.style.borderRight = "1px solid #F9F9F9";
		printTable.appendChild(clonedHeaders);
	}

	// Copy the table body content, excluding the last td
	var clonedTableBody = tableBody.cloneNode(true);
	var lastBodyCells = clonedTableBody.querySelectorAll('td:last-child');

	lastBodyCells.forEach(function(cell) {
		cell.remove();
	});

	printTable.appendChild(clonedTableBody);

	// Append the table to the new window
	printWindow.document.body.appendChild(printTable);

	printWindow.document.write('</body></html>');

	// Attach an event listener to clean up and close the window after printing
	printWindow.onafterprint = function() {
		// Clean up: Remove the cloned elements
		printTable.remove();
		// Close the blank page after the print dialog is closed
		printWindow.close();
	};

	// Trigger the print function for the new window
	printWindow.print();
}








function exportToCSV(tableHeader, tableBody) {
	// Extract the table headers, excluding the last td
	var headers = Array.from(tableHeader.querySelectorAll('th:not(:last-child)')).map(header => header.textContent);

	// Extract the table data, excluding the last td
	var rows = tableBody.querySelectorAll('tr');

	// Build CSV content manually
	var csvContent = headers.join(',') + '\n';

	// Add rows to the CSV content, excluding the last td
	rows.forEach(function(row) {
		var rowData = [];
		var cells = row.querySelectorAll('td:not(:last-child)');
		cells.forEach(function(cell) {
			rowData.push('"' + cell.textContent.replace(/"/g, '""') + '"'); // Enclose in double quotes and escape existing double quotes
		});
		csvContent += rowData.join(',') + '\n';
	});

	// Create a Blob with the CSV content
	var blob = new Blob([csvContent], {
		type: 'text/csv'
	});

	// Create a download link
	var link = document.createElement('a');
	link.href = URL.createObjectURL(blob);
	link.download = 'exported_table.csv';

	// Append the link to the document and trigger the click event
	document.body.appendChild(link);
	link.click();

	// Clean up: Remove the link from the document
	document.body.removeChild(link);

}






function displayMessage(messageElement, message, isError, ) {
	// Get the HTML element where the message should be displayed
	var targetElement = document.getElementById(messageElement);

	// Set the message text
	targetElement.innerText = message;

	// Add styling based on whether it's an error or success
	if (isError) {
		targetElement.style.color = 'red';
	} else {
		targetElement.style.color = 'green';
	}

	// Set a timeout to hide the message with the fade-out effect
	setTimeout(function() {
		targetElement.innerText = '';
	}, 1000);
}