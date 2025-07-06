<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Easy Wallet</title>

    <?php
    //naming the server/password/and username
    $servername = "localhost";
    $username = "root"; //root is the default username for mysql
    $password = "";
    $database = "easy wallet"; // Note: database name contains a space, which may cause issues

    //connection
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>
    <link rel="stylesheet" href="style.css">
    
</head>
<body id="body">
    <button id="dark_mode" onclick= DarkMode()>Dark mode</button>


    <div id="headings">
        <h1 >Welcome to easy wallet</h1>
        <h3>Your one stop payment management system</h3> 
    </div>
    <div class="form container" id="container">
        <form method="POST" action="question2.php">
            <fieldset>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required placeholder="John Doe"><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="john.doe@work.com"><br><br>

                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required min="1" max="100000"><br><br>

                <select name="payment_method" id="payment">
                    <option value="💳Visa/Mastercard">💳Visa/Mastercard</option>
                    <option value="Paypal">Paypal</option>
                    <option value="🖥Cryptocurrency">🖥Cryptocurrency</option>
                </select><br><br>

                <label>Transaction Type:</label><br>
                <input type="radio" id="make_payment" name="transaction_type" value="make payment" required>
                <label for="make_payment">Make Payment</label><br>
                <input type="radio" id="request_refund" name="transaction_type" value="request refund" required>
                <label for="request_refund">Request Refund</label><br><br>

                <input type="submit" value="Proceed">
            </fieldset>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //this section takes in the data and puts it into a variable 
            $Name = $_POST["name"];
            $email = $_POST["email"];
            $amount = $_POST["amount"];
            $payment_method = $_POST["payment_method"];

            if ($amount >= 100000) {
                echo "<p style='color:red;  text-align:center;'>Payments or refunds of R100 000 are not allowed </p>";
            } else {
                // Check if user already exists
                $result = $conn->query("SELECT id FROM users WHERE name='$Name' AND email='$email'");

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc(); //creates and fetches the data from the associtive array that is created from the if and sql statemet
                    $user_id = $row['id'];
                } else {
                    // Insert new user
                    $sql = "INSERT INTO users (name,email,balance) VALUES ('$Name','$email','20000')";
                    $conn->query($sql);
                    $user_id = $conn->insert_id; // gives you the last created id aka the current users id 
                }

                $transaction_type = $_POST["transaction_type"];

                //amount update and transaction fees applied using nested if statemnets 
                if ($payment_method == "💳Visa/Mastercard") {
                    $transact_fee = 0.20;
                } else if ($payment_method == "Paypal") {
                    $transact_fee = 0.30;
                } else {
                    $transact_fee = 0.04;
                }
                $amount = $amount + ($amount * $transact_fee);;

                $sql_2 = "INSERT INTO transactions (user_id,amount,method,transaction_type)
                VALUES ('$user_id','$amount','$payment_method','$transaction_type')";
                $conn->query($sql_2);

                if ($transaction_type == 'make payment') {
                    $conn->query("UPDATE users SET balance = balance - $amount WHERE id = $user_id");
                    echo "<div class='confirmation'>";
                    echo "<p>✅Payment of: $amount has successfully been made.Thank you for using easy wallet!</p>";
                    echo "</div>";
                } else if ($transaction_type == 'request refund') {
                    $conn->query("UPDATE users SET balance = balance + $amount WHERE id = $user_id");
                    echo "<div class='confirmation'>";
                    echo "<p>✅Refund of: R$amount has successfully been processed.Thank you for using easy wallet!</p>";
                    echo "</div>";
                }
            }
        }
        ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
