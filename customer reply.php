<?php include_once("head.php");?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reply Page</title>
    <style>
        /* Style definitions here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0; /* Light gray background */
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
        }
        h1 {
            text-align: center;
            color: #02000d; /* Dark blue text */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0; /* Light gray border */
        }
        th {
            background-color: #07203f; /* Dark blue header background */
            color: #f0f0f0; /* White header text */
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Light gray row background */
            color: #02000d; /* Dark blue text */
        }
        tr:hover {
            background-color: #d9aa90; /* Light brown hover background */
        }
        .reply-btn, .delete-btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .reply-btn {
            background-color: #4CAF50; /* Green button background */
            color: #f0f0f0; /* White button text */
        }
        .delete-btn {
            background-color: #f44336; /* Red button background */
            color: #f0f0f0; /* White button text */
        }
        .reply-btn:hover, .delete-btn:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Soft glow effect */
        }
        .reply-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .reply-modal-content {
            background-color: #f0f0f0;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        textarea {
            width: 100%;
            height: 200px; /* Adjust the height as needed */
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }
        input[type="submit"] {
            background-color: #07203f;
            color: #f0f0f0;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #02000d;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            
        }
        input[type="text"] {
            height: 40px;
        }
        textarea {
           height: 200px;
           resize: none;
        }
    </style>
</head>
<body>
    <h1>Customer Messages</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Created At</th>
            <th>Reply</th>
            <th>Delete</th>
        </tr>
        <?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'], $_POST['email'], $_POST['admin_subject'], $_POST['admin_message'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $admin_subject = $_POST['admin_subject'];
        $admin_message = $_POST['admin_message'];

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sammuelryan4050@gmail.com';
            $mail->Password = 'kbxp hwbl icxk btux';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('sammuelryan4050@gmail.com', 'CRMS Management');
            $mail->addAddress($email, $name);

            $mail->isHTML(false);
            $mail->Subject = $admin_subject;
            $mail->Body = $admin_message;

            $mail->send();

            echo '<script>alert("Thank you for your message. We will get back to you shortly."); window.location.href = "customer reply.php";</script>';
            exit();
        } catch (Exception $e) {
            echo '<script>alert("Oops! Something went wrong. Please try again later.")</script>';
        }
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpayment";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $sql = "DELETE FROM contact WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_id);

        $param_id = $_POST['id'];

        if ($stmt->execute()) {
            echo "Record deleted successfully.";
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}

$sql = "SELECT * FROM contact";
$result = $conn->query($sql);

$counter = 1;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $counter++ . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["subject"] . "</td>";
        echo "<td>" . $row["message"] . "</td>";
        echo "<td>" . $row["created_at"] . "</td>";
        echo "<td><button class='reply-btn' onclick='openReplyModal(\"" . $row["email"] . "\", \"" . $row["name"] . "\", \"RESPONSE TO YOUR INQUIRY\")'>Reply</button></td>";
        echo "<td><button class='delete-btn' onclick='deleteRecord(" . $row["id"] . ")'>Delete</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No messages found</td></tr>";
}
$conn->close();
?>
    </table>

    <div id="replyModal" class="reply-modal">
        <div class="reply-modal-content">
            <span class="close" onclick="closeReplyModal()">&times;</span>
            <h2>Reply to Customer</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="replyForm" onsubmit="return validateForm();">
                <label for="replyEmail">Email:</label><br>
                <input type="text" name="email" id="replyEmail" required readonly> 
                
                <label for="replyName">Name:</label><br>
                <input type="text" name="name" id="replyName" required readonly> 
                
                <label for="replySubject">Subject:</label><br>
                <input type="text" name="admin_subject" id="replySubject" required> 
                
                <label for="replyMessage">Message:</label><br>
                <textarea id="replyMessage" name="admin_message" rows="5" cols="50"></textarea><br>
                
                <input type="submit" value="Send Reply">
                <p id="error" style="color: red; display: none;">Please enter a reply message.</p>
            </form>
        </div>
    </div>

    <script>
        function deleteRecord(id) {
            if (confirm("Are you sure you want to delete this record?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        window.location.reload();
                    }
                };
                xhr.send("id=" + id);
            }
        }

        function openReplyModal(email, name, subject) {
            var modal = document.getElementById("replyModal");
            modal.style.display = "block";
            document.getElementById("replyEmail").value = email;
            document.getElementById("replyName").value = name;
            document.getElementById("replySubject").value = subject;
            document.getElementById("replyMessage").value = "";
        }

        function closeReplyModal() {
            var modal = document.getElementById("replyModal");
            modal.style.display = "none";
            document.getElementById("replyForm").reset();
            document.getElementById("error").style.display = "none";
        }

        function validateForm() {
            var replyMessage = document.getElementById("replyMessage").value.trim();
            if (replyMessage === "") {
                document.getElementById("error").style.display = "block";
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
