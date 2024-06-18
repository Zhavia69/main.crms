<?php 
include_once("head.php");

// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpayment";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user's email and role from the session
$email = $_SESSION['user_details']['username'];
$role = $_SESSION['user_details']['role'];

// Pagination
$records_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Determine the SQL query based on the user's role
if ($role == 'client') {
    $sql = "SELECT * FROM payment_history WHERE email = '$email' LIMIT $start_from, $records_per_page;";
} else {
    $sql = "SELECT * FROM payment_history LIMIT $start_from, $records_per_page;";
}
$result = $conn->query($sql);

// Search functionality for search term
$search_term = "";

if (isset($_POST['search_term_submit'])) {
    $search_term = $_POST['search_term'];
    
    if (!empty($search_term)) {
        if ($role == 'client') {
            $sql = "SELECT * FROM payment_history WHERE email = '$email' AND (business_name LIKE '%$search_term%' OR business_type LIKE '%$search_term%' OR period LIKE '%$search_term%' OR date LIKE '%$search_term%' OR amount LIKE '%$search_term%' OR payment_method LIKE '%$search_term%') LIMIT $start_from, $records_per_page";
        } else {
            $sql = "SELECT * FROM payment_history WHERE (business_name LIKE '%$search_term%' OR business_type LIKE '%$search_term%' OR period LIKE '%$search_term%' OR date LIKE '%$search_term%' OR amount LIKE '%$search_term%' OR payment_method LIKE '%$search_term%') LIMIT $start_from, $records_per_page";
        }
        $result = $conn->query($sql);
    }
}

// Search functionality for date range
$start_date = "";
$end_date = "";

