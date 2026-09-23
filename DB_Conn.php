<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // use PDO to connect to database
    $host = "localhost";
    $database = "cp476";

    try {
        // open connection to database
        $conn = new PDO("mysql:host=$host;dbname=$database", $_SESSION['username'], $_SESSION['password']);

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    function executeSQLFromFile($filename) {
        global $conn;
        try {
            // Read SQL statements from file
            $sql = file_get_contents($filename);
            // Execute SQL statements
            $conn->exec($sql);
        } catch(PDOException $e) {
            echo "Error executing SQL statements: " . $e->getMessage();
        }
    }
    
    // Close connection
    function closeConnection() {
        global $conn;
        $conn = null;
    }
?>