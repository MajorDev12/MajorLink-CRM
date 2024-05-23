
const spinner = document.querySelector('#spinner');
//loader
window.addEventListener("load", function(){
	fadeOutSpinner();
	const mode = localStorage.getItem('mode');
    if (mode === 'dark') {
        switchMode.checked = true;
        document.body.classList.add('dark');
    } else {
        switchMode.checked = false;
        document.body.classList.remove('dark');
    }
})


// Function to fade out the spinner
function fadeOutSpinner() {
    let opacity = 1; // Initial opacity
    
    // Interval function to decrease opacity gradually
    const fadeInterval = setInterval(() => {
        opacity -= 0.4; // Decrease opacity by 0.1
        
        // Apply the new opacity
        spinner.style.opacity = opacity;
        
        // Check if opacity is less than or equal to 0
        if (opacity <= 0) {
            clearInterval(fadeInterval); // Stop the interval
            spinner.style.display = 'none'; // Hide the spinner
        }
    }, 100); // Interval duration in milliseconds (adjust as needed)
}


function showSpinner() {
	var loader = document.getElementById('loader');
	if (loader) {
		loader.style.display = "flex";
		loader.style.opacity = 1;
	}
}

function hideSpinner() {
	var loader = document.getElementById('loader');
	if (loader) {
		loader.style.display = "none";
		loader.style.opacity = 0;
	}
}



const http = require('http');

// Create an HTTP server
const server = http.createServer((req, res) => {
  // Redirect all requests to port 3000
  const redirectUrl = `http://localhost:3000${req.url}`;
  
  // Send a 301 (Moved Permanently) redirect response
  res.writeHead(301, { 'Location': redirectUrl });
  res.end();
});

// Listen on port 80 for incoming HTTP requests
const port = 80;
server.listen(port, () => {
  console.log(`Server running on port ${port}`);
});





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


menuBar.addEventListener('click', function () {
	const sidebar = document.getElementById('sidebar');
	sidebar.classList.toggle('hide');
})



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

// Check the localStorage for the switch mode state when the page loads


// Toggle the switch mode and store its state in localStorage
switchMode.addEventListener('change', function () {
    if(this.checked) {
        document.body.classList.add('dark');
        localStorage.setItem('mode', 'dark');
    } else {
        document.body.classList.remove('dark');
        localStorage.setItem('mode', 'light');
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

function addSearchEventListener(searchIcon, searchInput, searchBtn) {
	searchIcon.addEventListener('click', function() {
		// Toggle the 'show' class on searchInput and searchBtn
		searchInput.classList.toggle('show');
		searchBtn.classList.toggle('show');

		// Focus on the searchInput when it becomes visible
		if (searchInput.classList.contains('show')) {
			searchInput.focus();
		}
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


function numberFormatJS(number) {
    // Convert the input to a float
    var floatNumber = parseFloat(number);
    
    // Check if the input is a valid number
    if (isNaN(floatNumber)) {
        return "Invalid input";
    }
    
    // Convert the float to a string with two decimal places
    var formattedNumber = floatNumber.toFixed(2);
    
    // Split the number into integer and decimal parts
    var parts = formattedNumber.split(".");
    
    // Add comma separators for thousands
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    
    // Join the integer and decimal parts back together
    formattedNumber = parts.join(".");
    
    return formattedNumber;
}



	  function populateDropdown(selectElement, options, defaultYear = 2024) {
		var dropdown = document.getElementById(selectElement);
		options.forEach(function(option) {
			var optionElement = document.createElement("option");
			optionElement.value = option.value;
			optionElement.text = option.text;
			if (option.value === defaultYear) {
				optionElement.selected = true; // Set the default year as selected
			}
			dropdown.appendChild(optionElement);
		});
	}



	


                // Populate the year dropdown
                var yearOptions = [];
                for (var year = 2020; year <= 2030; year++) {
                    yearOptions.push({
                        value: year,
                        text: year.toString()
                    });
                }


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




function displayMessage(messageElement, message, isError, ) {
	// Get the HTML element where the message should be displayed
	var targetElement = document.getElementById(messageElement);

	// Set the message text
	targetElement.innerText = message;
	targetElement.style.fontWeight = '600';

	// Add styling based on whether it's an error or success
	if (isError) {
		targetElement.style.color = '#DB504A';
		
	} else {
		targetElement.style.color = '#2cce89';
	}

	// Set a timeout to hide the message with the fade-out effect
	setTimeout(function() {
		targetElement.innerText = '';
	}, 3000);


}