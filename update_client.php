<?php
include 'db.php'; // Include your database connection file

// Variable to hold the notification message
$notification = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve values from POST data
    $client_id = $_POST['client_id'];
    $code = $_POST['code'];
    $category_id = $_POST['category_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $meter_code = $_POST['meter_code'];

    // Update client record in the database
    $stmt = $conn->prepare("UPDATE client_list SET code = ?, category_id = ?, firstname = ?, lastname = ?, contact = ?, address = ?, meter_code = ? WHERE id = ?");
    
    // Bind parameters
    $stmt->bind_param("sissssdi", $code, $category_id, $firstname, $lastname, $contact, $address, $meter_code, $client_id);

    // Execute the statement
    if ($stmt->execute()) {
        $notification = '<p class="notification success">Client updated successfully.</p>';
    } else {
        $notification = '<p class="notification error">Error updating client: ' . $stmt->error . '</p>';
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <title>Update Client</title>
    <style>
        .notification {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Client</h2>
        <?php echo $notification; ?>
        <a href="view_clients.php">Back</a>
    </div>
</body>
</html>
