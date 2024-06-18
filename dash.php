<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>County Revenue Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>County Revenue Management System</h1>
    </header>
    <nav>
        <ul>
            <li><a href="#dashboard" onclick="showSection('dashboard')">Dashboard</a></li>
            <li><a href="#revenue-breakdown" onclick="showSection('revenue-breakdown')">Revenue Breakdown</a></li>
            <li><a href="#trend-analysis" onclick="showSection('trend-analysis')">Trend Analysis</a></li>
            <!-- Add more navigation links as needed -->
        </ul>
    </nav>
    <main>
        <section id="dashboard" class="slide">
            <h2>Dashboard</h2>
            <?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpayment";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total amount from payment_history table
$sql = "SELECT SUM(amount) as total_amount FROM payment_history";
$result = $conn->query($sql);

$total_amount = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_amount = $row['total_amount'];
}
// Fetch user count from the users table
$user_sql = "SELECT COUNT(*) as user_count FROM users";
$user_result = $conn->query($user_sql);

$user_count = 0;
if ($user_result->num_rows > 0) {
    $user_row = $user_result->fetch_assoc();
    $user_count = $user_row['user_count'];
}
// Fetch data from payment_history table
$sql = "SELECT business_type, SUM(amount) AS total FROM payment_history GROUP BY business_type";
$result = $conn->query($sql);

// Array to store data for bar graph
$bar_graph_data = array();

