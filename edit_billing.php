<?php
include 'db.php';

// Initialize variables to hold current billing record data
$id = $client_id = $reading_date = $due_date = $reading = $previous = $rate = $total = $status = '';

// Check if ID parameter exists in URL
if(isset($_GET['id'])) {
    // Prepare SQL statement to fetch billing record based on ID
    $sql = "SELECT * FROM billing_list WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Bind ID parameter
    $stmt->bind_param("i", $_GET['id']);
    
    // Execute query
    $stmt->execute();
    
    // Store result
    $result = $stmt->get_result();
    
    // Fetch billing record
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Assign fetched data to variables
        $id = $row['id'];
        $client_id = $row['client_id'];
        $reading_date = $row['reading_date'];
        $due_date = $row['due_date'];
        $reading = $row['reading'];
        $previous = $row['previous'];
        $rate = $row['rate'];
        $total = $row['total'];
        $status = $row['status'];
    } else {
        echo "Billing record not found.";
        exit();
    }
    
    // Close prepared statement and result set
    $stmt->close();
    $result->close();
} else {
    echo "ID parameter is missing.";
    exit();
}

// Handle form submission to update billing record
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input data
    $client_id = $_POST['client_id'];
    $reading_date = $_POST['reading_date'];
    $due_date = $_POST['due_date'];
    $reading = $_POST['reading'];
    $previous = $_POST['previous'];
    // Rate is disabled, so it won't be submitted via the form
    // Calculate total based on new reading and rate (assuming rate doesn't change)
    // $rate = $_POST['rate']; // Assuming rate is not updated
    $total = $reading * $rate;
    $status = $_POST['status']; // Assuming status comes from the form
    
    // Update billing record in the database
    $sqlUpdate = "UPDATE billing_list SET client_id = ?, reading_date = ?, due_date = ?, reading = ?, previous = ?, total = ?, status = ?, date_updated = current_timestamp() WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("issddiii", $client_id, $reading_date, $due_date, $reading, $previous, $total, $status, $id);
    
    // Execute the update statement
    if ($stmtUpdate->execute()) {
        echo '<div class="message">Billing record updated successfully.</div>';
    } else {
        echo '<div class="error-message">Error updating record: ' . $stmtUpdate->error . '</div>';
    }
    
    // Close prepared statement
    $stmtUpdate->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <title>Edit Billing Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type=text], input[type=date], input[type=number] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=submit] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #0056b3;
        }
        .back-link {
            margin-top: 10px;
            display: inline-block;
            text-decoration: none;
            color: #007bff;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .message {
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .error-message {
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Billing Record</h2>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="client_id">Client ID:</label>
            <input type="text" id="client_id" name="client_id" value="<?php echo $client_id; ?>" required>
            <label for="reading_date">Reading Date:</label>
            <input type="date" id="reading_date" name="reading_date" value="<?php echo $reading_date; ?>" required>
            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date" value="<?php echo $due_date; ?>" required>
            <label for="reading">Reading:</label>
            <input type="text" id="reading" name="reading" value="<?php echo $reading; ?>" required>
            <label for="previous">Previous:</label>
            <input type="text" id="previous" name="previous" value="<?php echo $previous; ?>" required>
            <label for="rate">Rate:</label>
            <input type="number" id="rate" name="rate" value="<?php echo $rate; ?>" required disabled>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="0" <?php if ($status == 0) echo 'selected'; ?>>Pending</option>
                <option value="1" <?php if ($status == 1) echo 'selected'; ?>>Paid</option>
            </select>
            <input type="submit" value="Update Billing Record">
        </form>
        <a class="back-link" href="view_billings.php">Back to List of Billings</a>
    </div>
</body>
</html>

<?php
$conn->close(); // Close database connection
?>
