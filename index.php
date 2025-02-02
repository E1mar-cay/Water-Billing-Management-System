<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Include your database connection file

// Fetch the total number of categories
$sqlCategories = "SELECT COUNT(*) as totalCategories FROM category_list";
$resultCategories = $conn->query($sqlCategories);
$totalCategories = $resultCategories->fetch_assoc()['totalCategories'];

// Fetch the total number of clients
$sqlClients = "SELECT COUNT(*) as totalClients FROM client_list WHERE status = 1";
$resultClients = $conn->query($sqlClients);
$totalClients = $resultClients->fetch_assoc()['totalClients'];

// Fetch the total number of pending bills
$sqlPendingBills = "SELECT COUNT(*) as totalPendingBills FROM billing_list WHERE status = 0";
$resultPendingBills = $conn->query($sqlPendingBills);
$totalPendingBills = $resultPendingBills->fetch_assoc()['totalPendingBills'];

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>Water Billing Management System</title>
    <link rel="icon" href="logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            background-image: url('water2.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff94;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 25px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .header a {
            text-decoration: none;
            color: #333;
            padding: 10px 20px;
            background-color: #ea0a0a;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .header a:hover {
            background-color: #ddd;
        }
        .content {
            display: flex;
            justify-content: flex-start; /* Align items to the left */
            align-items: flex-start; /* Align items to the top */
        }
        .summary {
            width: 30%; /* Adjust the width of the summary section */
            margin-right: 20px; /* Add margin to separate from menu */
        }
        .summary div {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
            margin: 20px auto;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1);
        }
        .summary h3 {
            margin-top: 0;
        }
        .summary p {
            margin-bottom: 0;
        }
        .menu {
            width: 70%; /* Adjust the width of the menu section */
        }
        .menu p {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .menu a {
            display: block;
            text-decoration: none;
            color: #333;
            padding: 10px 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background-color 0.3s, color 0.3s;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1);
        }
        .menu a:hover {
            background-color: #ddd;
            color: #fff;
        }
        .menu a.settings-link {
            background-color: #2CA09A;
            color: white;
        }
        .menu a.settings-link:hover {
            background-color: #007770;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="logo.png" alt="WBMS Logo" style="height: 100px; margin-right: 10px;">
        <h1>Water Billing Management System</h1>
        <div>
            <span style="font-size: 18px; font-weight: bold;">Welcome, <?php echo $_SESSION['username']; ?>!</span>
            <a href="logout.php" style="color: white;">Logout</a>
        </div>
    </div>

    <div class="content">
        <div class="summary">
            <div style="background-color: #4ECDC46E; color: white;">
                <h3>Total Categories</h3>
                <p><?php echo $totalCategories; ?></p>
            </div>
            <div style="background-color: #36BFB663; color: white;">
                <h3>Total Clients</h3>
                <p><?php echo $totalClients; ?></p>
            </div>
            <div style="background-color: #247F795C; color: white;">
                <h3>Pending Bills</h3>
                <p><?php echo $totalPendingBills; ?></p>
            </div>
        </div>

        <div class="menu">
            <p><b>Menu</b></p>
            <a href="view_clients.php">View Clients</a>
            <a href="view_billings.php">Billing Records</a>
            <a href="monthly_biling.php" style="background-color: #4FCFC8; color: white;">Monthly Report</a>
            <a href="category_list.php" style="background-color: #30B0AA; color: white;">List of Category</a>
            <a href="user_list.php" style="background-color: #2CA09A; color: white;">User List</a>
            <a href="settings_rate.php" class="settings-link">Settings</a>
        </div>
    </div>
</div>
</body>
</html>


