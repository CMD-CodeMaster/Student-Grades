<?php
include_once 'DB_Conn.php';
executeSQLFromFile('SQL/del.sql');

session_start();
// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login screen
header("Location: Login.php");
exit;
?>