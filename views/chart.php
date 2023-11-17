<div class="content">
    <h1>chart.js</h1>
    <div class="chart-container">
        <h3>My Chart</h3>
        <canvas id="myChart"></canvas>
    </div>
</div>
<script src="
    https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js
    "></script>
<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
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
</script>