if ($result->num_rows > 0) {
    // Fetching data and adding to the bar graph data array
    while ($row = $result->fetch_assoc()) {
        $bar_graph_data[] = $row;
    }
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            background-color: #f8f9fa;
        }
        h1 {
            color: #4CAF50;
        }
        .counters {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .counter {
            font-size: 3em;
            color: #4CAF50;
            margin-bottom: 20px;
            transition: transform 0.5s, color 1s;
        }
        .counter:hover {
            transform: scale(1.1);
        }
        .counter-value {
            animation: blink 1s infinite alternate;
        }
        .counter-title {
            color: #999;
            font-size: 1em;
            transition: font-size 0.5s;
        }
        .counters:hover .counter-title {
            font-size: 0.8em;
        }
        .breakdown {
            margin-top: 30px;
            text-align: center;
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        .breakdown h2 {
            color: #555;
            margin-bottom: 10px;
        }
        .breakdown table {
            width: 100%;
            border-collapse: collapse;
        }
        .breakdown th, .breakdown td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .breakdown th {
            background-color: #f0f0f0;
            color: #333;
        }
        .breakdown td a {
            cursor: pointer;
            color: #007bff;
            text-decoration: none;
        }
        .breakdown td a:hover {
            text-decoration: underline;
        }
        .sector-details {
            display: none;
            margin-top: 30px;
            text-align: center;
        }
        .sector-details h2 {
            color: #555;
            margin-bottom: 10px;
        }
        .sector-details table {
            width: 80%;
            margin: 0 auto;
            margin-top: 20px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .sector-details th, .sector-details td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .sector-details .diagram {
            margin-top: 20px;
            width: 80%;
            height: 200px;
            background-color: #fff;
            margin: 0 auto;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2em;
            color: #555;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .bar-graph-container {
            display: flex;
            justify-content: space-evenly;
            align-items: flex-end;
            margin-top: 20px;
            height: 300px; /* Adjust height as needed */
        }
        .bar {
            background-color: #4CAF50;
            margin-bottom: 10px; /* Adjust spacing between bars */
            text-align: center;
            flex-grow: 1;
        }
        .bar-label {
            margin-top: 5px; /* Adjust vertical position of labels */
        }  
       
    </style>
</head>
<body>
<h1>Budget Management</h1>
    <div class="counters">
        <div class="counter">
            <h2>Total Amount</h2>
            <div id="counter">0</div>
        </div>
        <div class="counter">
            <h2>User Count</h2>
            <div id="user-counter">0</div>
        </div>
    </div>
    <!-- Bar graph section -->
    <div class="bar-graph-container" id="bar-graph"></div>

    <div class="breakdown">
        <h2>Budget Breakdown</h2>
        <table>
            <tr>
                <th>Sector</th>
                <th>Percentage</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td><a onclick="showSectorDetails('Government Budgets', governmentBudgetsDetails)">Government Budgets</a></td>
                <td>30%</td>
                <td id="government-budgets">0</td>
            </tr>
            <tr>
                <td><a onclick="showSectorDetails('Public Services', publicServicesDetails)">Public Services</a></td>
                <td>20%</td>
                <td id="public-services">0</td>
            </tr>
            <tr>
                <td><a onclick="showSectorDetails('Infrastructure Development', infrastructureDevelopmentDetails)">Infrastructure Development</a></td>
                <td>15%</td>
                <td id="infrastructure-development">0</td>
            </tr>
            <tr>
                <td><a onclick="showSectorDetails('Economic Development', economicDevelopmentDetails)">Economic Development</a></td>
                <td>10%</td>
                <td id="economic-development">0</td>
            </tr>
            <tr>
                <td><a onclick="showSectorDetails('Debt Servicing', debtServicingDetails)">Debt Servicing</a></td>
                <td>10%</td>
                <td id="debt-servicing">0</td>
            </tr>
            <tr>
                <td><a onclick="showSectorDetails('Reserves and Contingencies', reservesContingenciesDetails)">Reserves and Contingencies</a></td>
                <td>10%</td>
                <td id="reserves-contingencies">0</td>
            </tr>
            <tr>
                <td><a onclick="showSectorDetails('Public Investments', publicInvestmentsDetails)">Public Investments</a></td>
                <td>5%</td>
                <td id="public-investments">0</td>
            </tr>
        </table>
    </div>

    <div class="sector-details" id="sector-details">
        <h2 id="sector-title">Sector Details</h2>
        <table id="sector-table">
            <!-- Details will be populated here -->
        </table>
        

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        // Bar graph data from PHP
        const barGraphData = <?php echo json_encode($bar_graph_data); ?>;
        drawBarGraph(barGraphData);

        // Your existing JavaScript code
    });
    function drawBarGraph(data) {
        const barGraphContainer = document.getElementById('bar-graph');

        // Find maximum total for scaling
        const maxTotal = Math.max(...data.map(item => item.total));

        // Iterate through data to draw bars
        data.forEach(item => {
            const bar = document.createElement('div');
            bar.classList.add('bar');
            bar.style.height = (item.total / maxTotal * 100) + '%'; // Scale height based on percentage
            bar.innerHTML = item.business_type;
            
            const label = document.createElement('div');
            label.classList.add('bar-label');
            label.textContent = item.total.toLocaleString(); // Add commas to format the number

            bar.appendChild(label);
            barGraphContainer.appendChild(bar);
        });
    }


    function drawBarGraph(data) {
    const barGraphContainer = document.getElementById('bar-graph');
    const barGraphWidth = 800; // Adjust according to your preference
    const barGraphHeight = 300; // Adjust according to your preference

    // Find maximum total for scaling
    const maxTotal = Math.max(...data.map(item => item.total));
    const barWidth = barGraphWidth / data.length;

    barGraphContainer.innerHTML = '';

    // Iterate through data to draw bars
    data.forEach(item => {
        const bar = document.createElement('div');
        bar.style.width = barWidth + 'px';
        bar.style.height = (item.total / maxTotal * barGraphHeight) + 'px';
        bar.style.backgroundColor = '#4CAF50'; // You can set custom colors if needed
        bar.style.display = 'inline-block';
        bar.style.marginRight = '5px'; // Adjust spacing between bars
        bar.style.position = 'relative'; // Add positioning

        const label = document.createElement('div');
        label.textContent = item.business_type;
        label.style.textAlign = 'center';
        label.style.marginTop = '5px'; // Adjust vertical position of labels

        const value = document.createElement('div'); // New element for displaying value
        value.textContent = item.total.toLocaleString(); // Display calculated value
        value.style.position = 'absolute'; // Position above the bar
        value.style.top = '-20px'; // Adjust position above the bar
        value.style.left = '0'; // Center horizontally
        value.style.width = '100%';
        value.style.textAlign = 'center';
        value.style.fontSize = '0.8em'; // Adjust font size

        bar.appendChild(label);
        bar.appendChild(value); // Append the value element to the bar
        barGraphContainer.appendChild(bar);
    });
}
        
        document.addEventListener("DOMContentLoaded", function() {
            let totalAmount = <?php echo $total_amount; ?>;
            animateCounter(totalAmount);
            distributeAmounts(totalAmount);

            let userCount = <?php echo $user_count; ?>;
            animateUserCounter(userCount);
        });

        function animateCounter(target) {
            let counter = document.getElementById('counter');
            let count = 0;
            let step = Math.ceil(target / 100);

            let interval = setInterval(() => {
                count += step;
                if (count >= target) {
                    count = target;
                    clearInterval(interval);
                    counter.classList.remove('counter-value');
                }
                counter.innerText = count.toLocaleString();
            }, 20);
        }

        function animateUserCounter(target) {
            let userCounter = document.getElementById('user-counter');
            let count = 0;
            let step = Math.ceil(target / 100);

            let interval = setInterval(() => {
                count += step;
                if (count >= target) {
                    count = target;
                    clearInterval(interval);
                    userCounter.classList.remove('counter-value');
                }
                userCounter.innerText = count.toLocaleString();
            }, 20);
        }

        function distributeAmounts(total) {
            const sectors = {
                "government-budgets": 0.30,
                "public-services": 0.20,
                "infrastructure-development": 0.15,
                "economic-development": 0.10,
                "debt-servicing": 0.10,
                "reserves-contingencies": 0.10,
                "public-investments": 0.05
            };
            for (let sector in sectors) {
                let amount = total * sectors[sector];
                document.getElementById(sector).innerText = amount.toLocaleString();
            }
        }

        function showSectorDetails(title, details) {
            document.getElementById('sector-title').innerText = title + ' Details';
            const table = document.getElementById('sector-table');
            table.innerHTML = '';

            details.forEach(item => {
                let row = table.insertRow();
                let cell1 = row.insertCell(0);
                let cell2 = row.insertCell(1);
                cell1.innerHTML = item[0];
                cell2.innerHTML = item[1];
            });

            document.getElementById('sector-details').style.display = 'block';
        }

        const totalAmount = <?php echo $total_amount; ?>;

const governmentBudgetsDetails = [
    ["Defense and Security", calculateAmount(totalAmount, 0.05)],
    ["Healthcare", calculateAmount(totalAmount, 0.05)],
    ["Education", calculateAmount(totalAmount, 0.05)],
    ["Social Welfare", calculateAmount(totalAmount, 0.05)],
    ["Administrative Costs", calculateAmount(totalAmount, 0.04)],
    ["Environmental Protection", calculateAmount(totalAmount, 0.03)],
    ["Research and Development", calculateAmount(totalAmount, 0.03)]
];

const publicServicesDetails = [
    ["Healthcare Services", calculateAmount(totalAmount, 0.05)],
    ["Educational Services", calculateAmount(totalAmount, 0.04)],
    ["Public Safety", calculateAmount(totalAmount, 0.03)],
    ["Sanitation and Waste Management", calculateAmount(totalAmount, 0.03)],
    ["Public Transportation", calculateAmount(totalAmount, 0.02)],
    ["Housing Services", calculateAmount(totalAmount, 0.02)],
    ["Community Services", calculateAmount(totalAmount, 0.01)]
];

const infrastructureDevelopmentDetails = [
    ["Transportation Infrastructure", calculateAmount(totalAmount, 0.05)],
    ["Utility Infrastructure", calculateAmount(totalAmount, 0.03)],
    ["Communication Infrastructure", calculateAmount(totalAmount, 0.02)],
    ["Public Buildings", calculateAmount(totalAmount, 0.02)],
    ["Energy Infrastructure", calculateAmount(totalAmount, 0.02)],
    ["Urban Development", calculateAmount(totalAmount, 0.01)]
];

const economicDevelopmentDetails = [
    ["Business Incentives", calculateAmount(totalAmount, 0.03)],
    ["Workforce Development", calculateAmount(totalAmount, 0.02)],
    ["Innovation and Technology", calculateAmount(totalAmount, 0.02)],
    ["Trade and Export Promotion", calculateAmount(totalAmount, 0.01)],
    ["Rural Development", calculateAmount(totalAmount, 0.01)],
    ["Tourism Development", calculateAmount(totalAmount, 0.01)]
];

const debtServicingDetails = [
    ["Interest Payments", calculateAmount(totalAmount, 0.04)],
    ["Principal Repayment", calculateAmount(totalAmount, 0.03)],
    ["Debt Refinancing", calculateAmount(totalAmount, 0.01)],
    ["Emergency Debt Relief", calculateAmount(totalAmount, 0.01)],
    ["Sinking Funds", calculateAmount(totalAmount, 0.01)]
];

const reservesContingenciesDetails = [
    ["Emergency Fund", calculateAmount(totalAmount, 0.03)],
    ["Economic Stabilization Fund", calculateAmount(totalAmount, 0.02)],
    ["Budgetary Reserves", calculateAmount(totalAmount, 0.02)],
    ["Insurance Funds", calculateAmount(totalAmount, 0.02)],
    ["Contingency Planning", calculateAmount(totalAmount, 0.01)]
];

const publicInvestmentsDetails = [
    ["Green Investments", calculateAmount(totalAmount, 0.02)],
    ["Technology and Innovation", calculateAmount(totalAmount, 0.01)],
    ["Social Enterprises", calculateAmount(totalAmount, 0.01)],
    ["Cultural Projects", calculateAmount(totalAmount, 0.005)],
    ["Infrastructure Bonds", calculateAmount(totalAmount, 0.005)]
];

function calculateAmount(total, percentage) {
    return (total * percentage).toFixed(2);
}
    </script>
</body>
</html>
</section>
<section id="revenue-breakdown" class="slide">

    <div id="revenue-details">
        <h3>Revenue Trends</h3>
        <?php
// Database connection parameters
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "bpayment";

// Establish the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch monthly revenue trend
$trend_sql = "
    SELECT DATE_FORMAT(date, '%Y-%m') AS month, SUM(amount) AS total
    FROM payment_history
    GROUP BY month
    ORDER BY month
";
$trend_result = $conn->query($trend_sql);

$monthly_revenue_trend = [];
if ($trend_result->num_rows > 0) {
    while ($row = $trend_result->fetch_assoc()) {
        $monthly_revenue_trend[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payments Breakdown Graph</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    #container {
      width: 100%;
      max-width: 1200px; /* Changed to make it responsive */
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
    }
    #paymentGraph {
      margin: 20px auto;
      width: 100%; /* Changed to make it responsive */
      max-width: 800px; /* Changed to make it responsive */
      height: 400px; /* Reduced height for better display */
    }
    #breakdownText {
      text-align: center;
      margin-top: 20px;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div id="container">
  <h1>Payments Breakdown Graph</h1>

  <select id="timeUnit">
    <option value="years">Years</option>
    <option value="months">Months</option>
    <option value="weeks">Weeks</option>
    <option value="days">Days</option>
  </select>

  <canvas id="paymentGraph"></canvas>

  <div id="breakdownText"></div>
</div>

<script>
  // Event listener for time unit dropdown change
  document.getElementById('timeUnit').addEventListener('change', function() {
    var selectedUnit = this.value;
    fetch('?unit=' + selectedUnit)
      .then(response => response.json())
      .then(data => {
        updateGraph(selectedUnit, data.labels, data.data);
      })
      .catch(error => console.error('Error fetching data:', error));
  });

  // Function to update the graph
  function updateGraph(unit, labels, data) {
    // Update chart
    var ctx = document.getElementById('paymentGraph').getContext('2d');
    if (window.paymentChart) {
      window.paymentChart.destroy();
    }
    window.paymentChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Number of Payments',
          data: data,
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          pointRadius: 4,
          pointBackgroundColor: 'rgba(54, 162, 235, 1)',
          pointBorderColor: '#fff',
          pointHoverRadius: 6,
          pointHoverBackgroundColor: 'rgba(54, 162, 235, 1)',
          pointHoverBorderColor: '#fff'
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

    // Update breakdown text
    document.getElementById('breakdownText').innerText = 'Breakdown by ' + unit;
  }

  // Initial graph update
  document.addEventListener('DOMContentLoaded', function() {
    var selectedUnit = document.getElementById('timeUnit').value;
    fetch('?unit=' + selectedUnit)
      .then(response => response.json())
      .then(data => {
        updateGraph(selectedUnit, data.labels, data.data);
      })
      .catch(error => console.error('Error fetching data:', error));
  });
</script>

<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "bpayment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data based on the selected time unit
$unit = isset($_GET['unit']) ? $_GET['unit'] : 'years';

$sql = "";
if ($unit === 'years') {
    $sql = "SELECT YEAR(date) AS year, COUNT(*) AS count FROM payment_history GROUP BY YEAR(date)";
} else if ($unit === 'months') {
    $sql = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, COUNT(*) AS count FROM payment_history GROUP BY DATE_FORMAT(date, '%Y-%m')";
} else if ($unit === 'weeks') {
    $sql = "SELECT CONCAT(YEAR(date), '-W', WEEK(date)) AS week, COUNT(*) AS count FROM payment_history GROUP BY CONCAT(YEAR(date), '-W', WEEK(date))";
} else { // Days
    $sql = "SELECT DATE(date) AS day, COUNT(*) AS count FROM payment_history GROUP BY DATE(date)";
}

$result = $conn->query($sql);

$labels = array();
$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($labels, $row['year'] ?? $row['month'] ?? $row['week'] ?? $row['day']);
        array_push($data, $row['count']);
    }
}

