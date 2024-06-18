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
        
    </div>

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
