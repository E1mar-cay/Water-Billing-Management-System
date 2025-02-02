<?php
include 'db.php'; // Include your database connection file

// Validate and sanitize the incoming ID parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid billing ID.");
}

$billingId = $_GET['id'];

// Fetch billing information based on the billing ID
$sql = "SELECT b.id, b.client_id, b.reading_date, b.due_date, b.reading, b.previous, b.rate, b.total, b.status, c.firstname, c.lastname, c.address
        FROM billing_list b
        INNER JOIN client_list c ON b.client_id = c.id
        WHERE b.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $billingId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Generate a printable receipt
    $receiptContent = "
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Receipt</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }
                .receipt-container {
                    width: 80%;
                    margin: 0 auto;
                    background-color: #fff;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                }
                h1 {
                    text-align: center;
                }
                p {
                    margin-bottom: 10px;
                }
                .info-section {
                    margin-bottom: 20px;
                }
                .billing-details {
                    border-collapse: collapse;
                    width: 100%;
                    margin-top: 20px;
                }
                .billing-details, .billing-details th, .billing-details td {
                    border: 1px solid #ddd;
                }
                .billing-details th, .billing-details td {
                    padding: 10px;
                    text-align: left;
                }
                .billing-details th {
                    background-color: #f4f4f4;
                }
                .print-button {
                    text-align: center;
                    margin-top: 20px;
                }
                .print-button button {
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: #fff;
                    border: none;
                    cursor: pointer;
                }
                .print-button button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class='receipt-container'>
                <h1>Receipt</h1>
                <div class='info-section'>
                    <p><strong>Client Name:</strong> {$row['firstname']} {$row['lastname']}</p>
                    <p><strong>Address:</strong> {$row['address']}</p>
                </div>
                <table class='billing-details'>
                    <tr><th>Billing ID</th><td>{$row['id']}</td></tr>
                    <tr><th>Reading Date</th><td>{$row['reading_date']}</td></tr>
                    <tr><th>Due Date</th><td>{$row['due_date']}</td></tr>
                    <tr><th>Reading</th><td>{$row['reading']}</td></tr>
                    <tr><th>Previous</th><td>{$row['previous']}</td></tr>
                    <tr><th>Rate</th><td>{$row['rate']}</td></tr>
                    <tr><th>Total</th><td>{$row['total']}</td></tr>
                    <tr><th>Status</th><td>". ($row['status'] == 1 ? 'Paid' : 'Pending') ."</td></tr>
                </table>
                <div class='print-button'>
                    <button onclick='window.print()'>Print Receipt</button>
                </div>
            </div>
        </body>
        </html>
    ";

    // Output the receipt content
    echo $receiptContent;
} else {
    echo "Billing record not found.";
}

$stmt->close();
$conn->close();
?>
<a href="index.php">Back to Home</a>
