<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Assuming passwords are stored as md5 hashes

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
    } else {
        echo "Invalid credentials";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="icon" href="logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 30%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff70;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            position: absolute;
            left: 35%;
            top: 20%;
            border-radius: 34px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background-color: #4FCFC8;
            color: black;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4FCFC869;
        }
        .register-link {
            margin-top: 10px;
            text-align: center;
        }
        .register-link a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s;
        }
        .register-link a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
<img src="water2.jpg" alt="water" style="position: absolute;z-index: -1;"> 
<div class="container">
    <h2>Login</h2>
    <form method="post" action="">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>
