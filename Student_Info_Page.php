<?php
include_once 'DB_populate.php';

// Initialize an empty array to store the matching rows
$matchingRows = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if the search form was submitted
    if (isset($_POST['search-key']) && isset($_POST['search-value'])) {

        // Get the submitted values
        $searchKey = $_POST['search-key'];
        $searchValue = $_POST['search-value'];

        // Define the mapping between form values and database columns
        $keyMapping = [
            'student-id' => 'Student_ID',
            'course-code' => 'Course_ID',
            'name' => 'Student_NAME'
        ];

        // Check if the submitted key is valid
        if (isset($keyMapping[$searchKey])) {
            $dbKey = $keyMapping[$searchKey];

            // Loop through the joined data
            foreach($joinedData as $row) {

                // Check if the row matches the submitted value
                if ($row[$dbKey] == $searchValue) {

                    // Use Student_ID and Course_ID as a unique key to prevent duplicates
                    $uniqueKey = $row['Student_ID'] . '-' . $row['Course_ID'];

                    // Add the row to the matching rows array if not already added
                    $matchingRows[$uniqueKey] = $row;
                }
            }
        }
    }
}

// Check if the user wants to delete a record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    
    // Get the student ID and course code from the POST data
    $studentId = $_POST['student_id'];
    $courseId = $_POST['course_id'];

    // Prepare a SQL DELETE statement
    $sql = file_get_contents('SQL/del_student.sql');
    $stmt = $conn->prepare($sql);

    // Bind the student ID and course code to the statement
    $stmt->bindParam(':student_id', $studentId);
    $stmt->bindParam(':course_id', $courseId);

    // Execute the statement
    $stmt->execute();

    echo "<p>Record deleted successfully.</p>";

    // Call the updateTextFiles function
    updateTextFiles($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page title -->
    <title>Student Information</title>

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
    <!-- Page heading -->
    <h1>Student Information</h1>

    <!-- Search form -->
    <form method="post" action="">
        <div class="input-container">
            <label for="search-key">Search Key:</label>
            <select id="search-key" name="search-key">
                <option value="student-id">Student ID</option>
                <option value="course-code">Course Code</option>
                <option value="name">Name</option>
            </select>
        </div>
        <div class="input-container">
            <label for="search-value">Search Value:</label>
            <input type="text" id="search-value" name="search-value">
        </div>
        <button id="search-button" type="submit">Search</button>
    </form>

    <!-- Table to display the search results -->
    <table>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Course ID</th>
                    <th>Test 1</th>
                    <th>Test 2</th>
                    <th>Test 3</th>
                    <th>Final Exam</th>
                    <th></th>
                </tr>
            </thead>
        <?php endif; ?>
        <tbody>
            <!-- Loop through the matching rows and display them in the table -->
            <?php foreach($matchingRows as $row): ?>
                <tr>
                    <td><?php echo $row['Student_ID']; ?></td>
                    <td><?php echo $row['Student_NAME']; ?></td>
                    <td><?php echo $row['Course_ID']; ?></td>
                    <td><?php echo $row['Test_1'] . '%'; ?></td>
                    <td><?php echo $row['Test_2'] . '%'; ?></td>
                    <td><?php echo $row['Test_3'] . '%'; ?></td>
                    <td><?php echo $row['Final_Exam'] . '%'; ?></td>
                    <td class='delete-cell'>
                        <form action='Student_Info_Page.php' method='post'>
                            <input type='hidden' name='student_id' value='<?php echo $row['Student_ID']; ?>'>
                            <input type='hidden' name='course_id' value='<?php echo $row['Course_ID']; ?>'>
                            <button id="delete-button" type='submit' name='delete'>Delete</button>
                        </form>
                    <td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table><br>
    
    <?php
        // After the table, check if any matches were found
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($matchingRows)) {
                echo "<p>No matches found. Ensure correct key & value are submitted.</p>";
            }
        }
    ?>
</body>
</html>


