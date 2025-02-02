<?php
include 'db.php'; // Include your database connection file

$message = ''; // Initialize message variable

// Fetch list of active clients for selection
$sql_clients = "SELECT id, firstname, lastname, category_id FROM client_list WHERE status = 1";
$result_clients = $conn->query($sql_clients);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_POST['client_id'];
    $reading_date = $_POST['reading_date'];
    $due_date = $_POST['due_date'];
    $reading = $_POST['reading'];
    $status = $_POST['status']; // Assuming status comes from the form

    // Fetch the category_id of the selected client
    $sql_category = "SELECT category_id FROM client_list WHERE id = ?";
    $stmt_category = $conn->prepare($sql_category);
    $stmt_category->bind_param("i", $client_id);
    $stmt_category->execute();
    $stmt_category->bind_result($category_id);
    $stmt_category->fetch();
    $stmt_category->close();

    // Fetch the rate from category_list based on category_id
    $sql_rate = "SELECT rate FROM category_list WHERE id = ?";
    $stmt_rate = $conn->prepare($sql_rate);
    $stmt_rate->bind_param("i", $category_id);
    $stmt_rate->execute();
    $stmt_rate->bind_result($rate);
    $stmt_rate->fetch();
    $stmt_rate->close();

    // Fetch the latest reading for the client to use as previous
    $sql_latest_reading = "SELECT reading FROM billing_list WHERE client_id = ? ORDER BY reading_date DESC LIMIT 1";
    $stmt_latest_reading = $conn->prepare($sql_latest_reading);
    $stmt_latest_reading->bind_param("i", $client_id);
    $stmt_latest_reading->execute();
    $stmt_latest_reading->store_result(); // Store result to get num_rows
    $num_rows = $stmt_latest_reading->num_rows;
    if ($num_rows > 0) {
        $stmt_latest_reading->bind_result($previous);
        $stmt_latest_reading->fetch();
    } else {
        $previous = 0;
    }
    $stmt_latest_reading->close();

    // Calculate the total based on current reading and rate
    $total = ($reading - $previous) * $rate; // Calculate the total based on current and previous readings

    $stmt = $conn->prepare("INSERT INTO billing_list (client_id, reading_date, due_date, reading, previous, rate, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdddis", $client_id, $reading_date, $due_date, $reading, $previous, $rate, $total, $status);

    if ($stmt->execute()) {
        $message = "New billing record added successfully";
        $messageClass = "success-message";
    } else {
        $message = "Error: " . $stmt->error;
        $messageClass = "error-message";
    }

    $stmt->close();
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            padding: 20px;
            background-color: #ffffff82;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.53);
            width: 26%;
            border-radius: 13px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
        }
        select, input[type="date"], input[type="number"], input[type="submit"] {
            margin-top: 10px;
            padding: 10px;
            background-color: rgb(79, 207, 200);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            transition: background-color 0.3s, color 0.3s;
        }
        input[type="date"], input[type="number"] {
            color: #333;
        }
        input[type="submit"]:hover {
            background-color: #007770;
        }
        .message {
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .success-message {
            background-color: #4CAF50;
            color: white;
        }
        .error-message {
            background-color: #f44336;
            color: white;
        }
        .back-link {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4FCFC8;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .back-link:hover {
            background-color: #007770;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="post" action="">
            <label for="client_id">Select Client:</label>
            <select name="client_id" id="client_id">
                <?php
                if ($result_clients->num_rows > 0) {
                    while ($row = $result_clients->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['firstname']} {$row['lastname']}</option>";
                    }
                } else {
                    echo "<option value=''>No active clients found</option>";
                }
                ?>
            </select>
            <label for="reading_date">Reading Date:</label>
            <input type="date" name="reading_date" required min="<?php echo date('Y-m-d'); ?>">
            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" required min="<?php echo date('Y-m-d'); ?>">
            <label for="reading">Reading:</label>
            <input type="number" step="0.01" name="reading" required>
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="0">Pending</option>
                <option value="1">Paid</option>
            </select>
            <input type="submit" value="Add Billing Record">
        </form>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <a href="view_billings.php" class="back-link">Back</a>
    </div>
</body>
</html>
