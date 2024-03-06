
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





															// table navigation functions

  // Create a flexible table navigation module
 