if (isset($_POST['date_submit'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    if (!empty($start_date) && !empty($end_date)) {
        if ($role == 'client') {
            $sql = "SELECT * FROM payment_history WHERE email = '$email' AND date BETWEEN '$start_date' AND '$end_date' LIMIT $start_from, $records_per_page";
        } else {
            $sql = "SELECT * FROM payment_history WHERE date BETWEEN '$start_date' AND '$end_date' LIMIT $start_from, $records_per_page";
        }
        $result = $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Home Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Custom CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .form-inline {
            display: flex;
            align-items: center;
        }
        .form-inline .form-group {
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        h1 {
            text-align: center;
            color: #02000d; /* Dark blue text */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<h1>User Payments</h1>
<div class="container">
    
    <div class="row">
        <form action="" method="post" class="form-inline">
            <div class="form-group">
                <label for="search_term">Search:</label>
                <input type="text" name="search_term" id="search_term" class="form-control" value="<?php echo $search_term; ?>" placeholder="Enter search term">
            </div>
            <button type="submit" name="search_term_submit" class="btn btn-primary">Search</button>
        </form>
        
        <form action="" method="post" class="form-inline">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo $start_date; ?>">
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $end_date; ?>">
            </div>
            <button type="submit" name="date_submit" class="btn btn-primary">Search by Date</button>
        </form>
    </div>
</div>

</body>
</html>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Serial.no</th>
                    <th>Business name</th>
                    <th>Business Type</th>
                    <th>Period</th>
                    <th>Date</th>
                    <th>Amount (KES)</th>
                    <th>Payment Method</th>
                    <th>Action</th>
                    <?php if ($role == 'admin') { ?>
                        <th class='Delete'><i class='fa fa-trash'></i> Delete</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            <?php 
               if ($result->num_rows > 0) {
                   // Initialize the serial number
                   $serial_number = ($page - 1) * $records_per_page + 1;
                   while($payment = $result->fetch_assoc()) {
               ?>
                    <tr>
                        <td><?php echo $serial_number; ?></td>
                        <td><?php echo str_pad($payment['id'], 5, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo $payment['business_name']; ?></td>
                        <td><?php echo $payment['business_type']; ?></td>
                        <td><?php echo $payment['period']; ?></td>
                        <td><?php echo $payment['date']; ?></td>
                        <td><?php echo $payment['amount']; ?></td>
                        <td><?php echo $payment['payment_method']; ?></td>
                        <td>
                            <form action="receipt.php" method="post" target="_blank">
                                <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                <input type="hidden" name="business_name" value="<?php echo $payment['business_name']; ?>">
                                <input type="hidden" name="business_type" value="<?php echo $payment['business_type']; ?>">
                                <input type="hidden" name="period" value="<?php echo $payment['period']; ?>">
                                <input type="hidden" name="date" value="<?php echo $payment['date']; ?>">
                                <input type="hidden" name="amount" value="<?php echo $payment['amount']; ?>">
                                <input type="hidden" name="payment_method" value="<?php echo $payment['payment_method']; ?>">
                                <button type="submit" class="btn btn-info btn-sm">View Receipt</button>
                            </form>
                           </td>
                       
                       <?php if ($role == 'admin') { ?>
                           <td class='Delete'>
                               <a href='all-payment.php?id=<?php echo $payment['id']; ?>' class='btn btn-danger'>
                                   <i class='fa fa-trash'></i> Delete
                               </a>
                           </td>
                       <?php } ?>
                   </tr>
               <?php 
                   // Increment the serial number
                   $serial_number++;
                  }
              } else {
                  echo "<tr><td colspan='" . ($role == 'admin' ? '10' : '9') . "'>No payments found</td></tr>";
              }
              ?>
           </tbody>
       </table>
   </div>
</div>

<div class="row">
   <div class="col-md-12">
       <?php
       // Previous and Next links
       if (isset($search_term) && !empty($search_term)) {
           $count_sql = "SELECT COUNT(*) AS total FROM payment_history WHERE (business_name LIKE '%$search_term%' OR business_type LIKE '%$search_term%' OR period LIKE '%$search_term%' OR date LIKE '%$search_term%' OR amount LIKE '%$search_term%' OR payment_method LIKE '%$search_term%')";
           if ($role == 'client') {
               $count_sql .= " AND email = '$email'";
           }
       } elseif (!empty($start_date) && !empty($end_date)) {
           $count_sql = "SELECT COUNT(*) AS total FROM payment_history WHERE date BETWEEN '$start_date' AND '$end_date'";
           if ($role == 'client') {
               $count_sql .= " AND email = '$email'";
           }
       } else {
           $count_sql = "SELECT COUNT(*) AS total FROM payment_history";
           if ($role == 'client') {
               $count_sql .= " WHERE email = '$email'";
           }
       }

       $count_result = $conn->query($count_sql);
       $row = $count_result->fetch_assoc();
       $total_pages = ceil($row["total"] / $records_per_page);

       if ($page > 1) {
           echo "<a href='?page=" . ($page - 1) . "' class='previous'>&laquo; Previous</a>";
       }
       
       // Display page numbers between Previous and Next
       for ($i = 1; $i <= $total_pages; $i++) {
           if ($i == $page) {
               echo "<a href='?page=$i' class='active'>$i</a>";
           } else {
               echo "<a href='?page=$i'>$i</a>";
           }
       }
       
       if ($page < $total_pages) {
           echo "<a href='?page=" . ($page + 1) . "' class='next'>Next &raquo;</a>";
       } elseif ($total_pages == 0) {
           echo "No records found.";
       } else {
           echo "You have reached the end.";
       }
       ?>
   </div>
</div>

<?php 
$conn->close();
include_once("foot.php"); 
?>
<style>
a {
 text-decoration: none;
 display: inline-block;
 padding: 8px 16px;
}

a:hover {
 background-color: #ddd;
 color: black;
}

.previous {
 background-color: #f1f1f1;
 color: black;
}

.next {
 background-color: #04AA6D;
 color: white;
}

.round {
 border-radius: 50%;
}
</style>

</body>
</html>
