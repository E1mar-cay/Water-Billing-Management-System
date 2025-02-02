<?php
include 'db.php'; // Include your database connection file

// Fetch all users from the users table, distinguishing between admins (type 1) and staff (type 2)
$sql = "SELECT id, firstname, middlename, lastname, username, type, date_added, date_updated FROM users WHERE type IN (1, 2)";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <title>List of Users</title>
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
        .container {
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffffa1;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.53);
        }
        .add-user-link, .back-home-link {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #4FCFC8;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .add-user-link:hover, .back-home-link:hover {
            background-color: #007770;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>List of Users</h1>
        <a href="add_user.php" class="add-user-link">Add User</a> <!-- Link to add_user.php -->
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Username</th><th>Type</th><th>Date Added</th><th>Date Updated</th></tr>";
            while($row = $result->fetch_assoc()) {
                $type = $row['type'] == 1 ? 'Admin' : 'Staff'; // Display 'Admin' for type 1, 'Staff' for type 2
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['firstname']}</td>";
                echo "<td>{$row['middlename']}</td>";
                echo "<td>{$row['lastname']}</td>";
                echo "<td>{$row['username']}</td>";
                echo "<td>{$type}</td>";
                echo "<td>{$row['date_added']}</td>";
                echo "<td>{$row['date_updated']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No users found.";
        }
        $conn->close(); // Close the database connection
        ?>
        <a href="index.php" class="back-home-link">Back to Home</a>
    </div>
</body>
</html>
