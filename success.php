<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <script>
        // Function to redirect after 3 seconds
        function redirect() {
            setTimeout(function() {
                window.location.href = "home.php";
            }, 3000); // 3000 milliseconds = 3 seconds
        }

        // Call redirect function when the page loads
        window.onload = function() {
            redirect();
        };
    </script>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <!-- Add any other content or styling as needed -->
</body>
</html>
