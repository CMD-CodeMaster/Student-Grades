<?php
    session_start();

    // Get username and password from Login.php
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    $servername = "localhost";
    $database = "cp476";

    try {
        // Create a PDO connection
        $conn = new PDO("mysql:host=$servername;dbname=$database", $_SESSION['username'], $_SESSION['password']);
    
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Redirect to Main_menu.php if connection is successful
        header("Location: Main_menu.php");
        exit();
    } catch(PDOException $e) {
        // Redirect back to Login.php if connection fails
        header("Location: Login.php?error=invalid_credentials");
        exit();
    }
?>