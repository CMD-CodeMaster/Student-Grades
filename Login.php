<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GradeSmart</title>
    <style>
        
        .error-message {
        color: red;
        text-align: center;
        margin-top: 20px; 
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('Data/Background.jpg'); 
            background-size: cover; 
            background-repeat: no-repeat; 
        }

        form {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        header {
            background-color: black;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<header>
    <h1>GradeSmart Login</h1>
<header>
    
<body>
    <form action="Login_Credentials.php" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>

<?php
// Start the session
session_start();

// Check if there's an error message in the query parameter
if (isset($_GET['error']) && $_GET['error'] == "invalid_credentials") {
    echo '<div class="error-message">Invalid username or password. Please try again.</div>';
}
?>