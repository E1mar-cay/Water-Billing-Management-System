<?php
include 'db.php'; // Include your database connection file

// Fetch all categories from the category_list table
$sql = "SELECT * FROM category_list";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <title>List of Categories</title>
<style>
      body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
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
            background-color: #f4f4f4;}
        .container {
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffffa1;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.53);
        }

</style>
</head>
<body style="  font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 20px;">
<img src="water2.jpg" alt="water" style="position: absolute;z-index: -1;"> 
<div class="container">    
<h1>List of Categories</h1>
    <a href="add_category.php" style="display: inline-block; padding: 10px 20px; margin-bottom: 20px; background-color: #4FCFC8; color: #fff; text-decoration: none; border-radius: 5px;transition: background-color 0.3s, color 0.3s;"
   onmouseover="this.style.backgroundColor='#007770'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='#4FCFC8'; this.style.color='#fff';">Add Category</a> <!-- Link to add_category.php -->
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Status</th><th>Delete Flag</th><th>Date Created</th><th>Date Updated</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['status']}</td><td>{$row['delete_flag']}</td><td>{$row['date_created']}</td><td>{$row['date_updated']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No categories found.";
    }
    $conn->close(); // Close the database connection
    ?>
    <a href="index.php" style="display: inline-block; padding: 10px 20px; margin-bottom: 20px; background-color: #4FCFC8; color: #fff; text-decoration: none; border-radius: 5px;transition: background-color 0.3s, color 0.3s;"
   onmouseover="this.style.backgroundColor='#007770'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='#4FCFC8'; this.style.color='#fff';">Back to Home</a>
</div>
</body>
</html>
