<?php include_once("head.php"); ?>

<div class="row">
    <div class="col-md-12">
        <h2>Revenue</h2>
        <h5>Make Payment</h5>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        <?php
        if (isset($_POST['go'])) {
            extract($_POST);
            $p = new payment();
            $status = "Pending Approval";
            $p->create_payment($business_name, $date, $Period, $status, $Amount, $payment_method, $business_type);
            
            // Redirect based on payment method
            $confirmationMessage = "Do you wish to proceed with this payment?";
            // Display JavaScript confirmation dialog
            echo "<script>if(confirm('$confirmationMessage')) {
                      window.location.href='" . ($payment_method === 'Mpesa' ? 'mpesa-page.php' : 'stripe.php') . "';
                  } else {
                      window.location.href='home.php';
                  }</script>";
        
                             
            // Execute the API here after payment creation
            $apiURL = "";
            $apiKey = "abc1232";
            $apiSecret = "were3qa";

            // Construct the API request parameters
            $apiParams = http_build_query([
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'service_id' => 'update-payment', // Specify the service ID for adding payment
                'business_name' => $business_name,
                'date' => $date,
                'period' => $Period,
                'status' => $status
                // Add more parameters as needed
            ]);

            // Execute the API request
            $apiResponse = file_get_contents($apiURL . '?' . $apiParams);
            $apiFeedback = json_decode($apiResponse, true);

            // Handle API feedback as needed
            // For example, you can log API response or display a message to the user
            if ($apiFeedback['status'] == 200) {
                echo "<script>alert('Payment was sent.'); window.location.href='make-payment.php';</script>";
            } else {
                echo "<script>alert('Error: Payment was not sent.');</script>";
            }
        }
        ?>

        <form method="POST">
            <div class="row form-group">
                <div class="col-12 col-sm-4">Business Name</div>
                <div class="col-12 col-sm-9">
                    <input type="text" name="business_name" value="<?php echo $_SESSION['user_details']['business_name']; ?>" class="form-control" required >
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Category</div>
                <div class="col-12 col-sm-9">
                    <select name="business_type" class="form-control" required onchange="updateAmount(this.value)">
                        <option value="Option 0">choose your category</option>
                        <option value="Option 1">Event Centers</option>
                        <option value="Option 2">Fines and Penalties</option>
                        <option value="Option 3">Market Levy</option>
                        <option value="Option 4">Security</option>
                        <option value="Option 5">Waste Management</option>
                        <option value="Option 6">fees and permits</option>
                        <option value="Option 7">Express Way</option>
                    </select>
                </div>
</div>


            <div class="row form-group">
                <div class="col-12 col-sm-4">Date of Payment</div>
                <div class="col-12 col-sm-9">
                    <input type="date" name="date" class="form-control" required>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Period</div>
                <div class="col-12 col-sm-9">
                    <select name="Period" class="form-control" required>
                        <option value="">Select Period</option>
                        <option value="2023/2024">2023/2024</option>
                        <option value="2024/2025">2024/2025</option>
                        <option value="2025/2026">2025/2026</option>
                    </select>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Payment Method</div>
                <div class="col-12 col-sm-9">
                    <select name="payment_method" class="form-control" required>
                        <option value="">Select mode of payment</option>
                        <option value="Mpesa">Mpesa</option>
                        <option value="Credit Card">Credit</option>
                    </select>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Amount Paid (KES)</div>
                <div class="col-12 col-sm-9">
                    <input type="number" name="Amount" id="amount" class="form-control" required>
                    
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Phone Number</div>
                <div class="col-12 col-sm-9">
                    <input type="tel" name="phone_number" class="form-control" required>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Email</div>
                <div class="col-12 col-sm-9">
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>

            <button type="submit" name="go" class="btn btn-primary btn-lg btn-block">SUBMIT PAYMENT</button>
        </form>
    </div>
</div>
<script>
    function updateAmount(businessType) {
        var amountField = document.getElementById('amount');
        switch (businessType) {
            case 'Option 1':
                amountField.value = '10000'; // Set the amount based on the business type
                break;
            case 'Option 2':
                amountField.value = '5000';
                break;
            case 'Option 3':
                amountField.value = '3000';
                break;
            case 'Option 4':
                amountField.value = '2000';
                break;
            case 'Option 5':
                amountField.value = '5000';
                break;
            case 'Option 6':
                amountField.value = '12000';
                break;
            case 'Option 7':
                amountField.value = '6000';
                break;
           
        }
    }
</script>

<?php include_once("ffoot.php"); ?>