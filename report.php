<?php
include_once("head.php");

$error_message = "";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bpayment";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Function to fetch revenue data from database
function getRevenueData($conn) {
    $data = array();

    // Fetch revenue data from the database
    $sql = "SELECT business_type, SUM(Amount) AS total_amount FROM payment_history GROUP BY business_type";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[$row["business_type"]] = $row["total_amount"];
        }
    }

    return $data;
}

// Function to generate financial report
function generateFinancialReport($conn) {
    // Fetch total revenue
    $sql = "SELECT SUM(Amount) AS total_revenue FROM payment_history";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_revenue = $row["total_revenue"];

    // Fetch average revenue
    $sql = "SELECT AVG(Amount) AS average_revenue FROM payment_history";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $average_revenue = $row["average_revenue"];

    // Display financial report
    echo "<h2>Financial Report</h2>";
    echo "<p>Total Revenue: $total_revenue</p>";
    echo "<p>Average Revenue: $average_revenue</p>";
    echo "<canvas id='financialChart' width='400' height='200'></canvas>";

    // JavaScript for financial chart
    echo "<script>";
    echo "var ctxFinancial = document.getElementById('financialChart').getContext('2d');";
    echo "var financialChart = new Chart(ctxFinancial, {";
    echo "    type: 'doughnut',";
    echo "    data: {";
    echo "        labels: ['Total Revenue', 'Average Revenue'],";
    echo "        datasets: [{";
    echo "            label: 'Financial Report',";
    echo "            data: [$total_revenue, $average_revenue],";
    echo "            backgroundColor: [";
    echo "                'rgba(75, 192, 192, 0.8)',";
    echo "                'rgba(255, 99, 132, 0.8)'";
    echo "            ],";
    echo "            borderWidth: 1";
    echo "        }]";
    echo "    }";
    echo "});";
    echo "</script>";
}

// Function to generate revenue trends chart
function generateRevenueTrendsChart($conn) {
    // Fetch revenue data
    $data = getRevenueData($conn);
    $categories = array_keys($data);
    $revenues = array_values($data);

    // Display revenue trends chart
    echo "<h2>Revenue Trends</h2>";
    echo "<canvas id='revenueTrendsChart' width='400' height='200'></canvas>";

    // JavaScript for revenue trends chart
    echo "<script>";
    echo "var ctxRevenueTrends = document.getElementById('revenueTrendsChart').getContext('2d');";
    echo "var revenueTrendsChart = new Chart(ctxRevenueTrends, {";
    echo "    type: 'bar',";
    echo "    data: {";
    echo "        labels: " . json_encode($categories) . ",";
    echo "        datasets: [{";
    echo "            label: 'Revenue',";
    echo "            data: " . json_encode($revenues) . ",";
    echo "            backgroundColor: 'rgba(75, 192, 192, 0.2)',";
    echo "            borderColor: 'rgba(75, 192, 192, 1)',";
    echo "            borderWidth: 1";
    echo "        }]";
    echo "    },";
    echo "    options: {";
    echo "        scales: {";
    echo "            y: {";
    echo "                beginAtZero: true";
    echo "            }";
    echo "        }";
    echo "    }";
    echo "});";
    echo "</script>";
}

// Function to generate forecasting
function generateForecasting($conn) {
    // Fetch average revenue
    $sql = "SELECT AVG(Amount) AS average_revenue FROM payment_history";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $average_revenue = $row["average_revenue"];

    // Predict next month's revenue based on average revenue
    $next_month_forecast = $average_revenue * 1.1; // Assume a 10% increase for forecasting

    // Display forecasting
    echo "<h2>Forecasting</h2>";
    echo "<p>Next Month Forecast: $next_month_forecast</p>";
}

// Function to perform analysis
function performAnalysis($conn) {
    // Perform analysis here based on business logic
    echo "<h2>Analysis</h2>";
    echo "<p>Analyze revenue data here for informed decision-making.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Revenue Management System</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>
<body>
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

    <?php
    // Generate reports
    generateFinancialReport($conn);
    generateRevenueTrendsChart($conn);
    generateForecasting($conn);
    performAnalysis($conn);

    // Close database connection
    $conn->close();
    ?>
</body>
</html>
