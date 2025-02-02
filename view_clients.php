<?php
include 'db.php';

$sql = "SELECT * FROM client_list";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <title>List of Clients</title>
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
            background-color: #ffffffa1;
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
        .add-client {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #4FCFC8;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .add-client:hover {
            background-color: #007770;
        }
        .action-links a {
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
            color: #05A69D;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>List of Clients</h2>
    <a class="add-client" href="add_client.php">Add Client</a>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Code</th><th>Category</th><th>Firstname</th><th>Lastname</th><th>Contact</th><th>Address</th><th>Meter Code</th><th>Actions</th></tr>";
        while($row = $result->fetch_assoc()) {
            // Fetch category name based on category_id
            $category_id = $row['category_id'];
            $category_name = '';
            $category_sql = "SELECT name FROM category_list WHERE id = $category_id";
            $category_result = $conn->query($category_sql);
            if ($category_result && $category_result->num_rows > 0) {
                $category_data = $category_result->fetch_assoc();
                $category_name = $category_data['name'];
            }

            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['code']}</td>";
            echo "<td>{$category_name}</td>";
            echo "<td>{$row['firstname']}</td>";
            echo "<td>{$row['lastname']}</td>";
            echo "<td>{$row['contact']}</td>";
            echo "<td>{$row['address']}</td>";
            echo "<td>{$row['meter_code']}</td>";
            echo "<td class='action-links'>";
            echo "<a href='edit_client.php?id={$row['id']}'>Edit</a>";
            echo "<a href='delete_client.php?id={$row['id']}'>Delete</a>";
            echo "<a href='view_client.php?id={$row['id']}'>View</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>0 results</p>";
    }
    ?>
    <a href="index.php" class="add-client">Back to Home</a>
</div>
</body>
</html>
