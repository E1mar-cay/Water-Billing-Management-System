<?php
include 'db.php'; // Include your database connection file

// Initialize variables for filtering by default to current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Handle form submission for month and year filter
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentMonth = $_POST['month'];
    $currentYear = $_POST['year'];
}

// Fetch all active clients
$sql = "SELECT * FROM client_list WHERE status = 1";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <title>Clients and Billings Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
            background-image: url('water2.jpg');
            /* Adjust background properties */
            background-size: cover;
            background-repeat: no-repeat;
        }
        .container {
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff85;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.53);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .filter-form {
            margin-bottom: 20px;
        }
        .filter-form label, .filter-form select, .filter-form button {
            margin-right: 10px;
        }
        .print-link {
            display: inline-block;
            padding: 5px 10px;
            background-color: #4FCFC8;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .print-link:hover {
            background-color: #007770;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Clients and Billings Information</h2>
    <a href="index.php">Back to Dashboard</a>

    <!-- Month and Year Filter Form -->
    <form method="post" class="filter-form">
        <label for="month">Select Month:</label>
        <select name="month" id="month">
            <?php
            // Generate options for months
            for ($m = 1; $m <= 12; $m++) {
                $monthName = date("F", mktime(0, 0, 0, $m, 1));
                $selected = ($m == $currentMonth) ? 'selected' : '';
                echo "<option value='$m' $selected>$monthName</option>";
            }
            ?>
        </select>
        <label for="year">Select Year:</label>
        <select name="year" id="year">
            <?php
            // Generate options for years (you may adjust the range as needed)
            $startYear = date('Y') - 5;
            $endYear = date('Y') + 5;
            for ($y = $startYear; $y <= $endYear; $y++) {
                $selected = ($y == $currentYear) ? 'selected' : '';
                echo "<option value='$y' $selected>$y</option>";
            }
            ?>
        </select>
        <button type="submit">Apply Filter</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        while ($client = $result->fetch_assoc()) {
            echo "<h3>{$client['firstname']} {$client['lastname']}</h3>";
            echo "<p><strong>Address:</strong> {$client['address']}</p>";

            // Fetch billing information for the client based on selected month and year
            $clientId = $client['id'];
            $sqlBilling = "SELECT * FROM billing_list WHERE client_id = ? 
                           AND MONTH(reading_date) = ? AND YEAR(reading_date) = ?";
            $stmtBilling = $conn->prepare($sqlBilling);
            $stmtBilling->bind_param("iii", $clientId, $currentMonth, $currentYear);
            $stmtBilling->execute();
            $resultBilling = $stmtBilling->get_result();

            if ($resultBilling->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Billing ID</th><th>Reading Date</th><th>Due Date</th><th>Reading</th><th>Previous</th><th>Rate</th><th>Total</th><th>Status</th><th>Action</th></tr>";
                while ($rowBilling = $resultBilling->fetch_assoc()) {
                    $previous = $rowBilling['previous']; // Previous reading from billing record
                    $reading = $rowBilling['reading']; // Current reading from billing record
                    $rate = $rowBilling['rate']; // Rate from billing record

                    // Calculate total based on the formula: (reading * rate)
                    $total = $reading * $rate;

                    echo "<tr>";
                    echo "<td>{$rowBilling['id']}</td>";
                    echo "<td>{$rowBilling['reading_date']}</td>";
                    echo "<td>{$rowBilling['due_date']}</td>";
                    echo "<td>{$rowBilling['reading']}</td>";
                    echo "<td>{$rowBilling['previous']}</td>";
                    echo "<td>{$rowBilling['rate']}</td>";
                    echo "<td>{$total}</td>"; // Display computed total
                    echo "<td>". ($rowBilling['status'] == 1 ? 'Paid' : 'Pending') ."</td>";
                    echo "<td><a href='print_receipt.php?id={$rowBilling['id']}' class='print-link' target='_blank'>Print Receipt</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Print "Print All Receipts" link after printing all billing records for the client
                echo "<div><a href='print_all_receipts.php?client_id={$clientId}' class='print-link' target='_blank'>Print All Receipts for {$client['firstname']} {$client['lastname']}</a></div>";
            } else {
                echo "<p>No billing records found for this client in selected month and year.</p>";
            }

            $stmtBilling->close();
        }
    } else {
        echo "<p>No active clients found.</p>";
    }

    // Close the main result set and database connection
    $result->close();
    $conn->close();
    ?>
</div>
</body>
</html>
