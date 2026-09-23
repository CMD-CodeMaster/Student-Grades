<?php
    // Check if a session is already started, if not, start a new session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the user is logged in by checking if the 'username' session variable is set
    // If not, redirect the user to the login page
    if (!isset($_SESSION['username'])) {
        header("Location: Login.php");
        exit;
    }  

    // Include the database connection to connect with the database
    include 'DB_Conn.php';

    // Execute SQL commands from the 'table.sql' file to create tables in the database
    executeSQLFromFile('SQL/table.sql');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Main Menu</title>
    <!-- Link the CSS stylesheet for styling the HTML elements -->
    <link rel="stylesheet" href="./CSS/styles.css">
</head>
<body>
    <!-- Header div containing the GradeSmart header and the logout button -->
    <div id="header">
        <!-- GradeSmart header -->
        <h1 id="gradesmart-header">GradeSmart</h1>
        <!-- Logout button -->
        <a href="logout.php" id="logout-button">Logout</a>
    </div>
    <div id="intro-text">
        <p>Welcome to Gradesmart. Keeping track of student records can be a big hassle, especially with more students and course offerings.</p>
        <p>Gradesmart is here to help you manage your student records with ease.</p>
    </div>

    <!-- Images -->
    <div class="container">
        <a href="Student_Info_Page.php">
            <img src="Data\List.png" alt="Image 1">
            <p>View & Delete Student Records</p>
        </a>
        <a href="Update_Info_Page.php">
            <img src="Data\Update.png" alt="Image 2">
            <p>Update Student & Course Information</p>
        </a>
        <a href="Final_Grade_Page.php">
            <img src="Data\Grade.png" alt="Image 3">
            <p>Find a Students Final Grade</p>
        </a>
    </div>
</body>
</html>