<?php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $status = $_POST['status']; // Capture the selected status from the form
    $delete_flag = 0; // Assuming the delete flag is set to 0 by default

    $stmt = $conn->prepare("INSERT INTO category_list (name, status, delete_flag, date_created, date_updated) VALUES (?, ?, ?, current_timestamp(), current_timestamp())");
    $stmt->bind_param("sii", $name, $status, $delete_flag);

    if ($stmt->execute()) {
        echo "New category added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<div class="container">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <title>Add Category</title>
<style>
	.container {
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff82;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.53);
            width: 28%;
            position: absolute;
            top: 29%;
            left: 35%;
            border-radius: 16px;
        }
</style>
</head>
<body>
<img src="water2.jpg" alt="water" style="position: absolute;z-index: -1; top: -103%; left: -118%;"> 
    <h1>Add New Category</h1>
    <form method="post" action="">
        Name: <input type="text" name="name" required><br>
        Status:
        <select name="status" required style="margin-top: 10px; background-color: rgb(79, 207, 200); color: rgb(255, 255, 255);">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select><br>
        <input type="submit" value="Add Category" style="margin-top: 10px; background-color: rgb(79, 207, 200); color: rgb(255, 255, 255);">
    </form>
    
    <a href="category_list.php" style="display: inline-block; padding: 10px 2px; margin-bottom: 20px; background-color: #4FCFC8; color: #fff; text-decoration: none; border-radius: 5px; margin-top:10px; transition: background-color 0.3s, color 0.3s;"
   onmouseover="this.style.backgroundColor='#007770'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='#4FCFC8'; this.style.color='#fff'; ">Back to Category List</a> <!-- Assuming this is the file that lists categories -->
</div>
</body>
</html>
