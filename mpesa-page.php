<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lipa na Mpesa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap');

        body {
            background-color: #f5f5dc; /* Light goldenrod yellow */
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 310px;
            border: none;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .mpesa {
            background-color: #009688; /* Dark cyan */
            color: #fff;
            border-radius: 20px;
            padding: 5px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .mpesa:hover {
            background-color: #4caf50; /* Green */
        }

        img {
            border-radius: 15px;
        }

        .form-label, .form-control {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .btn-success {
            background: linear-gradient(45deg, #009688, #4caf50); /* Dark cyan to green */
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            letter-spacing: 1px;
            opacity: 0.8;
        }

        h6 {
            font-size: 15px;
        }

        .mt-0 {
            margin-top: 0 !important;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card mt-5">
        <div class="d-flex flex-row justify-content-around">
            <div class="mpesa"><span>Mpesa Payment </span></div>
        </div>
        <div class="media mt-4 pl-2">
            <img src="mpesa.png" class="mr-3" height="75" alt="Mpesa Logo">
            <div class="media-body">
                <h6 class="mt-0">Enter Amount & Number to proceed with payment</h6>
            </div>
        </div>
        <div class="media mt-3 pl-2">
            <form class="row g-3" action="./stk_initiate.php" method="POST">
                <div class="col-12">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Amount">
                </div>
                <div class="col-12">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success" name="submit" value="submit">Make Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
