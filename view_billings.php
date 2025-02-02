<?php
include 'db.php'; // Assuming this file contains your database connection details

$sql = "SELECT b.id, b.client_id, b.reading_date, b.due_date, b.reading, b.previous, b.rate, b.reading * b.rate AS total, b.status, c.firstname, c.lastname
        FROM billing_list b
        INNER JOIN client_list c ON b.client_id = c.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <title>List of Billings</title>
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
            background-color: #ffffff82;
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
        .view-billing {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #4FCFC8;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .view-billing:hover {
            background-color: #007770;
        }
        .action-link {
            display: inline-block;
            padding: 5px 10px;
            background-color: #4FCFC8;
            color: #fff;
            text-decoration: none;
            margin-right: 5px;
            border-radius: 3px;
        }
        .action-link:hover {
            background-color: #007770;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>List of Billings</h2>
    <a class="view-billing" href="add_billing.php">Add Billings</a>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Client Name</th><th>Reading Date</th><th>Due Date</th><th>Reading</th><th>Previous</th><th>Rate</th><th>Total</th><th>Status</th><th>Actions</th></tr>";
        while($row = $result->fetch_assoc()) {
            $statusText = $row['status'] == 1 ? 'Paid' : 'Pending';
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['firstname']} {$row['lastname']}</td>";
            echo "<td>{$row['reading_date']}</td>";
            echo "<td>{$row['due_date']}</td>";
            echo "<td>{$row['reading']}</td>";
            echo "<td>{$row['previous']}</td>";
            echo "<td>{$row['rate']}</td>";
            echo "<td>{$row['total']}</td>";
            echo "<td>{$statusText}</td>";
            echo "<td>";
            echo "<a class='action-link' href='edit_billing.php?id={$row['id']}'>Edit</a>";
            echo "<a class='action-link' href='delete_billing.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this billing record?\")'>Delete</a>";
            echo "<a class='action-link' href='view_billing.php?id={$row['id']}'>View</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    ?>
    <a href="index.php" class="view-billing">Back to Home</a>
</div>
</body>
</html>
