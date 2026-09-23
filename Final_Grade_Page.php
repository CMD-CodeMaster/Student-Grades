<?php
// Include the database population script
include 'DB_populate.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Final Grade</title>
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

    <!-- Page title -->
    <h1>Final Grade</h1>

    <!-- Search form -->
    <form method="post" action="">
        <div class="input-container">
            <label for="student_search">Student ID:</label>
            <input type="text" id="student_search" name="student_search">
        </div>
        <div class="input-container">
            <label for="course_code">Course Code:</label>
            <input type="text" id="course_code" name="course_code">
        </div>
        <button id="search-button" type="submit" name="search">Average</button>
    </form><br>

</body>
</html>

<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    // Get the form data
    $studentNumber = $_POST['student_search'];
    $courseCode = $_POST['course_code'];

    // SQL query to get the student's grades
    $sql = file_get_contents('SQL/final_grade.sql');
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':studentNumber', $studentNumber);
    $stmt->bindParam(':courseCode', $courseCode);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the query returned a result
    if ($result) {
        
        // Calculate the average of the tests
        $testAverage = ($result['Test_1'] + $result['Test_2'] + $result['Test_3']) / 3;

        // Calculate the weighted average of the tests (60%)
        $weightedTestAverage = $testAverage * 0.6;

        // Calculate the weighted average of the final exam (40%)
        $weightedFinalExam = $result['Final_Exam'] * 0.4;

        // Calculate the final grade
        $finalGrade = $weightedTestAverage + $weightedFinalExam;

        // Start table and add headers
        echo "<table>";
        echo "<tr><th>Student ID</th><th>Student Name</th><th>Course Code</th><th>Final Grade</th></tr>";

        // Display student name, course code, and average grade in a table row
        echo "<tr>";
        echo "<td>" . htmlspecialchars($result['Student_ID']) . "</td>";
        echo "<td>" . htmlspecialchars($result['Student_NAME']) . "</td>";
        echo "<td>" . htmlspecialchars($result['Course_ID']) . "</td>";
        echo "<td>" . $finalGrade . "%</td>";
        echo "</tr>";

        // Close the table
        echo "</table>";
    } else {
        // Display a message if no records were found
        echo "<p>No records found.</p>";
    }
}
?>