$conn->close();

// Prepare data to be sent as JSON
$response = array(
    'labels' => $labels,
    'data' => $data
);
?>

</body>
</html>


</section>

<section id="trend-analysis" class="slide">
    <h2 class="section-heading">Trend Analysis</h2>
    <div id="trend-details" class="details-container">
        <h3 class="sub-heading">Trend Analysis Details</h3>
        <div class="analysis-details">
            <ol class="analysis-list">
                <li>
                    <strong>Introduction:</strong>
                    <p>The purpose of this trend analysis is to examine revenue trends within a revenue management establishment over a specified time period. The analysis aims to identify patterns, fluctuations, and trends in revenue data to inform strategic decision-making.</p>
                </li>
                <li>
                    <strong>Data Overview:</strong>
                    <p>The dataset includes monthly revenue data for the past five years (2019-2023), sourced from internal sales records. Data preprocessing involved cleaning outliers and missing values to ensure accuracy and reliability.</p>
                </li>
                <li>
                    <strong>Data Exploration:</strong>
                    <p>Visual analysis of the revenue data reveals a steady upward trend in revenue over the five-year period, with seasonal fluctuations evident. Monthly revenue data is plotted on a line graph, showing a clear increasing trend with periodic peaks and valleys.</p>
                </li>
                <li>
                    <strong>Trend Identification:</strong>
                    <p>The primary trend identified is overall revenue growth, indicating a positive trajectory for the establishment. Seasonal trends are also evident, with revenue typically peaking during holiday seasons and experiencing dips during off-peak periods.</p>
                </li>
                <li>
                    <strong>Trend Analysis:</strong>
                    <p>The sustained revenue growth suggests successful revenue management strategies and effective marketing efforts. Seasonal trends may be attributed to factors such as consumer spending patterns, promotional campaigns, and external economic conditions.</p>
                </li>
                <li>
                    <strong>Key Findings:</strong>
                    <p>Revenue has consistently increased year-over-year, reflecting the establishment's strong performance and market demand. Seasonal fluctuations highlight the importance of adjusting pricing strategies and marketing initiatives to capitalize on peak periods.</p>
                </li>
                <li>
                    <strong>Recommendations:</strong>
                    <p>Capitalize on peak seasons by offering targeted promotions, packages, and incentives to drive sales. Implement dynamic pricing strategies to optimize revenue during high-demand periods while maintaining competitiveness during off-peak times.</p>
                </li>
                <li>
                    <strong>Future Considerations:</strong>
                    <p>Monitor emerging market trends, consumer preferences, and competitive landscape to anticipate future revenue opportunities and challenges. Explore opportunities for diversification and expansion into new markets or revenue streams to sustain long-term growth.</p>
                </li>
                <li>
                    <strong>Conclusion:</strong>
                    <p>The trend analysis underscores the establishment's robust revenue performance and highlights opportunities for further optimization. Strategic planning informed by data-driven insights will be instrumental in maintaining revenue growth and competitive advantage in the future.</p>
                </li>
                <li>
                    <strong>References:</strong>
                    <p>Internal sales records and financial reports. Industry reports on revenue management best practices and market trends.</p>
                </li>
            </ol>
        </div>
    </div>
