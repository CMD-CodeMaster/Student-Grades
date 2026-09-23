<?php

//include database connection file
include_once 'DB_Conn.php'; 
include_once ('DB_populate.php');

$grades = null; //Holds fetched grades
$message = ''; //Holds messages to display to the user

// Check if form is submitted with POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    //Filter user input for expected format
    $studentNumber = htmlspecialchars($_POST['student_number']);
    $courseCode = htmlspecialchars($_POST['course_code']);

    //Search functionality
    if (isset($_POST['search'])) {
        // Query the database for the student's grades
        $stmt = $conn->prepare("SELECT Test_1, Test_2, Test_3, Final_Exam FROM courses WHERE Student_ID = :studentNumber AND Course_ID = :courseCode");
        $stmt->bindParam(':studentNumber', $studentNumber);
        $stmt->bindParam(':courseCode', $courseCode);
        $stmt->execute();

        //Fetch grades
        $grades = $stmt->fetch(PDO::FETCH_ASSOC);

        //If no grades found, display message
        if ($grades === false) {
            $message = "<p>No matches found. Ensure correct Student ID & Course Code are submitted.</p>";
        }
    } elseif (isset($_POST['update'])) {
        //Filter user input and verify if they are numeric
        $test1 = is_numeric($_POST['test1']) ? $_POST['test1'] : null;
        $test2 = is_numeric($_POST['test2']) ? $_POST['test2'] : null;
        $test3 = is_numeric($_POST['test3']) ? $_POST['test3'] : null;
        $finalExam = is_numeric($_POST['final_exam']) ? $_POST['final_exam'] : null;
        
        //Calling SQL query from file
        $sql = file_get_contents('SQL/update.sql');

        //Update grades in the database
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':test1', $test1);
        $stmt->bindParam(':test2', $test2);
        $stmt->bindParam(':test3', $test3);
        $stmt->bindParam(':finalExam', $finalExam);
        $stmt->bindParam(':studentNumber', $studentNumber);
        $stmt->bindParam(':courseCode', $courseCode);
        $stmt->execute();

        //Displays success message
        $message = "Records updated successfully.";

        //Update txt file
        updateTextFiles($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page title -->
    <title>Update Information</title>

    <!-- Link to the stylesheet -->
    <link rel="stylesheet" href="./CSS/styles.css">
</head>
<body>
    <!-- Navigation bar -->

    <div id="header">
        <div id="navbar">
            <a href="Main_Menu.php">Main Menu</a>
            <a href="Student_Info_Page.php">Student Info</a>
            <a href="Update_Info_Page.php">Update Info</a>
            <a href="Final_Grade_Page.php">Final Grades</a>
        </div>
        <a href="logout.php" id="logout-button">Logout</a>
    </div>

    <h1>Update Information</h1>

    <form method="post" action="">
        <div class="input-container">
            <label for="student_number">Student ID:</label>
            <input type="text" id="student_number" name="student_number">
        </div>
        <div class="input-container">
            <label for="course_code">Course Code:</label>
            <input type="text" id="course_code" name="course_code">
        </div>
        <button id="search-button" type="submit" name="search">Search</button>
    </form>

    <?php
    //Display form for updating grades if grades are fetched
    if ($grades !== null && $grades !== false) {
        echo '
        <form method="post">
            <input type="hidden" name="student_number" value="' . $studentNumber . '">
            <input type="hidden" name="course_code" value="' . $courseCode . '">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Course Code</th>
                        <th>Test 1</th>
                        <th>Test 2</th>
                        <th>Test 3</th>
                        <th>Final</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="student_number" value="' . $studentNumber . '"></td>
                        <td><input type="text" name="course_code" value="' . $courseCode . '"></td>
                        <td><input type="text" name="test1" value="' . $grades['Test_1'] . '"></td>
                        <td><input type="text" name="test2" value="' . $grades['Test_2'] . '"></td>
                        <td><input type="text" name="test3" value="' . $grades['Test_3'] . '"></td>
                        <td><input type="text" name="final_exam" value="' . $grades['Final_Exam'] . '"></td>
                    </tr>
                </tbody>
            </table>
            <button id="search-button" type="submit" name="update">Update</button>
        </form>';
    }
    ?>

    <p id="message"><?php echo $message; ?></p>

</body>
</html>