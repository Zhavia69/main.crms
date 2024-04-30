<?php
include_once("head.php");

$error_message = "";

$p = payment::read_payment();
if (!is_array($p)) {
    $p = array();
}

// Creating an array to store transaction counts for each month
$transactionsCount = array();

// Counting transactions for each month
foreach ($p as $transaction) {
    $date = date('F Y', strtotime($transaction['date']));
    if (!isset($transactionsCount[$date])) {
        $transactionsCount[$date] = 1;
    } else {
        $transactionsCount[$date]++;
    }
}
?>

<div class="row">
    <div class="col-md-6">
        <h2>Payment History Report</h2>
        <h5><i class="fa fa-money"></i> All Payments</h5>
    </div>
    <hr />
</div>

<!-- Graphical Representation Section -->
<div class="row">
    <div class="col-md-6">
        <h3>Transaction Counts by Month (Bar Graph)</h3>
        <canvas id="transactionBarChart" width="400" height="400"></canvas>
    </div>
    <div class="col-md-6">
        <h3>Transaction Counts by Payment Method (Pie Chart)</h3>
        <canvas id="transactionPieChart" width="400" height="400"></canvas>
    </div>
</div>

<!-- Add a Line Graph Section -->
<div class="row">
    <div class="col-md-12">
        <h3>Transaction Counts Over Time (Line Graph)</h3>
        <canvas id="transactionLineChart" width="800" height="400"></canvas>
    </div>
</div>

<!-- JavaScript Section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
    // Transaction counts data
    var transactionMonths = <?php echo json_encode(array_keys($transactionsCount)); ?>;
    var transactionCounts = <?php echo json_encode(array_values($transactionsCount)); ?>;

    // Bar chart
    var ctxBar = document.getElementById('transactionBarChart').getContext('2d');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: transactionMonths,
            datasets: [{
                label: 'Transactions',
                data: transactionCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Line chart
    var ctxLine = document.getElementById('transactionLineChart').getContext('2d');
    var lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: transactionMonths,
            datasets: [{
                label: 'Transactions Over Time',
                data: transactionCounts,
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });


    // Pie chart
    var ctxPie = document.getElementById('transactionPieChart').getContext('2d');
    var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: transactionMonths,
            datasets: [{
                label: 'Payment Method',
                data: transactionCounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        }
    });
</script>

<?php include_once("foot.php"); ?>
<?php include("ffoot.php"); ?>