</section>

        <!-- Add more sections for other functionalities -->
    </main>
    <footer>
        <p>&copy; 2024 County Revenue Management System</p>
    </footer>
    <script src="script.js"></script>
    <script>
        // Function to show the selected section and hide others
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.slide');
            sections.forEach(section => {
                if (section.id === sectionId) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        }

        // JavaScript for countdown
        const totalRevenue = 10000;
        const totalUsers = 10000;
        const revenueCounter = document.getElementById('revenue-counter');
        const usersCounter = document.getElementById('users-counter');

        function updateCountdown() {
            let revenueCount = 0;
            let usersCount = 0;
            const interval = setInterval(() => {
                if (revenueCount <= totalRevenue) {
                    revenueCounter.textContent = numberWithCommas(revenueCount);
                    revenueCount += Math.ceil(totalRevenue / 1000); // Increment smoothly
                    if (revenueCount >= totalRevenue) {
                        clearInterval(interval);
                        animateCountdown(revenueCounter);
                    }
                }
                if (usersCount <= totalUsers) {
                    usersCounter.textContent = numberWithCommas(usersCount);
                    usersCount += Math.ceil(totalUsers / 1000); // Increment smoothly
                    if (usersCount >= totalUsers) {
                        clearInterval(interval);
                        animateCountdown(usersCounter);
                    }
                }
            }, 10);
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function animateCountdown(counter) {
            counter.style.fontSize = '2em'; // Increase font size
            counter.style.color = 'red'; // Change color
            counter.style.transition = 'font-size 1s, color 1s'; // Apply transition effect
        }

        updateCountdown();
    </script>
</body>
<style>
    /* styles.css */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 20px 0;
    }

    nav ul {
        list-style-type: none;
        padding: 0;
        text-align: center;
    }

    nav ul li {
        display: inline;
        margin-right: 20px;
    }

    nav ul li a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    nav ul li a:hover {
        color: #999;
    }

    main {
        padding: 20px;
    }

    .slide {
        display: none;
        animation: slideIn 0.5s ease forwards;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    footer {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 20px 0;
    }

    /* Additional styles for countdowns */
    #countdowns {
        display: flex;
        justify-content: space-between;
    }

    #revenue-countdown,
    #users-countdown {
        flex: 1;
    }

    @media (max-width: 768px) {
        #countdowns {
            flex-direction: column;
        }
    }
</style>
</html>
